<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';
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
    margin-bottom: 4px;
  }

  .breadcrumb {
    color: #bdbdbd;
    font-size: 0.95rem;
    margin-bottom: 25px;
  }

  .card-custom {
    border-radius: 12px;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border: none;
  }

  .card-header-custom {
    background-color: #ffb703;
    color: #ffffff;
    padding: 18px 24px;
    font-weight: 600;
    font-size: 1.1rem;
  }

  .btn-kuning {
    background-color: #ffb703;
    color: #fff !important;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
  }

  .btn-kuning:hover {
    background-color: #e09e00;
  }

  .btn-kembali {
    background-color: #6c757d;
    color: #fff !important;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
  }

  .btn-kembali:hover {
    background-color: #5a6268;
  }
</style>

<div class="content-wrapper">

  <div class="page-title">Tambah Promo</div>
  <div class="breadcrumb">Dashboard / Promo / Tambah</div>

  <div class="card card-custom">
    <div class="card-header-custom">Form Tambah Promo</div>

    <div class="card-body">

      <form action="../../actions/promo/store.php" method="POST">

        <!-- NAMA PROMO -->
        <div class="mb-3">
          <label class="fw-semibold mb-1">Nama Promo</label>
          <input type="text" name="nama_promo" class="form-control form-control-lg" required>
        </div>

        <!-- POTONGAN -->
        <div class="mb-3">
          <label class="fw-semibold mb-1">Potongan (Rp)</label>
          <input type="number" name="potongan" class="form-control form-control-lg" min="0" required>
        </div>

        <!-- TANGGAL MULAI -->
        <div class="mb-3">
          <label class="fw-semibold mb-1">Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control form-control-lg" required>
        </div>

        <!-- TANGGAL SELESAI -->
        <div class="mb-3">
          <label class="fw-semibold mb-1">Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai" class="form-control form-control-lg" required>
        </div>

        <!-- STATUS -->
        <div class="mb-3">
          <label class="fw-semibold mb-1">Status Promo</label>
          <select name="status" class="form-select form-select-lg" required>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>

        <!-- BUTTON -->
        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="index.php" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
          <button type="submit" class="btn-kuning">
            <i class="bi bi-check2-circle"></i> Simpan Promo
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
