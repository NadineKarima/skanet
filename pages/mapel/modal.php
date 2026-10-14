<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/mapel/tambah.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Mata Pelajaran
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">Kode Mapel</label>

                        <input
                            type="text"
                            name="kode_mapel"
                            class="form-control"
                            placeholder="Contoh: MTK-01"
                            required
                            autofocus>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Nama Mapel</label>

                        <input
                            type="text"
                            name="nama_mapel"
                            class="form-control"
                            placeholder="Contoh: Matematika"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">KKM</label>

                        <input
                            type="number"
                            name="kkm"
                            class="form-control"
                            min="0"
                            max="100"
                            value="75"
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
    id="modalEditMapel"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="actions/mapel/edit.php"
            method="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Mata Pelajaran
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
                        name="id_mapel"
                        id="edit_id_mapel">

                    <div class="mb-3">

                        <label class="form-label">Kode Mapel</label>

                        <input
                            type="text"
                            name="kode_mapel"
                            id="edit_kode_mapel"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Nama Mapel</label>

                        <input
                            type="text"
                            name="nama_mapel"
                            id="edit_nama_mapel"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">KKM</label>

                        <input
                            type="number"
                            name="kkm"
                            id="edit_kkm"
                            class="form-control"
                            min="0"
                            max="100"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Status</label>

                        <select
                            name="status"
                            id="edit_status"
                            class="form-select"
                            required>

                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>

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
