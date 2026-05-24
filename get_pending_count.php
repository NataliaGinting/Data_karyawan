<?php
session_start();
include 'koneksi.php';

$jabatan_lower = isset($_SESSION['jabatan']) ? strtolower(trim($_SESSION['jabatan'])) : '';
if(!in_array($jabatan_lower,['mandor','asisten','krani personalia'])){
    echo json_encode(['count'=>0,'list'=>[]]);
    exit();
}

$count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM karyawan WHERE status_verifikasi='pending'"))['total'];

$list = [];
$res = mysqli_query($conn,"SELECT id_karyawan,nama,divisi FROM karyawan WHERE status_verifikasi='pending'");
while($row=mysqli_fetch_assoc($res)){
    $list[] = ['id'=>$row['id_karyawan'],'nama'=>$row['nama'],'divisi'=>$row['divisi']];
}

echo json_encode(['count'=>$count,'list'=>$list]);
