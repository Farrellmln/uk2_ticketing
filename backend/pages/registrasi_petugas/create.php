<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
?>

<style>
    .content-wrapper {
        background-color: #f8fafc;
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
        color: #6c757d;
        margin-bottom: 25px;
    }

    .card-custom {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #ffffff;
    }

    .card-header-custom {
        background-color: #ffb703;
        color: #fff;
        padding: 18px 24px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        color: #1b3c74;
    }

    .form-control,
    .form-select {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #d0d7de;
    }

    .btn-save {
        background-color: #ffb703;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        color: #fff;
    }

    .btn-save:hover {
        background-color: #ff9800;
    }

    .btn-back {
        background-color: #6c757d;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 500;
        color: #fff;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">Tambah User Baru</div>
    <div class="breadcrumb">Dashboard / User / Tambah</div>

    <div class="card card-custom">
        <div class="card-header-custom">
            Form Tambah Petugas / Administrator
        </div>

        <div class="card-body p-4">
            <form action="../../actions/registrasi_petugas/store.php" method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap..." required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role User</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Petugas">Petugas</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username..." required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email... (boleh dikosongkan)">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor HP..." required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="index.php" class="btn-back me-2">Kembali</a>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
