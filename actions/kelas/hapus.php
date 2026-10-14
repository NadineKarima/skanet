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

    header("Location: ../../index.php?page=kelas");
    exit;

}

try {

    $stmt = $pdo->prepare("DELETE FROM kelas WHERE id_kelas = ?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Kelas berhasil dihapus."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Kelas tidak bisa dihapus karena masih ada siswa terdaftar di kelas ini."
        : "Gagal menghapus data kelas.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=kelas");
exit;
