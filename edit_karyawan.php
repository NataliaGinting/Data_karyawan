<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = strtolower($_SESSION['role'] ?? '');

if ($role != 'personalia') {
    die("<h3 style='font-family:Segoe UI'>Tidak memiliki akses</h3>");
}

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID tidak valid");
}

$query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data Karyawan</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
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
}

.sidebar a:hover{background:#43a047;}

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
}

/* MAIN */
.main{
    margin-left:230px;
    margin-top:70px;
    padding:30px;
}

.box{
    background:#fff;
    padding:25px;
    border-radius:15px;
}

label{
    display:block;
    margin-top:10px;
    font-weight:bold;
}

input,select,textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    margin-top:20px;
    padding:10px 15px;
    background:#2e7d32;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{background:#1b5e20;}
</style>
</head>

<body>

<!-- SIDEBAR (SAMA DENGAN DATA KARYAWAN) -->
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
    <div><?= htmlspecialchars($username) ?></div>
</div>

<!-- MAIN -->
<div class="main">

<div class="box">

<h2>Edit Data Karyawan</h2>

<form action="update_karyawan.php" method="POST">

<input type="hidden" name="id" value="<?= $data['id'] ?>">

<!-- ===== DATA PRIBADI ===== -->
<h3>Data Pribadi</h3>

<label>Nama</label>
<input type="text" name="nama" value="<?= $data['nama'] ?>" required>

<label>Jenis Kelamin</label>
<select name="jenis_kelamin">
    <option value="Laki-laki" <?= ($data['jenis_kelamin']=='Laki-laki'?'selected':'') ?>>Laki-laki</option>
    <option value="Perempuan" <?= ($data['jenis_kelamin']=='Perempuan'?'selected':'') ?>>Perempuan</option>
</select>

<label>Tempat Lahir</label>
<input type="text" name="tempat_lahir" value="<?= $data['tempat_lahir'] ?>">

<label>Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" value="<?= $data['tanggal_lahir'] ?>">

<label>Alamat</label>
<textarea name="alamat"><?= $data['alamat'] ?></textarea>

<label>NIK</label>
<input type="text" name="nik" value="<?= $data['nik'] ?>">

<label>No Telepon</label>
<input type="text" name="no_telepon" value="<?= $data['no_telepon'] ?>">

<label>Status Pernikahan</label>
<select name="status_pernikahan">
    <option value="Belum Menikah" <?= ($data['status_pernikahan']=='Belum Menikah'?'selected':'') ?>>Belum Menikah</option>
    <option value="Menikah" <?= ($data['status_pernikahan']=='Menikah'?'selected':'') ?>>Menikah</option>
</select>

<!-- ===== DATA PEKERJAAN ===== -->
<h3>Data Pekerjaan</h3>

<label>Status Karyawan</label>
<select name="status_karyawan">
    <option value="BHL" <?= ($data['status_karyawan']=='BHL'?'selected':'') ?>>BHL</option>
    <option value="Karyawan Tetap" <?= ($data['status_karyawan']=='Karyawan Tetap'?'selected':'') ?>>Karyawan Tetap</option>
</select>

<label>Divisi</label>
<select name="divisi">
    <option value="KANTOR" <?= ($data['divisi']=='KANTOR'?'selected':'') ?>>KANTOR</option>
    <option value="TRAKSI" <?= ($data['divisi']=='TRAKSI'?'selected':'') ?>>TRAKSI</option>
    <option value="DIVISI I" <?= ($data['divisi']=='DIVISI I'?'selected':'') ?>>DIVISI I</option>
    <option value="DIVISI II" <?= ($data['divisi']=='DIVISI II'?'selected':'') ?>>DIVISI II</option>
    <option value="DIVISI III" <?= ($data['divisi']=='DIVISI III'?'selected':'') ?>>DIVISI III</option>
    <option value="DIVISI IV" <?= ($data['divisi']=='DIVISI IV'?'selected':'') ?>>DIVISI IV</option>
    <option value="DIVISI V" <?= ($data['divisi']=='DIVISI V'?'selected':'') ?>>DIVISI V</option>
</select>

<label>Jabatan</label>
<select name="jabatan">
    <option value="Pemanen" <?= ($data['jabatan']=='Pemanen'?'selected':'') ?>>Pemanen</option>
    <option value="Perawatan" <?= ($data['jabatan']=='Perawatan'?'selected':'') ?>>Perawatan</option>
    <option value="Mandor Panen" <?= ($data['jabatan']=='Mandor Panen'?'selected':'') ?>>Mandor Panen</option>
    <option value="Mekanik" <?= ($data['jabatan']=='Mekanik'?'selected':'') ?>>Mekanik</option>
</select>

<label>Tanggal Masuk</label>
<input type="date" name="tgl_masuk" value="<?= $data['tgl_masuk'] ?>">

<label>No BPJS</label>
<input type="text" name="no_bpjs" value="<?= $data['no_bpjs'] ?>">

<label>No Rekening</label>
<input type="text" name="no_rekening" value="<?= $data['no_rekening'] ?>">

<button type="submit">💾 Simpan Perubahan</button>

</form>

</div>

</div>

</body>
</html>