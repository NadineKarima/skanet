<?php requireAdmin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Data Guru
        </h3>

        <p class="text-secondary mb-0">
            Kelola akun dan data guru, termasuk mapel yang diampu.
        </p>

    </div>

    <button
        class="btn btn-primary"
        data-bs-toggle="offcanvas"
        data-bs-target="#tambahGuru">

        <i class="bi bi-plus-circle"></i>

        Tambah Guru

    </button>

</div>

<?php

$listMapel = $pdo
    ->query("SELECT id_mapel, nama_mapel, status FROM mapel ORDER BY nama_mapel ASC")
    ->fetchAll();

$query = $pdo->query("
    SELECT
        g.id_guru, g.nip, g.username, g.nama_guru, g.jenis_kelamin,
        g.alamat, g.no_hp, g.status,
        GROUP_CONCAT(m.nama_mapel ORDER BY m.nama_mapel SEPARATOR ', ') AS mapel_list,
        GROUP_CONCAT(gm.id_mapel ORDER BY m.nama_mapel SEPARATOR ',') AS mapel_ids
    FROM guru g
    LEFT JOIN guru_mapel gm ON gm.id_guru = g.id_guru
    LEFT JOIN mapel m ON m.id_mapel = gm.id_mapel
    GROUP BY g.id_guru
    ORDER BY g.nama_guru ASC
");

$guruList = $query->fetchAll();

include "pages/guru/table.php";
include "pages/guru/modal.php";
include "pages/guru/detail.php";

?>

