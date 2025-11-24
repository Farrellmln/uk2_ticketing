<?php
$host = "localhost";
$username = "root";
$password = "";
$dbName = "db_ticketing_transport";

// Membuat koneksi ke database
$connect = mysqli_connect($host, $username, $password, $dbName);

// Cek apakah koneksi berhasil
if (!$connect) {
    echo "Database gagal tersambung";
    exit;
}

// 🕒 Atur zona waktu PHP ke WIB (Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

// 🕒 Atur zona waktu MySQL juga ke WIB
mysqli_query($connect, "SET time_zone = '+07:00'");
?>
