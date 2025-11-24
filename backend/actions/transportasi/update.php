<?php
include '../../app.php';

$id = $_POST['id_transportasi'];
$jenis = $_POST['jenis'];
$nama_transportasi = $_POST['nama_transportasi'];
$kode = $_POST['kode'];
$kapasitas = $_POST['kapasitas'];

// Cek kode duplikat, tapi jangan bandingkan dengan diri sendiri
$cek = mysqli_query($connect, "SELECT * FROM transportasi WHERE kode = '$kode' AND id_transportasi != '$id'");
if (mysqli_num_rows($cek) > 0) {
  echo "
    <script>
      alert('Kode transportasi sudah digunakan oleh data lain!');
      window.history.back();
    </script>
  ";
  exit;
}

// Update data
$q = "UPDATE transportasi 
      SET jenis = '$jenis', nama_transportasi = '$nama_transportasi', 
          kode = '$kode', kapasitas = '$kapasitas' 
      WHERE id_transportasi = '$id'";

if (mysqli_query($connect, $q)) {
  echo "
    <script>
      alert('Data transportasi berhasil diperbarui!');
      window.location.href='../../pages/transportasi/index.php';
    </script>
  ";
} else {
  echo "
    <script>
      alert('Gagal memperbarui data transportasi!');
      window.history.back();
    </script>
  ";
}
?>
