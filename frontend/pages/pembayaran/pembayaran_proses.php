<?php
include '../../app.php';
session_start();

// ==================================================================
// CEK AGAR HANYA BISA DIAKSES VIA POST
// ==================================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: pembayaran.php");
    exit;
}

// ==================================================================
// AMBIL DATA DARI FORM
// ==================================================================
$id_pemesanan       = intval($_POST['id_pemesanan']);
$total_asli         = intval($_POST['total_bayar']);           // dari DB
$total_final        = intval($_POST['final_total_bayar']);      // setelah promo
$id_promo           = intval($_POST['id_promo'] ?? 0);
$jenis              = $_POST['jenis'] ?? '';
$metode             = 0;

// ==================================================================
// TENTUKAN id_metode SESUAI JENIS
// ==================================================================
if ($jenis === "Bank") {
    $metode = intval($_POST['metode_bank'] ?? 0);
}
elseif ($jenis === "E-Wallet") {
    $metode = intval($_POST['metode_ewallet'] ?? 0);
}
elseif ($jenis === "Cash") {
    $metode = intval($_POST['metode_cash'] ?? 0);
}

// ==================================================================
// VALIDASI DASAR
// ==================================================================
if ($jenis === '') {
    echo "<script>alert('Jenis pembayaran harus dipilih!'); history.back();</script>";
    exit;
}

if ($metode == 0) {
    echo "<script>alert('Silakan pilih metode pembayaran!'); history.back();</script>";
    exit;
}

// CEK METODE ADA DI DATABASE
$cek = mysqli_query($connect, "SELECT id_metode FROM metode_pembayaran WHERE id_metode = $metode");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Metode pembayaran tidak valid!'); history.back();</script>";
    exit;
}

// ==================================================================
// PROSES UPLOAD BUKTI PEMBAYARAN
// ==================================================================
$nama_file = null;

if (!empty($_FILES['bukti_transfer']['name'])) {

    $folder = "../../../storages/bukti_pembayaran/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION);
    $nama_file = "bukti_" . time() . "_" . rand(100,999) . "." . $ext;

    move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $folder . $nama_file);
}

// ==================================================================
// INSERT PEMBAYARAN BARU — (MEMAKAI TOTAL AKHIR)
// ==================================================================
$stmt = $connect->prepare("
    INSERT INTO pembayaran 
        (id_pemesanan, id_metode, total_bayar, bukti_transfer, id_promo, status_bayar, tanggal_bayar)
    VALUES 
        (?, ?, ?, ?, ?, 'Menunggu Verifikasi', NOW())
");

$stmt->bind_param("iiisi", 
    $id_pemesanan, 
    $metode, 
    $total_final,   // <== menggunakan total promo
    $nama_file, 
    $id_promo
);

$stmt->execute();

// ==================================================================
// UPDATE STATUS PEMESANAN
// ==================================================================
$connect->query("
    UPDATE pemesanan 
    SET status_pemesanan = 'Menunggu Verifikasi'
    WHERE id_pemesanan = $id_pemesanan
");

// ==================================================================
// REDIRECT
// ==================================================================
echo "
<script>
    alert('Pembayaran berhasil dikirim! Menunggu verifikasi admin.');
    window.location.href = 'pembayaran.php?id_pemesanan=$id_pemesanan';
</script>
";
exit;

?>
