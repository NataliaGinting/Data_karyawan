<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = strtolower($_SESSION['role'] ?? '');

// hanya boleh admin / personalia
if (!in_array($role, ['admin', 'personalia'])) {
    die("Tidak punya akses");
}

// pastikan id dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID tidak valid atau tidak dikirim.");
}

// cek dulu apakah data ada
$cek = mysqli_query($conn, "SELECT id FROM karyawan WHERE id='$id'");

if (mysqli_num_rows($cek) == 0) {
    die("Data tidak ditemukan");
}

// update status
$update = mysqli_query($conn, "
    UPDATE karyawan 
    SET status='nonaktif'
    WHERE id='$id'
");

if ($update) {
    echo "<script>
        alert('Karyawan berhasil dinonaktifkan');
        window.location='lihat_data.php';
    </script>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>