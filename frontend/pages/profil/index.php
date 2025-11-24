<?php
include '../../app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Penumpang') {
    echo "<script>alert('Akses ditolak! Silakan login dahulu.');history.back();</script>";
    exit();
}

$id_user = $_SESSION['id_user'];

$q_user = "SELECT * FROM user WHERE id_user = '$id_user'";
$user_res = mysqli_query($connect, $q_user);
$user = mysqli_fetch_assoc($user_res);

$q_pesanan = "
    SELECT p.*, r.asal, r.tujuan, r.jadwal_berangkat, 
           r.harga, t.nama_transportasi, t.jenis
    FROM pemesanan p
    JOIN rute r ON p.id_rute = r.id_rute
    JOIN transportasi t ON r.id_transportasi = t.id_transportasi
    WHERE p.id_user = '$id_user'
    ORDER BY p.id_pemesanan DESC
";
$pesanan_res = mysqli_query($connect, $q_pesanan);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Saya - Goticket</title>

    <link href="../../template/public/assets/css/theme.css" rel="stylesheet">
<link rel="icon" type="image/png" sizes="16x16" href="../../../storages/navbar/g.png">
    <style>
        /* === FIX BIAR TIDAK BISA GESER KANAN KIRI === */
        html, body {
            overflow-x: hidden !important;
            width: 100%;
            max-width: 100%;
        }

        body {
            background: #f5f6fb;
        }

        /* Background dekorasi */
        #profil-wrap {
            padding-top: 40px;
            padding-bottom: 60px;
            background: linear-gradient(to bottom right, #fffdf5, #ffffff 50%);
            position: relative;
            overflow-x: hidden; /* FIX */
        }

        #profil-wrap::before {
            content: "";
            position: absolute;
            top: 10%;
            left: 0; /* FIX dari -5% */
            width: 220px;
            height: 220px;
            background: #f9b23333;
            border-radius: 50%;
            filter: blur(25px);
            pointer-events: none;
        }

        #profil-wrap::after {
            content: "";
            position: absolute;
            bottom: 5%;
            right: 0; /* FIX dari -5% */
            width: 260px;
            height: 260px;
            background: #004c8420;
            border-radius: 50%;
            filter: blur(35px);
            pointer-events: none;
        }

        /* PROFILE CARD */
        .profile-card {
            max-width: 900px;
            width: 100%;
            background: #ffffffee;
            backdrop-filter: blur(8px);
            border-radius: 24px;
            padding: 45px 50px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.07);
            border: 1px solid #e6e9f0;
            position: relative;
            overflow-x: hidden; /* FIX */
        }

        .profile-card::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 140px;
            height: 140px;
            background: #f9b23344;
            border-radius: 50%;
            filter: blur(30px);
        }

        /* Judul section */
        .section-title {
            font-weight: 800;
            color: #004c84;
            margin-bottom: 20px;
        }

        .section-sub {
            color: #6d7a8a;
            margin-bottom: 35px;
        }

        /* Icon user — tidak diubah ukurannya */
        .profile-icon {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            background: #fff6e4;
            border: 5px solid #f9b233;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            box-shadow: 0 8px 14px rgba(0,0,0,0.1);
        }

        .profile-icon svg {
            width: 70px;
            height: 70px;
            color: #f9b233;
        }

        /* TABLE */
        .profile-table th {
            width: 180px;
            color: #004c84;
            font-weight: 700;
            padding: 12px 0;
        }

        .profile-table td {
            padding: 12px 0;
            font-weight: 500;
            color: #333;
        }

        .profile-table tr:not(:last-child) td,
        .profile-table tr:not(:last-child) th {
            border-bottom: 1px dashed #e1e5ec;
        }

        /* BADGE */
        .role-badge {
            background: #f9b233;
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 600;
        }

        /* PESANAN CARD */
        .pesanan-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            padding: 25px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .pesanan-card::before {
            content: "";
            position: absolute;
            top: -20px;
            right: -20px;
            width: 110px;
            height: 110px;
            background: #004c8411;
            border-radius: 50%;
        }

        .pesanan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.10);
        }

        .history-title {
            font-weight: 800;
            color: #004c84;
            margin-bottom: 25px;
        }

        .btn-warning {
            background-color: #f9b233 !important;
            border: none;
        }
    </style>
</head>

<body>

<?php include '../../partials/header.php'; ?>

<main id="profil-wrap">
    <div class="container d-flex flex-column align-items-center">

        <div class="align-self-start mb-3">
            <button onclick="history.back()" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </button>
        </div>

        <div class="text-center mb-4">
            <h2 class="section-title">Profil Pengguna</h2>
            <p class="section-sub">Lihat identitas dan informasi akun Anda</p>
        </div>

        <div class="profile-card">

            <div class="profile-icon mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    <path fill-rule="evenodd"
                        d="M8 9a5 5 0 0 0-4.546 2.916c-.25.5-.066 1.084.416 1.332
                    A9.98 9.98 0 0 0 8 14c1.57 0 3.06-.362 
                    4.13-.752.48-.181.666-.77.415-1.27A5 5 0 0 0 8 9z" />
                </svg>
            </div>

            <table class="table profile-table mb-4">
                <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($user['nama_lengkap']); ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($user['email']); ?></td></tr>
                <tr><th>No. Telepon</th><td><?= htmlspecialchars($user['no_hp']); ?></td></tr>
                <tr><th>Username</th><td><?= htmlspecialchars($user['username']); ?></td></tr>
                <tr><th>Role</th><td><span class="role-badge"><?= $user['role']; ?></span></td></tr>
            </table>

            <div class="text-center">
                <a href="../auth/logout.php" class="btn btn-danger px-4 py-2 fw-semibold rounded-3">Logout</a>
            </div>

        </div>
    </div>
</main>

<div class="container mt-5" style="max-width: 1100px;">
    <h3 class="history-title text-center">Riwayat Pesanan Anda</h3>

    <?php if (mysqli_num_rows($pesanan_res) > 0): ?>
    <div class="row g-4">

        <?php while ($p = mysqli_fetch_assoc($pesanan_res)): ?>

            <?php
            $status = $p['status_pemesanan'];
            $badge = [
                "Menunggu Pembayaran" => "warning",
                "Menunggu Verifikasi" => "info",
                "Dibayar" => "primary",
                "Diverifikasi" => "primary",
                "Selesai" => "success",
                "Dibatalkan" => "danger"
            ][$status] ?? "secondary";
            ?>

            <div class="col-md-6">
                <div class="pesanan-card">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1"><?= $p['asal']; ?> → <?= $p['tujuan']; ?></h6>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date("d M Y", strtotime($p['jadwal_berangkat'])); ?>
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-train-front me-1"></i>
                                <?= $p['nama_transportasi']; ?> (<?= $p['jenis']; ?>)
                            </p>
                        </div>
                        <span class="badge bg-<?= $badge ?> px-3 py-2"><?= $status ?></span>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="../pesan/detail_pesanan.php?id_pemesanan=<?= $p['id_pemesanan']; ?>"
                            class="btn btn-outline-primary btn-sm px-4 rounded-3">Detail</a>
                    </div>

                </div>
            </div>

        <?php endwhile; ?>

    </div>

    <?php else: ?>
        <p class="text-center text-muted mt-4">Belum ada pesanan.</p>
    <?php endif; ?>
</div>

</body>
</html>
<?php include '../../partials/footer.php'; ?>
