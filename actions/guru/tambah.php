<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$nip      = trim($_POST['nip'] ?? "");
$username = trim($_POST['username'] ?? "");
$password = $_POST['password'] ?? "";
$nama     = trim($_POST['nama_guru'] ?? "");
$jk       = $_POST['jenis_kelamin'] ?? "";
$alamat   = trim($_POST['alamat'] ?? "");
$hp       = trim($_POST['no_hp'] ?? "");
$mapelIds = array_map('intval', $_POST['mapel'] ?? []);

if (
    $nip === "" || $username === "" || strlen($password) < 7 ||
    $nama === "" || !in_array($jk, ['L', 'P'], true)
) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data guru belum lengkap, atau password kurang dari 7 karakter."
    ];

    header("Location: ../../index.php?page=guru");
    exit;

}

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO guru
            (nip, username, password, nama_guru, jenis_kelamin, alamat, no_hp)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $nip,
        $username,
        password_hash($password, PASSWORD_DEFAULT),
        $nama,
        $jk,
        $alamat,
        $hp
    ]);

    $idGuru = $pdo->lastInsertId();

    if (!empty($mapelIds)) {

        $stmtMapel = $pdo->prepare("
            INSERT INTO guru_mapel (id_guru, id_mapel) VALUES (?, ?)
        ");

        foreach ($mapelIds as $idMapel) {
            $stmtMapel->execute([$idGuru, $idMapel]);
        }

    }

    $pdo->commit();

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Guru \"$nama\" berhasil ditambahkan."
    ];

} catch (PDOException $e) {

    $pdo->rollBack();

    $pesan = ($e->getCode() === "23000")
        ? "NIP atau username sudah dipakai guru lain."
        : "Gagal menyimpan data guru.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=guru");
exit;
