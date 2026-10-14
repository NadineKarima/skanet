<?php

$sekolah = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();

?>

<div class="row g-4">

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-body text-center py-5">

                <img src="assets/img/logo.png" width="80" class="d-block mx-auto mb-3">

                <h4 class="fw-bold mb-1">SK@NET</h4>

                <p class="text-secondary mb-3">
                    Sistem Kelola Nilai
                </p>

                <p class="mb-1">Versi 1.0.0</p>

                <p class="text-secondary small mb-0">
                    Aplikasi manajemen data guru, siswa, dan nilai berbasis web, project 11 RPL 2 <?= htmlspecialchars($sekolah['nama_sekolah'] ?? 'sekolah ini'); ?>.
                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header bg-white fw-bold">
                Informasi Sekolah
            </div>

            <div class="card-body">

                <table class="table table-borderless mb-0">

                    <tr>
                        <th width="35%">Nama Sekolah</th>
                        <td><?= htmlspecialchars($sekolah['nama_sekolah'] ?? '-'); ?></td>
                    </tr>

                    <tr>
                        <th>NPSN</th>
                        <td><?= htmlspecialchars($sekolah['npsn'] ?? '-'); ?></td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td><?= htmlspecialchars($sekolah['alamat'] ?? '-'); ?></td>
                    </tr>

                    <tr>
                        <th>Telepon</th>
                        <td><?= htmlspecialchars($sekolah['telepon'] ?? '-'); ?></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($sekolah['email'] ?? '-'); ?></td>
                    </tr>

                    <tr>
                        <th>Kepala Sekolah</th>
                        <td><?= htmlspecialchars($sekolah['kepala_sekolah'] ?? '-'); ?></td>
                    </tr>

                </table>

                <?php if (isAdmin()) : ?>
                <p class="text-secondary small mt-3 mb-0">
                    Data di atas bisa diubah lewat menu <a href="index.php?page=setting">Setting</a>.
                </p>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <div class="col-12">

        <div class="card shadow-sm">

            <div class="card-header bg-white fw-bold">
                Teknologi yang Digunakan
            </div>

            <div class="card-body">

                <div class="row g-3 text-center">

                    <div class="col-6 col-md-3">
                        <i class="bi bi-filetype-php fs-1 text-primary"></i>
                        <p class="mb-0 small">PHP (native)</p>
                    </div>

                    <div class="col-6 col-md-3">
                        <i class="bi bi-database fs-1 text-primary"></i>
                        <p class="mb-0 small">MySQL / MariaDB</p>
                    </div>

                    <div class="col-6 col-md-3">
                        <i class="bi bi-bootstrap fs-1 text-primary"></i>
                        <p class="mb-0 small">Bootstrap 5</p>
                    </div>

                    <div class="col-6 col-md-3">
                        <i class="bi bi-table fs-1 text-primary"></i>
                        <p class="mb-0 small">DataTables</p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
