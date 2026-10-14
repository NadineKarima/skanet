<?php

?>

<div class="mb-4">

    <h3 class="fw-bold mb-1">
        Laporan Rekap Nilai
    </h3>

    <p class="text-secondary mb-0">
        Rekap nilai akhir semua mata pelajaran dalam satu kelas.
    </p>

</div>

<?php

$tahunAktif = $pdo->query("SELECT id_tahun, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();

if (!$tahunAktif) :

?>

<div class="alert alert-warning">
    Belum ada tahun ajaran yang aktif.
</div>

<?php else : ?>

<?php

$listKelas = $pdo->query("
    SELECT k.id_kelas, k.tingkat, k.rombel, j.nama_jurusan
    FROM kelas k
    JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    ORDER BY j.nama_jurusan ASC, FIELD(k.tingkat, 'X', 'XI', 'XII') ASC, k.rombel ASC
")->fetchAll();

$idKelas = (int) ($_GET['id_kelas'] ?? 0);

?>

<form method="GET" action="index.php" class="row g-3 align-items-end mb-4">

    <input type="hidden" name="page" value="laporan">

    <div class="col-md-4">

        <label class="form-label">Kelas</label>

        <select
            name="id_kelas"
            class="form-select"
            onchange="this.form.submit()">

            <option value="">-- Pilih Kelas --</option>

            <?php foreach ($listKelas as $k) : ?>

            <option value="<?= $k['id_kelas']; ?>" <?= $idKelas === (int) $k['id_kelas'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($k['tingkat'] . ' ' . $k['nama_jurusan'] . ' ' . $k['rombel']); ?>
            </option>

            <?php endforeach; ?>

        </select>

    </div>

</form>

<?php if ($idKelas) : ?>

<?php

$listMapel = $pdo
    ->query("SELECT id_mapel, nama_mapel, kkm FROM mapel WHERE status = 'Aktif' ORDER BY nama_mapel ASC")
    ->fetchAll();

$stmtSiswa = $pdo->prepare("
    SELECT s.id_siswa, s.nis, s.nama_lengkap
    FROM siswa s
    JOIN siswa_kelas sk ON sk.id_siswa = s.id_siswa AND sk.id_kelas = ? AND sk.id_tahun = ?
    WHERE s.status = 'Aktif'
    ORDER BY s.nama_lengkap ASC
");
$stmtSiswa->execute([$idKelas, $tahunAktif['id_tahun']]);
$siswaList = $stmtSiswa->fetchAll();

$nilaiMap = [];

if (!empty($siswaList)) {

    $idSiswaList = array_column($siswaList, 'id_siswa');
    $placeholder = implode(',', array_fill(0, count($idSiswaList), '?'));

    $stmtNilai = $pdo->prepare("
        SELECT id_siswa, id_mapel, nilai_akhir, predikat
        FROM nilai
        WHERE id_tahun = ? AND id_siswa IN ($placeholder)
    ");
    $stmtNilai->execute(array_merge([$tahunAktif['id_tahun']], $idSiswaList));

    foreach ($stmtNilai->fetchAll() as $n) {
        $nilaiMap[$n['id_siswa']][$n['id_mapel']] = $n;
    }

}

if (empty($siswaList)) :

?>

<div class="alert alert-info">
    Belum ada siswa aktif di kelas ini.
</div>

<?php elseif (empty($listMapel)) : ?>

<div class="alert alert-info">
    Belum ada mata pelajaran aktif.
</div>

<?php else : ?>

<div class="d-flex justify-content-end gap-2 mb-3">

    <a
        href="actions/laporan/export_excel.php?id_kelas=<?= $idKelas; ?>"
        class="btn btn-outline-success btn-sm">
        <i class="bi bi-file-earmark-excel"></i>
        Export Excel
    </a>

    <a
        href="actions/laporan/cetak.php?id_kelas=<?= $idKelas; ?>"
        target="_blank"
        class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer"></i>
        Cetak / PDF
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-body table-responsive">

        <table id="tableLaporan" class="table table-hover align-middle">

            <thead>

            <tr>

                <th width="50">No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>

                <?php foreach ($listMapel as $m) : ?>
                <th class="text-center"><?= htmlspecialchars($m['nama_mapel']); ?></th>
                <?php endforeach; ?>

                <th width="100" class="text-center">Rata-rata</th>
                <th width="80" class="text-center">Aksi</th>

            </tr>

            </thead>

            <tbody>

            <?php $no = 1; ?>

            <?php foreach ($siswaList as $s) : ?>

            <?php

            $totalNilai = 0;
            $jumlahMapelTerisi = 0;

            ?>

            <tr>

                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($s['nis']); ?></td>
                <td><?= htmlspecialchars($s['nama_lengkap']); ?></td>

                <?php foreach ($listMapel as $m) : ?>

                    <?php

                    $sel = $nilaiMap[$s['id_siswa']][$m['id_mapel']] ?? null;

                    if ($sel) {
                        $totalNilai += (float) $sel['nilai_akhir'];
                        $jumlahMapelTerisi++;
                    }

                    $belumTuntas = $sel && (float) $sel['nilai_akhir'] < (int) $m['kkm'];

                    ?>

                    <td class="text-center <?= $belumTuntas ? 'text-danger fw-bold' : ''; ?>">
                        <?= $sel ? $sel['nilai_akhir'] : '-'; ?>
                    </td>

                <?php endforeach; ?>

                <td class="text-center fw-bold">
                    <?= $jumlahMapelTerisi > 0 ? round($totalNilai / $jumlahMapelTerisi, 2) : '-'; ?>
                </td>

                <td class="text-center">
                    <a
                        href="actions/laporan/cetak_individu.php?id_siswa=<?= (int) $s['id_siswa']; ?>&id_kelas=<?= $idKelas; ?>"
                        target="_blank"
                        class="btn btn-outline-secondary btn-sm"
                        title="Cetak laporan siswa ini">
                        <i class="bi bi-printer"></i>
                    </a>
                </td>

            </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <p class="text-secondary small mb-0 mt-2">
            <span class="text-danger fw-bold">Merah</span> = nilai di bawah KKM mata pelajaran tersebut.
            <br>
            Klik <i class="bi bi-printer"></i> pada kolom Aksi untuk mencetak laporan per siswa.
        </p>

    </div>

</div>

<?php endif; // empty siswaList/listMapel ?>

<?php endif; // $idKelas ?>

<?php endif; // !$tahunAktif ?>
