<?php
session_start();
include 'koneksi.php';

// ===== Cek login =====
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$username      = $_SESSION['username'];
$jabatan       = $_SESSION['jabatan'];
$jabatan_lower = strtolower($jabatan);
$divisi        = $_SESSION['divisi'] ?? null;
$role          = $_SESSION['role_manajer_ktu'] ?? null;

// ===== Tentukan status pending sesuai jabatan =====
switch($jabatan_lower){
    case 'mandor':
        $status_pending = "'pending_mandor'";
        break;
    case 'asisten':
        $status_pending = "'pending_asisten'";
        break;
    case 'askep':
        $status_pending = "'pending_askep'";
        break;
    case 'manajer':
        $status_pending = "'pending_manager'";
        break;
    default:
        $status_pending = "'pending'";
}

// ===== Query data pending =====
$where = "status_verifikasi IN ($status_pending)";

// Mandor & Asisten hanya divisinya
if(in_array($jabatan_lower,['mandor','asisten']) && $divisi){
    $where .= " AND divisi='".mysqli_real_escape_string($conn,$divisi)."'";
}

$sql = "SELECT * FROM karyawan WHERE $where ORDER BY tgl_masuk DESC";
$result = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pending Karyawan</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Poppins,sans-serif;background:#f4f6f9;padding:20px;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{border:1px solid #ddd;padding:8px;text-align:left;}
th{background:#2e7d32;color:white;}
button{padding:5px 10px;border:none;border-radius:5px;cursor:pointer;margin-right:5px;}
.approve{background:#4caf50;color:white;}
.reject{background:#f44336;color:white;}
.dashboard-btn{background:#2196f3;color:white;}
</style>
</head>
<body>

<a href="dashboard.php"><button class="dashboard-btn"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</button></a>

<h2>Pending Karyawan</h2>
<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Divisi</th>
        <th>Jabatan</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while($row = mysqli_fetch_assoc($result)):
    ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['divisi']) ?></td>
        <td><?= htmlspecialchars($row['jabatan']) ?></td>
        <td><?= htmlspecialchars($row['status_verifikasi']) ?></td>
        <td>
            <form action="proses_pending.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="approve" class="approve">Setujui</button>
            </form>
            <form action="proses_pending.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="reject" class="reject">Tolak</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
