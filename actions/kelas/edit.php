<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$id         = (int) ($_POST['id_kelas'] ?? 0);
$tingkat    = $_POST['tingkat'] ?? "";
$id_jurusan = (int) ($_POST['id_jurusan'] ?? 0);
$rombel     = trim($_POST['rombel'] ?? "");

$tingkatValid = ['X', 'XI', 'XII'];

if ($id <= 0 || !in_array($tingkat, $tingkatValid, true) || $id_jurusan <= 0 || $rombel === "") {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data kelas tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=kelas");
    exit;

}

try {

    $stmt = $pdo->prepare("
        UPDATE kelas
        SET tingkat = ?, id_jurusan = ?, rombel = ?
        WHERE id_kelas = ?
    ");
    $stmt->execute([$tingkat, $id_jurusan, $rombel, $id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Kelas berhasil diperbarui."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Kelas dengan tingkat, jurusan, dan rombel yang sama sudah ada."
        : "Gagal memperbarui data kelas.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=kelas");
exit;
