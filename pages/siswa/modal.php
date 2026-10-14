<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/siswa/tambah.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Siswa
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">NIS</label>

                        <input
                            type="text"
                            name="nis"
                            class="form-control"
                            required
                            autofocus>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Nama Lengkap</label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Jenis Kelamin</label>

                        <select
                            name="jenis_kelamin"
                            class="form-select">

                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Kelas</label>

                        <select
                            name="id_kelas"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Kelas --</option>

                            <?php foreach ($listKelas as $k) : ?>

                            <option value="<?= $k['id_kelas']; ?>">
                                <?= htmlspecialchars($k['tingkat'] . ' ' . $k['nama_jurusan'] . ' ' . $k['rombel']); ?>
                            </option>

                            <?php endforeach; ?>

                        </select>

                        <div class="form-text">
                            Jurusan otomatis mengikuti kelas yang dipilih.
                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Alamat</label>

                        <textarea
                            name="alamat"
                            class="form-control"
                            rows="3"></textarea>

                    </div>

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
    id="modalEditSiswa"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/siswa/edit.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Siswa
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
                        name="id_siswa"
                        id="edit_id_siswa">

                    <div class="mb-3">

                        <label class="form-label">NIS</label>

                        <input
                            type="text"
                            id="edit_nis"
                            class="form-control"
                            disabled>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Nama Lengkap</label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            id="edit_nama_lengkap"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Jenis Kelamin</label>

                        <select
                            name="jenis_kelamin"
                            id="edit_jenis_kelamin"
                            class="form-select">

                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Kelas</label>

                        <select
                            name="id_kelas"
                            id="edit_id_kelas"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Kelas --</option>

                            <?php foreach ($listKelas as $k) : ?>

                            <option value="<?= $k['id_kelas']; ?>">
                                <?= htmlspecialchars($k['tingkat'] . ' ' . $k['nama_jurusan'] . ' ' . $k['rombel']); ?>
                            </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Alamat</label>

                        <textarea
                            name="alamat"
                            id="edit_alamat"
                            class="form-control"
                            rows="3"></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Status</label>

                        <select
                            name="status"
                            id="edit_status"
                            class="form-select">

                            <option value="Aktif">Aktif</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Pindah">Pindah</option>

                        </select>

                    </div>

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
