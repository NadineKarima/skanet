<?php

$tahunAktif   = $pdo->query("SELECT id_tahun, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();
$idTahunAktif = $tahunAktif['id_tahun'] ?? 0;

?>

<div class="mb-4">

    <h3 class="fw-bold mb-1">
        Input Nilai
    </h3>

    <p class="text-secondary mb-0">
        Pilih mapel dan kelas, lalu isi nilai siswa.
    </p>

</div>

<?php if (!$tahunAktif) : ?>

<div class="alert alert-warning">
    Belum ada tahun ajaran yang aktif. Nilai tidak bisa diinput sampai admin mengaktifkan salah satu tahun ajaran.
</div>

<?php else : ?>

<?php

if (isAdmin()) {

    $listMapel = $pdo
        ->query("SELECT id_mapel, nama_mapel, kkm FROM mapel WHERE status = 'Aktif' ORDER BY nama_mapel ASC")
        ->fetchAll();

} else {

    $stmtMapel = $pdo->prepare("
        SELECT m.id_mapel, m.nama_mapel, m.kkm
        FROM guru_mapel gm
        JOIN mapel m ON m.id_mapel = gm.id_mapel
        WHERE gm.id_guru = ? AND m.status = 'Aktif'
        ORDER BY m.nama_mapel ASC
    ");
    $stmtMapel->execute([$_SESSION['id']]);
    $listMapel = $stmtMapel->fetchAll();

}

if (empty($listMapel)) :

?>

<div class="alert alert-info">
    <?= isAdmin()
        ? 'Belum ada mapel aktif. Tambahkan dulu di menu Mata Pelajaran.'
        : 'Kamu belum ditugaskan mengajar mapel apa pun. Hubungi admin untuk ditugaskan lewat menu Guru.'; ?>
</div>

<?php else : ?>

<?php

$idMapel = (int) ($_GET['id_mapel'] ?? 0);
$idKelas = (int) ($_GET['id_kelas'] ?? 0);

$mapelValid = array_column($listMapel, 'id_mapel');
if ($idMapel && !in_array($idMapel, $mapelValid, true)) {
    $idMapel = 0;
}

$mapelDipilih = null;
foreach ($listMapel as $m) {
    if ((int) $m['id_mapel'] === $idMapel) {
        $mapelDipilih = $m;
        break;
    }
}

$listGuruMapel = [];

if (isAdmin()) {

    $idGuru = (int) ($_GET['id_guru'] ?? 0);

    if ($idMapel) {

        $stmtG = $pdo->prepare("
            SELECT g.id_guru, g.nama_guru
            FROM guru_mapel gm
            JOIN guru g ON g.id_guru = gm.id_guru
            WHERE gm.id_mapel = ?
            ORDER BY g.nama_guru ASC
        ");
        $stmtG->execute([$idMapel]);
        $listGuruMapel = $stmtG->fetchAll();

        if (count($listGuruMapel) === 1) {
            $idGuru = (int) $listGuruMapel[0]['id_guru'];
        }

    }

} else {

    $idGuru = (int) $_SESSION['id'];

}

$listKelas = $pdo->query("
    SELECT k.id_kelas, k.tingkat, k.rombel, j.nama_jurusan
    FROM kelas k
    JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    ORDER BY j.nama_jurusan ASC, FIELD(k.tingkat, 'X', 'XI', 'XII') ASC, k.rombel ASC
")->fetchAll();

?>

<form method="GET" action="index.php" class="row g-3 align-items-end mb-4">

    <input type="hidden" name="page" value="nilai">

    <div class="col-md-4">

        <label class="form-label">Mata Pelajaran</label>

        <select
            name="id_mapel"
            class="form-select"
            onchange="this.form.submit()">

            <option value="">-- Pilih Mapel --</option>

            <?php foreach ($listMapel as $m) : ?>

            <option value="<?= $m['id_mapel']; ?>" <?= $idMapel === (int) $m['id_mapel'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($m['nama_mapel']); ?> (KKM <?= $m['kkm']; ?>)
            </option>

            <?php endforeach; ?>

        </select>

    </div>

    <?php if (isAdmin() && $idMapel) : ?>

    <div class="col-md-3">

        <label class="form-label">Guru Pengampu</label>

        <select
            name="id_guru"
            class="form-select"
            onchange="this.form.submit()">

            <option value="">-- Pilih Guru --</option>

            <?php foreach ($listGuruMapel as $g) : ?>

            <option value="<?= $g['id_guru']; ?>" <?= $idGuru === (int) $g['id_guru'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($g['nama_guru']); ?>
            </option>

            <?php endforeach; ?>

        </select>

        <?php if (empty($listGuruMapel)) : ?>
        <div class="form-text text-danger">
            Belum ada guru yang mengampu mapel ini.
        </div>
        <?php endif; ?>

    </div>

    <?php elseif (isAdmin()) : ?>

        <input type="hidden" name="id_guru" value="">

    <?php else : ?>

        <input type="hidden" name="id_guru" value="<?= (int) $idGuru; ?>">

    <?php endif; ?>

    <div class="col-md-3">

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

<?php

$siap = $idMapel && $idKelas && $idGuru;

if ($siap) :

    $stmtS = $pdo->prepare("
        SELECT
            s.id_siswa, s.nis, s.nama_lengkap,
            n.nilai_akhir, n.predikat
        FROM siswa s
        JOIN siswa_kelas sk ON sk.id_siswa = s.id_siswa AND sk.id_kelas = ? AND sk.id_tahun = ?
        LEFT JOIN nilai n ON n.id_siswa = s.id_siswa AND n.id_mapel = ? AND n.id_tahun = ?
        WHERE s.status = 'Aktif'
        ORDER BY s.nama_lengkap ASC
    ");
    $stmtS->execute([$idKelas, $idTahunAktif, $idMapel, $idTahunAktif]);

    $siswaList = $stmtS->fetchAll();

    $kkm = (int) ($mapelDipilih['kkm'] ?? 0);

    if (empty($siswaList)) :

?>

<div class="alert alert-info">
    Belum ada siswa aktif di kelas ini untuk tahun ajaran <?= htmlspecialchars($tahunAktif['tahun'] . ' ' . $tahunAktif['semester']); ?>.
</div>

<?php else : ?>

<div class="d-flex justify-content-end gap-2 mb-3">

    <a
        href="actions/nilai/export_excel.php?id_mapel=<?= $idMapel; ?>&id_guru=<?= $idGuru; ?>&id_kelas=<?= $idKelas; ?>"
        class="btn btn-outline-success btn-sm">
        <i class="bi bi-file-earmark-excel"></i>
        Export Excel
    </a>

    <a
        href="actions/nilai/cetak.php?id_mapel=<?= $idMapel; ?>&id_guru=<?= $idGuru; ?>&id_kelas=<?= $idKelas; ?>"
        target="_blank"
        class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer"></i>
        Cetak / PDF
    </a>

</div>

<form action="actions/nilai/simpan.php" method="POST">

    <input type="hidden" name="id_mapel" value="<?= $idMapel; ?>">
    <input type="hidden" name="id_guru" value="<?= $idGuru; ?>">
    <input type="hidden" name="id_kelas" value="<?= $idKelas; ?>">

    <div class="card shadow-sm">

        <div class="card-body">

            <table id="tableNilai" class="table table-hover align-middle" data-kkm="<?= $kkm; ?>">

                <thead>

                <tr>

                    <th width="50">No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th width="110">Nilai (KKM <?= $kkm; ?>)</th>
                    <th width="90">Predikat</th>
                    <th width="140">Aksi</th>

                </tr>

                </thead>

                <tbody>

                <?php $no = 1; ?>

                <?php foreach ($siswaList as $s) : ?>

                <?php

                $sudahAdaNilai = $s['nilai_akhir'] !== null;
                $belowKkm      = $sudahAdaNilai && (float) $s['nilai_akhir'] < $kkm;

                ?>

                <tr id="rowSiswa<?= $s['id_siswa']; ?>">

                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($s['nis']); ?></td>
                    <td><?= htmlspecialchars($s['nama_lengkap']); ?></td>

                    <td>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            name="nilai[<?= $s['id_siswa']; ?>]"
                            id="nilai_<?= $s['id_siswa']; ?>"
                            class="form-control form-control-sm input-nilai <?= $belowKkm ? 'text-danger fw-bold' : ''; ?>"
                            value="<?= $sudahAdaNilai ? $s['nilai_akhir'] : ''; ?>">
                    </td>

                    <td class="text-center preview-predikat <?= $belowKkm ? 'text-danger fw-bold' : ''; ?>">
                        <?= $s['predikat'] ?? '-'; ?>
                    </td>

                    <td>

                        <button
                            type="button"
                            class="btn btn-warning btn-sm"
                            title="Fokus ke baris ini buat diedit"
                            onclick="fokusNilai(<?= $s['id_siswa']; ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <?php if ($sudahAdaNilai) : ?>

                        <a
                            href="actions/nilai/cetak_individu.php?id_siswa=<?= $s['id_siswa']; ?>&id_mapel=<?= $idMapel; ?>&id_guru=<?= $idGuru; ?>"
                            target="_blank"
                            class="btn btn-outline-secondary btn-sm"
                            title="Cetak nilai siswa ini">
                            <i class="bi bi-printer"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            title="Hapus nilai siswa ini"
                            onclick="konfirmasi('actions/nilai/hapus.php?id_siswa=<?= $s['id_siswa']; ?>&id_mapel=<?= $idMapel; ?>&id_kelas=<?= $idKelas; ?>&id_guru=<?= $idGuru; ?>')">
                            <i class="bi bi-trash"></i>
                        </button>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

    <button type="submit" class="btn btn-primary mt-3">
        <i class="bi bi-save"></i>
        Simpan Semua Nilai
    </button>

</form>

<?php endif; // empty siswaList ?>

<?php endif; // $siap ?>

<?php endif; // empty listMapel ?>

<?php endif; // !$tahunAktif ?>
