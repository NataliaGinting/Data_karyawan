<?php
include "koneksi.php";

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_karyawan_hrd.xls");
header("Pragma: no-cache");
header("Expires: 0");

// =====================
// FILTER (SINKRON DENGAN LAPORAN)
// =====================
$tgl_awal      = $_GET['tgl_awal'] ?? '';
$tgl_akhir     = $_GET['tgl_akhir'] ?? '';
$status_filter = $_GET['status_verifikasi'] ?? '';
$divisi_filter = $_GET['divisi'] ?? '';

$where = "WHERE 1=1";

// FILTER TANGGAL MASUK
if(!empty($tgl_awal)){
    $where .= " AND DATE(k.tgl_masuk) >= '$tgl_awal'";
}

if(!empty($tgl_akhir)){
    $where .= " AND DATE(k.tgl_masuk) <= '$tgl_akhir'";
}

// FILTER VERIFIKASI
if(!empty($status_filter)){
    $status_filter = mysqli_real_escape_string($conn, $status_filter);
    $where .= " AND k.status_verifikasi = '$status_filter'";
}

// FILTER DIVISI
if(!empty($divisi_filter)){
    $divisi_filter = mysqli_real_escape_string($conn, $divisi_filter);
    $where .= " AND k.divisi = '$divisi_filter'";
}

// =====================
// QUERY
// =====================
$sql = "SELECT 
    k.id,
    k.kode_pekerja,
    k.nama,
    k.nik,
    k.jenis_kelamin,
    k.tempat_lahir,
    k.tanggal_lahir,
    k.usia,
    k.agama,
    k.pendidikan,
    k.no_telepon,
    k.alamat,
    k.unit_kerja,
    k.divisi,
    k.jabatan,
    k.status_karyawan,
    k.status_verifikasi,
    k.tgl_masuk,
    k.tanggal_input
FROM karyawan k
$where
ORDER BY k.id ASC";

$query = mysqli_query($conn, $sql);

if (!$query) {
    die("Query error: " . mysqli_error($conn));
}
?>

<style>
table {
    border-collapse: collapse;
    width: 100%;
    font-family: Arial;
    font-size: 12px;
}

th {
    background: #4F81BD;
    color: white;
    border: 1px solid #000;
    padding: 8px;
    text-align: center;
}

td {
    border: 1px solid #000;
    padding: 6px;
    vertical-align: top;
    word-wrap: break-word;
    white-space: normal;
}

.nik {
    mso-number-format:"\@";
}

.alamat {
    width: 250px;
}
</style>

<table>

<tr>
    <th>ID</th>
    <th>KODE PEKERJA</th>
    <th>NAMA</th>
    <th>NIK</th>
    <th>JK</th>
    <th>TEMPAT LAHIR</th>
    <th>TANGGAL LAHIR</th>
    <th>USIA</th>
    <th>AGAMA</th>
    <th>PENDIDIKAN</th>
    <th>NO TELP</th>
    <th>ALAMAT</th>
    <th>UNIT KERJA</th>
    <th>DIVISI</th>
    <th>JABATAN</th>
    <th>STATUS</th>
    <th>VERIFIKASI</th>
    <th>TGL MASUK</th>
    <th>TGL INPUT</th>
    <th>AYAH</th>
    <th>IBU</th>
</tr>

<?php
while ($row = mysqli_fetch_assoc($query)) {

    $id = $row['id'];

    // AYAH
    $q1 = mysqli_query($conn,"SELECT nama_keluarga FROM keluarga_karyawan WHERE id_karyawan='$id' AND hubungan='ayah' LIMIT 1");
    $d1 = mysqli_fetch_assoc($q1);
    $ayah = $d1['nama_keluarga'] ?? '-';

    // IBU
    $q2 = mysqli_query($conn,"SELECT nama_keluarga FROM keluarga_karyawan WHERE id_karyawan='$id' AND hubungan='ibu' LIMIT 1");
    $d2 = mysqli_fetch_assoc($q2);
    $ibu = $d2['nama_keluarga'] ?? '-';
?>

<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['kode_pekerja']; ?></td>
    <td><?= $row['nama']; ?></td>

    <td class="nik"><?= $row['nik']; ?></td>

    <td><?= $row['jenis_kelamin']; ?></td>
    <td><?= $row['tempat_lahir']; ?></td>
    <td><?= $row['tanggal_lahir']; ?></td>
    <td><?= $row['usia']; ?></td>
    <td><?= $row['agama']; ?></td>
    <td><?= $row['pendidikan']; ?></td>
    <td><?= $row['no_telepon']; ?></td>

    <td class="alamat"><?= $row['alamat']; ?></td>

    <td><?= $row['unit_kerja']; ?></td>
    <td><?= $row['divisi']; ?></td>
    <td><?= $row['jabatan']; ?></td>
    <td><?= $row['status_karyawan']; ?></td>
    <td><?= $row['status_verifikasi']; ?></td>
    <td><?= $row['tgl_masuk']; ?></td>
    <td><?= $row['tanggal_input']; ?></td>

    <td><?= $ayah; ?></td>
    <td><?= $ibu; ?></td>
</tr>

<?php } ?>

</table>