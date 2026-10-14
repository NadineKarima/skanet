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

    header("Location: ../../index.php?page=siswa");
    exit;

}

try {

    $stmt = $pdo->prepare("DELETE FROM siswa WHERE id_siswa = ?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Siswa berhasil dihapus."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Siswa tidak bisa dihapus karena sudah punya data nilai. Ubah status jadi \"Lulus\" atau \"Pindah\" lewat tombol Edit, jangan dihapus."
        : "Gagal menghapus data siswa.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=siswa");
exit;
