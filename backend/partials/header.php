<?php
session_start();

// Cegah akses tanpa login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        window.location.href='../../../frontend/pages/auth/login.php';
    </script>";
    exit();
}

// Batasi hanya Admin & Petugas
if ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Petugas') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk Admin dan Petugas.');
        window.location.href='../../../frontend/pages/auth/login.php';
    </script>";
    exit();
}
?>
<!-- isi header backend kamu di bawah ini -->


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Goticket.com</title>
  <link rel="shortcut icon" type="image/png" href="../../../storages/navbar/g.png" />
  <link rel="stylesheet" href="../../template/src/assets/css/styles.min.css" />
</head>
<!-- Wajib untuk dropdown Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
