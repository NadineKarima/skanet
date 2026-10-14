<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$nama = trim($_POST['nama_jurusan'] ?? "");

if ($nama === "") {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Nama jurusan wajib diisi."
    ];

    header("Location: ../../index.php?page=jurusan");
    exit;

}

try {

    $stmt = $pdo->prepare("INSERT INTO jurusan (nama_jurusan) VALUES (?)");
    $stmt->execute([$nama]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Jurusan \"$nama\" berhasil ditambahkan."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Nama jurusan \"$nama\" sudah ada."
        : "Gagal menyimpan data jurusan.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=jurusan");
exit;
