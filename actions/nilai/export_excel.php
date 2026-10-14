<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

$idMapel = (int) ($_GET['id_mapel'] ?? 0);
$idGuru  = (int) ($_GET['id_guru'] ?? 0);
$idKelas = (int) ($_GET['id_kelas'] ?? 0);

if ($idMapel <= 0 || $idKelas <= 0) {
    die("Data tidak lengkap.");
}

if (!isAdmin()) {

    $idGuruSesi = (int) $_SESSION['id'];

    $cek = $pdo->prepare("SELECT 1 FROM guru_mapel WHERE id_guru = ? AND id_mapel = ?");
    $cek->execute([$idGuruSesi, $idMapel]);

    if (!$cek->fetch()) {
        die("Kamu tidak berhak mengakses data ini.");
    }

    $idGuru = $idGuruSesi;

}

$tahunAktif = $pdo->query("SELECT id_tahun, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();

if (!$tahunAktif) {
    die("Tidak ada tahun ajaran yang aktif.");
}

$sekolah = $pdo->query("SELECT nama_sekolah FROM sekolah LIMIT 1")->fetch();

$mapel = $pdo->prepare("SELECT nama_mapel, kkm FROM mapel WHERE id_mapel = ?");
$mapel->execute([$idMapel]);
$mapel = $mapel->fetch();

$kelas = $pdo->prepare("
    SELECT k.tingkat, k.rombel, j.nama_jurusan
    FROM kelas k JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    WHERE k.id_kelas = ?
");
$kelas->execute([$idKelas]);
$kelas = $kelas->fetch();

$guru = null;
if ($idGuru) {
    $g = $pdo->prepare("SELECT nama_guru FROM guru WHERE id_guru = ?");
    $g->execute([$idGuru]);
    $guru = $g->fetch();
}

$stmt = $pdo->prepare("
    SELECT
        s.nis, s.nama_lengkap,
        n.nilai_akhir, n.predikat
    FROM siswa s
    JOIN siswa_kelas sk ON sk.id_siswa = s.id_siswa AND sk.id_kelas = ? AND sk.id_tahun = ?
    LEFT JOIN nilai n ON n.id_siswa = s.id_siswa AND n.id_mapel = ? AND n.id_tahun = ?
    WHERE s.status = 'Aktif'
    ORDER BY s.nama_lengkap ASC
");
$stmt->execute([$idKelas, $tahunAktif['id_tahun'], $idMapel, $tahunAktif['id_tahun']]);
$siswaList = $stmt->fetchAll();

$namaKelas = ($kelas['tingkat'] ?? '') . ' ' . ($kelas['nama_jurusan'] ?? '') . ' ' . ($kelas['rombel'] ?? '');
$namaFile  = "Nilai_" . preg_replace('/[^A-Za-z0-9]/', '_', $mapel['nama_mapel'] ?? 'mapel') . "_" . preg_replace('/[^A-Za-z0-9]/', '_', $namaKelas) . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$kkm = (int) ($mapel['kkm'] ?? 0);

?>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; font-family:Calibri,Arial,sans-serif;">

    <colgroup>
        <col style="width:45px;">
        <col style="width:110px;">
        <col style="width:260px;">
        <col style="width:80px;">
        <col style="width:90px;">
    </colgroup>


    <tr>
        <td colspan="5" style="font-size:16px; font-weight:bold; background:#1e3a8a; color:#ffffff;">
            <?= htmlspecialchars($sekolah['nama_sekolah'] ?? 'SK@NET'); ?> -- Daftar Nilai <?= htmlspecialchars($mapel['nama_mapel'] ?? '-'); ?>
        </td>
    </tr>

    <tr>
        <td colspan="5" style="background:#dbeafe;">
            Kelas: <b><?= htmlspecialchars($namaKelas); ?></b> &nbsp; | &nbsp;
            Guru: <b><?= htmlspecialchars($guru['nama_guru'] ?? '-'); ?></b> &nbsp; | &nbsp;
            Tahun Ajaran: <b><?= htmlspecialchars($tahunAktif['tahun'] . ' ' . $tahunAktif['semester']); ?></b> &nbsp; | &nbsp;
            KKM: <b><?= $kkm; ?></b>
        </td>
    </tr>

    <tr><td colspan="5"></td></tr>

    <tr style="background:#1e3a8a; color:#ffffff; font-weight:bold;">
        <th>No</th>
        <th>NIS</th>
        <th>Nama Lengkap</th>
        <th>Nilai</th>
        <th>Predikat</th>
    </tr>

    <?php $no = 1; foreach ($siswaList as $s) : ?>

    <?php
    $rowBg = ($no % 2 === 0) ? '#f1f5f9' : '#ffffff';
    $belumTuntas = $s['nilai_akhir'] !== null && (float) $s['nilai_akhir'] < $kkm;
    $warnaNilai  = $belumTuntas ? 'color:#dc2626; font-weight:bold;' : '';
    ?>

    <tr style="background:<?= $rowBg; ?>;">
        <td align="center"><?= $no++; ?></td>
        <td><?= htmlspecialchars($s['nis']); ?></td>
        <td><?= htmlspecialchars($s['nama_lengkap']); ?></td>
        <td align="center" style="<?= $warnaNilai; ?>"><?= $s['nilai_akhir'] !== null ? $s['nilai_akhir'] : ''; ?></td>
        <td align="center" style="<?= $warnaNilai; ?>"><?= $s['predikat'] ?? ''; ?></td>
    </tr>

    <?php endforeach; ?>

</table>
