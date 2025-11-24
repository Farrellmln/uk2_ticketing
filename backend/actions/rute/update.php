<?php
include '../../app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_rute = $_POST['id_rute'];
  $id_transportasi = $_POST['id_transportasi'];
  $asal = $_POST['asal'];
  $tujuan = $_POST['tujuan'];
  $harga = $_POST['harga'];
  $jadwal_berangkat = $_POST['jadwal_berangkat'];
  $jadwal_tiba = $_POST['jadwal_tiba'];

  $query = "UPDATE rute SET 
              id_transportasi = '$id_transportasi',
              asal = '$asal',
              tujuan = '$tujuan',
              harga = '$harga',
              jadwal_berangkat = '$jadwal_berangkat',
              jadwal_tiba = '$jadwal_tiba'
            WHERE id_rute = '$id_rute'";

  $result = mysqli_query($connect, $query);

  if ($result) {
    echo "
      <script>
        alert('Data rute berhasil diperbarui!');
        window.location.href = '../../pages/rute/index.php';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Gagal memperbarui data rute!');
        window.history.back();
      </script>
    ";
  }
} else {
  echo "
    <script>
      alert('Akses tidak valid!');
      window.location.href = '../../pages/rute/index.php';
    </script>
  ";
}
?>
