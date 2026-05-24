<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

if(!isset($_POST['id'], $_POST['action'])){
    die("Data tidak lengkap.");
}

$id     = intval($_POST['id']);
$action = $_POST['action'];

// Ambil status sekarang
$q = mysqli_query($conn, "SELECT status_verifikasi FROM karyawan WHERE id=$id");
if(mysqli_num_rows($q)==0) die("Data tidak ditemukan.");
$row = mysqli_fetch_assoc($q);
$current_status = $row['status_verifikasi'];

// Tentukan status baru jika approve
if($action=='approve'){
    switch($current_status){
        case 'pending_mandor': $new_status = 'pending_asisten'; break;
        case 'pending_asisten': $new_status = 'pending_askep'; break;
        case 'pending_askep': $new_status = 'pending_manager'; break;
        case 'pending_manager': $new_status = 'verified'; break;
        default: $new_status = 'verified';
    }
} elseif($action=='reject'){
    $new_status = 'rejected';
} else{
    die("Aksi tidak valid.");
}

// Update status
$sql = "UPDATE karyawan SET status_verifikasi='$new_status' WHERE id=$id";
if(mysqli_query($conn,$sql)){
    header("Location: pending_karyawan.php");
    exit();
}else{
    die("Gagal update status: ".mysqli_error($conn));
}
