<?php
session_start();
include 'koneksi.php';

// =========================
// CEK LOGIN
// =========================

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'];

// =========================
// STATISTIK
// =========================

// TOTAL USER
$user_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total FROM users"
);

$total_user = mysqli_fetch_assoc($user_query)['total'];

// TOTAL KARYAWAN
$karyawan_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total FROM karyawan"
);

$total_karyawan = mysqli_fetch_assoc($karyawan_query)['total'];

// PENDING
$pending_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total 
     FROM karyawan 
     WHERE status_verifikasi='pending'"
);

$pending = mysqli_fetch_assoc($pending_query)['total'];

// APPROVED
$approved_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total 
     FROM karyawan 
     WHERE status_verifikasi='approved'"
);

$approved = mysqli_fetch_assoc($approved_query)['total'];

// REJECTED
$rejected_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total 
     FROM karyawan 
     WHERE status_verifikasi='rejected'"
);

$rejected = mysqli_fetch_assoc($rejected_query)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard - Barapala HR</title>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
}

/* =========================
SIDEBAR
========================= */

.sidebar{
    width:240px;
    background:#2e7d32;
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    overflow-y:auto;
    padding-top:20px;
}

.logo{
    text-align:center;
    margin-bottom:20px;
}

.logo h2{
    color:white;
    font-size:24px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:14px 20px;
    margin:8px 12px;
    color:white;
    text-decoration:none;
    border-radius:12px;
    transition:0.3s;
    font-size:15px;
}

.sidebar a:hover{
    background:#43a047;
}

/* =========================
HEADER
========================= */

.header{
    position:fixed;
    top:0;
    left:240px;
    right:0;
    height:70px;
    background:#2e7d32;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 25px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    z-index:100;
}

.header h2{
    font-size:24px;
}

/* =========================
MAIN
========================= */

.main{
    margin-left:240px;
    margin-top:70px;
    padding:30px;
}

/* =========================
WELCOME
========================= */

.welcome-box{
    background:linear-gradient(135deg,#2e7d32,#43a047);
    color:white;
    padding:35px;
    border-radius:20px;
    margin-bottom:30px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.welcome-box h1{
    font-size:32px;
    margin-bottom:10px;
}

.welcome-box p{
    opacity:0.95;
    line-height:1.6;
}

/* =========================
CARDS
========================= */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.card-top span{
    color:#666;
    font-size:16px;
}

.icon{
    font-size:35px;
}

.card h1{
    margin-top:20px;
    font-size:38px;
    color:#2e7d32;
}

/* =========================
BOX
========================= */

.box{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.box h3{
    margin-bottom:15px;
}

/* =========================
CHART
========================= */

.chart-container{
    width:100%;
    max-width:100%;
    overflow-x:auto;
}

/* =========================
RESPONSIVE
========================= */

@media screen and (max-width:768px){

    .sidebar{
        width:75px;
    }

    .logo h2{
        display:none;
    }

    .sidebar a{
        justify-content:center;
        padding:16px 10px;
        font-size:20px;
    }

    .sidebar a span{
        display:none;
    }

    .header{
        left:75px;
        height:auto;
        padding:15px;
        flex-direction:column;
        align-items:flex-start;
        gap:5px;
    }

    .header h2{
        font-size:20px;
    }

    .main{
        margin-left:75px;
        margin-top:90px;
        padding:15px;
    }

    .welcome-box{
        padding:20px;
    }

    .welcome-box h1{
        font-size:24px;
    }

    .cards{
        grid-template-columns:1fr;
    }

    .card h1{
        font-size:30px;
    }
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo">
        <h2>Barapala HR</h2>
    </div>

    <a href="dashboard.php">
        🏠 <span>Dashboard</span>
    </a>

    <?php if($role == 'admin'){ ?>

    <a href="user.php">
        👤 <span>Kelola User</span>
    </a>

    <a href="lihat_data.php">
        📋 <span>Data Karyawan</span>
    </a>

    <?php } ?>

    <?php if($role == 'personalia'){ ?>

    <a href="inputkaryawan.php">
        ➕ <span>Input Karyawan</span>
    </a>

    <a href="lihat_data.php">
        📋 <span>Data Karyawan</span>
    </a>

    <?php } ?>

    <?php if($role == 'manajer'){ ?>

    <a href="verifikasi.php">
        ✔ <span>Verifikasi</span>
    </a>

    <a href="lihat_data.php">
        📋 <span>Data Karyawan</span>
    </a>

    <?php } ?>

    <a href="laporan.php">
        📄 <span>Laporan</span>
    </a>

    <a href="logout.php">
        🚪 <span>Logout</span>
    </a>

</div>

<!-- HEADER -->
<div class="header">

    <h2>Dashboard</h2>

    <div>
        <?= $username ?> (<?= $role ?>)
    </div>

</div>

<!-- MAIN -->
<div class="main">

    <!-- WELCOME -->
    <div class="welcome-box">

        <h1>
            Selamat Datang, <?= $username ?> 👋
        </h1>

        <p>
            Kelola data karyawan dengan lebih mudah
            menggunakan sistem Barapala HR.
        </p>

    </div>

    <!-- CARDS -->
    <div class="cards">

        <?php if($role == 'admin'){ ?>

        <div class="card">

            <div class="card-top">
                <span>Total User</span>
                <div class="icon">👤</div>
            </div>

            <h1><?= $total_user ?></h1>

        </div>

        <?php } ?>

        <div class="card">

            <div class="card-top">
                <span>Total Karyawan</span>
                <div class="icon">👥</div>
            </div>

            <h1><?= $total_karyawan ?></h1>

        </div>

        <div class="card">

            <div class="card-top">
                <span>Pending</span>
                <div class="icon">⏳</div>
            </div>

            <h1><?= $pending ?></h1>

        </div>

        <div class="card">

            <div class="card-top">
                <span>Approved</span>
                <div class="icon">✅</div>
            </div>

            <h1><?= $approved ?></h1>

        </div>

        <div class="card">

            <div class="card-top">
                <span>Rejected</span>
                <div class="icon">❌</div>
            </div>

            <h1><?= $rejected ?></h1>

        </div>

    </div>

    <!-- CHART -->
    <div class="box">

        <h3>
            📊 Diagram Status Karyawan
        </h3>

        <div class="chart-container">

            <canvas id="myChart"></canvas>

        </div>

    </div>

    <!-- INFORMASI -->
    <div class="box">

        <h3>
            📌 Informasi Sistem
        </h3>

        <p>
            Barapala HR membantu pengelolaan data karyawan,
            verifikasi data, dan pembuatan laporan secara
            lebih modern dan efisien.
        </p>

    </div>

</div>

<!-- CHART SCRIPT -->
<script>

const ctx = document.getElementById('myChart');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [
            'Pending',
            'Approved',
            'Rejected'
        ],

        datasets: [{

            label: 'Jumlah Karyawan',

            data: [
                <?= $pending ?>,
                <?= $approved ?>,
                <?= $rejected ?>
            ],

            backgroundColor: [
                '#fbc02d',
                '#43a047',
                '#e53935'
            ],

            borderRadius: 10,
            borderWidth: 1

        }]
    },

    options: {

        responsive: true,

        plugins: {

            legend: {
                display: false
            }
        },

        scales: {

            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

</body>
</html>