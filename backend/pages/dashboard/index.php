<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';
global $connect;

/* ============================
        QUERY STATISTIK
===============================*/

// TOTAL ADMIN
$total_admin = mysqli_fetch_assoc(mysqli_query(
    $connect,
    "SELECT COUNT(*) AS total FROM user WHERE role = 'Administrator'"
))['total'];

// TOTAL PETUGAS
$total_petugas = mysqli_fetch_assoc(mysqli_query(
    $connect,
    "SELECT COUNT(*) AS total FROM user WHERE role = 'Petugas'"
))['total'];

// TOTAL PEMESANAN
$total_pemesanan = mysqli_fetch_assoc(mysqli_query(
    $connect,
    "SELECT COUNT(*) AS total FROM pemesanan"
))['total'];

// TOTAL PEMBAYARAN VALIDASI
$total_pembayaran_valid = mysqli_fetch_assoc(mysqli_query(
    $connect,
    "SELECT COUNT(*) AS total FROM pembayaran WHERE status_bayar = 'Validasi'"
))['total'];

// TOTAL TRANSPORTASI + RUTE
$total_transportasi_rute = mysqli_fetch_assoc(mysqli_query(
    $connect,
    "SELECT (SELECT COUNT(*) FROM transportasi) + (SELECT COUNT(*) FROM rute) AS total"
))['total'];


/* ============================
        DATA TERBARU
===============================*/

$pemesanan_terbaru = mysqli_query($connect,"
    SELECT p.*, u.nama_lengkap, r.asal, r.tujuan
    FROM pemesanan p
    LEFT JOIN user u ON p.id_user = u.id_user
    LEFT JOIN rute r ON p.id_rute = r.id_rute
    ORDER BY p.tanggal_pesan DESC
    LIMIT 5
");

$pembayaran_terbaru = mysqli_query($connect,"
    SELECT pb.*, pm.nama_metode
    FROM pembayaran pb
    LEFT JOIN metode_pembayaran pm ON pb.id_metode = pm.id_metode
    ORDER BY pb.tanggal_bayar DESC
    LIMIT 5
");

$verifikasi_terbaru = mysqli_query($connect,"
    SELECT v.*, u.nama_lengkap, pb.id_pemesanan
    FROM verifikasi v
    LEFT JOIN user u ON v.id_petugas = u.id_user
    LEFT JOIN pembayaran pb ON v.id_pembayaran = pb.id_pembayaran
    ORDER BY v.tanggal_verifikasi DESC
    LIMIT 5
");

$username = $_SESSION['username'] ?? "Admin";
?>

<style>
    .content-wrapper {
        background-color: #f8f9fa;
        padding: 90px 60px 50px 60px;
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
    }

    .welcome-box {
        text-align: center;
        margin-bottom: 35px;
    }

    .welcome-title {
        font-size: 30px;
        font-weight: 700;
        color: #ffb703;
    }

    .welcome-sub {
        font-size: 15px;
        color: #6c757d;
    }

    /* ==== CARD STAT ==== */
    .stat-card {
        border-radius: 18px;
        padding: 25px 20px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        text-align: center;
        transition: .25s;
    }
    .stat-card:hover {
        transform: translateY(-6px);
    }

    .stat-icon {
        font-size: 35px;
        margin-bottom: 8px;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;
        color: #818181;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-top: 2px;
    }

    /* CARD TABEL */
    .card-custom {
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.07);
        overflow: hidden;
        background: #fff;
    }
    .card-header-custom {
        background: #fff; /* BUKAN KUNING */
        padding: 14px 20px;
        font-weight: 700;
        font-size: 18px;
        color: #ffb703; /* TULISANNYA KUNING */
        border-bottom: 2px solid #ffb703;
    }

    table tbody tr:hover {
        background-color: #fff8e1;
        transition: .15s;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
    }
</style>

<div class="content-wrapper">

    <div class="welcome-box">
        <div class="welcome-title">Selamat Datang, <?= htmlspecialchars($username) ?> 👋</div>
        <div class="welcome-sub">Berikut adalah ringkasan aktivitas pada sistem ticketing kamu.</div>
    </div>

    <!-- ================= CARD STATISTIK ================ -->
    <div class="row g-4 mb-4">

        <!-- ADMIN + PETUGAS -->
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-user-shield stat-icon" style="color:#7e57c2;"></i>
                <div class="stat-title">Admin & Petugas</div>
                <div class="stat-value" style="color:#7e57c2;">
                    <?= $total_admin + $total_petugas ?>
                </div>
            </div>
        </div>

        <!-- PEMESANAN -->
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-ticket-alt stat-icon" style="color:#1565c0;"></i>
                <div class="stat-title">Total Pemesanan</div>
                <div class="stat-value" style="color:#1565c0;"><?= $total_pemesanan ?></div>
            </div>
        </div>

        <!-- PEMBAYARAN -->
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-check-circle stat-icon" style="color:#2ecc71;"></i>
                <div class="stat-title">Pembayaran Validasi</div>
                <div class="stat-value" style="color:#2ecc71;"><?= $total_pembayaran_valid ?></div>
            </div>
        </div>

        <!-- TRANSPORTASI + RUTE -->
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-route stat-icon" style="color:#ffb703;"></i>
                <div class="stat-title">Transportasi + Rute</div>
                <div class="stat-value" style="color:#ffb703;"><?= $total_transportasi_rute ?></div>
            </div>
        </div>

    </div>

    <!-- ================= PEMESANAN TERBARU ================ -->
    <div class="card card-custom mb-4">
        <div class="card-header-custom">Pemesanan Terbaru</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Rute</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = mysqli_fetch_assoc($pemesanan_terbaru)) : ?>
                    <tr>
                        <td><?= $p['nama_lengkap'] ?></td>
                        <td><?= $p['asal'] ?> → <?= $p['tujuan'] ?></td>
                        <td><span class="badge-status bg-warning"><?= $p['status_pemesanan'] ?></span></td>
                        <td><?= $p['tanggal_pesan'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= PEMBAYARAN TERBARU ================ -->
    <div class="card card-custom mb-4">
        <div class="card-header-custom">Pembayaran Terbaru</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($b = mysqli_fetch_assoc($pembayaran_terbaru)) : ?>
                    <tr>
                        <td><?= $b['nama_metode'] ?></td>
                        <td>Rp <?= number_format($b['total_bayar'], 0, ',', '.') ?></td>
                        <td><span class="badge-status bg-info"><?= $b['status_bayar'] ?></span></td>
                        <td><?= $b['tanggal_bayar'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= VERIFIKASI ================ -->
    <div class="card card-custom">
        <div class="card-header-custom">Verifikasi Terbaru</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Petugas</th>
                        <th>ID Pemesanan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($v = mysqli_fetch_assoc($verifikasi_terbaru)) : ?>
                    <tr>
                        <td><?= $v['nama_lengkap'] ?></td>
                        <td><?= $v['id_pemesanan'] ?></td>
                        <td>
                            <span class="badge-status <?= $v['status_verifikasi'] == 'Valid' ? 'bg-success' : 'bg-danger' ?>">
                                <?= $v['status_verifikasi'] ?>
                            </span>
                        </td>
                        <td><?= $v['tanggal_verifikasi'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
