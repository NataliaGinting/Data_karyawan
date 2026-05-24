<?php
session_start();
include "koneksi.php";

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
CEK ROLE
========================= */
if ($role != 'manajer') {
    die("Akses ditolak - hanya manajer");
}

/* =========================
QUERY DATA
========================= */
$query = mysqli_query($conn, "
SELECT * FROM karyawan
WHERE status_verifikasi='pending'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verifikasi Karyawan</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
    margin:0;
    font-family:Segoe UI,sans-serif;
    background:#f4f6f9;
}

/* SIDEBAR */
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

/* HEADER */
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

/* MAIN */
.main{
    margin-left:230px;
    margin-top:70px;
    padding:30px;
}

/* BOX */
.box{
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

/* TITLE */
.box h2{
    margin-top:0;
    margin-bottom:20px;
    color:#2e7d32;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th{
    background:#2e7d32;
    color:white;
    padding:12px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#fafafa;
}

/* BUTTON */
.btn{
    padding:7px 12px;
    border:none;
    color:white;
    border-radius:6px;
    cursor:pointer;
    text-decoration:none;
    font-size:13px;
    display:inline-block;
}

.approve{
    background:#2e7d32;
}

.reject{
    background:#d32f2f;
}

.detail{
    background:#1976d2;
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
<?= htmlspecialchars($username) ?>(<?= strtoupper(trim($role ?? '')) ?>)
</div>

</div>

<!-- MAIN -->
<div class="main">

<div class="box">

<h2>Data Pending Verifikasi</h2>

<table>

<tr>
<th>No</th>
<th>Nama</th>
<th>NIK</th>
<th>Divisi</th>
<th>Jabatan</th>
<th>Aksi</th>
</tr>

<?php
$no = 1;

if(mysqli_num_rows($query) == 0){
    echo "
    <tr>
        <td colspan='6'>
            Tidak ada data pending
        </td>
    </tr>
    ";
}

while($r = mysqli_fetch_assoc($query)):
?>

<tr>

<td><?= $no++ ?></td>

<td>
<?= htmlspecialchars($r['nama']) ?>
</td>

<td>
<?= htmlspecialchars($r['nik']) ?>
</td>

<td>
<?= htmlspecialchars($r['divisi']) ?>
</td>

<td>
<?= htmlspecialchars($r['jabatan']) ?>
</td>

<td>

<a class="btn detail"
href="detail_karyawan.php?id_karyawan=<?= urlencode($r['id']) ?>&from=verifikasi">
<i class="fa fa-eye"></i> Detail
</a>

<button class="btn approve"
onclick="verifikasi(<?= $r['id'] ?>,'disetujui')">
Setujui
</button>

<button class="btn reject"
onclick="verifikasi(<?= $r['id'] ?>,'ditolak')">
Tolak
</button>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>
</div>

<script>

function verifikasi(id,status){

fetch('verifikasi_ajax.php',{

method:'POST',

headers:{
'Content-Type':'application/x-www-form-urlencoded'
},

body:'id_karyawan='+id+'&status='+status

})

.then(r => r.json())

.then(d => {

alert(d.message);

if(d.status == 'success'){
location.reload();
}

})

.catch(() => {
alert('Terjadi error server');
});

}

</script>

</body>
</html>