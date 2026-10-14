<?php requireAdmin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Jurusan
        </h3>

        <p class="text-secondary mb-0">
            Kelola data jurusan.
        </p>

    </div>

    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalTambah">

        <i class="bi bi-plus-circle"></i>

        Tambah Jurusan

    </button>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table
            id="tableJurusan"
            class="table table-hover align-middle">

            <thead>

            <tr>

                <th width="80">No</th>

                <th>Nama Jurusan</th>

                <th width="180">Aksi</th>

            </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            $query = $pdo->query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC");

            while ($row = $query->fetch()) :

            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>

                <td>
<button
                        type="button"
                        class="btn btn-warning btn-sm btn-edit-jurusan"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditJurusan"
                        data-id="<?= $row['id_jurusan']; ?>"
                        data-nama="<?= htmlspecialchars($row['nama_jurusan']); ?>">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasi('actions/jurusan/hapus.php?id=<?= $row['id_jurusan']; ?>')">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

            <?php endwhile; ?>

            <?php if ($no === 1) : ?>

            <tr>
                <td colspan="3" class="text-center text-secondary py-3">Belum ada data jurusan.</td>
            </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/jurusan/tambah.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Tambah Jurusan

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <label class="form-label">

                        Nama Jurusan

                    </label>

                    <input
                        type="text"
                        name="nama_jurusan"
                        class="form-control"
                        required
                        autofocus>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<div
    class="modal fade"
    id="modalEditJurusan"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/jurusan/edit.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Jurusan
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id_jurusan"
                        id="edit_id_jurusan">

                    <label class="form-label">
                        Nama Jurusan
                    </label>

                    <input
                        type="text"
                        name="nama_jurusan"
                        id="edit_nama_jurusan"
                        class="form-control"
                        required>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Update
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
