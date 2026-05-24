<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

/* =========================
CEK LOGIN
========================= */
if (!isset($_SESSION['username'])) {

    header("Location: login.php");
    exit();

}

/* =========================
AMBIL ROLE
========================= */
$role = strtolower($_SESSION['role'] ?? '');

/* =========================
HAK AKSES
========================= */
if (!in_array($role, ['admin', 'personalia'])) {

    die("
    <h3 style='font-family:Segoe UI'>
    Anda tidak memiliki hak akses.
    </h3>
    ");

}

/* =========================
AMBIL ID
========================= */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* =========================
VALIDASI ID
========================= */
if ($id <= 0) {

    die("
    <h3 style='font-family:Segoe UI;color:red'>
    ID tidak valid atau tidak dikirim.
    </h3>
    ");

}

/* =========================
CEK DATA KARYAWAN
========================= */
$cek = mysqli_query($conn, "
    SELECT * FROM karyawan
    WHERE id = '$id'
");

if (mysqli_num_rows($cek) == 0) {

    die("
    <h3 style='font-family:Segoe UI;color:red'>
    Data karyawan tidak ditemukan.
    </h3>
    ");

}

/* =========================
AKTIFKAN KEMBALI
========================= */
$update = mysqli_query($conn, "
    UPDATE karyawan
    SET status = 'aktif'
    WHERE id = '$id'
");

/* =========================
HASIL
========================= */
if ($update) {

    echo "
    <script>

    alert('Karyawan berhasil diaktifkan kembali');

    window.location='lihat_data.php';

    </script>
    ";

} else {

    echo "
    <h3 style='font-family:Segoe UI;color:red'>
    Gagal mengaktifkan data :
    ".mysqli_error($conn)."
    </h3>
    ";

}
?>