<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$kode = trim($_POST['kode_mapel'] ?? "");
$nama = trim($_POST['nama_mapel'] ?? "");
$kkm  = (int) ($_POST['kkm'] ?? 75);

if ($kode === "" || $nama === "") {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Kode dan nama mapel wajib diisi."
    ];

    header("Location: ../../index.php?page=mapel");
    exit;

}

$kkm = max(0, min(100, $kkm));

try {

    $stmt = $pdo->prepare("
        INSERT INTO mapel (kode_mapel, nama_mapel, kkm)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$kode, $nama, $kkm]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Mapel \"$nama\" berhasil ditambahkan."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Kode mapel \"$kode\" sudah dipakai."
        : "Gagal menyimpan data mapel.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=mapel");
exit;
