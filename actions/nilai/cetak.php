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

$sekolah = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();

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
$kkm = (int) ($mapel['kkm'] ?? 0);

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Cetak Nilai - <?= htmlspecialchars($mapel['nama_mapel'] ?? ''); ?></title>

<style>

    body {
        font-family: Arial, sans-serif;
        font-size: 13px;
        color: #000;
        margin: 30px;
    }

    .kop {
        text-align: center;
        border-bottom: 3px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .kop h2 {
        margin: 0;
    }

    .kop p {
        margin: 2px 0;
    }

    .info-table {
        width: 100%;
        margin-bottom: 15px;
    }

    .info-table td {
        padding: 2px 0;
    }

    table.data {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table.data th, table.data td {
        border: 1px solid #333;
        padding: 7px;
        text-align: center;
    }

    table.data thead th {
        background: #1e3a8a;
        color: #ffffff;
    }

    table.data tbody tr:nth-child(even) {
        background: #f1f5f9;
    }

    table.data td.nama {
        text-align: left;
    }

    td.belum-tuntas {
        color: #dc2626;
        font-weight: bold;
    }

    .ttd {
        margin-top: 50px;
        display: flex;
        justify-content: space-between;
    }

    .ttd div {
        text-align: center;
        width: 250px;
    }

    .ttd .garis {
        margin-top: 60px;
        border-top: 1px solid #000;
    }

    .no-print {
        text-align: center;
        margin-bottom: 20px;
    }

    @media print {
        .no-print {
            display: none;
        }
    }

</style>

</head>
<body>

<div class="no-print">
    <button onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="kop">
    <h2><?= htmlspecialchars($sekolah['nama_sekolah'] ?? 'Nama Sekolah'); ?></h2>
    <p><?= htmlspecialchars($sekolah['alamat'] ?? ''); ?></p>
</div>

<h3 style="text-align:center;">DAFTAR NILAI SISWA</h3>

<table class="info-table">
    <tr>
        <td width="120">Mata Pelajaran</td>
        <td width="20">:</td>
        <td><?= htmlspecialchars($mapel['nama_mapel'] ?? '-'); ?></td>

        <td width="120">Kelas</td>
        <td width="20">:</td>
        <td><?= htmlspecialchars($namaKelas); ?></td>
    </tr>
    <tr>
        <td>Guru Pengampu</td>
        <td>:</td>
        <td><?= htmlspecialchars($guru['nama_guru'] ?? '-'); ?></td>

        <td>Tahun Ajaran</td>
        <td>:</td>
        <td><?= htmlspecialchars($tahunAktif['tahun'] . ' - ' . $tahunAktif['semester']); ?></td>
    </tr>
    <tr>
        <td>KKM</td>
        <td>:</td>
        <td><?= $kkm; ?></td>
    </tr>
</table>

<table class="data">

    <thead>
    <tr>
        <th>No</th>
        <th>NIS</th>
        <th>Nama Siswa</th>
        <th>Nilai</th>
        <th>Predikat</th>
    </tr>
    </thead>

    <tbody>

    <?php $no = 1; foreach ($siswaList as $s) : ?>

    <?php
    $belumTuntas = $s['nilai_akhir'] !== null && (float) $s['nilai_akhir'] < $kkm;
    $cls = $belumTuntas ? 'belum-tuntas' : '';
    ?>

    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($s['nis']); ?></td>
        <td class="nama"><?= htmlspecialchars($s['nama_lengkap']); ?></td>
        <td class="<?= $cls; ?>"><?= $s['nilai_akhir'] !== null ? $s['nilai_akhir'] : '-'; ?></td>
        <td class="<?= $cls; ?>"><?= $s['predikat'] ?? '-'; ?></td>
    </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<div class="ttd">

    <div>
        <p>Guru Mata Pelajaran</p>
        <div class="garis"></div>
        <p><?= htmlspecialchars($guru['nama_guru'] ?? '(.........................)'); ?></p>
    </div>

    <div>
        <p>Mengetahui,<br>Kepala Sekolah</p>
        <div class="garis"></div>
        <p><?= htmlspecialchars($sekolah['kepala_sekolah'] ?? '(.........................)'); ?></p>
    </div>

</div>

</body>
</html>
