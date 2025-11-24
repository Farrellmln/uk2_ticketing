<?php
include '../../app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "
        <script>
            alert('Akses tidak valid!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
    exit;
}

$id_promo        = $_POST['id_promo'];
$nama_promo      = mysqli_real_escape_string($connect, $_POST['nama_promo']);
$potongan        = intval($_POST['potongan']);
$tanggal_mulai   = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];
$status          = $_POST['status'];

// Ambil data promo lama
$q = mysqli_query($connect, "SELECT * FROM promo WHERE id_promo = '$id_promo'");
$old = mysqli_fetch_assoc($q);

if (!$old) {
    echo "
        <script>
            alert('Promo tidak ditemukan!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
    exit;
}

// ======================= HANDLE GAMBAR =======================
$folder = "../../../storages/promo/";
$nama_file = $old['gambar']; // default tetap gambar lama

// Jika upload gambar baru
if (!empty($_FILES['gambar']['name'])) {

    // Hapus gambar lama jika ada
    if (!empty($old['gambar']) && file_exists($folder . $old['gambar'])) {
        unlink($folder . $old['gambar']);
    }

    // Upload gambar baru
    $ext  = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_file_baru = "promo_" . time() . "_" . rand(100,999) . "." . $ext;

    move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $nama_file_baru);

    $nama_file = $nama_file_baru;
}

// ======================= UPDATE PROMO =======================
$query = "
    UPDATE promo SET 
        nama_promo      = '$nama_promo',
        potongan        = '$potongan',
        tanggal_mulai   = '$tanggal_mulai',
        tanggal_selesai = '$tanggal_selesai',
        status          = '$status',
        gambar          = '$nama_file'
    WHERE id_promo = '$id_promo'
";

$run = mysqli_query($connect, $query);

// ======================= RESPON =======================
if ($run) {
    echo "
        <script>
            alert('Promo berhasil diperbarui!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Gagal memperbarui promo: " . mysqli_error($connect) . "');
            window.history.back();
        </script>
    ";
}
?>
