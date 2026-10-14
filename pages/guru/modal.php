<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="tambahGuru"
    style="width: 420px;">

    <div class="offcanvas-header">

        <h5 class="offcanvas-title">
            Tambah Guru
        </h5>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas">
        </button>

    </div>

    <div class="offcanvas-body">

        <form
            action="actions/guru/tambah.php"
            method="POST"
            id="formTambahGuru">

            <div class="mb-3">

                <label class="form-label">NIP</label>

                <input
                    type="text"
                    name="nip"
                    class="form-control"
                    required
                    autofocus>

            </div>

            <div class="mb-3">

                <label class="form-label">Username</label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">Password</label>

                <input
                    type="password"
                    name="password"
                    id="passwordGuru"
                    class="form-control"
                    required
                    minlength="7">

                <div class="form-text">
                    Minimal 7 karakter.
                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Ulangi Password</label>

                <input
                    type="password"
                    id="password2"
                    class="form-control"
                    required
                    minlength="7">

                <div class="form-text" id="passwordMatchHint"></div>

            </div>

            <div class="mb-3">

                <label class="form-label">Nama Guru</label>

                <input
                    type="text"
                    name="nama_guru"
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

                <label class="form-label">No HP</label>

                <input
                    type="text"
                    name="no_hp"
                    class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">Alamat</label>

                <textarea
                    name="alamat"
                    class="form-control"
                    rows="3"></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label d-block">
                    Mapel yang Diampu
                </label>

                <?php if (empty($listMapel)) : ?>

                <p class="text-muted small">
                    Belum ada data mapel. Tambahkan dulu di menu Mapel.
                </p>

                <?php else : ?>

                <?php foreach ($listMapel as $m) : ?>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="mapel[]"
                        value="<?= $m['id_mapel']; ?>"
                        id="tambah_mapel_<?= $m['id_mapel']; ?>">

                    <label
                        class="form-check-label"
                        for="tambah_mapel_<?= $m['id_mapel']; ?>">

                        <?= htmlspecialchars($m['nama_mapel']); ?>

                        <?php if ($m['status'] === 'Nonaktif') : ?>
                            <span class="text-muted">(Nonaktif)</span>
                        <?php endif; ?>

                    </label>

                </div>

                <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <button
                type="submit"
                class="btn btn-primary w-100">
                Simpan
            </button>

        </form>

    </div>

</div>
