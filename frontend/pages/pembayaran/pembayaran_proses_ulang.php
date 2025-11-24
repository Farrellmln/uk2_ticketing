<?php
include '../../app.php';
session_start();

// ==================================================================
// HANYA BOLEH POST
// ==================================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: pembayaran.php");
    exit;
}

// ==================================================================
// AMBIL DATA DARI FORM
// ==================================================================
$id_pemesanan        = intval($_POST['id_pemesanan']);
$id_pembayaran_lama  = intval($_POST['id_pembayaran_lama']);
$total_asli          = intval($_POST['total_bayar']);
$total_final         = intval($_POST['final_total_bayar']);   // TOTAL SETELAH PROMO
$id_promo            = ($_POST['id_promo'] !== "") ? intval($_POST['id_promo']) : NULL;

$jenis  = $_POST['jenis'] ?? '';
$metode = 0;

// ==================================================================
// TENTUKAN METODE PEMBAYARAN
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
// VALIDASI
// ==================================================================
if ($metode == 0) {
    echo "<script>alert('Pilih metode pembayaran dengan benar!'); history.back();</script>";
    exit;
}

// Cek apakah metode valid
$cek = mysqli_query($connect, "SELECT id_metode FROM metode_pembayaran WHERE id_metode = $metode");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Metode pembayaran tidak valid!'); history.back();</script>";
    exit;
}

// ==================================================================
// UPLOAD BUKTI PEMBAYARAN ULANG
// ==================================================================
$nama_file = null;

if (!empty($_FILES['bukti_transfer']['name'])) {

    $folder = "../../../storages/bukti_pembayaran/";
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $ext = pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION);
    $nama_file = "bukti_ulang_" . time() . "_" . rand(100,999) . "." . $ext;

    move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $folder . $nama_file);
}

// ==================================================================
// UPDATE PEMBAYARAN LAMA → DIGANTI (TAPIIII promo lama tetap dibiarkan)
// ==================================================================
if ($id_pembayaran_lama > 0) {
    $connect->query("
        UPDATE pembayaran 
        SET status_bayar = 'Diganti'
        WHERE id_pembayaran = $id_pembayaran_lama
    ");
}

// ==================================================================
// INSERT PEMBAYARAN BARU — memakai total FINAL + promo BARU (atau NULL)
// ==================================================================
$stmt = $connect->prepare("
    INSERT INTO pembayaran 
        (id_pemesanan, id_metode, total_bayar, bukti_transfer, id_promo, status_bayar, tanggal_bayar)
    VALUES (?, ?, ?, ?, ?, 'Menunggu Verifikasi', NOW())
");

$stmt->bind_param("iiisi",
    $id_pemesanan,
    $metode,
    $total_final,   // <--- total sudah dipotong promo
    $nama_file,
    $id_promo       // <--- promo baru atau NULL
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
    alert('Pembayaran ulang berhasil dikirim! Menunggu verifikasi admin.');
    window.location.href = 'pembayaran.php?id_pemesanan=$id_pemesanan';
</script>
";
exit;

?>
