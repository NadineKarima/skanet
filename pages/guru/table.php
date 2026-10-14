<div class="card shadow-sm">

    <div class="card-body">

        <table
            id="tableGuru"
            class="table table-hover align-middle">

            <thead>

            <tr>

                <th width="50">No</th>
                <th>NIP</th>
                <th>Nama Guru</th>
                <th width="60">Jenis Kelamin</th>
                <th>No HP</th>
                <th>Mapel Diampu</th>
                <th width="100">Status</th>
                <th width="180">Aksi</th>

            </tr>

            </thead>

            <tbody>

            <?php $no = 1; ?>

            <?php foreach ($guruList as $row) : ?>

            <tr>

                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nip']); ?></td>
                <td><?= htmlspecialchars($row['nama_guru']); ?></td>

                <td>

                    <?php if ($row['jenis_kelamin'] === 'L') : ?>
                        <span class="badge bg-primary">L</span>
                    <?php else : ?>
                        <span class="badge bg-danger">P</span>
                    <?php endif; ?>

                </td>

                <td><?= htmlspecialchars($row['no_hp'] ?: '-'); ?></td>

                <td>

                    <?php if ($row['mapel_list']) : ?>
                        <?= htmlspecialchars($row['mapel_list']); ?>
                    <?php else : ?>
                        <span class="text-muted">Belum ada</span>
                    <?php endif; ?>

                </td>

                <td>

                    <?php if ($row['status'] === 'Aktif') : ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php else : ?>
                        <span class="badge bg-secondary">Nonaktif</span>
                    <?php endif; ?>

                </td>

                <td>

                    <button
                        type="button"
                        class="btn btn-info btn-sm text-white btn-detail-guru"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetailGuru"
                        data-nip="<?= htmlspecialchars($row['nip']); ?>"
                        data-username="<?= htmlspecialchars($row['username']); ?>"
                        data-nama="<?= htmlspecialchars($row['nama_guru']); ?>"
                        data-jk="<?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>"
                        data-alamat="<?= htmlspecialchars($row['alamat'] ?: '-'); ?>"
                        data-hp="<?= htmlspecialchars($row['no_hp'] ?: '-'); ?>"
                        data-mapel="<?= $row['mapel_list'] ? htmlspecialchars($row['mapel_list']) : 'Belum ada mapel diampu'; ?>"
                        data-status="<?= $row['status']; ?>">

                        <i class="bi bi-eye"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-edit-guru"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditGuru"
                        data-id="<?= $row['id_guru']; ?>"
                        data-nip="<?= htmlspecialchars($row['nip']); ?>"
                        data-username="<?= htmlspecialchars($row['username']); ?>"
                        data-nama="<?= htmlspecialchars($row['nama_guru']); ?>"
                        data-jk-value="<?= $row['jenis_kelamin']; ?>"
                        data-hp="<?= htmlspecialchars($row['no_hp'] ?? ''); ?>"
                        data-alamat="<?= htmlspecialchars($row['alamat'] ?? ''); ?>"
                        data-status="<?= $row['status']; ?>"
                        data-mapel-ids="<?= htmlspecialchars($row['mapel_ids'] ?? ''); ?>"
                        title="Edit Guru">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasi('actions/guru/hapus.php?id=<?= $row['id_guru']; ?>')">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "pages/guru/edit.php"; ?>
