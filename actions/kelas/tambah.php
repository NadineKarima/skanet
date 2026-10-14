<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$tingkat    = $_POST['tingkat'] ?? "";
$id_jurusan = (int) ($_POST['id_jurusan'] ?? 0);
$rombel     = trim($_POST['rombel'] ?? "");

$tingkatValid = ['X', 'XI', 'XII'];

if (!in_array($tingkat, $tingkatValid, true) || $id_jurusan <= 0 || $rombel === "") {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data kelas tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=kelas");
    exit;

}

try {

    $stmt = $pdo->prepare("
        INSERT INTO kelas (tingkat, id_jurusan, rombel)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$tingkat, $id_jurusan, $rombel]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Kelas $tingkat / $rombel berhasil ditambahkan."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Kelas dengan tingkat, jurusan, dan rombel yang sama sudah ada."
        : "Gagal menyimpan data kelas.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=kelas");
exit;
