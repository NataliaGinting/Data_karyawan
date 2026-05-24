<?php
include "koneksi.php";

$message = "";
$type = "";

if (isset($_POST["reset"])) {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm  = trim($_POST["confirm"]);

    // cek password
    if ($password !== $confirm) {

        $message = "Konfirmasi password tidak cocok!";
        $type = "red";

    } else {

        // cek username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            // hash password baru
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // update password
            $update = $conn->prepare(
                "UPDATE users SET password=? WHERE username=?"
            );

            $update->bind_param("ss", $hash, $username);
            $update->execute();

            $message = "Password berhasil direset!";
            $type = "green";

        } else {

            $message = "Username tidak ditemukan!";
            $type = "red";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Lupa Password</title>

<style>

body{
    font-family:Segoe UI;
    background:#2e7d32;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    width:350px;
    padding:30px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.2);
}

h3{
    text-align:center;
    margin-bottom:20px;
    color:#2e7d32;
}

input{
    width:100%;
    padding:12px;
    margin-top:12px;
    border:1px solid #ccc;
    border-radius:8px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    background:#2e7d32;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
}

button:hover{
    background:#256428;
}

.message{
    margin-bottom:15px;
    text-align:center;
    font-weight:bold;
}

a{
    display:block;
    margin-top:15px;
    text-align:center;
    text-decoration:none;
    color:#2e7d32;
}

</style>

</head>

<body>

<div class="box">

<h3>Lupa Password</h3>

<?php if($message): ?>
<div class="message" style="color:<?= $type ?>;">
    <?= $message ?>
</div>
<?php endif; ?>

<form method="POST">

<input type="text"
name="username"
placeholder="Masukkan Username"
required>

<input type="password"
name="password"
placeholder="Password Baru"
required>

<input type="password"
name="confirm"
placeholder="Konfirmasi Password"
required>

<button type="submit" name="reset">
Reset Password
</button>

</form>

<a href="login.php">
Kembali ke Login
</a>

</div>

</body>
</html>