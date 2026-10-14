<?php

require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../includes/permission.php";
require_once "../../includes/nilai_helper.php";

$idMapel = (int) ($_POST['id_mapel'] ?? 0);
$idKelas = (int) ($_POST['id_kelas'] ?? 0);
$idGuru  = (int) ($_POST['id_guru'] ?? 0);

if (!isAdmin()) {

    // Guru gak boleh input atas nama guru lain -- paksa ke session sendiri,
    // apapun yang dikirim dari form (jaga-jaga form di-tempered).
    $idGuru = (int) $_SESSION['id'];

    $cek = $pdo->prepare("SELECT 1 FROM guru_mapel WHERE id_guru = ? AND id_mapel = ?");
    $cek->execute([$idGuru, $idMapel]);

    if (!$cek->fetch()) {

        $_SESSION['flash'] = [
            "type"  => "error",
            "pesan" => "Kamu tidak mengampu mapel ini."
        ];

        header("Location: ../../index.php?page=nilai");
        exit;

    }

}

if ($idMapel <= 0 || $idKelas <= 0 || $idGuru <= 0) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Pilih mapel, guru, dan kelas dulu sebelum menyimpan nilai."
    ];

    header("Location: ../../index.php?page=nilai");
    exit;

}

$tahunAktif = $pdo
    ->query("SELECT id_tahun FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")
    ->fetch();

if (!$tahunAktif) {

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Tidak ada tahun ajaran yang aktif."
    ];

    header("Location: ../../index.php?page=nilai");
    exit;

}

$idTahun = $tahunAktif['id_tahun'];

$nilaiArr = $_POST['nilai'] ?? [];

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO nilai
            (id_siswa, id_mapel, id_guru, id_tahun, nilai_akhir, predikat)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            nilai_akhir = VALUES(nilai_akhir),
            predikat = VALUES(predikat),
            id_guru = VALUES(id_guru)
    ");

    $tersimpan = 0;

    foreach ($nilaiArr as $idSiswa => $nilaiVal) {

        $idSiswa = (int) $idSiswa;

        // Kosong -> skip, jangan bikin baris nilai buat siswa yang
        // emang belum diisi.
        if ($nilaiVal === "") {
            continue;
        }

        $nilai = round((float) $nilaiVal, 2);
        $nilai = max(0, min(100, $nilai));

        $predikat = hitungPredikat($nilai);

        $stmt->execute([
            $idSiswa, $idMapel, $idGuru, $idTahun,
            $nilai, $predikat
        ]);

        $tersimpan++;

    }

    $pdo->commit();

    $_SESSION['flash'] = [
        "type"  => "success",
        "pesan" => "$tersimpan nilai berhasil disimpan."
    ];

} catch (PDOException $e) {

    $pdo->rollBack();

    $_SESSION['flash'] = [
        "type"  => "error",
        "pesan" => "Gagal menyimpan nilai."
    ];

}

header("Location: ../../index.php?page=nilai&id_mapel=$idMapel&id_guru=$idGuru&id_kelas=$idKelas");
exit;
