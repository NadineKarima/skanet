<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$nis      = trim($_POST['nis'] ?? "");
$nama     = trim($_POST['nama_lengkap'] ?? "");
$jk       = $_POST['jenis_kelamin'] ?? "";
$id_kelas = (int) ($_POST['id_kelas'] ?? 0);
$alamat   = trim($_POST['alamat'] ?? "");

if ($nis === "" || $nama === "" || !in_array($jk, ['L', 'P'], true) || $id_kelas <= 0) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data siswa tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=siswa");
    exit;

}

$tahunAktif = $pdo
    ->query("SELECT id_tahun FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")
    ->fetch();

if (!$tahunAktif) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Tidak ada tahun ajaran yang aktif, siswa tidak bisa didaftarkan ke kelas."
    ];

    header("Location: ../../index.php?page=siswa");
    exit;

}

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO siswa (nis, nama_lengkap, jenis_kelamin, alamat)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$nis, $nama, $jk, $alamat]);

    $idSiswa = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("
        INSERT INTO siswa_kelas (id_siswa, id_kelas, id_tahun)
        VALUES (?, ?, ?)
    ");
    $stmt2->execute([$idSiswa, $id_kelas, $tahunAktif['id_tahun']]);

    $pdo->commit();

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Siswa \"$nama\" berhasil ditambahkan."
    ];

} catch (PDOException $e) {

    $pdo->rollBack();

    $pesan = ($e->getCode() === "23000")
        ? "NIS \"$nis\" sudah dipakai siswa lain."
        : "Gagal menyimpan data siswa.";

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => $pesan
    ];

}

header("Location: ../../index.php?page=siswa");
exit;
