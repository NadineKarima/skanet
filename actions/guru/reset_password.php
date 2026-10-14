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

$cek = $pdo->prepare("SELECT nip FROM guru WHERE id_guru = ?");
$cek->execute([$id]);
$guru = $cek->fetch();

if (!$guru) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Guru tidak ditemukan."
    ];

    header("Location: ../../index.php?page=guru");
    exit;

}

$passwordBaru = password_hash($guru['nip'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE guru SET password = ? WHERE id_guru = ?");
$stmt->execute([$passwordBaru, $id]);

$_SESSION['flash'] = [
    "type"  => "success",
    "pesan" => "Password direset ke NIP (" . $guru['nip'] . "). Sampaikan ke guru yang bersangkutan, lalu minta ganti password lewat menu Setting."
];

header("Location: ../../index.php?page=guru");
exit;
