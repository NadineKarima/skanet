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

    header("Location: ../../index.php?page=setting");
    exit;

}

try {

    $pdo->beginTransaction();

    $pdo->exec("UPDATE tahun_ajaran SET status = 'Nonaktif'");

    $stmt = $pdo->prepare("UPDATE tahun_ajaran SET status = 'Aktif' WHERE id_tahun = ?");
    $stmt->execute([$id]);

    $pdo->commit();

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Tahun ajaran berhasil diaktifkan."
    ];

} catch (PDOException $e) {

    $pdo->rollBack();

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Gagal mengaktifkan tahun ajaran."
    ];

}

header("Location: ../../index.php?page=setting");
exit;
