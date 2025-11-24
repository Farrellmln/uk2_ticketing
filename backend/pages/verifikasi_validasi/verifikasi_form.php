<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// ===== VALIDASI =====
if (!isset($_GET['id_pemesanan']) || !isset($_GET['id_pembayaran'])) {
    echo "<script>alert('Data tidak lengkap!'); window.location.href='index.php';</script>";
    exit;
}

$id_pemesanan  = intval($_GET['id_pemesanan']);
$id_pembayaran = intval($_GET['id_pembayaran']);

// ===== QUERY DETAIL PEMBAYARAN TERPILIH =====
// (WAJIB: ambil data berdasar id_pembayaran agar tidak nyasar)
$q = "
SELECT 
    p.id_pemesanan,
    p.status_pemesanan,
    p.kursi,

    u.nama_lengkap, 
    u.email, 
    u.no_hp,

    r.asal, 
    r.tujuan,

    t.jenis, 
    t.nama_transportasi,

    pb.id_pembayaran, 
    pb.total_bayar, 
    pb.bukti_transfer,
    pb.id_promo,

    pr.nama_promo,
    pr.potongan,

    m.jenis AS jenis_pembayaran,
    m.nama_metode AS nama_pembayaran

FROM pembayaran pb
LEFT JOIN pemesanan p ON pb.id_pemesanan = p.id_pemesanan
LEFT JOIN user u ON p.id_user = u.id_user
LEFT JOIN rute r ON p.id_rute = r.id_rute
LEFT JOIN transportasi t ON r.id_transportasi = t.id_transportasi
LEFT JOIN metode_pembayaran m ON pb.id_metode = m.id_metode
LEFT JOIN promo pr ON pb.id_promo = pr.id_promo

WHERE pb.id_pembayaran = $id_pembayaran
LIMIT 1
";

$res = mysqli_query($connect, $q);
if (mysqli_num_rows($res) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($res);

// ===== SUBMIT VERIFIKASI =====
if (isset($_POST['submit_verifikasi'])) {

    $status     = $_POST['status'];
    $catatan    = mysqli_real_escape_string($connect, $_POST['catatan']);
    $id_petugas = $_SESSION['id_user'];

    // Insert log verifikasi
    mysqli_query($connect, "
        INSERT INTO verifikasi (id_pembayaran, id_petugas, status_verifikasi, catatan, created_at)
        VALUES ('$id_pembayaran', '$id_petugas', '$status', '$catatan', NOW())
    ");

    if ($status == 'Valid') {

        mysqli_query($connect, "
            UPDATE pembayaran 
            SET status_bayar='Validasi' 
            WHERE id_pembayaran='$id_pembayaran'
        ");

        mysqli_query($connect, "
            UPDATE pemesanan 
            SET status_pemesanan='Diverifikasi' 
            WHERE id_pemesanan='$id_pemesanan'
        ");

    } else {

        mysqli_query($connect, "
            UPDATE pembayaran 
            SET status_bayar='Tidak Valid' 
            WHERE id_pembayaran='$id_pembayaran'
        ");

        mysqli_query($connect, "
            UPDATE pemesanan 
            SET status_pemesanan='Menunggu Verifikasi'
            WHERE id_pemesanan='$id_pemesanan'
        ");
    }

    echo "<script>alert('Verifikasi berhasil disimpan!'); window.location.href='index.php';</script>";
    exit;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>

<style>
/* === SEMUA STYLE TETAP — sesuai punyamu === */
.content-wrapper {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 90px 70px 50px 70px;
}

.page-title {
    font-size: 1.9rem;
    font-weight: 600;
    color: #ffb703;
}

.card-custom {
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    border: none;
    background: #fff;
}

.card-header-custom {
    background-color: #ffb703;
    color: #fff;
    padding: 18px 24px;
    font-weight: 600;
}

.detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px 80px;
    margin-bottom: 40px;
}

.detail-label {
    font-weight: 600;
    color: #ffb703;
    font-size: 15px;
    margin-bottom: 4px;
}

.detail-value {
    color: #333;
    font-size: 15px;
}

.form-section-title {
    font-weight: 700;
    color: #ffb703;
    font-size: 1.2rem;
}

.btn-kuning {
    background-color: #ffb703;
    color: #fff !important;
    border: none;
    padding: 10px 26px;
    border-radius: 10px;
    font-weight: 600;
}
.btn-kuning:hover {
    background-color: #e6a000;
}

.btn-verif {
    padding: 10px 26px;
    border-radius: 10px;
    font-weight: 600;
}

.btn-wrapper {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
</style>

<div class="content-wrapper">
    <div class="page-title">Verifikasi Pembayaran</div>
    <div class="breadcrumb">Dashboard / Verifikasi / Form</div>

    <div class="card card-custom">
        <div class="card-header-custom">Informasi Pemesanan</div>

        <div class="card-body">

            <!-- GRID DETAIL -->
            <div class="detail-container">

                <div>
                    <div class="detail-label">Nama Penumpang</div>
                    <div class="detail-value"><?= $data['nama_lengkap'] ?></div>
                </div>

                <div>
                    <div class="detail-label">Transportasi</div>
                    <div class="detail-value">
                        <?= $data['jenis']." - ".$data['nama_transportasi'] ?>
                    </div>
                </div>

                <div>
                    <div class="detail-label">Rute</div>
                    <div class="detail-value">
                        <?= $data['asal']." → ".$data['tujuan'] ?>
                    </div>
                </div>

                <div>
                    <div class="detail-label">Kursi</div>
                    <div class="detail-value"><?= $data['kursi'] ?: '-' ?></div>
                </div>

                <div>
                    <div class="detail-label">Promo</div>
                    <div class="detail-value">
                        <?php if ($data['id_promo']): ?>
                            <strong class="text-success"><?= $data['nama_promo']; ?></strong><br>
                            <small class="text-muted">
                                Potongan: <?= formatRupiah($data['potongan']); ?>
                            </small>
                        <?php else: ?>
                            <span class="text-muted">Tidak memakai promo</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div class="detail-label">Total Bayar Setelah Promo</div>
                    <div class="detail-value"><?= formatRupiah($data['total_bayar']); ?></div>
                </div>

                <div>
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value">
                        <?= $data['jenis_pembayaran']." - ".$data['nama_pembayaran'] ?>
                    </div>
                </div>

            </div>

            <hr>

            <!-- BUKTI TRANSFER -->
            <div class="detail-label">Bukti Transfer</div>
            <img src="../../../storages/bukti_pembayaran/<?= $data['bukti_transfer']; ?>"
                 class="img-fluid rounded border"
                 style="max-height:300px;">

            <hr class="mt-4">

            <!-- FORM VERIFIKASI -->
            <div class="form-section-title mb-3">Form Verifikasi</div>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Pembayaran</label>
                    <select name="status" class="form-select" required>
                        <option value="Valid">Valid</option>
                        <option value="Tidak Valid">Tidak Valid (Tolak)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3" required></textarea>
                </div>

                <div class="btn-wrapper mt-4">
                    <a href="index.php" class="btn-kuning">Kembali</a>
                    <button type="submit" name="submit_verifikasi" class="btn btn-success btn-verif">
                        Simpan Verifikasi
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
