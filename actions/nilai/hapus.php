<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

$idSiswa = (int) ($_GET['id_siswa'] ?? 0);
$idMapel = (int) ($_GET['id_mapel'] ?? 0);
$idKelas = (int) ($_GET['id_kelas'] ?? 0);
$idGuru  = (int) ($_GET['id_guru'] ?? 0);

if ($idSiswa <= 0 || $idMapel <= 0) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data tidak valid."
    ];

    header("Location: ../../index.php?page=nilai");
    exit;

}

if (!isAdmin()) {

    $idGuruSesi = (int) $_SESSION['id'];

    $cek = $pdo->prepare("SELECT 1 FROM guru_mapel WHERE id_guru = ? AND id_mapel = ?");
    $cek->execute([$idGuruSesi, $idMapel]);

    if (!$cek->fetch()) {

        $_SESSION['flash'] = [
            "type"  => "error",
            "pesan" => "Kamu tidak berhak menghapus nilai ini."
        ];

        header("Location: ../../index.php?page=nilai");
        exit;

    }

}

$tahunAktif = $pdo
    ->query("SELECT id_tahun FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")
    ->fetch();

$idTahun = $tahunAktif['id_tahun'] ?? 0;

$stmt = $pdo->prepare("
    DELETE FROM nilai WHERE id_siswa = ? AND id_mapel = ? AND id_tahun = ?
");
$stmt->execute([$idSiswa, $idMapel, $idTahun]);

$_SESSION['flash'] = [
    "type"  => "success",
    "pesan" => "Nilai berhasil dihapus."
];

header("Location: ../../index.php?page=nilai&id_mapel=$idMapel&id_kelas=$idKelas&id_guru=$idGuru");
exit;
