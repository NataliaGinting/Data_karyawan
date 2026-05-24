<?php
session_start();
include 'koneksi.php';

// ==== LOAD FPDF ====
if (file_exists('fpdf/fpdf.php')) {
    require 'fpdf/fpdf.php';
} elseif (file_exists('fpdf184/fpdf.php')) {
    require 'fpdf184/fpdf.php';
} else {
    die("FPDF tidak ditemukan! Pastikan folder fpdf atau fpdf184 ada.");
}

// ===== QUERY DATA =====
$sql = "SELECT * FROM karyawan ORDER BY nama ASC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// ===== MULAI PDF =====
$pdf = new FPDF("P", "mm", "A4");
$pdf->SetAutoPageBreak(true, 20); // page break otomatis

while ($r = mysqli_fetch_assoc($result)) {
    $pdf->AddPage();

    // Judul halaman
    $pdf->SetFont("Arial", "B", 16);
    $pdf->Cell(0, 10, "BIODATA KARYAWAN", 0, 1, "C");
    $pdf->Ln(5);

    $pdf->SetFont("Arial", "", 12);
    $lineHeight = 8;
    $labelWidth = 60;

    // Data dasar
    $fields = [
        "Nama" => $r['nama'] ?? '-',
        "Alamat" => $r['alamat'] ?? '-',
        "Tempat / Tanggal Lahir" => ($r['tempat_lahir'] ?? '-') . " / " . ($r['tanggal_lahir'] ?? '-'),
        "Usia" => $r['usia'] ?? '-',
        "Agama" => $r['agama'] ?? '-',
        "Pendidikan" => $r['pendidikan'] ?? '-',
        "No HP" => $r['no_telepon'] ?? '-',
        "NIK" => $r['nik'] ?? '-',
        "Status" => $r['status'] ?? '-'
    ];

    foreach ($fields as $label => $value) {
        $pdf->Cell($labelWidth, $lineHeight, $label);
        $pdf->Cell(5, $lineHeight, ":");
        $pdf->MultiCell(0, $lineHeight, $value);
    }

    $pdf->Ln(3);

    // ===== TABEL KELUARGA / TANGGUNGAN =====
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(0, 8, "Keluarga / Tanggungan", 0, 1);
    $pdf->SetFont("Arial", "B", 11);

    // Header tabel
    $pdf->Cell(60, 8, "Nama", 1, 0, "C");
    $pdf->Cell(60, 8, "Hubungan", 1, 0, "C");
    $pdf->Cell(60, 8, "NIK", 1, 1, "C");

    // Data keluarga
    $pdf->SetFont("Arial", "", 11);
    $id_karyawan = $r['id'] ?? 0;

    // Cek apakah tabel 'keluarga' ada
    $keluarga_table_check = mysqli_query($conn, "SHOW TABLES LIKE 'keluarga'");
    if($keluarga_table_check && mysqli_num_rows($keluarga_table_check) > 0){
        $kel_sql = "SELECT nama, hubungan, nik FROM keluarga WHERE id_karyawan='$id_karyawan'";
        $kel_res = mysqli_query($conn, $kel_sql);
        if ($kel_res && mysqli_num_rows($kel_res) > 0) {
            while ($k = mysqli_fetch_assoc($kel_res)) {
                $pdf->Cell(60, 7, $k['nama'] ?? '-', 1);
                $pdf->Cell(60, 7, $k['hubungan'] ?? '-', 1);
                $pdf->Cell(60, 7, $k['nik'] ?? '-', 1);
                $pdf->Ln();
            }
        } else {
            $pdf->Cell(60, 7, "-", 1, 0, "C");
            $pdf->Cell(60, 7, "-", 1, 0, "C");
            $pdf->Cell(60, 7, "-", 1, 1, "C");
        }
    } else {
        // Jika tabel keluarga tidak ada
        $pdf->Cell(180, 7, "Tabel keluarga belum dibuat", 1, 1, "C");
    }

    $pdf->Ln(3);

    // ===== Lanjut Data Karyawan =====
    $fields2 = [
        "Unit Kerja" => $r['unit_kerja'] ?? '-',
        "Divisi" => $r['divisi'] ?? '-',
        "Jabatan" => $r['jabatan'] ?? '-',
        "Tanggal Masuk" => $r['tgl_masuk'] ?? '-',
        "No BPJS" => $r['no_bpjs'] ?? '-',
        "No Rekening" => $r['no_rekening'] ?? '-',
        "Status Karyawan" => $r['stats_karyawan'] ?? '-',
        "No ID Finger" => $r['no_id_finger'] ?? '-'
    ];

    foreach ($fields2 as $label => $value) {
        $pdf->Cell($labelWidth, $lineHeight, $label);
        $pdf->Cell(5, $lineHeight, ":");
        $pdf->MultiCell(0, $lineHeight, $value);
    }
}

$pdf->Output("Biodata_Karyawan_Full_Keluarga.pdf", "I");
exit;
?>
