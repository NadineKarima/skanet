<?php

require_once "../config/session.php";

if (isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SK@NET</title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-4 col-md-6">
                <div class="login-card p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-mortarboard-fill login-logo"></i>
                        <h2 class="login-title mt-3">SK@NET</h2>
                        <p class="login-subtitle">
                            Sistem Kelola Nilai
                        </p>
                    </div>
                    <?php if(isset($_GET['error'])) : ?>
                    <div class="alert alert-danger">
                        Username atau Password salah.
                    </div>
                    <?php endif; ?>
                    <form action="login_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">
                                Username
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="username"
                                required
                                autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">
                                Password
                            </label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    name="password"
                                    required>
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword()">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(){
            let pass=document.getElementById("password");
            if(pass.type==="password"){
                pass.type="text";
            }else{
                pass.type="password";
            }
        }
    </script>

</body>
</html>