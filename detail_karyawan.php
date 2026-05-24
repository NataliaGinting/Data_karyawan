<?php
session_start();
include 'koneksi.php';

/* =========================
CEK LOGIN
========================= */

if (!isset($_SESSION['username'])) {

    header("Location: login.php");
    exit();

}

$username = $_SESSION['username'];
$role     = $_SESSION['role'] ?? '';

/* =========================
AMBIL ID KARYAWAN
========================= */

$id_karyawan =
$_GET['id_karyawan'] ?? '';

if (empty($id_karyawan)) {

    echo "ID karyawan tidak ditemukan.";
    exit();

}

/* =========================
QUERY DATA KARYAWAN
========================= */

$query = "
SELECT * FROM karyawan
WHERE id='$id_karyawan'
";

$result =
mysqli_query($conn, $query);

$data =
mysqli_fetch_assoc($result);

if (!$data) {

    echo "Data karyawan tidak ditemukan.";
    exit();

}

/* =========================
QUERY DATA KELUARGA
========================= */

$query_keluarga = "
SELECT * FROM keluarga_karyawan
WHERE id_karyawan='$id_karyawan'
";

$result_keluarga =
mysqli_query($conn, $query_keluarga);

/* =========================
FORMAT TANGGAL
========================= */

function formatTanggal($tanggal){

    if(
        empty($tanggal) ||
        $tanggal == '0000-00-00'
    ){
        return '-';
    }

    return date(
        'd-m-Y',
        strtotime($tanggal)
    );

}

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Detail Karyawan - Barapala HR
</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* =========================
BODY
========================= */

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
}

/* =========================
SIDEBAR
========================= */

.sidebar{
    width:230px;
    height:100vh;
    background:#2e7d32;
    position:fixed;
    left:0;
    top:0;
    padding-top:20px;
    overflow-y:auto;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:14px 20px;
    margin:8px 12px;
    border-radius:10px;
    transition:0.3s;
    font-size:16px;
}

.sidebar a:hover{
    background:#43a047;
}

/* =========================
HEADER
========================= */

.header{
    position:fixed;
    top:0;
    left:230px;
    right:0;
    height:70px;
    background:#2e7d32;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 25px;
    color:white;
    z-index:100;
}

/* =========================
MAIN
========================= */

.main{
    margin-left:230px;
    margin-top:70px;
    padding:30px;
}

/* =========================
BOX
========================= */

.box{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    max-width:1000px;
    margin:auto;
}

/* =========================
TITLE
========================= */

.box h2{
    color:#2e7d32;
    margin-bottom:25px;
    text-align:center;
}

.box h3{
    margin-top:35px;
    color:#1b5e20;
    border-left:5px solid #2e7d32;
    padding-left:10px;
}

/* =========================
TABLE
========================= */

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

table td,
table th{
    border:1px solid #ddd;
    padding:12px;
    font-size:14px;
}

table th{
    background:#2e7d32;
    color:white;
}

.label{
    width:30%;
    background:#f1f1f1;
    font-weight:bold;
}

/* =========================
BUTTON
========================= */

.btn{
    display:inline-block;
    padding:10px 18px;
    background:#2e7d32;
    color:white;
    text-decoration:none;
    border-radius:8px;
    margin-top:25px;
}

.btn:hover{
    background:#1b5e20;
}

/* =========================
STATUS
========================= */

.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.disetujui{
    background:#e8f5e9;
    color:#2e7d32;
}

.pending{
    background:#fff8e1;
    color:#f9a825;
}

.ditolak{
    background:#ffebee;
    color:#d32f2f;
}

/* =========================
RESPONSIVE
========================= */

@media(max-width:768px){

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .header{
        left:0;
    }

    .main{
        margin-left:0;
    }

}

</style>

</head>

<body>

<!-- =========================
SIDEBAR
========================= -->

<div class="sidebar">

<a href="dashboard.php">
🏠 Dashboard
</a>

<?php if($role == 'admin'){ ?>

<a href="user.php">
👤 Kelola User
</a>

<?php } ?>

<?php if($role == 'personalia'){ ?>

<a href="inputkaryawan.php">
➕ Input Karyawan
</a>

<?php } ?>

<?php if($role == 'manager'){ ?>

<a href="verifikasi.php">
✔ Verifikasi
</a>

<?php } ?>

<a href="lihat_data.php">
📋 Data Karyawan
</a>

<a href="laporan.php">
📄 Laporan
</a>

<a href="logout.php">
🚪 Logout
</a>

</div>

<!-- =========================
HEADER
========================= -->

<div class="header">

<div>
<h2>Barapala HR</h2>
</div>

<div>

<?= htmlspecialchars($username) ?>

</div>

</div>

<!-- =========================
MAIN
========================= -->

<div class="main">

<div class="box">

<h2>Detail Data Karyawan</h2>

<!-- =========================
DATA PRIBADI
========================= -->

<h3>Data Pribadi</h3>

<table>

<tr>
<td class="label">Kode Pekerja</td>
<td><?= htmlspecialchars($data['kode_pekerja'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Nama Lengkap</td>
<td><?= htmlspecialchars($data['nama'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Jenis Kelamin</td>
<td><?= htmlspecialchars($data['jenis_kelamin'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Tempat Lahir</td>
<td><?= htmlspecialchars($data['tempat_lahir'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Tanggal Lahir</td>
<td><?= formatTanggal($data['tanggal_lahir'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Usia</td>
<td><?= htmlspecialchars($data['usia'] ?? '-') ?> Tahun</td>
</tr>

<tr>
<td class="label">Agama</td>
<td><?= htmlspecialchars($data['agama'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Pendidikan</td>
<td><?= htmlspecialchars($data['pendidikan'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Alamat</td>
<td><?= htmlspecialchars($data['alamat'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">No Telepon</td>
<td><?= htmlspecialchars($data['no_telepon'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">NIK</td>
<td><?= htmlspecialchars($data['nik'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Status Pernikahan</td>
<td><?= htmlspecialchars($data['status_menikah'] ?? '-') ?></td>
</tr>

</table>

<!-- =========================
DATA KELUARGA
========================= -->

<h3>Data Keluarga</h3>

<table>

<tr>
<th>No</th>
<th>Nama Keluarga</th>
<th>Hubungan</th>
<th>NIK</th>
</tr>

<?php

$no = 1;

if(mysqli_num_rows($result_keluarga) > 0){

    while($keluarga = mysqli_fetch_assoc($result_keluarga)){

?>

<tr>

<td><?= $no++ ?></td>

<td>
<?= htmlspecialchars($keluarga['nama_keluarga']) ?>
</td>

<td>
<?= htmlspecialchars($keluarga['hubungan']) ?>
</td>

<td>
<?= htmlspecialchars($keluarga['nik_keluarga']) ?>
</td>

</tr>

<?php

    }

}else{

?>

<tr>

<td colspan="4">
Data keluarga tidak ada
</td>

</tr>

<?php } ?>

</table>

<!-- =========================
DATA PEKERJAAN
========================= -->

<h3>Data Pekerjaan</h3>

<table>

<tr>
<td class="label">Status Karyawan</td>
<td><?= htmlspecialchars($data['status_karyawan'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Unit Kerja</td>
<td><?= htmlspecialchars($data['unit_kerja'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Divisi</td>
<td><?= htmlspecialchars($data['divisi'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Jabatan</td>
<td><?= htmlspecialchars($data['jabatan'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Tanggal Masuk</td>
<td><?= formatTanggal($data['tgl_masuk'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">No BPJS</td>
<td><?= htmlspecialchars($data['no_bpjs'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">No Rekening</td>
<td><?= htmlspecialchars($data['no_rekening'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Diinput Oleh</td>
<td><?= htmlspecialchars($data['created_by'] ?? '-') ?></td>
</tr>

<tr>
<td class="label">Tanggal Input</td>
<td><?= htmlspecialchars($data['tanggal_input'] ?? '-') ?></td>
</tr>

</table>

<!-- =========================
DOKUMEN
========================= -->

<h3>Dokumen</h3>

<table>

<tr>

<td class="label">
Foto KTP
</td>

<td>

<?php if(!empty($data['foto_ktp'])): ?>

<a
href="uploads/<?= $data['foto_ktp'] ?>"
target="_blank">

Lihat File KTP

</a>

<?php else: ?>

Tidak ada file

<?php endif; ?>

</td>

</tr>

<tr>

<td class="label">
Foto KK
</td>

<td>

<?php if(!empty($data['foto_kk'])): ?>

<a
href="uploads/<?= $data['foto_kk'] ?>"
target="_blank">

Lihat File KK

</a>

<?php else: ?>

Tidak ada file

<?php endif; ?>

</td>

</tr>

</table>

<!-- =========================
STATUS VERIFIKASI
========================= -->

<h3>Status Verifikasi</h3>

<table>

<tr>

<td class="label">
Status
</td>

<td>

<?php

$status =
strtolower(
$data['status_verifikasi']
?? 'pending'
);

?>

<span class="status <?= $status ?>">

<?= htmlspecialchars(
$data['status_verifikasi']
?? 'Pending'
) ?>

</span>

</td>

</tr>

<tr>

<td class="label">
Keterangan
</td>

<td>

<?= nl2br(
htmlspecialchars(
$data['keterangan']
?? '-'
)
) ?>

</td>

</tr>

</table>

<?php
$from = $_GET['from'] ?? 'data';

$back = ($from == 'verifikasi')
    ? 'verifikasi.php'
    : 'lihat_data.php';
?>

<a href="<?= $back ?>" class="btn">

<i class="fa fa-arrow-left"></i>
Kembali

</a>

</div>

</div>

</body>
</html>