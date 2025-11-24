<?php
include '../../app.php';

if (!isset($_GET['id'])) {
    echo "
        <script>
            alert('ID promo tidak ditemukan!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
    exit;
}

$id = intval($_GET['id']);

// Ambil data promo dulu (untuk hapus gambar)
$q = mysqli_query($connect, "SELECT gambar FROM promo WHERE id_promo = $id");

if (mysqli_num_rows($q) == 0) {
    echo "
        <script>
            alert('Data promo tidak ditemukan!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
    exit;
}

$data = mysqli_fetch_assoc($q);
$gambar = $data['gambar'];

// Hapus database
$delete = mysqli_query($connect, "DELETE FROM promo WHERE id_promo = $id");

if ($delete) {

    // Hapus gambar jika ada
    if (!empty($gambar)) {
        $path = "../../../storages/promo/" . $gambar;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    echo "
        <script>
            alert('Promo berhasil dihapus!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Terjadi kesalahan saat menghapus promo!');
            window.location.href = '../../pages/promo/index.php';
        </script>
    ";
}
?>
