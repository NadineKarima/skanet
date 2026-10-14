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

    header("Location: ../../index.php?page=mapel");
    exit;

}

try {

    $stmt = $pdo->prepare("DELETE FROM mapel WHERE id_mapel = ?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Mapel berhasil dihapus."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Mapel tidak bisa dihapus karena masih terhubung ke guru pengampu atau data nilai. Nonaktifkan saja kalau sudah tidak dipakai."
        : "Gagal menghapus data mapel.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=mapel");
exit;
