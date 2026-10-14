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

$sekolah = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();

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

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Rekap Nilai - <?= htmlspecialchars($namaKelas); ?></title>

<style>

    @page {
        size: landscape;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #000;
        margin: 25px;
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

    table.data {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table.data th, table.data td {
        border: 1px solid #333;
        padding: 6px;
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

    .belum-tuntas {
        color: #dc2626;
        font-weight: bold;
    }

    .ttd {
        margin-top: 50px;
        display: flex;
        justify-content: flex-end;
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

<h3 style="text-align:center;">
    REKAP NILAI KELAS <?= htmlspecialchars(strtoupper($namaKelas)); ?>
    <br>
    <small>Tahun Ajaran <?= htmlspecialchars($tahunAktif['tahun'] . ' - ' . $tahunAktif['semester']); ?></small>
</h3>

<table class="data">

    <thead>
    <tr>
        <th>No</th>
        <th>NIS</th>
        <th>Nama Siswa</th>
        <?php foreach ($listMapel as $m) : ?>
        <th><?= htmlspecialchars($m['nama_mapel']); ?></th>
        <?php endforeach; ?>
        <th>Rata-rata</th>
    </tr>
    </thead>

    <tbody>

    <?php $no = 1; foreach ($siswaList as $s) : ?>

    <?php $total = 0; $jumlah = 0; ?>

    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($s['nis']); ?></td>
        <td class="nama"><?= htmlspecialchars($s['nama_lengkap']); ?></td>

        <?php foreach ($listMapel as $m) : ?>

            <?php
            $nilai = $nilaiMap[$s['id_siswa']][$m['id_mapel']] ?? null;
            $belumTuntas = $nilai !== null && (float) $nilai < (int) $m['kkm'];
            if ($nilai !== null) { $total += (float) $nilai; $jumlah++; }
            ?>

            <td class="<?= $belumTuntas ? 'belum-tuntas' : ''; ?>">
                <?= $nilai !== null ? $nilai : '-'; ?>
            </td>

        <?php endforeach; ?>

        <td><b><?= $jumlah > 0 ? round($total / $jumlah, 2) : '-'; ?></b></td>

    </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<div class="ttd">

    <div>
        <p>Mengetahui,<br>Kepala Sekolah</p>
        <div class="garis"></div>
        <p><?= htmlspecialchars($sekolah['kepala_sekolah'] ?? '(.........................)'); ?></p>
    </div>

</div>

</body>
</html>
