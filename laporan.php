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

/* =========================
SESSION
========================= */
$username = $_SESSION['username'];
$role     = strtolower($_SESSION['role'] ?? '');

/* =========================
FILTER
========================= */
$tgl_awal      = $_GET['tgl_awal'] ?? '';
$tgl_akhir     = $_GET['tgl_akhir'] ?? '';
$status_filter = $_GET['status_verifikasi'] ?? '';
$divisi_filter = $_GET['divisi'] ?? '';

$where = "WHERE 1=1";

/* FILTER TANGGAL */
if(!empty($tgl_awal)){

    $where .= "
    AND DATE(tgl_masuk) >= '$tgl_awal'
    ";
}

if(!empty($tgl_akhir)){

    $where .= "
    AND DATE(tgl_masuk) <= '$tgl_akhir'
    ";
}

/* FILTER STATUS */
if(!empty($status_filter)){

    $where .= "
    AND status_verifikasi='$status_filter'
    ";
}

/* FILTER DIVISI */
if(!empty($divisi_filter)){

    $divisi_filter = mysqli_real_escape_string($conn, $divisi_filter);

    $where .= "
    AND divisi = '$divisi_filter'
    ";
}

/* =========================
QUERY DATA
========================= */
$query = "
SELECT *
FROM karyawan
$where
ORDER BY id DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Karyawan</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
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
}

.sidebar a{
    display:block;
    color:#fff;
    text-decoration:none;
    padding:14px 20px;
    margin:8px 12px;
    border-radius:10px;
    transition:0.3s;
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
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 25px;
    z-index:100;
}

.header h3{
    margin:0;
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
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.box h2{
    margin-bottom:20px;
    color:#2e7d32;
}

/* =========================
FILTER
========================= */

.filter-box{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.filter-box input,
.filter-box select{
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
    min-width:150px;
}

/* =========================
BUTTON
========================= */

.btn{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    color:white;
    cursor:pointer;
    font-size:14px;
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
}

.btn-filter{
    background:#2e7d32;
}

.btn-export{
    background:#1976d2;
}

.btn-pdf{
    background:#d32f2f;
}

/* =========================
TABLE
========================= */

.table-responsive{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#2e7d32;
    color:white;
    padding:12px;
    text-align:center;
    white-space:nowrap;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
    white-space:nowrap;
}

/* =========================
BADGE
========================= */

.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    display:inline-block;
    min-width:80px;
}

/* STATUS KERJA */

.badge-bhl{
    background:#e3f2fd;
    color:#1565c0;
}

.badge-tetap{
    background:#e8f5e9;
    color:#2e7d32;
}

.badge-nonaktif{
    background:#ffebee;
    color:#d32f2f;
}

/* =========================
STATUS VERIFIKASI
========================= */

.status{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    display:inline-block;
    min-width:90px;
    text-align:center;
}

.status-approved{
    background:#e8f5e9;
    color:#2e7d32;
}

.status-pending{
    background:#fff8e1;
    color:#f9a825;
}

.status-rejected{
    background:#ffebee;
    color:#d32f2f;
}

/* =========================
RESPONSIVE
========================= */

@media(max-width:768px){

    .sidebar{
        width:75px;
    }

    .sidebar a{
        text-align:center;
        padding:15px 5px;
        font-size:12px;
    }

    .header{
        left:75px;
    }

    .main{
        margin-left:75px;
        padding:15px;
    }

    .filter-box{
        flex-direction:column;
    }

    .filter-box input,
    .filter-box select,
    .btn{
        width:100%;
    }
}

</style>

</head>

<body>

<!-- SIDEBAR -->
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

<?php if($role == 'manajer'){ ?>

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

<!-- HEADER -->
<div class="header">

<h3>Barapala HR</h3>

<div>
<?= htmlspecialchars($username) ?>
(<?= strtoupper($role) ?>)
</div>

</div>

<!-- MAIN -->
<div class="main">

<div class="box">

<h2>📄 Laporan Karyawan</h2>

<!-- FILTER -->
<form method="GET" class="filter-box">

<input
type="date"
name="tgl_awal"
value="<?= htmlspecialchars($tgl_awal) ?>">

<input
type="date"
name="tgl_akhir"
value="<?= htmlspecialchars($tgl_akhir) ?>">

<select name="status_verifikasi">

<option value="">
Semua Verifikasi
</option>

<option value="pending"
<?= $status_filter == 'pending' ? 'selected' : '' ?>>
Pending
</option>

<option value="disetujui"
<?= $status_filter == 'disetujui' ? 'selected' : '' ?>>
Disetujui
</option>

<option value="ditolak"
<?= $status_filter == 'ditolak' ? 'selected' : '' ?>>
Ditolak
</option>

</select>

<select name="divisi">

<option value="">Semua Divisi</option>

<?php
$divisi_list = [
    "KANTOR",
    "TRAKSI",
    "DIVISI I",
    "DIVISI II",
    "DIVISI III",
    "DIVISI IV",
    "DIVISI V"
];

foreach ($divisi_list as $d) {
?>
    <option value="<?= $d ?>"
        <?= ($divisi_filter == $d) ? 'selected' : '' ?>>
        <?= $d ?>
    </option>
<?php } ?>

</select>

<button type="submit"
class="btn btn-filter">
🔍 Filter
</button>

<a href="export_excel.php?<?= http_build_query($_GET) ?>"
class="btn btn-export">
📊 Export Excel
</a>

<a href="export_pdf.php?<?= http_build_query($_GET) ?>"
class="btn btn-pdf">
📄 Export PDF
</a>

</form>

<!-- TABLE -->
<div class="table-responsive">

<table>

<tr>

<th>No</th>
<th>Kode</th>
<th>Nama</th>
<th>NIK</th>
<th>JK</th>
<th>Usia</th>
<th>Divisi</th>
<th>Jabatan</th>
<th>Status Kerja</th>
<th>Status Data</th>
<th>Tanggal Masuk</th>
<th>Verifikasi</th>

</tr>

<?php
$no = 1;

while($row = mysqli_fetch_assoc($result)):

$status_verif = strtolower($row['status_verifikasi'] ?? '');

$class = match($status_verif){

    'disetujui' => 'status-approved',
    'pending'   => 'status-pending',
    'ditolak'   => 'status-rejected',

    default => 'status-pending'
};

$tgl_masuk = '-';

if(!empty($row['tgl_masuk'])){

    $tgl_masuk = date(
        'd-m-Y',
        strtotime($row['tgl_masuk'])
    );
}
?>

<tr>

<td><?= $no++ ?></td>

<td>
<?= htmlspecialchars($row['kode_pekerja'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['nama'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['nik'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['usia'] ?? '-') ?> Tahun
</td>

<td>
<?= htmlspecialchars($row['divisi'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['jabatan'] ?? '-') ?>
</td>

<td>

<?php if(($row['status_karyawan'] ?? '') == 'BHL'){ ?>

<span class="badge badge-bhl">
BHL
</span>

<?php } else { ?>

<span class="badge badge-tetap">
<?= htmlspecialchars($row['status_karyawan'] ?? '-') ?>
</span>

<?php } ?>

</td>

<td>

<?php if(($row['status'] ?? '') == 'nonaktif'){ ?>

<span class="badge badge-nonaktif">
NONAKTIF
</span>

<?php } else { ?>

<span class="badge badge-tetap">
AKTIF
</span>

<?php } ?>

</td>

<td>
<?= $tgl_masuk ?>
</td>

<td>

<span class="status <?= $class ?>">

<?= ucfirst($status_verif) ?>

</span>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</div>

</body>
</html>