<?php

require_once "../../config/session.php";
require_once "../../config/database.php";

header("Content-Type: application/json");

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    http_response_code(401);
    echo json_encode(["ok" => false]);
    exit;
}

$aktif = isset($_POST['aktif']) && $_POST['aktif'] === '1' ? 1 : 0;

$userType = $_SESSION['role'];
$userId   = (int) $_SESSION['id'];

$stmt = $pdo->prepare("
    INSERT INTO preferensi_user (user_type, user_id, dark_mode)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE dark_mode = VALUES(dark_mode)
");
$stmt->execute([$userType, $userId, $aktif]);

$_SESSION['dark_mode'] = (bool) $aktif;

echo json_encode(["ok" => true]);
