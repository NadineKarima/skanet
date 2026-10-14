<?php

require_once __DIR__ . "/../config/session.php";

if (
    !isset($_SESSION["login"]) ||
    $_SESSION["login"] !== true
) {

    header("Location: auth/login.php");
    exit;

}