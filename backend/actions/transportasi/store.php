<?php
include '../../app.php';

$jenis = $_POST['jenis'];
$nama_transportasi = $_POST['nama_transportasi'];
$kode = $_POST['kode'];
$kapasitas = $_POST['kapasitas'];

// Cek kode duplikat
$cek = mysqli_query($connect, "SELECT * FROM transportasi WHERE kode = '$kode'");
if (mysqli_num_rows($cek) > 0) {
  echo "
    <script>
      alert('Kode transportasi sudah terdaftar di data lain!');
      window.history.back();
    </script>
  ";
  exit;
}

// Simpan data baru
$q = "INSERT INTO transportasi (jenis, nama_transportasi, kode, kapasitas) 
      VALUES ('$jenis', '$nama_transportasi', '$kode', '$kapasitas')";

if (mysqli_query($connect, $q)) {
  echo "
    <script>
      alert('Data transportasi berhasil ditambahkan!');
      window.location.href='../../pages/transportasi/index.php';
    </script>
  ";
} else {
  echo "
    <script>
      alert('Gagal menambahkan data transportasi!');
      window.history.back();
    </script>
  ";
}
?>
