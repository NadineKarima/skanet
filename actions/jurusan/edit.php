<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$id   = (int) ($_POST['id_jurusan'] ?? 0);
$nama = trim($_POST['nama_jurusan'] ?? "");

if ($id <= 0 || $nama === "") {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data tidak valid."
    ];

    header("Location: ../../index.php?page=jurusan");
    exit;

}

try {

    $stmt = $pdo->prepare("
        UPDATE jurusan
        SET nama_jurusan = ?
        WHERE id_jurusan = ?
    ");
    $stmt->execute([$nama, $id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Jurusan berhasil diperbarui."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Nama jurusan \"$nama\" sudah dipakai jurusan lain."
        : "Gagal memperbarui data jurusan.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=jurusan");
exit;
