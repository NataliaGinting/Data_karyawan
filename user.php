<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "koneksi.php";

// =====================
// CEK LOGIN
// =====================
$username = $_SESSION['username'] ?? '';
$role     = $_SESSION['role'] ?? '';

if (!$username) {
    header("Location: login.php");
    exit;
}

// =====================
// NONAKTIFKAN USER
// =====================
if (isset($_GET['nonaktif_user'])) {

    $id = $_GET['nonaktif_user'];

    $stmt = $conn->prepare(
    "UPDATE users SET status='nonaktif' WHERE id=?");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: user.php");
    exit;
}

// =====================
// AKTIFKAN USER
// =====================
if (isset($_GET['aktif_user'])) {

    $id = $_GET['aktif_user'];

    $stmt = $conn->prepare(
    "UPDATE users SET status='aktif' WHERE id=?");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: user.php");
    exit;
}

// =====================
// AMBIL DATA EDIT
// =====================
$edit_user = null;

if (isset($_GET['edit_user'])) {

    $id = $_GET['edit_user'];

    $stmt = $conn->prepare(
    "SELECT * FROM users WHERE id=?");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $edit_user = $result->fetch_assoc();
}

// =====================
// TAMBAH USER
// =====================
if (isset($_POST['simpan_user'])) {

    $username_input = trim($_POST['username']);
    $password_input = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_input     = $_POST['role'];

    $cek = $conn->prepare(
    "SELECT id FROM users WHERE username=?");

    $cek->bind_param("s", $username_input);
    $cek->execute();

    $hasil = $cek->get_result();

    if ($hasil->num_rows > 0) {

        echo "<script>alert('Username sudah digunakan!');</script>";

    } else {

        $stmt = $conn->prepare(
        "INSERT INTO users
        (username,password,role,status)
        VALUES (?,?,?,'aktif')");

        $stmt->bind_param(
        "sss",
        $username_input,
        $password_input,
        $role_input
        );

        $stmt->execute();

        header("Location: user.php");
        exit;
    }
}

// =====================
// UPDATE USER
// =====================
if (isset($_POST['update_user'])) {

    $id             = $_POST['id'];
    $username_input = trim($_POST['username']);
    $role_input     = $_POST['role'];

    $stmt = $conn->prepare(
    "UPDATE users
    SET username=?, role=?
    WHERE id=?");

    $stmt->bind_param(
    "ssi",
    $username_input,
    $role_input,
    $id
    );

    $stmt->execute();

    header("Location: user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kelola User - Barapala HR</title>

<style>

/* BODY */

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
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

/* HEADER */

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
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
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
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

/* TITLE */

.title{
    margin-bottom:20px;
}

.title h2{
    margin:0;
    color:#2e7d32;
}

.title p{
    color:#666;
}

/* FORM */

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

input,select{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    margin-top:5px;
    box-sizing:border-box;
}

button{
    background:#2e7d32;
    color:white;
    border:none;
    padding:12px 18px;
    border-radius:8px;
    cursor:pointer;
    margin-top:10px;
}

button:hover{
    background:#388e3c;
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
    background:white;
    border-radius:15px;
    overflow:hidden;
}

th{
    background:#2e7d32;
    color:white;
}

th,td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}

/* STATUS */

.status-aktif{
    background:#d4edda;
    color:#155724;
    padding:6px 12px;
    border-radius:20px;
    font-size:14px;
    font-weight:bold;
}

.status-nonaktif{
    background:#f8d7da;
    color:#721c24;
    padding:6px 12px;
    border-radius:20px;
    font-size:14px;
    font-weight:bold;
}

/* AKSI */

.aksi{
    text-decoration:none;
    font-weight:bold;
    margin:0 5px;
}

.edit{
    color:#1976d2;
}

.nonaktif{
    color:#d32f2f;
}

.aktif{
    color:#2e7d32;
}

</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">

<a href="dashboard.php">🏠 Dashboard</a>
<a href="user.php">👤 Kelola User</a>
<a href="lihat_data.php">📋 Data Karyawan</a>
<a href="laporan.php">📄 Laporan</a>
<a href="logout.php">🚪 Logout</a>

</div>

<!-- HEADER -->
<div class="header">

<div>
<h2>Barapala HR</h2>
</div>

<div>
<?= htmlspecialchars($username) ?>
(<?= strtoupper(htmlspecialchars($role)) ?>)
</div>

</div>

<!-- MAIN -->
<div class="main">

<div class="box">

<div class="title">

<h2>Kelola User</h2>

<p>
Tambah, edit, dan nonaktifkan akun pengguna sistem.
</p>

</div>

<!-- FORM -->
<form method="POST">

<input
type="hidden"
name="id"
value="<?= $edit_user['id'] ?? '' ?>"
>

<div class="form-grid">

<div>
<label>Username</label>

<input
type="text"
name="username"
placeholder="Masukkan username"
value="<?= $edit_user['username'] ?? '' ?>"
required>
</div>

<div>

<?php if(!$edit_user){ ?>

<label>Password</label>

<input
type="password"
name="password"
placeholder="Masukkan password"
required>

<?php } ?>

</div>

<div>
<label>Role</label>

<select name="role" required>

<option value="">-- Pilih Role --</option>

<option value="admin"
<?= ($edit_user['role'] ?? '')=='admin'?'selected':'' ?>>
Admin
</option>

<option value="personalia"
<?= ($edit_user['role'] ?? '')=='personalia'?'selected':'' ?>>
Personalia
</option>

<option value="manajer"
<?= ($edit_user['role'] ?? '')=='manajer'?'selected':'' ?>>
Manajer
</option>

</select>

</div>

</div>

<?php if($edit_user){ ?>

<button name="update_user">
Update User
</button>

<?php } else { ?>

<button name="simpan_user">
Simpan User
</button>

<?php } ?>

</form>

<!-- TABEL -->
<table>

<tr>
<th>No</th>
<th>Username</th>
<th>Role</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php

$no = 1;

$q = $conn->query(
"SELECT * FROM users ORDER BY id DESC");

if($q->num_rows > 0){

while($d = $q->fetch_assoc()){

?>

<tr>

<td><?= $no++ ?></td>

<td>
<?= htmlspecialchars($d['username']) ?>
</td>

<td>
<?= htmlspecialchars($d['role']) ?>
</td>

<td>

<?php if(($d['status'] ?? 'aktif') == "aktif"): ?>

<span class="status-aktif">
Aktif
</span>

<?php else: ?>

<span class="status-nonaktif">
Nonaktif
</span>

<?php endif; ?>

</td>

<td>

<a class="aksi edit"
href="user.php?edit_user=<?= $d['id'] ?>">
Edit
</a>

|

<?php if(($d['status'] ?? 'aktif') == "aktif"): ?>

<a class="aksi nonaktif"
href="user.php?nonaktif_user=<?= $d['id'] ?>"
onclick="return confirm('Nonaktifkan user ini?')">

Nonaktifkan

</a>

<?php else: ?>

<a class="aksi aktif"
href="user.php?aktif_user=<?= $d['id'] ?>"
onclick="return confirm('Aktifkan user ini?')">

Aktifkan

</a>

<?php endif; ?>

</td>

</tr>

<?php
}
}else{
?>

<tr>
<td colspan="5">
Tidak ada data user
</td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>