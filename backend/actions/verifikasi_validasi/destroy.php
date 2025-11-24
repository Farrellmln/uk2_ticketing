<?php
include '../../app.php'; // koneksi ke database

// Pastikan parameter id dikirim
if (!isset($_GET['id'])) {
  echo "<script>alert('ID tidak ditemukan!'); window.location.href='../../pages/verifikasi_validasi/index.php';</script>";
  exit;
}

$id = $_GET['id'];

// Ambil data dulu buat hapus file foto kalau ada
$query = mysqli_query($connect, "SELECT foto FROM pengaduan WHERE id_pengaduan = '$id'");
$data = mysqli_fetch_assoc($query);

if ($data) {
  // Hapus foto dari folder jika ada
  if (!empty($data['foto'])) {
    $filePath = "../../../storages/laporan_pengaduan/" . $data['foto'];
    if (file_exists($filePath)) {
      unlink($filePath);
    }
  }

  // Hapus data dari database
  $delete = mysqli_query($connect, "DELETE FROM pengaduan WHERE id_pengaduan = '$id'");

  if ($delete) {
    echo "<script>alert('Laporan berhasil dihapus!'); window.location.href='../../pages/verifikasi_validasi/index.php';</script>";
  } else {
    echo "<script>alert('Gagal menghapus laporan!'); window.location.href='../../pages/verifikasi_validasi/index.php';</script>";
  }
} else {
  echo "<script>alert('Data tidak ditemukan!'); window.location.href='../../pages/verifikasi_validasi/index.php';</script>";
}
?>
