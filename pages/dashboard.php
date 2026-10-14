<?php

$tahunAktif = $pdo->query("SELECT tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();

$totalGuru  = (int) $pdo->query("SELECT COUNT(*) FROM guru")->fetchColumn();
$totalSiswa = (int) $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
$totalData  = $totalGuru + $totalSiswa;

$guruTerbaru = $pdo->query("
    SELECT
        g.id_guru, g.nama_guru, g.created_at,
        GROUP_CONCAT(m.nama_mapel ORDER BY m.nama_mapel SEPARATOR ', ') AS mapel_list
    FROM guru g
    LEFT JOIN guru_mapel gm ON gm.id_guru = g.id_guru
    LEFT JOIN mapel m ON m.id_mapel = gm.id_mapel
    GROUP BY g.id_guru
    ORDER BY g.created_at DESC
    LIMIT 5
")->fetchAll();

$siswaTerbaru = $pdo->query("
    SELECT
        s.id_siswa, s.nama_lengkap, s.created_at,
        k.tingkat, k.rombel, j.nama_jurusan
    FROM siswa s
    LEFT JOIN siswa_kelas sk ON sk.id_siswa = s.id_siswa
        AND sk.id_tahun = (SELECT id_tahun FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1)
    LEFT JOIN kelas k ON k.id_kelas = sk.id_kelas
    LEFT JOIN jurusan j ON j.id_jurusan = k.id_jurusan
    ORDER BY s.created_at DESC
    LIMIT 5
")->fetchAll();

$sekolah = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();

?>

<h3 class="fw-bold mb-4">
    Home
</h3>

<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="card shadow-sm h-100">

            <div class="card-body d-flex align-items-center gap-3">

                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width:56px;height:56px;flex-shrink:0;">
                    <i class="bi bi-people fs-3"></i>
                </div>

                <div>
                    <div class="text-uppercase text-secondary small">Total Guru</div>
                    <div class="fs-4 fw-bold text-primary"><?= $totalGuru; ?></div>
                    <div class="text-secondary small">Semua Guru</div>
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm h-100">

            <div class="card-body d-flex align-items-center gap-3">

                <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width:56px;height:56px;flex-shrink:0;">
                    <i class="bi bi-mortarboard fs-3"></i>
                </div>

                <div>
                    <div class="text-uppercase text-secondary small">Total Siswa</div>
                    <div class="fs-4 fw-bold text-success"><?= $totalSiswa; ?></div>
                    <div class="text-secondary small">Semua Siswa</div>
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm h-100">

            <div class="card-body d-flex align-items-center gap-3">

                <div class="rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info" style="width:56px;height:56px;flex-shrink:0;">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>

                <div>
                    <div class="text-uppercase text-secondary small">Total Data</div>
                    <div class="fs-4 fw-bold"><?= $totalData; ?></div>
                    <div class="text-secondary small">Guru + Siswa</div>
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm h-100">

            <div class="card-body d-flex align-items-center gap-3">

                <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width:56px;height:56px;flex-shrink:0;">
                    <i class="bi bi-calendar-check fs-3"></i>
                </div>

                <div>
                    <div class="text-uppercase text-secondary small">Tahun Ajaran</div>

                    <?php if ($tahunAktif) : ?>
                        <div class="fs-5 fw-bold text-warning"><?= htmlspecialchars($tahunAktif['tahun']); ?></div>
                        <div class="text-secondary small">Semester <?= htmlspecialchars($tahunAktif['semester']); ?></div>
                    <?php else : ?>
                        <div class="fs-6 text-danger">Belum ada yang aktif</div>
                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <span class="fw-bold">Guru Terbaru</span>
                <?php if (isAdmin()) : ?>
                <a href="index.php?page=guru" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                <?php endif; ?>
            </div>

            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama</th>
                        <th>Mata Pelajaran</th>
                        <th class="pe-3">Bergabung</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($guruTerbaru)) : ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-3">Belum ada data guru.</td>
                    </tr>
                    <?php endif; ?>

                    <?php $no = 1; foreach ($guruTerbaru as $g) : ?>
                    <tr>
                        <td class="ps-3"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($g['nama_guru']); ?></td>
                        <td><?= $g['mapel_list'] ? htmlspecialchars($g['mapel_list']) : '<span class="text-muted">-</span>'; ?></td>
                        <td class="pe-3"><?= date('d M Y', strtotime($g['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow-sm h-100">

            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <span class="fw-bold">Siswa Terbaru</span>
                <a href="index.php?page=siswa" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>

            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th class="pe-3">Bergabung</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($siswaTerbaru)) : ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-3">Belum ada data siswa.</td>
                    </tr>
                    <?php endif; ?>

                    <?php $no = 1; foreach ($siswaTerbaru as $s) : ?>
                    <tr>
                        <td class="ps-3"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($s['nama_lengkap']); ?></td>
                        <td>
                            <?= $s['tingkat']
                                ? htmlspecialchars($s['tingkat'] . ' ' . $s['nama_jurusan'] . ' ' . $s['rombel'])
                                : '<span class="text-muted">-</span>'; ?>
                        </td>
                        <td class="pe-3"><?= date('d M Y', strtotime($s['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-white fw-bold">
        Informasi Sekolah
    </div>

    <div class="card-body">

        <?php if (!$sekolah || !$sekolah['nama_sekolah']) : ?>

        <p class="text-secondary mb-0">
            Profil sekolah belum diisi.
            <?php if (isAdmin()) : ?>
            Lengkapi di menu <a href="index.php?page=setting">Setting</a>.
            <?php endif; ?>
        </p>

        <?php else : ?>

        <div class="row g-3">

            <div class="col-md-4">
                <i class="bi bi-building"></i>
                <strong>Nama Sekolah</strong><br>
                <?= htmlspecialchars($sekolah['nama_sekolah']); ?>
            </div>

            <div class="col-md-4">
                <i class="bi bi-geo-alt"></i>
                <strong>Alamat</strong><br>
                <?= htmlspecialchars($sekolah['alamat'] ?: '-'); ?>
            </div>

            <div class="col-md-4">
                <i class="bi bi-person-badge"></i>
                <strong>Kepala Sekolah</strong><br>
                <?= htmlspecialchars($sekolah['kepala_sekolah'] ?: '-'); ?>
            </div>

            <div class="col-md-4">
                <i class="bi bi-card-text"></i>
                <strong>NPSN</strong><br>
                <?= htmlspecialchars($sekolah['npsn'] ?: '-'); ?>
            </div>

            <div class="col-md-4">
                <i class="bi bi-telephone"></i>
                <strong>Telepon</strong><br>
                <?= htmlspecialchars($sekolah['telepon'] ?: '-'); ?>
            </div>

            <div class="col-md-4">
                <i class="bi bi-envelope"></i>
                <strong>Email</strong><br>
                <?= htmlspecialchars($sekolah['email'] ?: '-'); ?>
            </div>

        </div>

        <?php endif; ?>

    </div>

</div>
