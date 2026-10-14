<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

$idSiswa = (int) ($_GET['id_siswa'] ?? 0);
$idMapel = (int) ($_GET['id_mapel'] ?? 0);
$idGuru  = (int) ($_GET['id_guru'] ?? 0);

if ($idSiswa <= 0 || $idMapel <= 0) {
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

$siswa = $pdo->prepare("SELECT nis, nama_lengkap FROM siswa WHERE id_siswa = ?");
$siswa->execute([$idSiswa]);
$siswa = $siswa->fetch();

if (!$siswa) {
    die("Siswa tidak ditemukan.");
}

$kelasRow = $pdo->prepare("
    SELECT k.tingkat, k.rombel, j.nama_jurusan
    FROM siswa_kelas sk
    JOIN kelas k ON k.id_kelas = sk.id_kelas
    JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    WHERE sk.id_siswa = ? AND sk.id_tahun = ?
");
$kelasRow->execute([$idSiswa, $tahunAktif['id_tahun']]);
$kelasRow = $kelasRow->fetch();
$namaKelas = $kelasRow ? ($kelasRow['tingkat'] . ' ' . $kelasRow['nama_jurusan'] . ' ' . $kelasRow['rombel']) : '-';

$mapel = $pdo->prepare("SELECT nama_mapel, kkm FROM mapel WHERE id_mapel = ?");
$mapel->execute([$idMapel]);
$mapel = $mapel->fetch();

$guru = null;
if ($idGuru) {
    $g = $pdo->prepare("SELECT nama_guru FROM guru WHERE id_guru = ?");
    $g->execute([$idGuru]);
    $guru = $g->fetch();
}

$nilai = $pdo->prepare("
    SELECT nilai_akhir, predikat
    FROM nilai
    WHERE id_siswa = ? AND id_mapel = ? AND id_tahun = ?
");
$nilai->execute([$idSiswa, $idMapel, $tahunAktif['id_tahun']]);
$nilai = $nilai->fetch();

if (!$nilai) {
    die("Siswa ini belum punya nilai untuk mapel tersebut.");
}

$kkm = (int) ($mapel['kkm'] ?? 0);
$tuntas = (float) $nilai['nilai_akhir'] >= $kkm;

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Nilai <?= htmlspecialchars($siswa['nama_lengkap']); ?> - <?= htmlspecialchars($mapel['nama_mapel'] ?? ''); ?></title>

<style>

    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #000;
        margin: 0;
        padding: 30px;
    }

    .slip {
        max-width: 460px;
        margin: 0 auto;
        border: 2px solid #1e3a8a;
        border-radius: 8px;
        padding: 25px;
    }

    .kop {
        text-align: center;
        border-bottom: 2px solid #1e3a8a;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .kop h3 {
        margin: 0;
        color: #1e3a8a;
    }

    .kop p {
        margin: 2px 0;
        font-size: 12px;
        color: #555;
    }

    table.info td {
        padding: 3px 0;
        vertical-align: top;
    }

    .nilai-akhir-box {
        text-align: center;
        margin: 20px 0;
        padding: 18px;
        border-radius: 8px;
        background: <?= $tuntas ? '#dcfce7' : '#fee2e2'; ?>;
    }

    .nilai-akhir-box .angka {
        font-size: 42px;
        font-weight: bold;
        color: <?= $tuntas ? '#15803d' : '#dc2626'; ?>;
    }

    .nilai-akhir-box .status {
        font-weight: bold;
        color: <?= $tuntas ? '#15803d' : '#dc2626'; ?>;
    }

    .ttd {
        margin-top: 40px;
        text-align: right;
    }

    .ttd .garis {
        margin-top: 55px;
        border-top: 1px solid #000;
        width: 200px;
        margin-left: auto;
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

<div class="slip">

    <div class="kop">
        <h3><?= htmlspecialchars($sekolah['nama_sekolah'] ?? 'SK@NET'); ?></h3>
        <p><?= htmlspecialchars($sekolah['alamat'] ?? ''); ?></p>
    </div>

    <table class="info" width="100%">
        <tr>
            <td width="35%">Nama Siswa</td>
            <td width="5%">:</td>
            <td><b><?= htmlspecialchars($siswa['nama_lengkap']); ?></b></td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td><?= htmlspecialchars($siswa['nis']); ?></td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td><?= htmlspecialchars($namaKelas); ?></td>
        </tr>
        <tr>
            <td>Mata Pelajaran</td>
            <td>:</td>
            <td><?= htmlspecialchars($mapel['nama_mapel'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Guru Pengampu</td>
            <td>:</td>
            <td><?= htmlspecialchars($guru['nama_guru'] ?? '-'); ?></td>
        </tr>
        <tr>
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

    <div class="nilai-akhir-box">
        <div>Nilai</div>
        <div class="angka"><?= $nilai['nilai_akhir']; ?></div>
        <div>Predikat: <b><?= $nilai['predikat']; ?></b></div>
        <div class="status"><?= $tuntas ? 'TUNTAS' : 'BELUM TUNTAS'; ?></div>
    </div>

    <div class="ttd">
        <p>Guru Mata Pelajaran</p>
        <div class="garis"></div>
        <p><?= htmlspecialchars($guru['nama_guru'] ?? '(.........................)'); ?></p>
    </div>

</div>

</body>
</html>
