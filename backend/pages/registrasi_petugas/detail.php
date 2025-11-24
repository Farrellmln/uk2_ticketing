<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

$q = mysqli_query($connect, "SELECT * FROM user WHERE id_user='$id'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    echo "<script>alert('Data user tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .content-wrapper {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 90px 70px 50px 70px;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 600;
        color: #ffb703;
        margin-bottom: 8px;
    }

    .breadcrumb {
        color: #bdbdbd;
        font-size: 0.95rem;
        margin-bottom: 25px;
    }

    .card-custom {
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        background-color: #ffffff;
        border: none;
    }

    .card-header-custom {
        background-color: #ffb703;
        color: #ffffff;
        padding: 18px 24px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .card-body {
        padding: 40px 45px;
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
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-value {
        color: #333;
        font-size: 15px;
    }

    hr {
        border-top: 1px solid #eee;
        margin: 10px 0 0 0;
    }

    .btn-kembali {
        background-color: #ffb703;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-kembali:hover {
        background-color: #f29c02;
        color: #fff;
    }

    @media (max-width: 768px) {
        .detail-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-title">Detail User</div>
    <div class="breadcrumb">Dashboard / User / Detail</div>

    <div class="card card-custom">
        <div class="card-header-custom">Informasi User</div>

        <div class="card-body">

            <div class="detail-container">
                
                <div>
                    <div class="detail-label"><i class="bi bi-person-fill"></i> Nama Lengkap</div>
                    <div class="detail-value"><?= htmlspecialchars($data['nama_lengkap']); ?></div>
                    <hr>
                </div>

                <div>
                    <div class="detail-label"><i class="bi bi-person-badge-fill"></i> Username</div>
                    <div class="detail-value"><?= htmlspecialchars($data['username']); ?></div>
                    <hr>
                </div>

                <div>
                    <div class="detail-label"><i class="bi bi-envelope-fill"></i> Email</div>
                    <div class="detail-value"><?= htmlspecialchars($data['email']); ?></div>
                    <hr>
                </div>

                <div>
                    <div class="detail-label"><i class="bi bi-phone-fill"></i> Nomor HP</div>
                    <div class="detail-value"><?= htmlspecialchars($data['no_hp']); ?></div>
                    <hr>
                </div>

                <div>
                    <div class="detail-label"><i class="bi bi-shield-lock-fill"></i> Role</div>
                    <div class="detail-value text-capitalize">
                        <?= htmlspecialchars($data['role']); ?>
                    </div>
                    <hr>
                </div>

            </div>

            <div class="d-flex justify-content-end">
                <a href="index.php" class="btn-kembali"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

        </div>
    </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
