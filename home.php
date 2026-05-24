<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home - PT. BARAPALA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <h1>Selamat Datang di Home Dashboard!</h1>
    <p>Halo <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>, anda login sebagai <strong><?= htmlspecialchars($_SESSION['jabatan']); ?></strong>.</p>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
