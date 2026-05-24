<?php
include "koneksi.php";

// data user
$username = "admin";
$password_plain = "admin123";
$role = "admin";

// hash password
$password = password_hash($password_plain, PASSWORD_DEFAULT);

// simpan ke database
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    echo "User berhasil dibuat";
} else {
    echo "Gagal: " . $stmt->error;
}
?>