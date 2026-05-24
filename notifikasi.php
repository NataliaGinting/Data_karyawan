<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi - PT. BARAPALA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 25px;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }

        .notif {
            background: #e8f5e9;
            border-left: 5px solid #43a047;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .notif:hover {
            background: #dcedc8;
        }

        .notif i {
            font-size: 22px;
            color: #2e7d32;
            margin-right: 15px;
        }

        .notif p {
            margin: 0;
            color: #333;
        }

        .no-notif {
            text-align: center;
            color: #777;
            padding: 30px 0;
        }

        .back {
            text-align: center;
            margin-top: 30px;
        }

        .btn-back {
            background: #2e7d32;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-back:hover {
            background: #1b5e20;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Notifikasi</h2>

    <?php
    // Ambil contoh data dari database jika ada tabel notifikasi
    $query = mysqli_query($conn, "SELECT * FROM notifikasi ORDER BY tanggal DESC");

    if (mysqli_num_rows($query) > 0) {
        while ($data = mysqli_fetch_assoc($query)) {
            echo "
            <div class='notif'>
                <i class='fa fa-bell'></i>
                <p><strong>{$data['judul']}</strong><br>{$data['pesan']}<br>
                <small><i class='fa fa-clock'></i> {$data['tanggal']}</small></p>
            </div>";
        }
    } else {
        echo "<div class='no-notif'><i class='fa fa-bell-slash'></i><br>Belum ada notifikasi baru.</div>";
    }
    ?>

    <div class="back">
        <a href="dashboard.php" class="btn-back">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

</body>
</html>
