<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Input Data Karyawan - Barapala HR</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* =========================
BODY
========================= */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
}

/* =========================
SIDEBAR
========================= */
.sidebar{
    width:230px;
    height:100vh;
    background:#2e7d32;
    position:fixed;
    left:0;
    top:0;
    padding-top:20px;
    overflow-y:auto;
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

/* =========================
HEADER
========================= */
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
    z-index:100;
}

/* =========================
MAIN
========================= */
.main{
    margin-left:230px;
    margin-top:70px;
    padding:30px;
}

/* =========================
BOX
========================= */
.box{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    max-width:1000px;
    margin:auto;
}

/* =========================
TITLE
========================= */
.box h2{
    text-align:center;
    color:#2e7d32;
    margin-bottom:25px;
}

.box h3{
    margin-top:35px;
    color:#1b5e20;
    border-left:5px solid #2e7d32;
    padding-left:10px;
}

/* =========================
FORM
========================= */
label{
    font-weight:600;
    display:block;
    margin-top:15px;
    color:#333;
}

input,
select,
textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    border:1px solid #ccc;
    border-radius:8px;
    box-sizing:border-box;
    font-size:14px;
}

input[readonly]{
    background:#eeeeee;
}

/* =========================
TABLE
========================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

table th,
table td{
    border:1px solid #ccc;
    padding:8px;
}

/* =========================
BUTTON
========================= */
button{
    background:#2e7d32;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    margin-top:30px;
    font-size:15px;
    font-weight:bold;
}

button:hover{
    background:#1b5e20;
}

/* =========================
RESPONSIVE
========================= */
@media(max-width:768px){

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .header{
        left:0;
    }

    .main{
        margin-left:0;
    }

}

</style>

</head>

<body>

<!-- =========================
SIDEBAR
========================= -->

<div class="sidebar">

<a href="dashboard.php">
🏠 Dashboard
</a>

<a href="inputkaryawan.php">
➕ Input Karyawan
</a>

<a href="lihat_data.php">
📋 Data Karyawan
</a>

<a href="laporan.php">
📄 Laporan
</a>

<a href="logout.php">
🚪 Logout
</a>

</div>

<!-- =========================
HEADER
========================= -->

<div class="header">

<div>
<h2>Barapala HR</h2>
</div>

<div>
<?= htmlspecialchars($username); ?>(<?= strtoupper(trim($_SESSION['role'] ?? '')) ?>)
</div>

</div>

<!-- =========================
MAIN
========================= -->

<div class="main">

<div class="box">

<h2>Input Data Karyawan</h2>

<form action="proses_input.php"
method="POST"
enctype="multipart/form-data">

<!-- =========================
DATA PRIBADI
========================= -->

<h3>Data Pribadi</h3>

<label>Nama Lengkap *</label>
<input type="text"
name="nama"
required>

<label>Jenis Kelamin *</label>
<select name="jenis_kelamin" required>

<option value="">
-- Pilih Jenis Kelamin --
</option>

<option value="Laki-laki">
Laki-laki
</option>

<option value="Perempuan">
Perempuan
</option>

</select>

<label>Tempat Lahir *</label>
<input type="text"
name="tempat_lahir"
required>

<label>Tanggal Lahir *</label>
<input type="date"
name="tanggal_lahir"
id="tanggal_lahir"
required
onchange="hitungUsia()">

<label>Usia</label>
<input type="number"
name="usia"
id="usia"
readonly>

<label>Agama</label>
<select name="agama">

<option value="">
-- Pilih Agama --
</option>

<option>Islam</option>
<option>Kristen</option>
<option>Katolik</option>
<option>Hindu</option>
<option>Buddha</option>
<option>Konghucu</option>

</select>

<label>Pendidikan Terakhir</label>
<select name="pendidikan">

<option value="">
-- Pilih Pendidikan --
</option>

<option>SD</option>
<option>SMP</option>
<option>SMA/SMK</option>
<option>D3</option>
<option>S1</option>

</select>

<label>Alamat *</label>
<textarea name="alamat"
rows="3"
required></textarea>

<label>No Telepon / HP *</label>
<input type="text"
name="no_telepon"
required>

<label>No KTP / NIK *</label>
<input type="text"
name="nik"
maxlength="16"
required>

<label>Status Pernikahan *</label>
<select name="status_pernikahan"
required>

<option value="">
-- Pilih Status --
</option>

<option value="Belum Menikah">
Belum Menikah
</option>

<option value="Menikah">
Menikah
</option>

</select>

<!-- =========================
DATA KELUARGA
========================= -->

<h3>Data Keluarga</h3>

<table>

<tr>
<th>Nama Keluarga</th>
<th>Hubungan</th>
<th>NIK</th>
</tr>

<tr>

<td>
<input type="text"
name="nama_keluarga[]">
</td>

<td>
<input type="text"
name="hubungan[]">
</td>

<td>
<input type="text"
name="nik_keluarga[]">
</td>

</tr>

<tr>

<td>
<input type="text"
name="nama_keluarga[]">
</td>

<td>
<input type="text"
name="hubungan[]">
</td>

<td>
<input type="text"
name="nik_keluarga[]">
</td>

</tr>

<tr>

<td>
<input type="text"
name="nama_keluarga[]">
</td>

<td>
<input type="text"
name="hubungan[]">
</td>

<td>
<input type="text"
name="nik_keluarga[]">
</td>

</tr>

</table>

<!-- =========================
DATA PEKERJAAN
========================= -->

<h3>Data Pekerjaan</h3>

<label>Status Karyawan *</label>
<select name="status_karyawan"
required>

<option value="">
-- Pilih Status --
</option>

<option value="BHL">
BHL
</option>

<option value="Karyawan Tetap">
Karyawan Tetap
</option>

</select>

<label>Unit Kerja</label>
<input type="text"
name="unit_kerja">

<label>Divisi *</label>
<select name="divisi"
id="divisi"
required
onchange="updateJabatan()">

<option value="">
-- Pilih Divisi --
</option>

<option value="KANTOR">
KANTOR
</option>

<option value="TRAKSI">
TRAKSI
</option>

<option value="DIVISI I">
DIVISI I
</option>

<option value="DIVISI II">
DIVISI II
</option>

<option value="DIVISI III">
DIVISI III
</option>

<option value="DIVISI IV">
DIVISI IV
</option>

<option value="DIVISI V">
DIVISI V
</option>

</select>

<label>Jabatan *</label>
<select name="jabatan"
id="jabatan"
required>

<option value="">
-- Pilih Jabatan --
</option>

</select>

<label>TMK</label>
<input type="date"
name="tmk">

<label>Tanggal Masuk</label>
<input type="date"
name="tgl_masuk">

<label>No BPJS</label>
<input type="text"
name="no_bpjs">

<label>No Rekening</label>
<input type="text"
name="no_rekening">

<!-- =========================
DOKUMEN
========================= -->

<h3>Dokumen</h3>

<label>Upload KTP</label>
<input type="file"
name="foto_ktp"
accept=".jpg,.jpeg,.png,.pdf">

<label>Upload KK</label>
<input type="file"
name="foto_kk"
accept=".jpg,.jpeg,.png,.pdf">

<input type="hidden"
name="status_verifikasi"
value="pending">

<button type="submit"
name="submit">

<i class="fa fa-save"></i>
Simpan Data

</button>

</form>

</div>

</div>

<script>

function hitungUsia(){

    const tanggalLahir =
    document.getElementById('tanggal_lahir').value;

    if(tanggalLahir){

        const today = new Date();

        const birthDate =
        new Date(tanggalLahir);

        let usia =
        today.getFullYear() -
        birthDate.getFullYear();

        const bulan =
        today.getMonth() -
        birthDate.getMonth();

        if(
            bulan < 0 ||
            (
                bulan === 0 &&
                today.getDate() <
                birthDate.getDate()
            )
        ){
            usia--;
        }

        document.getElementById('usia').value =
        usia;

    }

}

function updateJabatan(){

    const divisi =
    document.getElementById('divisi').value;

    const jabatanSelect =
    document.getElementById('jabatan');

    jabatanSelect.innerHTML =
    '<option value="">-- Pilih Jabatan --</option>';

    let jabatanList = [];

    if(divisi == "KANTOR"){

        jabatanList = [

            "Kr. Tanaman",
            "Kr. Personalia",
            "Kr. Timbangan",
            "Ka. Gudang",
            "Kr. Gudang",
            "Pembantu Staff",
            "Guru SD",
            "Takmir Mesjid",
            "Jaga Batas Areal", 
            " Seraf Pembantu Staff"

        ];

    }

    else if(divisi == "TRAKSI"){

        jabatanList = [

            "Mandor Traksi",
            "Mekanik",
            "OP Excavator",
            "OP Dozer"

        ];

    }

    else{

        jabatanList = [

            "Mandor Panen",
            "Mandor Perawatan",
            "KCS",
            "Pemanen",
            "Perawatan",
            "Pemuat TBS"

        ];

    }

    jabatanList.forEach(function(j){

        const option =
        document.createElement('option');

        option.value = j;
        option.textContent = j;

        jabatanSelect.appendChild(option);

    });

}

</script>

</body>
</html>