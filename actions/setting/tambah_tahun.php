<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$tahun    = trim($_POST['tahun'] ?? "");
$semester = $_POST['semester'] ?? "";

if ($tahun === "" || !in_array($semester, ['Ganjil', 'Genap'], true)) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data tahun ajaran tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=setting");
    exit;

}

try {

    $stmt = $pdo->prepare("
        INSERT INTO tahun_ajaran (tahun, semester, status)
        VALUES (?, ?, 'Nonaktif')
    ");
    $stmt->execute([$tahun, $semester]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Tahun ajaran $tahun $semester berhasil ditambahkan. Klik \"Aktifkan\" kalau sudah siap dipakai."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Tahun ajaran $tahun $semester sudah ada."
        : "Gagal menambahkan tahun ajaran.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=setting");
exit;
