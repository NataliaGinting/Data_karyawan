<?php
session_start();
include "koneksi.php";

$message = "";

// PROSES LOGIN
if (isset($_POST["login"])) {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // VALIDASI INPUT
    if (empty($username) || empty($password)) {

        $message = "Username dan Password wajib diisi!";

    } else {

        // CEK USERNAME
        $stmt = $conn->prepare("
            SELECT * FROM users 
            WHERE username=?
        ");

        // JIKA QUERY ERROR
        if (!$stmt) {

            die("Query Error: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        // USER DITEMUKAN
        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            // CEK PASSWORD
            if (password_verify($password, $user["password"])) {

                // SESSION
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"]     = $user["role"];

                // REDIRECT
                header("Location: dashboard.php");
                exit;

            } else {

                $message = "Username atau Password salah!";
            }

        } else {

            $message = "Username atau Password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - BARAPALA HR</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#2E8B57,#66BB6A);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

/* CONTAINER */

.form-container{
    width:380px;
    background:#fff;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    text-align:center;
    animation:fadeIn 0.5s ease;
}

/* ANIMASI */

@keyframes fadeIn{

    from{
        opacity:0;
        transform:translateY(20px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* LOGO */

.logo{
    margin-bottom:15px;
}

.logo img{
    width:220px;
    max-width:100%;
    object-fit:contain;
}

/* TITLE */

h2{
    color:#2E8B57;
    margin-bottom:25px;
    font-size:30px;
}

/* MESSAGE */

.message{
    background:#f8d7da;
    color:#721c24;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    font-size:14px;
    font-weight:bold;
}

/* INPUT */

.input-group{
    margin-bottom:18px;
}

.input-group input{
    width:100%;
    padding:13px;
    border:1px solid #ccc;
    border-radius:10px;
    outline:none;
    font-size:15px;
    transition:0.3s;
}

.input-group input:focus{
    border-color:#2E8B57;
    box-shadow:0 0 5px rgba(46,139,87,0.3);
}

/* PASSWORD */

.password-box{
    position:relative;
}

.password-box input{
    padding-right:45px;
}

.toggle{
    position:absolute;
    top:50%;
    right:15px;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
}

/* BUTTON */

button{
    width:100%;
    padding:13px;
    border:none;
    border-radius:10px;
    background:#2E8B57;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#3CB371;
    transform:scale(1.02);
}

/* FOOTER */

.footer{
    margin-top:18px;
}

.footer a{
    text-decoration:none;
    color:#2E8B57;
    font-size:14px;
    font-weight:bold;
}

.footer a:hover{
    text-decoration:underline;
}

/* RESPONSIVE */

@media(max-width:450px){

    .form-container{
        width:100%;
        padding:25px;
    }

    .logo img{
        width:180px;
    }
}

</style>

</head>

<body>

<div class="form-container">

    <!-- LOGO -->
    <div class="logo">

        <img 
        src="uploads/assets/logo.png"
        alt="Logo BARAPALA HR">

    </div>

    <!-- TITLE -->
    <h2>Login</h2>

    <!-- MESSAGE -->
    <?php if($message): ?>

        <div class="message">
            <?= htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <!-- FORM LOGIN -->
    <form method="POST">

        <!-- USERNAME -->
        <div class="input-group">

            <input
            type="text"
            name="username"
            placeholder="Masukkan Username"
            required>

        </div>

        <!-- PASSWORD -->
        <div class="input-group password-box">

            <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan Password"
            required>

            <span class="toggle" onclick="togglePassword()">
                👁
            </span>

        </div>

        <!-- BUTTON -->
        <button type="submit" name="login">
            Login
        </button>

    </form>

    <!-- FOOTER -->
    <div class="footer">

        <a href="lupa_password.php">
            Lupa Password?
        </a>

    </div>

</div>

<script>

function togglePassword(){

    var password = document.getElementById("password");

    if(password.type === "password"){

        password.type = "text";

    }else{

        password.type = "password";
    }
}

</script>

</body>
</html>