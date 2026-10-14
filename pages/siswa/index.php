<?php

$tahunAktif   = $pdo->query("SELECT id_tahun, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();
$idTahunAktif = $tahunAktif['id_tahun'] ?? 0;

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Data Siswa
        </h3>

        <p class="text-secondary mb-0">
            <?= isAdmin() ? 'Kelola data siswa.' : 'Pantau data siswa.'; ?>
        </p>

    </div>

    <?php if (isAdmin()) : ?>

    <button
        type="button"
        class="btn btn-primary"
        <?= !$tahunAktif ? 'disabled title="Set tahun ajaran aktif dulu"' : ''; ?>
        data-bs-toggle="modal"
        data-bs-target="#modalTambah">

        <i class="bi bi-plus-circle"></i>

        Tambah Siswa

    </button>

    <?php endif; ?>

</div>

<?php if (!$tahunAktif) : ?>

<div class="alert alert-warning">

    Belum ada tahun ajaran yang aktif.

    <?= isAdmin()
        ? 'Set salah satu tahun ajaran jadi "Aktif" dulu di menu Tahun Ajaran, supaya siswa bisa didaftarkan ke kelas.'
        : 'Hubungi admin untuk mengaktifkan tahun ajaran.'; ?>

</div>

<?php endif; ?>

<div class="card shadow-sm">

    <div class="card-body">

        <table
            id="tableSiswa"
            class="table table-hover align-middle">

            <thead>

            <tr>

                <th width="50">No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th width="70">JK</th>
                <th>Kelas</th>
                <th width="100">Status</th>
                <th width="170">Aksi</th>

            </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            $stmt = $pdo->prepare("
                SELECT
                    s.id_siswa, s.nis, s.nama_lengkap, s.jenis_kelamin, s.alamat, s.status,
                    k.id_kelas, k.tingkat, k.rombel, j.nama_jurusan
                FROM siswa s
                LEFT JOIN siswa_kelas sk ON sk.id_siswa = s.id_siswa AND sk.id_tahun = ?
                LEFT JOIN kelas k ON k.id_kelas = sk.id_kelas
                LEFT JOIN jurusan j ON j.id_jurusan = k.id_jurusan
                ORDER BY s.nama_lengkap ASC
            ");
            $stmt->execute([$idTahunAktif]);

            $siswaList = $stmt->fetchAll();

            $statusBadge = [
                'Aktif'  => 'success',
                'Lulus'  => 'info',
                'Pindah' => 'secondary'
            ];

            foreach ($siswaList as $row) :

                $namaKelas = $row['id_kelas']
                    ? $row['tingkat'] . ' ' . $row['nama_jurusan'] . ' ' . $row['rombel']
                    : '-';

                $badgeColor = $statusBadge[$row['status']] ?? 'secondary';

            ?>

            <tr>

                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nis']); ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>

                <td>

                    <?php if ($row['jenis_kelamin'] === 'L') : ?>
                        <span class="badge bg-primary">L</span>
                    <?php else : ?>
                        <span class="badge bg-danger">P</span>
                    <?php endif; ?>

                </td>

                <td><?= htmlspecialchars($namaKelas); ?></td>

                <td>
                    <span class="badge bg-<?= $badgeColor; ?>">
                        <?= $row['status']; ?>
                    </span>
                </td>

                <td>

                    <button
                        type="button"
                        class="btn btn-info btn-sm text-white btn-detail-siswa"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetail"
                        data-nis="<?= htmlspecialchars($row['nis']); ?>"
                        data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>"
                        data-jk="<?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>"
                        data-kelas="<?= htmlspecialchars($namaKelas); ?>"
                        data-alamat="<?= htmlspecialchars($row['alamat'] ?: '-'); ?>"
                        data-status="<?= $row['status']; ?>">

                        <i class="bi bi-eye"></i>

                    </button>

                    <?php if (isAdmin()) : ?>

                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-edit-siswa"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditSiswa"
                        data-id="<?= $row['id_siswa']; ?>"
                        data-nis="<?= htmlspecialchars($row['nis']); ?>"
                        data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>"
                        data-jk="<?= $row['jenis_kelamin']; ?>"
                        data-kelas="<?= (int) $row['id_kelas']; ?>"
                        data-alamat="<?= htmlspecialchars($row['alamat'] ?? ''); ?>"
                        data-status="<?= $row['status']; ?>">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasi('actions/siswa/hapus.php?id=<?= $row['id_siswa']; ?>')">

                        <i class="bi bi-trash"></i>

                    </button>

                    <?php endif; ?>

                </td>

            </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

if (isAdmin()) {

    $listKelas = $pdo->query("
        SELECT k.id_kelas, k.tingkat, k.rombel, j.nama_jurusan
        FROM kelas k
        JOIN jurusan j ON j.id_jurusan = k.id_jurusan
        ORDER BY j.nama_jurusan ASC, FIELD(k.tingkat, 'X', 'XI', 'XII') ASC, k.rombel ASC
    ")->fetchAll();

    include "pages/siswa/modal.php";

}

include "pages/siswa/detail.php";

?>
