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
    margin-bottom: 8px;
  }

  .breadcrumb {
    color: #bdbdbd;
    font-size: 0.95rem;
    margin-bottom: 25px;
  }

  .card-custom {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    background-color: #ffffff;
  }

  .card-header-custom {
    background-color: #ffb703;
    color: #ffffff;
    padding: 18px 24px;
    font-weight: 600;
    font-size: 1.1rem;
  }

  .form-label {
    font-weight: 600;
    color: #ffb703;
  }

  .form-control,
  .form-select {
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid #ddd;
    transition: 0.2s;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #ffb703;
    box-shadow: 0 0 4px rgba(255,183,3,0.4);
  }

  .btn-simpan {
    background-color: #ffb703;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    transition: 0.2s;
  }

  .btn-simpan:hover {
    background-color: #f29c02;
    color: #fff;
  }

  .btn-batal {
    background-color: #adb5bd;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    text-decoration: none;
  }

  .btn-batal:hover {
    background-color: #868e96;
    color: #fff;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">Tambah Promo</div>
  <div class="breadcrumb">Dashboard / Promo / Tambah</div>

  <div class="card card-custom">
    <div class="card-header-custom">Form Tambah Promo</div>

    <div class="card-body p-5">

      <form action="../../actions/promo/store.php" method="POST" enctype="multipart/form-data">

        <!-- NAMA PROMO -->
        <div class="mb-3">
          <label class="form-label">Nama Promo</label>
          <input type="text" name="nama_promo" class="form-control" placeholder="Masukkan nama promo" required>
        </div>

        <!-- KETERANGAN -->
        <div class="mb-3">
          <label class="form-label">Keterangan</label>
          <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan promo" required></textarea>
        </div>

        <!-- POTONGAN -->
        <div class="mb-3">
          <label class="form-label">Potongan (Rp)</label>
          <input type="number" name="potongan" class="form-control" placeholder="Masukkan nominal potongan" required>
        </div>

        <!-- GAMBAR PROMO -->
        <div class="mb-3">
          <label class="form-label">Gambar Promo</label>
          <input type="file" name="gambar" class="form-control" required>
          <small class="text-muted">Format jpg/png • Maks. 2MB</small>
        </div>

        <!-- TANGGAL MULAI & SELESAI -->
        <div class="row mb-3">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="datetime-local" name="tanggal_mulai" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="datetime-local" name="tanggal_selesai" class="form-control" required>
          </div>
        </div>

        <!-- STATUS -->
        <div class="mb-3">
          <label class="form-label">Status Promo</label>
          <select name="status" class="form-select" required>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>

        <!-- BUTTON -->
        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="index.php" class="btn-batal"><i class="bi bi-x-lg"></i> Batal</a>
          <button type="submit" class="btn-simpan"><i class="bi bi-save"></i> Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
