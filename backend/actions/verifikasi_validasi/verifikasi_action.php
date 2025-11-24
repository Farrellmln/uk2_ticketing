<?php
include '../../app.php';
session_start();

// Validasi input
$id_pembayaran = $_POST['id_pembayaran'] ?? 0;
$action        = $_POST['action'] ?? '';
$id_petugas    = $_SESSION['id_user'] ?? 0;

if (!$id_pembayaran || !in_array($action, ['accept', 'reject'])) {
    http_response_code(400);
    exit('Invalid input');
}

// Ambil id_pemesanan
$q = mysqli_query($connect, "
    SELECT id_pemesanan 
    FROM pembayaran 
    WHERE id_pembayaran = $id_pembayaran
    LIMIT 1
");
$data = mysqli_fetch_assoc($q);
$id_pemesanan = $data['id_pemesanan'] ?? 0;

if (!$id_pemesanan) {
    http_response_code(400);
    exit('Data not found');
}

$connect->begin_transaction();

try {

    if ($action === 'accept') {

        // ============ VERIF VALID ============
        $connect->query("
            UPDATE pembayaran 
            SET status_bayar = 'Validasi'
            WHERE id_pembayaran = $id_pembayaran
        ");

        $connect->query("
            UPDATE pemesanan
            SET status_pemesanan = 'Diverifikasi'
            WHERE id_pemesanan = $id_pemesanan
        ");

        // Insert verifikasi terbaru
        $connect->query("
            INSERT INTO verifikasi (id_pembayaran, id_petugas, status_verifikasi, catatan, created_at)
            VALUES ($id_pembayaran, $id_petugas, 'Valid', 'Pembayaran valid.', NOW())
        ");

    } else {

        // ============ VERIF TIDAK VALID ============
        $connect->query("
            UPDATE pembayaran 
            SET status_bayar = 'Tidak Valid'
            WHERE id_pembayaran = $id_pembayaran
        ");

        $connect->query("
            UPDATE pemesanan
            SET status_pemesanan = 'Menunggu Verifikasi'
            WHERE id_pemesanan = $id_pemesanan
        ");

        // Insert verifikasi terbaru
        $connect->query("
            INSERT INTO verifikasi (id_pembayaran, id_petugas, status_verifikasi, catatan, created_at)
            VALUES ($id_pembayaran, $id_petugas, 'Tidak Valid', 'Bukti pembayaran tidak valid.', NOW())
        ");
    }

    $connect->commit();
    echo "OK";

} catch (Exception $e) {
    $connect->rollback();
    echo "Error: " . $e->getMessage();
}
?>
