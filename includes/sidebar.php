<div class="sidebar" id="sidebar">
    <div class="logo">

        <img src="assets/img/logo.png" alt="Logo">

        <div class="logo-text">

            <h4>SK@NET</h4>

            <small>Sistem Kelola Nilai</small>

        </div>

    </div>

    <ul class="menu">

        <li>
            <a href="index.php?page=dashboard"
            class="<?= ($page=='dashboard')?'active':''; ?>">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>
        </li>

        <?php if (isAdmin()) : ?>

        <li>
            <a href="index.php?page=guru"
            class="<?= ($page=='guru')?'active':''; ?>">
                <i class="bi bi-person-workspace"></i>
                <span>Guru</span>
            </a>
        </li>

        <?php endif; ?>

        <li>
            <a href="index.php?page=siswa"
            class="<?= ($page=='siswa')?'active':''; ?>">
                <i class="bi bi-people"></i>
                <span>Siswa</span>
            </a>
        </li>

        <?php if (isAdmin()) : ?>

        <li>
            <a href="index.php?page=jurusan"
            class="<?= ($page=='jurusan')?'active':''; ?>">
                <i class="bi bi-diagram-3"></i>
                <span>Jurusan</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=kelas"
            class="<?= ($page=='kelas')?'active':''; ?>">
                <i class="bi bi-building"></i>
                <span>Kelas</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=mapel"
            class="<?= ($page=='mapel')?'active':''; ?>">
                <i class="bi bi-book"></i>
                <span>Mata Pelajaran</span>
            </a>
        </li>

        <?php endif; ?>

        <li>
            <a href="index.php?page=nilai"
            class="<?= ($page=='nilai')?'active':''; ?>">
                <i class="bi bi-journal-check"></i>
                <span>Nilai</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=laporan"
            class="<?= ($page=='laporan')?'active':''; ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laporan</span>
            </a>
        </li>

    </ul>

    <div class="sidebar-footer">

        <a href="index.php?page=setting"
        class="<?= ($page=='setting')?'active':''; ?>">
            <i class="bi bi-gear"></i>
            <span>Setting</span>
        </a>

        <a href="index.php?page=tentang"
        class="<?= ($page=='tentang')?'active':''; ?>">
            <i class="bi bi-info-circle"></i>
            <span>Tentang</span>
        </a>

    </div>

</div>
