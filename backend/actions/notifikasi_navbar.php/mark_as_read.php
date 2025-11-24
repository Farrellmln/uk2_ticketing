<?php
include '../../app.php';

// pastikan error bisa kelihatan kalau ada masalah
error_reporting(E_ALL);
ini_set('display_errors', 1);

// cek koneksi
if (!$connect) {
    die('Koneksi database gagal');
}

// ubah status laporan baru (0) jadi 1 (dibaca)
$query = "UPDATE pengaduan SET status = 1 WHERE status = '0'";
$result = mysqli_query($connect, $query);

if ($result) {
    echo 'success';
} else {
    echo 'error: ' . mysqli_error($connect);
}
?>
