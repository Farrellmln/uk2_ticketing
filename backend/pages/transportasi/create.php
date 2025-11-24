<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';
?>

<style>
    .content-wrapper {
        background-color: #f8f9fa; /* ubah jadi abu muda terang */
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
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        border: none;
        background-color: #ffffff;
    }

    .card-header-custom {
        background-color: #ffb703;
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
        padding: 18px 24px;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }

    .form-label {
        font-weight: 600;
        color: #000;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 10px 12px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ffb703;
        box-shadow: 0 0 0 0.15rem rgba(255, 183, 3, 0.25);
    }

    .btn-submit {
        background-color: #ffb703;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 1rem;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #f4a900;
    }

    .btn-back {
        background-color: #f5f5f5;
        color: #000;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: background-color 0.2s;
        border: none;
    }

    .btn-back:hover {
        background-color: #e0e0e0;
    }

    .form-container {
        padding: 35px 45px;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 90px 20px;
        }

        .form-container {
            padding: 25px;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-title">Tambah Transportasi</div>
    <div class="breadcrumb">Dashboard / Transportasi / Tambah</div>

    <div class="card card-custom">
        <div class="card-header-custom">Form Tambah Transportasi</div>
        <div class="form-container">
            <form action="../../actions/transportasi/store.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Transportasi</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pesawat">Pesawat</option>
                            <option value="Kereta">Kereta</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Transportasi</label>
                        <input type="text" name="nama_transportasi" class="form-control" placeholder="Misal: Garuda Indonesia" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Transportasi</label>
                        <input type="text" name="kode" class="form-control" placeholder="Misal: GA-123" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kapasitas (kursi)</label>
                        <input type="number" name="kapasitas" class="form-control" min="1" placeholder="Misal: 150" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn-back">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
