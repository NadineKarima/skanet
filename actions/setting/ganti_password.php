<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

$passwordLama = $_POST['password_lama'] ?? "";
$passwordBaru = $_POST['password_baru'] ?? "";

if ($passwordLama === "" || strlen($passwordBaru) < 7) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Password lama wajib diisi, password baru minimal 7 karakter."
    ];

    header("Location: ../../index.php?page=setting");
    exit;

}

$tabel   = isAdmin() ? "admin" : "guru";
$kolomId = isAdmin() ? "id_admin" : "id_guru";
$id      = (int) $_SESSION['id'];

$stmt = $pdo->prepare("SELECT password FROM $tabel WHERE $kolomId = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user || !password_verify($passwordLama, $user['password'])) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Password lama yang kamu masukkan salah."
    ];

    header("Location: ../../index.php?page=setting");
    exit;

}

// Password baru gak boleh sama persis kayak password lama.
// Dicek dengan password_verify() (bukan string compare biasa),
// soalnya yang kita punya cuma hash password lama di database.
if (password_verify($passwordBaru, $user['password'])) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Password baru tidak boleh sama dengan password lama."
    ];

    header("Location: ../../index.php?page=setting");
    exit;

}

$hashBaru = password_hash($passwordBaru, PASSWORD_DEFAULT);

$update = $pdo->prepare("UPDATE $tabel SET password = ? WHERE $kolomId = ?");
$update->execute([$hashBaru, $id]);

$_SESSION['flash'] = [
    "type"  => "success",
    "pesan" => "Password berhasil diubah."
];

header("Location: ../../index.php?page=setting");
exit;
