<?php
session_start();
include "koneksi.php";

// pastikan hanya admin yang bisa akses
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    echo "Akses ditolak!";
    exit;
}

$message = "";

if (isset($_POST["reset"])) {
    $username = $_POST["username"];
    $password_baru = $_POST["password"];

    // hash password baru
    $hashed = password_hash($password_baru, PASSWORD_DEFAULT);

    // update ke database
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
    $stmt->bind_param("ss", $hashed, $username);

    if ($stmt->execute()) {
        $message = "Password berhasil direset!";
    } else {
        $message = "Gagal reset password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>

<h2>Reset Password User</h2>

<?php if($message): ?>
<p><?= $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password Baru" required><br><br>
    <button type="submit" name="reset">Reset Password</button>
</form>

</body>
</html>