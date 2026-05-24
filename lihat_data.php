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
QUERY DATA
========================= */
$query = "SELECT * FROM karyawan ORDER BY id DESC";

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

<title>Data Karyawan</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Segoe UI,sans-serif;
    background:#f4f6f9;
    overflow-x:hidden;
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
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

/* =========================
MAIN
========================= */
.main{
    margin-left:230px;
    margin-top:70px;
    padding:25px;
    min-height:calc(100vh - 70px);
    background:#f4f6f9;
}

/* =========================
BOX
========================= */
.box{
    width:100%;
    background:#ffffff;
    padding:25px;
    border-radius:18px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    overflow-x:auto;
}

/* =========================
TITLE
========================= */
.box h2{
    color:#2e7d32;
    margin-bottom:20px;
}

/* =========================
TABLE
========================= */
table{
    width:100%;
    border-collapse:collapse;
    min-width:1200px;
}

th{
    background:#2e7d32;
    color:white;
    padding:14px 10px;
    text-align:center;
    font-size:14px;
}

td{
    padding:14px 10px;
    text-align:center;
    border-bottom:1px solid #e0e0e0;
    font-size:14px;
    vertical-align:middle;
}

tr:hover{
    background:#fafafa;
}

/* =========================
BUTTON
========================= */
.btn{
    padding:7px 12px;
    border-radius:6px;
    color:white;
    text-decoration:none;
    font-size:13px;
    display:inline-block;
    margin:2px;
    border:none;
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
}

.btn-detail{
    background:#ff9800;
}

.btn-edit{
    background:#2196f3;
}

.btn-nonaktif{
    background:#e53935;
}

.btn-aktif{
    background:#43a047;
}

/* =========================
STATUS
========================= */
.status{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.status-disetujui{
    background:#e8f5e9;
    color:#2e7d32;
}

.status-pending{
    background:#fff8e1;
    color:#f9a825;
}

.status-ditolak{
    background:#ffebee;
    color:#d32f2f;
}

/* =========================
BADGE
========================= */
.badge-bhl{
    background:#e3f2fd;
    color:#1565c0;
    padding:5px 12px;
    border-radius:20px;
}

.badge-tetap{
    background:#e8f5e9;
    color:#2e7d32;
    padding:5px 12px;
    border-radius:20px;
}

.badge-nonaktif{
    background:#ffebee;
    color:#d32f2f;
    padding:5px 12px;
    border-radius:20px;
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
        padding:15px;
    }

    .box{
        padding:15px;
    }

}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<a href="dashboard.php">🏠 Dashboard</a>

<?php if($role == 'admin'){ ?>
<a href="user.php">👤 Kelola User</a>
<?php } ?>

<?php if($role == 'personalia'){ ?>
<a href="inputkaryawan.php">➕ Input Karyawan</a>
<?php } ?>

<?php if($role == 'manajer'){ ?>
<a href="verifikasi.php">✔ Verifikasi</a>
<?php } ?>

<a href="lihat_data.php">📋 Data Karyawan</a>

<a href="laporan.php">📄 Laporan</a>

<a href="logout.php">🚪 Logout</a>

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

<h2>Data Karyawan</h2>

<table>

<tr>
<th>No</th>
<th>Kode</th>
<th>Nama</th>
<th>JK</th>
<th>TTL</th>
<th>Usia</th>
<th>HP</th>
<th>Divisi</th>
<th>Jabatan</th>
<th>Status Kerja</th>
<th>Status Data</th>
<th>Verifikasi</th>
<th>Aksi</th>
</tr>

<?php
$no = 1;

while($row = mysqli_fetch_assoc($result)):

$status_verif = strtolower($row['status_verifikasi'] ?? '');

$class = match($status_verif){
    'disetujui' => 'status-disetujui',
    'pending' => 'status-pending',
    'ditolak' => 'status-ditolak',
    default => 'status-pending'
};

$tgl = !empty($row['tanggal_lahir'])
    ? date('d-m-Y', strtotime($row['tanggal_lahir']))
    : '-';
?>

<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($row['kode_pekerja']) ?></td>

<td><?= htmlspecialchars($row['nama']) ?></td>

<td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>

<td>
<?= htmlspecialchars($row['tempat_lahir']) ?>,
<?= $tgl ?>
</td>

<td><?= htmlspecialchars($row['usia']) ?></td>

<td><?= htmlspecialchars($row['no_telepon']) ?></td>

<td><?= htmlspecialchars($row['divisi']) ?></td>

<td><?= htmlspecialchars($row['jabatan']) ?></td>

<td>

<?php if(($row['status_karyawan'] ?? '') == 'BHL'){ ?>

<span class="badge-bhl">
BHL
</span>

<?php } else { ?>

<span class="badge-tetap">
<?= htmlspecialchars($row['status_karyawan']) ?>
</span>

<?php } ?>

</td>

<td>

<?php if(($row['status'] ?? '') == 'nonaktif'){ ?>

<span class="badge-nonaktif">
NONAKTIF
</span>

<?php } else { ?>

<span class="badge-tetap">
AKTIF
</span>

<?php } ?>

</td>

<td>

<span class="status <?= $class ?>">
<?= ucfirst($status_verif) ?>
</span>

</td>

<td>

<div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">

<a href="detail_karyawan.php?id_karyawan=<?= $row['id'] ?>"
class="btn btn-detail">
Detail
</a>

<?php if($role == 'personalia'){ ?>

<a href="edit_karyawan.php?id=<?= $row['id'] ?>"
class="btn btn-edit">
Edit
</a>

<?php if(($row['status'] ?? '') == 'aktif'){ ?>

<a href="nonaktifkan_karyawan.php?id=<?= $row['id'] ?>"
class="btn btn-nonaktif"
onclick="return confirm('Nonaktifkan karyawan ini?')">
Nonaktif
</a>

<?php } else { ?>

<a href="aktifkan_karyawan.php?id=<?= $row['id'] ?>"
class="btn btn-aktif"
onclick="return confirm('Aktifkan kembali karyawan ini?')">
Aktifkan
</a>

<?php } ?>

<?php } ?>

</div>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>
</div>

</body>
</html>