<style>
    #modalEditGuru .modal-dialog {
        max-width: 820px;
    }

    #modalEditGuru .modal-body {
        padding: 16px 20px;
    }

    #modalEditGuru .form-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    #modalEditGuru .mb-3 {
        margin-bottom: 10px !important;
    }

    #modalEditGuru .form-control,
    #modalEditGuru .form-select {
        min-height: 36px;
        padding: 6px 10px;
        font-size: 13px;
    }

    #modalEditGuru textarea.form-control {
        min-height: 60px;
        resize: vertical;
    }

    #modalEditGuru .mapel-box {
        max-height: 185px;
        overflow-y: auto;
        padding: 8px 12px;
    }

    #modalEditGuru .form-check {
        margin-bottom: 5px !important;
        min-height: 20px;
    }

    #modalEditGuru .form-check-label {
        font-size: 13px;
    }

    #modalEditGuru .reset-box {
        margin-top: 10px;
        padding: 9px 11px;
        border-radius: 8px;
        background: var(--bs-light, #f8f9fa);
    }

    #modalEditGuru .modal-footer {
        padding: 10px 20px;
    }
</style>

<div
    class="modal fade"
    id="modalEditGuru"
    tabindex="-1"
    aria-labelledby="modalEditGuruLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <form action="actions/guru/edit.php" method="POST" class="w-100">

            <div class="modal-content">

                <div class="modal-header py-2 px-3">
                    <h5 class="modal-title" id="modalEditGuruLabel">
                        <i class="bi bi-pencil-square me-1"></i> Edit Guru
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_guru" id="edit_id_guru">

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input
                                type="text"
                                id="edit_nip_guru"
                                class="form-control"
                                disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                id="edit_username_guru"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Guru</label>
                            <input
                                type="text"
                                name="nama_guru"
                                id="edit_nama_guru"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select
                                name="jenis_kelamin"
                                id="edit_jenis_kelamin_guru"
                                class="form-select"
                                required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="form-label">Status</label>
                            <select
                                name="status"
                                id="edit_status_guru"
                                class="form-select"
                                required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">No HP</label>
                            <input
                                type="text"
                                name="no_hp"
                                id="edit_no_hp_guru"
                                class="form-control">
                        </div>

                        <div class="col-md-7">
                            <label class="form-label">Alamat</label>
                            <textarea
                                name="alamat"
                                id="edit_alamat_guru"
                                class="form-control"
                                rows="2"></textarea>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="form-label d-block mb-1">Mapel yang Diampu</label>

                            <div class="border rounded mapel-box">
                                <div class="row g-1">
                                    <?php foreach ($listMapel as $m) : ?>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input edit-guru-mapel"
                                                    type="checkbox"
                                                    name="mapel[]"
                                                    value="<?= $m['id_mapel']; ?>"
                                                    id="edit_guru_mapel_<?= $m['id_mapel']; ?>">

                                                <label
                                                    class="form-check-label"
                                                    for="edit_guru_mapel_<?= $m['id_mapel']; ?>">
                                                    <?= htmlspecialchars($m['nama_mapel']); ?>
                                                    <?php if ($m['status'] === 'Nonaktif') : ?>
                                                        <span class="text-muted">(Nonaktif)</span>
                                                    <?php endif; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php if (empty($listMapel)) : ?>
                                        <div class="text-muted small">
                                            Belum ada mata pelajaran.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="reset-box d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <div class="fw-semibold small">Lupa password?</div>
                                    <div class="text-secondary" style="font-size:11px;">Reset password kembali ke NIP guru.</div>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    id="btnResetPasswordGuru">
                                    <i class="bi bi-key"></i>
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary btn-sm">
                        <i class="bi bi-check2"></i> Update
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
