<?php
include '../../app.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "DELETE FROM rute WHERE id_rute = '$id'";
  $result = mysqli_query($connect, $query);

  if ($result) {
    echo "
      <script>
        alert('Data rute berhasil dihapus!');
        window.location.href = '../../pages/rute/index.php';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Gagal menghapus data rute!');
        window.history.back();
      </script>
    ";
  }
} else {
  echo "
    <script>
      alert('ID rute tidak ditemukan!');
      window.location.href = '../../pages/rute/index.php';
    </script>
  ";
}
?>
