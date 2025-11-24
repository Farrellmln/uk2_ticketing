<?php
session_start();
include '../../app.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
            alert('Anda harus login untuk membatalkan pesanan!');
            window.location.href='../../pages/auth/login.php';
          </script>";
    exit;
}

$id_pemesanan = isset($_GET['id_pemesanan']) ? intval($_GET['id_pemesanan']) : 0;
$id_user      = $_SESSION['id_user'];

// Cek apakah pesanan milik user & valid
$q = "SELECT * FROM pemesanan 
      WHERE id_pemesanan = $id_pemesanan 
        AND id_user = $id_user
      LIMIT 1";

$res  = mysqli_query($connect, $q);
$data = mysqli_fetch_assoc($res);

if (!$data) {
    echo "<script>
            alert('Pesanan tidak ditemukan atau bukan milik Anda!');
            window.location.href='index.php';
          </script>";
    exit;
}

// Hanya bisa batalkan jika status "Menunggu Pembayaran"
if ($data['status_pemesanan'] !== "Menunggu Pembayaran") {
    echo "<script>
            alert('Pesanan ini tidak dapat dibatalkan!');
            window.location.href='index.php';
          </script>";
    exit;
}

// =====================
// 1. Kembalikan kursi ke "kosong"
// =====================
$kursi = $data['kursi']; // contoh: "12A,12B"

if (!empty($kursi)) {
    $kursiList = explode(",", $kursi);

    foreach ($kursiList as $k) {
        $k = trim($k);
        mysqli_query($connect, "
            UPDATE kursi 
            SET status_kursi = 'kosong'
            WHERE nomor_kursi = '$k'
        ");
    }
}

// =====================
// 2. Update status pesanan → Dibatalkan
// =====================
$update = "
    UPDATE pemesanan 
    SET status_pemesanan = 'Dibatalkan'
    WHERE id_pemesanan = $id_pemesanan
";

if (mysqli_query($connect, $update)) {
    echo "<script>
            alert('Pesanan berhasil dibatalkan.');
            window.location.href='index.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal membatalkan pesanan!');
            window.location.href='index.php';
          </script>";
}
?>
