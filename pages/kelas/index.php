<?php requireAdmin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Kelas
        </h3>

        <p class="text-secondary mb-0">
            Kelola data kelas.
        </p>

    </div>

    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalTambah">

        <i class="bi bi-plus-circle"></i>

        Tambah Kelas

    </button>

</div>

<?php

$tahunAktif = $pdo
    ->query("SELECT id_tahun, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")
    ->fetch();

if (!$tahunAktif) :

?>

<div class="alert alert-warning">
    Belum ada tahun ajaran yang aktif. Set salah satu tahun ajaran jadi
    "Aktif" dulu di menu Tahun Ajaran supaya jumlah siswa per kelas bisa dihitung.
</div>

<?php endif; ?>

<div class="card shadow-sm">

    <div class="card-body">

        <table
            id="tableKelas"
            class="table table-hover align-middle">

            <thead>

            <tr>

                <th width="60">No</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th width="120">Jml Siswa</th>
                <th width="160">Aksi</th>

            </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            $stmt = $pdo->prepare("
                SELECT
                    k.id_kelas,
                    k.tingkat,
                    k.rombel,
                    j.id_jurusan,
                    j.nama_jurusan,
                    (
                        SELECT COUNT(*)
                        FROM siswa_kelas sk
                        WHERE sk.id_kelas = k.id_kelas
                          AND sk.id_tahun = ?
                    ) AS jml_siswa
                FROM kelas k
                JOIN jurusan j ON j.id_jurusan = k.id_jurusan
                ORDER BY
                    j.nama_jurusan ASC,
                    FIELD(k.tingkat, 'X', 'XI', 'XII') ASC,
                    k.rombel ASC
            ");

            $stmt->execute([$tahunAktif['id_tahun'] ?? 0]);

            while ($row = $stmt->fetch()) :

                $namaKelas = $row['tingkat'] . ' ' . $row['nama_jurusan'] . ' ' . $row['rombel'];

            ?>

            <tr>

                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($namaKelas); ?></td>
                <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>
                <td><?= (int) $row['jml_siswa']; ?></td>

                <td>
<button
                        type="button"
                        class="btn btn-warning btn-sm btn-edit-kelas"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditKelas"
                        data-id="<?= $row['id_kelas']; ?>"
                        data-tingkat="<?= htmlspecialchars($row['tingkat']); ?>"
                        data-jurusan="<?= $row['id_jurusan']; ?>"
                        data-rombel="<?= htmlspecialchars($row['rombel']); ?>">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasi('actions/kelas/hapus.php?id=<?= $row['id_kelas']; ?>')">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

$listJurusan = $pdo
    ->query("SELECT id_jurusan, nama_jurusan FROM jurusan ORDER BY nama_jurusan ASC")
    ->fetchAll();

include "pages/kelas/modal.php";

?>
