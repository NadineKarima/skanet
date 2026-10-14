<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/kelas/tambah.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Kelas
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">Tingkat</label>

                        <select
                            name="tingkat"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Tingkat --</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Jurusan</label>

                        <select
                            name="id_jurusan"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Jurusan --</option>

                            <?php foreach ($listJurusan as $j) : ?>

                            <option value="<?= $j['id_jurusan']; ?>">
                                <?= htmlspecialchars($j['nama_jurusan']); ?>
                            </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Rombel</label>

                        <input
                            type="text"
                            name="rombel"
                            class="form-control"
                            placeholder="Contoh: 1"
                            required>

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
    id="modalEditKelas"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/kelas/edit.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Kelas
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
                        name="id_kelas"
                        id="edit_id_kelas">

                    <div class="mb-3">

                        <label class="form-label">Tingkat</label>

                        <select
                            name="tingkat"
                            id="edit_tingkat"
                            class="form-select"
                            required>

                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Jurusan</label>

                        <select
                            name="id_jurusan"
                            id="edit_id_jurusan"
                            class="form-select"
                            required>

                            <?php foreach ($listJurusan as $j) : ?>

                            <option value="<?= $j['id_jurusan']; ?>">
                                <?= htmlspecialchars($j['nama_jurusan']); ?>
                            </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Rombel</label>

                        <input
                            type="text"
                            name="rombel"
                            id="edit_rombel"
                            class="form-control"
                            required>

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
