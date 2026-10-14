<?php

$sekolah = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();

?>

<div class="row g-4">

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header bg-white fw-bold">
                Tampilan
            </div>

            <div class="card-body">

                <div class="form-check form-switch">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="darkModeSwitch"
                        <?= !empty($_SESSION['dark_mode']) ? 'checked' : ''; ?>>

                    <label class="form-check-label" for="darkModeSwitch">
                        Mode Gelap (Dark Mode)
                    </label>

                </div>

                <p class="text-secondary small mb-0 mt-2">
                    Perubahan langsung tersimpan dan berlaku tiap kamu login,
                    di perangkat manapun.
                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header bg-white fw-bold">
                Ganti Password
            </div>

            <div class="card-body">

                <form action="actions/setting/ganti_password.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label">Password Lama</label>

                        <input
                            type="password"
                            name="password_lama"
                            id="passwordLama"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Password Baru</label>

                        <input
                            type="password"
                            name="password_baru"
                            id="passwordBaru"
                            class="form-control"
                            minlength="7"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Ulangi Password Baru</label>

                        <input
                            type="password"
                            id="passwordBaruUlang"
                            class="form-control"
                            minlength="7"
                            required>

                        <div class="form-text" id="passwordMatchHint"></div>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Password Baru
                    </button>

                </form>

            </div>

        </div>

    </div>

    <?php if (isAdmin()) : ?>

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header bg-white fw-bold">
                Profil Sekolah
            </div>

            <div class="card-body">

                <form action="actions/setting/update_sekolah.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="text" name="nama_sekolah" class="form-control"
                            value="<?= htmlspecialchars($sekolah['nama_sekolah'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NPSN</label>
                        <input type="text" name="npsn" class="form-control"
                            value="<?= htmlspecialchars($sekolah['npsn'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($sekolah['alamat'] ?? ''); ?></textarea>
                    </div>

                    <div class="row g-2">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control"
                                value="<?= htmlspecialchars($sekolah['telepon'] ?? ''); ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($sekolah['email'] ?? ''); ?>">
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kepala Sekolah</label>
                        <input type="text" name="kepala_sekolah" class="form-control"
                            value="<?= htmlspecialchars($sekolah['kepala_sekolah'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Profil Sekolah
                    </button>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header bg-white fw-bold">
                Tahun Ajaran
            </div>

            <div class="card-body">

                <table class="table table-sm align-middle mb-3">

                    <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th width="90"></th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php

                    $listTahun = $pdo->query("
                        SELECT id_tahun, tahun, semester, status
                        FROM tahun_ajaran
                        ORDER BY tahun DESC, FIELD(semester, 'Genap', 'Ganjil') ASC
                    ")->fetchAll();

                    foreach ($listTahun as $t) :

                    ?>

                    <tr>
                        <td><?= htmlspecialchars($t['tahun']); ?></td>
                        <td><?= htmlspecialchars($t['semester']); ?></td>
                        <td>
                            <?php if ($t['status'] === 'Aktif') : ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($t['status'] !== 'Aktif') : ?>
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                onclick="konfirmasi('actions/setting/aktifkan_tahun.php?id=<?= $t['id_tahun']; ?>')">
                                Aktifkan
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php endforeach; ?>

                    <?php if (empty($listTahun)) : ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary">Belum ada data.</td>
                    </tr>
                    <?php endif; ?>

                    </tbody>

                </table>

                <hr>

                <p class="fw-semibold small mb-2">Tambah Tahun Ajaran Baru</p>

                <form action="actions/setting/tambah_tahun.php" method="POST" class="row g-2">

                    <div class="col-6">
                        <input type="text" name="tahun" class="form-control form-control-sm"
                            placeholder="cth: 2026/2027" required>
                    </div>

                    <div class="col-4">
                        <select name="semester" class="form-select form-select-sm" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">+</button>
                    </div>

                </form>

                <p class="text-secondary small mb-0 mt-2">
                    Tahun ajaran baru otomatis Nonaktif -- klik "Aktifkan" kalau
                    sudah siap dipakai. Cuma boleh ada 1 yang Aktif; ngaktifin
                    yang baru otomatis nonaktifin yang lama.
                </p>

            </div>

        </div>

    </div>

    <?php endif; ?>

</div>
