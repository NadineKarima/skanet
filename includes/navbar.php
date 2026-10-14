<nav class="navbar navbar-expand">

    <div class="container-fluid">

        <button
            class="btn btn-toggle"
            id="toggleSidebar">

            <i class="bi bi-list"></i>

        </button>

        <div class="ms-auto d-flex align-items-center">

            <span id="dateTime" class="me-4 text-secondary"></span>

            <div class="dropdown">

                <a
                    href="#"
                    class="d-flex align-items-center text-dark dropdown-toggle"
                    data-bs-toggle="dropdown">

                    <div class="avatar">

                        <i class="bi bi-person-circle"></i>

                    </div>

                    <div class="ms-2">

                        <div class="fw-semibold">

                            <?= $_SESSION['nama']; ?>

                        </div>

                        <small class="text-muted">

                            <?= ucfirst($_SESSION['role']); ?>

                        </small>

                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>

                        <a class="dropdown-item"
                           href="index.php?page=setting">

                            <i class="bi bi-gear me-2"></i>

                            Setting

                        </a>

                    </li>

                    <li>

                        <hr class="dropdown-divider">

                    </li>

                    <li>

                        <a
                            class="dropdown-item text-danger"
                            href="auth/logout.php">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Logout

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>