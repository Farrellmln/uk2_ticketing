<?php
include '../../app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_transportasi = mysqli_real_escape_string($connect, $_POST['id_transportasi']);
  $asal             = mysqli_real_escape_string($connect, $_POST['asal']);
  $tujuan           = mysqli_real_escape_string($connect, $_POST['tujuan']);
  $harga            = mysqli_real_escape_string($connect, $_POST['harga']);
  $jadwal_berangkat = mysqli_real_escape_string($connect, $_POST['jadwal_berangkat']);
  $jadwal_tiba      = mysqli_real_escape_string($connect, $_POST['jadwal_tiba']);

  // Validasi sederhana
  if (empty($id_transportasi) || empty($asal) || empty($tujuan) || empty($harga) || empty($jadwal_berangkat) || empty($jadwal_tiba)) {
    echo "
      <script>
        alert('Semua field wajib diisi!');
        window.history.back();
      </script>
    ";
    exit;
  }

  $query = "INSERT INTO rute (id_transportasi, asal, tujuan, harga, jadwal_berangkat, jadwal_tiba)
            VALUES ('$id_transportasi', '$asal', '$tujuan', '$harga', '$jadwal_berangkat', '$jadwal_tiba')";

  $result = mysqli_query($connect, $query);

  if ($result) {
    echo "
      <script>
        alert('Rute berhasil ditambahkan!');
        window.location.href = '../../pages/rute/index.php';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Terjadi kesalahan saat menambahkan rute: " . mysqli_error($connect) . "');
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
