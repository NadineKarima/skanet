<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$id     = (int) ($_POST['id_mapel'] ?? 0);
$kode   = trim($_POST['kode_mapel'] ?? "");
$nama   = trim($_POST['nama_mapel'] ?? "");
$kkm    = (int) ($_POST['kkm'] ?? 75);
$status = $_POST['status'] ?? "Aktif";

$statusValid = ['Aktif', 'Nonaktif'];

if ($id <= 0 || $kode === "" || $nama === "" || !in_array($status, $statusValid, true)) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data mapel tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=mapel");
    exit;

}

$kkm = max(0, min(100, $kkm));

try {

    $stmt = $pdo->prepare("
        UPDATE mapel
        SET kode_mapel = ?, nama_mapel = ?, kkm = ?, status = ?
        WHERE id_mapel = ?
    ");
    $stmt->execute([$kode, $nama, $kkm, $status, $id]);

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Mapel berhasil diperbarui."
    ];

} catch (PDOException $e) {

    $pesan = ($e->getCode() === "23000")
        ? "Kode mapel \"$kode\" sudah dipakai mapel lain."
        : "Gagal memperbarui data mapel.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=mapel");
exit;
