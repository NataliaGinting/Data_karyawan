<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$jabatan_lower = strtolower($_SESSION['jabatan']);
$role = $_SESSION['role_manajer_ktu'] ?? null;

// Fungsi cek hak akses
function checkAccess($allowed_roles = [], $allowed_special = []) {
    global $jabatan_lower, $role;
    if (!in_array($jabatan_lower, $allowed_roles) && !in_array($role, $allowed_special)) {
        header("Location: dashboard.php?error=akses");
        exit();
    }
}

