<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';
global $connect;

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

<style>
    .content-wrapper {
        background-color: #f8f9fa;
        padding: 90px 70px 50px 70px;
        min-height: 100vh;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 600;
        color: #ffb703;
        margin-bottom: 8px;
    }

    .breadcrumb {
        color: #bdbdbd;
        margin-bottom: 25px;
    }

    .card-custom {
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #ffb703;
        color: white;
        padding: 18px 24px;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 10px 14px;
        border: 1px solid #d0d7de;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ffb703;
        box-shadow: 0 0 0 0.15rem rgba(255, 183, 3, 0.25);
    }

    .btn-primary-custom {
        background-color: #ffb703;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-primary-custom:hover {
        background-color: #e6a300;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">Edit User</div>
    <div class="breadcrumb">Dashboard / User / Edit</div>

    <div class="card card-custom">
        <div class="card-header-custom">Form Edit User</div>

        <div class="card-body p-4">

            <form action="../../actions/registrasi_petugas/update.php" method="POST" class="row g-3">

                <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control"
                        value="<?= htmlspecialchars($data['nama_lengkap']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Level</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Level --</option>
                        <option value="administrator" <?= ($data['role'] == 'administrator') ? 'selected' : ''; ?>>
                            Administrator
                        </option>
                        <option value="petugas" <?= ($data['role'] == 'petugas') ? 'selected' : ''; ?>>
                            Petugas
                        </option>
                    </select>
                </div>


                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?= htmlspecialchars($data['username']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($data['email']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="<?= htmlspecialchars($data['no_hp']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Password baru...">
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="index.php" class="btn-back me-2">Kembali</a>
                    <button class="btn-primary-custom">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>