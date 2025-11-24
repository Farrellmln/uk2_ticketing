<?php
include '../../app.php';

$id_pembayaran = $_GET['id_pembayaran'] ?? null;
$id_pemesanan  = $_GET['id_pemesanan'] ?? null;

// VALIDASI
if (!$id_pemesanan) {
    echo "<script>alert('ID pemesanan tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

/*
  ==================================================
  AMBIL SEMUA PEMBAYARAN YANG TERKAIT DENGAN PEMESANAN
  ==================================================
*/
$qPembayaran = mysqli_query($connect, "
    SELECT * FROM pembayaran WHERE id_pemesanan = '$id_pemesanan'
");

if (mysqli_num_rows($qPembayaran) > 0) {

    while ($p = mysqli_fetch_assoc($qPembayaran)) {

        $idPay = $p['id_pembayaran'];

        // HAPUS FILE BUKTI
        if (!empty($p['bukti_transfer'])) {
            $path = "../../../storages/bukti_pembayaran/" . $p['bukti_transfer'];
            if (file_exists($path)) unlink($path);
        }

        // HAPUS VERIFIKASI YANG BERKAITAN
        mysqli_query($connect, "DELETE FROM verifikasi WHERE id_pembayaran = '$idPay'");

        // HAPUS PEMBAYARAN
        mysqli_query($connect, "DELETE FROM pembayaran WHERE id_pembayaran = '$idPay'");
    }
}

/*
  ==================================================
  HAPUS DATA PEMESANAN
  ==================================================
*/
mysqli_query($connect, "DELETE FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'");

/*
  ==================================================
  SELESAI
  ==================================================
*/
echo "<script>
    alert('Berhasil dihapus beserta seluruh data terkait!');
    window.location.href='index.php';
</script>";
exit;
?>
