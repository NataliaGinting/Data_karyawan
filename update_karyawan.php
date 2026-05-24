<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = strtolower($_SESSION['role'] ?? '');

if($role != 'personalia'){
    die("Tidak punya akses");
}

$id = intval($_POST['id'] ?? 0);

if($id <= 0){
    die("ID tidak valid");
}

/* =========================
AMBIL DATA FORM
========================= */
function clean($conn, $key){
    return mysqli_real_escape_string($conn, $_POST[$key] ?? '');
}

$kode_pekerja   = clean($conn,'kode_pekerja');
$nama           = clean($conn,'nama');
$jenis_kelamin  = clean($conn,'jenis_kelamin');
$tempat_lahir   = clean($conn,'tempat_lahir');
$tanggal_lahir  = clean($conn,'tanggal_lahir');
$agama          = clean($conn,'agama');
$pendidikan     = clean($conn,'pendidikan');
$alamat         = clean($conn,'alamat');
$no_telepon     = clean($conn,'no_telepon');
$nik            = clean($conn,'nik');
$status_karyawan= clean($conn,'status_karyawan');
$unit_kerja     = clean($conn,'unit_kerja');
$divisi         = clean($conn,'divisi');
$jabatan        = clean($conn,'jabatan');
$tgl_masuk      = clean($conn,'tgl_masuk');
$no_bpjs        = clean($conn,'no_bpjs');
$no_rekening    = clean($conn,'no_rekening');
$keterangan     = clean($conn,'keterangan');

/* =========================
UPDATE QUERY (AMAN & FIX)
========================= */
$query = mysqli_query($conn, "
UPDATE karyawan SET
kode_pekerja='$kode_pekerja',
nama='$nama',
jenis_kelamin='$jenis_kelamin',
tempat_lahir='$tempat_lahir',
tanggal_lahir='$tanggal_lahir',
agama='$agama',
pendidikan='$pendidikan',
alamat='$alamat',
no_telepon='$no_telepon',
nik='$nik',
status_karyawan='$status_karyawan',
unit_kerja='$unit_kerja',
divisi='$divisi',
jabatan='$jabatan',
tgl_masuk='$tgl_masuk',
no_bpjs='$no_bpjs',
no_rekening='$no_rekening',
keterangan='$keterangan'
WHERE id='$id'
");

if($query){
    echo "<script>
    alert('Berhasil update data');
    window.location='lihat_data.php';
    </script>";
}else{
    echo "<h3>Error Update:</h3>";
    echo mysqli_error($conn);
}
?>