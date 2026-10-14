<?php

require_once "includes/auth_check.php";
require_once "config/database.php";
require_once "includes/permission.php";

$page = $_GET["page"] ?? "dashboard";

$allowed = [

    "dashboard",
    "guru",
    "siswa",
    "jurusan",
    "kelas",
    "mapel",
    "nilai",
    "laporan",
    "setting",
    "tentang"

];

if (!in_array($page, $allowed)) {

    $page = "dashboard";

}

$halamanAdminSaja = ["guru", "jurusan", "kelas", "mapel"];

if (in_array($page, $halamanAdminSaja, true)) {
    requireAdmin();
}

include "includes/header.php";
?>

<div class="wrapper">

    <?php include "includes/sidebar.php"; ?>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">

        <?php include "includes/navbar.php"; ?>

        <div class="content">

            <?php require "includes/flash.php"; ?>

            <?php
            $file_module = "pages/$page/index.php";
            $file_flat   = "pages/$page.php";

            if (file_exists($file_module)) {
                include $file_module;
            } else {
                include $file_flat;
            }
            ?>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>
