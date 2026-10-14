<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$nama    = trim($_POST['nama_sekolah'] ?? "");
$npsn    = trim($_POST['npsn'] ?? "");
$alamat  = trim($_POST['alamat'] ?? "");
$telepon = trim($_POST['telepon'] ?? "");
$email   = trim($_POST['email'] ?? "");
$kepsek  = trim($_POST['kepala_sekolah'] ?? "");

if ($nama === "") {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Nama sekolah wajib diisi."
    ];

    header("Location: ../../index.php?page=setting");
    exit;

}

$ada = $pdo->query("SELECT id_sekolah FROM sekolah LIMIT 1")->fetch();

if ($ada) {

    $stmt = $pdo->prepare("
        UPDATE sekolah
        SET nama_sekolah = ?, npsn = ?, alamat = ?, telepon = ?, email = ?, kepala_sekolah = ?
        WHERE id_sekolah = ?
    ");
    $stmt->execute([$nama, $npsn, $alamat, $telepon, $email, $kepsek, $ada['id_sekolah']]);

} else {

    $stmt = $pdo->prepare("
        INSERT INTO sekolah (nama_sekolah, npsn, alamat, telepon, email, kepala_sekolah)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nama, $npsn, $alamat, $telepon, $email, $kepsek]);

}

$_SESSION['flash'] = [
    "type"  => "success",
    "pesan" => "Profil sekolah berhasil diperbarui."
];

header("Location: ../../index.php?page=setting");
exit;
