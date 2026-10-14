<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$id       = (int) ($_POST['id_guru'] ?? 0);
$username = trim($_POST['username'] ?? "");
$nama     = trim($_POST['nama_guru'] ?? "");
$jk       = $_POST['jenis_kelamin'] ?? "";
$alamat   = trim($_POST['alamat'] ?? "");
$hp       = trim($_POST['no_hp'] ?? "");
$status   = $_POST['status'] ?? "Aktif";
$mapelIds = array_map('intval', $_POST['mapel'] ?? []);

if (
    $id <= 0 || $username === "" || $nama === "" ||
    !in_array($jk, ['L', 'P'], true) ||
    !in_array($status, ['Aktif', 'Nonaktif'], true)
) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data guru tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=guru");
    exit;

}

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        UPDATE guru
        SET username = ?, nama_guru = ?, jenis_kelamin = ?,
            alamat = ?, no_hp = ?, status = ?
        WHERE id_guru = ?
    ");
    $stmt->execute([$username, $nama, $jk, $alamat, $hp, $status, $id]);

    $pdo->prepare("DELETE FROM guru_mapel WHERE id_guru = ?")->execute([$id]);

    if (!empty($mapelIds)) {

        $stmtMapel = $pdo->prepare("
            INSERT INTO guru_mapel (id_guru, id_mapel) VALUES (?, ?)
        ");

        foreach ($mapelIds as $idMapel) {
            $stmtMapel->execute([$id, $idMapel]);
        }

    }

    $pdo->commit();

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Data guru berhasil diperbarui."
    ];

} catch (PDOException $e) {

    $pdo->rollBack();

    $pesan = ($e->getCode() === "23000")
        ? "Username sudah dipakai guru lain."
        : "Gagal memperbarui data guru.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=guru");
exit;
