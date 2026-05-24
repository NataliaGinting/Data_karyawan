<?php
session_start();
header('Content-Type: application/json');

include 'koneksi.php';

// ======================
// CEK LOGIN
// ======================
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Anda belum login'
    ]);
    exit;
}

// ======================
// CEK ROLE (HARUS KONSISTEN)
// ======================
$role = strtolower($_SESSION['role'] ?? '');

if ($role !== 'manajer') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak. Hanya manajer yang boleh verifikasi.'
    ]);
    exit;
}

// ======================
// AMBIL DATA POST
// ======================
$id_karyawan = $_POST['id_karyawan'] ?? '';
$status       = $_POST['status'] ?? '';

// ======================
// VALIDASI INPUT
// ======================
if (empty($id_karyawan) || empty($status)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

// ======================
// VALIDASI STATUS
// ======================
$allowed_status = ['disetujui', 'ditolak'];

if (!in_array($status, $allowed_status)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Status tidak valid'
    ]);
    exit;
}

// ======================
// CEK KONEKSI
// ======================
if (!$conn) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal'
    ]);
    exit;
}

// ======================
// UPDATE DATA (FIX: kolom id = BUKAN id_karyawan)
// ======================
$stmt = $conn->prepare("
    UPDATE karyawan 
    SET status_verifikasi = ? 
    WHERE id = ?
");

$stmt->bind_param("si", $status, $id_karyawan);

// ======================
// EKSEKUSI
// ======================
if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil diverifikasi'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak ditemukan atau tidak ada perubahan'
        ]);
    }

} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal update data'
    ]);
}

// ======================
// CLOSE
// ======================
$stmt->close();
$conn->close();
?>