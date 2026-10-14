<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireLogin();

$idSiswa = (int) ($_GET['id_siswa'] ?? 0);
$idKelas = (int) ($_GET['id_kelas'] ?? 0);

if ($idSiswa <= 0 || $idKelas <= 0) {
    die("Data tidak lengkap.");
}

$tahunAktif = $pdo->query("
    SELECT id_tahun, tahun, semester
    FROM tahun_ajaran
    WHERE status = 'Aktif'
    LIMIT 1
")->fetch();

if (!$tahunAktif) {
    die("Tidak ada tahun ajaran yang aktif.");
}

$sekolah = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();

$stmtSiswa = $pdo->prepare("
    SELECT s.id_siswa, s.nis, s.nama_lengkap
    FROM siswa s
    JOIN siswa_kelas sk
        ON sk.id_siswa = s.id_siswa
       AND sk.id_kelas = ?
       AND sk.id_tahun = ?
    WHERE s.id_siswa = ?
    LIMIT 1
");
$stmtSiswa->execute([$idKelas, $tahunAktif['id_tahun'], $idSiswa]);
$siswa = $stmtSiswa->fetch();

if (!$siswa) {
    die("Siswa tidak ditemukan di kelas tersebut.");
}

$stmtKelas = $pdo->prepare("
    SELECT k.tingkat, k.rombel, j.nama_jurusan
    FROM kelas k
    JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    WHERE k.id_kelas = ?
    LIMIT 1
");
$stmtKelas->execute([$idKelas]);
$kelas = $stmtKelas->fetch();

$namaKelas = $kelas
    ? $kelas['tingkat'] . ' ' . $kelas['nama_jurusan'] . ' ' . $kelas['rombel']
    : '-';

$listMapel = $pdo->query("
    SELECT id_mapel, nama_mapel, kkm
    FROM mapel
    WHERE status = 'Aktif'
    ORDER BY nama_mapel ASC
")->fetchAll();

$stmtNilai = $pdo->prepare("
    SELECT id_mapel, nilai_akhir, predikat
    FROM nilai
    WHERE id_siswa = ? AND id_tahun = ?
");
$stmtNilai->execute([$idSiswa, $tahunAktif['id_tahun']]);

$nilaiMap = [];
foreach ($stmtNilai->fetchAll() as $n) {
    $nilaiMap[$n['id_mapel']] = $n;
}

$total = 0;
$jumlah = 0;

foreach ($listMapel as $m) {
    if (isset($nilaiMap[$m['id_mapel']])) {
        $total += (float) $nilaiMap[$m['id_mapel']]['nilai_akhir'];
        $jumlah++;
    }
}

$rataRata = $jumlah > 0 ? round($total / $jumlah, 2) : null;

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan <?= htmlspecialchars($siswa['nama_lengkap']); ?></title>

<style>
    @page {
        size: A4 portrait;
        margin: 18mm;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 13px;
        color: #000;
        margin: 0;
    }

    .no-print {
        text-align: center;
        margin-bottom: 20px;
    }

    .kop {
        text-align: center;
        border-bottom: 3px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .kop h2 {
        margin: 0;
        font-size: 20px;
    }

    .kop p {
        margin: 3px 0;
    }

    .judul {
        text-align: center;
        margin-bottom: 20px;
    }

    .judul h3 {
        margin: 0 0 5px;
    }

    table.info,
    table.nilai {
        width: 100%;
        border-collapse: collapse;
    }

    table.info {
        margin-bottom: 20px;
    }

    table.info td {
        padding: 4px 0;
    }

    table.nilai th,
    table.nilai td {
        border: 1px solid #333;
        padding: 7px;
    }

    table.nilai th {
        background: #1e3a8a;
        color: #fff;
        text-align: center;
    }

    table.nilai td {
        text-align: center;
    }

    table.nilai td.mapel {
        text-align: left;
    }

    .belum-tuntas {
        color: #dc2626;
        font-weight: bold;
    }

    .rata {
        margin-top: 18px;
        text-align: right;
        font-size: 15px;
    }

    .ttd {
        margin-top: 55px;
        display: flex;
        justify-content: flex-end;
    }

    .ttd div {
        text-align: center;
        width: 220px;
    }

    .garis {
        margin-top: 55px;
        border-top: 1px solid #000;
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

<div class="judul">
    <h3>LAPORAN NILAI SISWA</h3>
    <div>Tahun Ajaran <?= htmlspecialchars($tahunAktif['tahun'] . ' - ' . $tahunAktif['semester']); ?></div>
</div>

<table class="info">
    <tr>
        <td width="25%">Nama Siswa</td>
        <td width="3%">:</td>
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
</table>

<table class="nilai">
    <thead>
        <tr>
            <th width="50">No</th>
            <th>Mata Pelajaran</th>
            <th width="80">KKM</th>
            <th width="90">Nilai</th>
            <th width="90">Predikat</th>
            <th width="120">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($listMapel)) : ?>
            <tr>
                <td colspan="6">Belum ada mata pelajaran aktif.</td>
            </tr>
        <?php else : ?>
            <?php $no = 1; foreach ($listMapel as $m) : ?>
                <?php
                    $n = $nilaiMap[$m['id_mapel']] ?? null;
                    $tuntas = $n && (float) $n['nilai_akhir'] >= (int) $m['kkm'];
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="mapel"><?= htmlspecialchars($m['nama_mapel']); ?></td>
                    <td><?= (int) $m['kkm']; ?></td>
                    <td class="<?= $n && !$tuntas ? 'belum-tuntas' : ''; ?>">
                        <?= $n ? htmlspecialchars($n['nilai_akhir']) : '-'; ?>
                    </td>
                    <td><?= $n ? htmlspecialchars($n['predikat']) : '-'; ?></td>
                    <td class="<?= $n && !$tuntas ? 'belum-tuntas' : ''; ?>">
                        <?= $n ? ($tuntas ? 'TUNTAS' : 'BELUM TUNTAS') : 'BELUM ADA NILAI'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="rata">
    <b>Rata-rata: <?= $rataRata !== null ? $rataRata : '-'; ?></b>
</div>

<div class="ttd">
    <div>
        <p>Mengetahui,<br>Guru / Wali Kelas</p>
        <div class="garis"></div>
        <p>(................................)</p>
    </div>
</div>

</body>
</html>
