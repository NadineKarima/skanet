<?php requireAdmin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Mata Pelajaran
        </h3>

        <p class="text-secondary mb-0">
            Kelola data mata pelajaran beserta KKM-nya.
        </p>

    </div>

    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalTambah">

        <i class="bi bi-plus-circle"></i>

        Tambah Mapel

    </button>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table
            id="tableMapel"
            class="table table-hover align-middle">

            <thead>

            <tr>

                <th width="60">No</th>
                <th width="120">Kode</th>
                <th>Nama Mapel</th>
                <th width="100">KKM</th>
                <th width="120">Status</th>
                <th width="160">Aksi</th>

            </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            $query = $pdo->query("
                SELECT id_mapel, kode_mapel, nama_mapel, kkm, status
                FROM mapel
                ORDER BY nama_mapel ASC
            ");

            while ($row = $query->fetch()) :

            ?>

            <tr>

                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['kode_mapel']); ?></td>
                <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
                <td><?= (int) $row['kkm']; ?></td>

                <td>

                    <?php if ($row['status'] === 'Aktif') : ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php else : ?>
                        <span class="badge bg-secondary">Nonaktif</span>
                    <?php endif; ?>

                </td>

                <td>
<button
                        type="button"
                        class="btn btn-warning btn-sm btn-edit-mapel"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditMapel"
                        data-id="<?= $row['id_mapel']; ?>"
                        data-kode="<?= htmlspecialchars($row['kode_mapel']); ?>"
                        data-nama="<?= htmlspecialchars($row['nama_mapel']); ?>"
                        data-kkm="<?= (int) $row['kkm']; ?>"
                        data-status="<?= $row['status']; ?>">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasi('actions/mapel/hapus.php?id=<?= $row['id_mapel']; ?>')">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "pages/mapel/modal.php"; ?>
