<?php
include '../../app.php';
session_start();

// Ambil ID dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../../pages/registrasi_petugas/index.php';</script>";
    exit;
}

// Cek apakah user ada
$cek = mysqli_query($connect, "SELECT * FROM user WHERE id_user='$id'");

if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Data user tidak ditemukan!'); window.location.href='../../pages/registrasi_petugas/index.php';</script>";
    exit;
}

// Hapus user
$del = mysqli_query($connect, "DELETE FROM user WHERE id_user='$id'");

if ($del) {
    echo "<script>alert('User berhasil dihapus!'); window.location.href='../../pages/registrasi_petugas/index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus user!'); window.location.href='../../pages/registrasi_petugas/index.php';</script>";
}
?>
