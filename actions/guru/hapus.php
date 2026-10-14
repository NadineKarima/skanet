<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data tidak valid."
    ];

    header("Location: ../../index.php?page=guru");
    exit;

}

try {

    $stmt = $pdo->prepare("DELETE FROM guru WHERE id_guru = ?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Guru berhasil dihapus."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Guru tidak bisa dihapus karena masih ada data nilai yang diinput olehnya. Nonaktifkan saja akunnya lewat tombol Edit."
        : "Gagal menghapus data guru.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=guru");
exit;
