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

    header("Location: ../../index.php?page=jurusan");
    exit;

}

try {

    $stmt = $pdo->prepare("DELETE FROM jurusan WHERE id_jurusan = ?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Jurusan berhasil dihapus."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Jurusan tidak bisa dihapus karena masih dipakai di data Kelas."
        : "Gagal menghapus data jurusan.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=jurusan");
exit;
