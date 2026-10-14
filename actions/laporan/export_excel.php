<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

$idKelas = (int) ($_GET['id_kelas'] ?? 0);

if ($idKelas <= 0) {
    die("Data tidak lengkap.");
}

$tahunAktif = $pdo->query("SELECT id_tahun, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();

if (!$tahunAktif) {
    die("Tidak ada tahun ajaran yang aktif.");
}

$kelas = $pdo->prepare("
    SELECT k.tingkat, k.rombel, j.nama_jurusan
    FROM kelas k JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    WHERE k.id_kelas = ?
");
$kelas->execute([$idKelas]);
$kelas = $kelas->fetch();
$namaKelas = ($kelas['tingkat'] ?? '') . ' ' . ($kelas['nama_jurusan'] ?? '') . ' ' . ($kelas['rombel'] ?? '');

$listMapel = $pdo->query("SELECT id_mapel, nama_mapel, kkm FROM mapel WHERE status = 'Aktif' ORDER BY nama_mapel ASC")->fetchAll();

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
        SELECT id_siswa, id_mapel, nilai_akhir
        FROM nilai
        WHERE id_tahun = ? AND id_siswa IN ($placeholder)
    ");
    $stmtNilai->execute(array_merge([$tahunAktif['id_tahun']], $idSiswaList));

    foreach ($stmtNilai->fetchAll() as $n) {
        $nilaiMap[$n['id_siswa']][$n['id_mapel']] = $n['nilai_akhir'];
    }

}

$namaFile = "Rekap_Nilai_" . preg_replace('/[^A-Za-z0-9]/', '_', $namaKelas) . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$jumlahKolom = 4 + count($listMapel);

?>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; font-family:Calibri,Arial,sans-serif;">

    <colgroup>
        <col style="width:45px;">
        <col style="width:110px;">
        <col style="width:220px;">
        <?php foreach ($listMapel as $m) : ?>
        <col style="width:110px;">
        <?php endforeach; ?>
        <col style="width:90px;">
    </colgroup>


    <tr>
        <td colspan="<?= $jumlahKolom; ?>" style="font-size:16px; font-weight:bold; background:#1e3a8a; color:#ffffff;">
            Rekap Nilai - <?= htmlspecialchars($namaKelas); ?>
        </td>
    </tr>

    <tr>
        <td colspan="<?= $jumlahKolom; ?>" style="background:#dbeafe;">
            Tahun Ajaran: <b><?= htmlspecialchars($tahunAktif['tahun'] . ' ' . $tahunAktif['semester']); ?></b>
        </td>
    </tr>

    <tr><td colspan="<?= $jumlahKolom; ?>"></td></tr>

    <tr style="background:#1e3a8a; color:#ffffff; font-weight:bold;">
        <th>No</th>
        <th>NIS</th>
        <th>Nama Siswa</th>
        <?php foreach ($listMapel as $m) : ?>
        <th><?= htmlspecialchars($m['nama_mapel']); ?></th>
        <?php endforeach; ?>
        <th>Rata-rata</th>
    </tr>

    <?php $no = 1; foreach ($siswaList as $s) : ?>

    <?php
    $total = 0;
    $jumlah = 0;
    $rowBg = ($no % 2 === 0) ? '#f1f5f9' : '#ffffff';
    ?>

    <tr style="background:<?= $rowBg; ?>;">
        <td align="center"><?= $no++; ?></td>
        <td><?= htmlspecialchars($s['nis']); ?></td>
        <td><?= htmlspecialchars($s['nama_lengkap']); ?></td>
        <?php foreach ($listMapel as $m) : ?>
            <?php
            $nilai = $nilaiMap[$s['id_siswa']][$m['id_mapel']] ?? null;
            if ($nilai !== null) { $total += (float) $nilai; $jumlah++; }
            $belumTuntas = $nilai !== null && (float) $nilai < (int) $m['kkm'];
            $warna = $belumTuntas ? 'color:#dc2626; font-weight:bold;' : '';
            ?>
            <td align="center" style="<?= $warna; ?>"><?= $nilai !== null ? $nilai : ''; ?></td>
        <?php endforeach; ?>
        <td align="center" style="font-weight:bold;"><?= $jumlah > 0 ? round($total / $jumlah, 2) : ''; ?></td>
    </tr>

    <?php endforeach; ?>

</table>
