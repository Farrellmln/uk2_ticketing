<?php
include '../../app.php';

// Pastikan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "
      <script>
        alert('Akses tidak valid!');
        window.location.href='../../pages/promo/index.php';
      </script>
    ";
    exit;
}

$nama_promo      = mysqli_real_escape_string($connect, $_POST['nama_promo'] ?? '');
$keterangan      = mysqli_real_escape_string($connect, $_POST['keterangan'] ?? '');
$potongan        = mysqli_real_escape_string($connect, $_POST['potongan'] ?? '');
$tanggal_mulai   = mysqli_real_escape_string($connect, $_POST['tanggal_mulai'] ?? '');
$tanggal_selesai = mysqli_real_escape_string($connect, $_POST['tanggal_selesai'] ?? '');
$status          = mysqli_real_escape_string($connect, $_POST['status'] ?? 'nonaktif');

// Validasi wajib
if (
    empty($nama_promo) ||
    empty($keterangan) ||
    empty($potongan) ||
    empty($tanggal_mulai) ||
    empty($tanggal_selesai)
) {
    echo "
      <script>
        alert('Semua field wajib diisi!');
        window.history.back();
      </script>
    ";
    exit;
}

// ==========================
// UPLOAD GAMBAR
// ==========================
$uploadDir = '../../../storages/promo/';
$gambarName = '';

if (!empty($_FILES['gambar']['name'])) {

    $fileName = $_FILES['gambar']['name'];
    $tmpName  = $_FILES['gambar']['tmp_name'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo "
          <script>
            alert('Format gambar tidak valid! Gunakan JPG, PNG, atau WebP.');
            window.history.back();
          </script>
        ";
        exit;
    }

    // nama file unik
    $gambarName = time() . '_' . uniqid() . '.' . $ext;

    move_uploaded_file($tmpName, $uploadDir . $gambarName);
}

// ==========================
// INSERT
// ==========================
$q = "
  INSERT INTO promo (nama_promo, keterangan, potongan, gambar, tanggal_mulai, tanggal_selesai, status)
  VALUES ('$nama_promo', '$keterangan', '$potongan', '$gambarName', '$tanggal_mulai', '$tanggal_selesai', '$status')
";

$result = mysqli_query($connect, $q);

// ==========================
// RESPONSE
// ==========================
if ($result) {
    echo "
      <script>
        alert('Promo berhasil ditambahkan!');
        window.location.href='../../pages/promo/index.php';
      </script>
    ";
} else {
    $err = mysqli_error($connect);
    echo "
      <script>
        alert('Terjadi kesalahan saat menambahkan promo: $err');
        window.history.back();
      </script>
    ";
}
exit;
?>
