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
    <title>Divisi & Jabatan - PT. BARAPALA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f3;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input {
            width: 48%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #1b5e20;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #2e7d32;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn-delete {
            background: #c62828;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-delete:hover {
            background: #8e0000;
        }

        .back {
            text-align: center;
            margin-top: 30px;
        }

        .btn-back {
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #1b5e20;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manajemen Divisi & Jabatan</h2>

    <!-- Form tambah data -->
    <form action="" method="POST">
        <input type="text" name="divisi" placeholder="Nama Divisi" required>
        <input type="text" name="jabatan" placeholder="Nama Jabatan" required>
        <button type="submit" name="tambah"><i class="fa fa-plus"></i> Tambah</button>
    </form>

    <?php
    // Tambah data ke database
    if (isset($_POST['tambah'])) {
        $divisi = mysqli_real_escape_string($conn, $_POST['divisi']);
        $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);

        $query = "INSERT INTO divisi_jabatan (divisi, jabatan) VALUES ('$divisi', '$jabatan')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data berhasil ditambahkan!'); window.location='divisi_jabatan.php';</script>";
        } else {
            echo "<p style='color:red;'>Gagal menambahkan data: " . mysqli_error($conn) . "</p>";
        }
    }

    // Hapus data
    if (isset($_GET['hapus'])) {
        $id = $_GET['hapus'];
        mysqli_query($conn, "DELETE FROM divisi_jabatan WHERE id='$id'");
        echo "<script>alert('Data berhasil dihapus!'); window.location='divisi_jabatan.php';</script>";
    }
    ?>

    <!-- Tabel Data -->
    <table>
        <tr>
            <th>No</th>
            <th>Divisi</th>
            <th>Jabatan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $result = mysqli_query($conn, "SELECT * FROM divisi_jabatan ORDER BY id DESC");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['divisi']}</td>
                    <td>{$row['jabatan']}</td>
                    <td>
                        <a href='divisi_jabatan.php?hapus={$row['id']}' class='btn-delete' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">
                            <i class='fa fa-trash'></i> Hapus
                        </a>
                    </td>
                </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='4'>Belum ada data divisi dan jabatan.</td></tr>";
        }
        ?>
    </table>

    <!-- Tombol Kembali -->
    <div class="back">
        <a href="dashboard.php" class="btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>
