<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";

requireAdmin();

$id       = (int) ($_POST['id_siswa'] ?? 0);
$nama     = trim($_POST['nama_lengkap'] ?? "");
$jk       = $_POST['jenis_kelamin'] ?? "";
$id_kelas = (int) ($_POST['id_kelas'] ?? 0);
$alamat   = trim($_POST['alamat'] ?? "");
$status   = $_POST['status'] ?? "Aktif";

$statusValid = ['Aktif', 'Lulus', 'Pindah'];

if (
    $id <= 0 || $nama === "" ||
    !in_array($jk, ['L', 'P'], true) || $id_kelas <= 0 ||
    !in_array($status, $statusValid, true)
) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Data siswa tidak lengkap atau tidak valid."
    ];

    header("Location: ../../index.php?page=siswa");
    exit;

}

try {

    $pdo->beginTransaction();

    // CATATAN: kolom `nis` SENGAJA gak ada di SET -- NIS gak boleh
    // diubah lagi setelah siswa dibuat (permintaan guru). Field-nya
    // di form juga udah di-disable, tapi validasi yang beneran
    // ngejamin itu di sini: server gak pernah nyentuh nis, apapun
    // yang dikirim dari client.
    $stmt = $pdo->prepare("
        UPDATE siswa
        SET nama_lengkap = ?, jenis_kelamin = ?, alamat = ?, status = ?
        WHERE id_siswa = ?
    ");
    $stmt->execute([$nama, $jk, $alamat, $status, $id]);

    $tahunAktif = $pdo
        ->query("SELECT id_tahun FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")
        ->fetch();

    if ($tahunAktif) {

        $stmt2 = $pdo->prepare("
            INSERT INTO siswa_kelas (id_siswa, id_kelas, id_tahun)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE id_kelas = VALUES(id_kelas)
        ");
        $stmt2->execute([$id, $id_kelas, $tahunAktif['id_tahun']]);

    }

    $pdo->commit();

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "Data siswa berhasil diperbarui."
    ];

} catch (PDOException $e) {

    $pdo->rollBack();

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Gagal memperbarui data siswa."
    ];

}

header("Location: ../../index.php?page=siswa");
exit;
