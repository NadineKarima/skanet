<?php

require "../../config/database.php";

$nama = trim($_POST['nama_jurusan']);

if ($nama == "") {
    header("Location: ../../index.php?page=jurusan");
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO jurusan (nama_jurusan)
    VALUES (?)
");

$stmt->execute([$nama]);

header("Location: ../../index.php?page=jurusan");
exit;