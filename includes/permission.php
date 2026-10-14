<?php

function requireLogin(): void
{
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: ../../auth/login.php');
        exit;
    }
}

function isAdmin(): bool
{
    return ($_SESSION['role'] ?? null) === 'admin';
}

function isGuru(): bool
{
    return ($_SESSION['role'] ?? null) === 'guru';
}

function requireAdmin(): void
{
    if (!isAdmin()) {

        $_SESSION['flash'] = [
            "type"  => "error",
            "pesan" => "Kamu tidak punya akses ke halaman itu."
        ];

        header("Location: index.php?page=dashboard");
        exit;

    }
}

function idGuruSaatIni(): ?int
{
    return isGuru() ? (int) $_SESSION['id'] : null;
}