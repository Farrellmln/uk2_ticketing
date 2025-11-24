<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

if (!isset($_GET['id'])) {
  echo "
    <script>
      alert('ID promo tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$id = $_GET['id'];
$q = "SELECT * FROM promo WHERE id_promo = '$id'";
$r = mysqli_query($connect, $q);

if (mysqli_num_rows($r) == 0) {
  echo "
    <script>
      alert('Data promo tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$data = mysqli_fetch_assoc($r);
?>

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

  .card-body {
    padding: 35px 40px;
  }

  .form-label {
    font-weight: 600;
    color: #ffb703;
  }

  .form-control, .form-select {
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid #ddd;
  }

  .btn-submit {
    background-color: #ffb703;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
  }

  .btn-submit:hover {
    background-color: #f29c02;
    color: #fff;
  }

  .btn-batal {
    background-color: #ccc;
    color: #333;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
  }

  .btn-batal:hover {
    background-color: #bdbdbd;
  }

  .btn-group-bottom {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
  }

  .preview-img {
    max-height: 150px;
    border-radius: 10px;
    border: 1px solid #ddd;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">Edit Promo</div>
  <div class="breadcrumb">Dashboard / Promo / Edit</div>

  <div class="card card-custom">
    <div class="card-header-custom">Form Edit Promo</div>
    <div class="card-body">
      <form action="../../actions/promo/update.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id_promo" value="<?= $data['id_promo']; ?>">

        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label">Nama Promo</label>
            <input type="text" class="form-control" name="nama_promo"
                   value="<?= htmlspecialchars($data['nama_promo']); ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Potongan (Rp)</label>
            <input type="number" class="form-control" name="potongan"
                   value="<?= $data['potongan']; ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" name="tanggal_mulai"
                   value="<?= $data['tanggal_mulai']; ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" class="form-control" name="tanggal_selesai"
                   value="<?= $data['tanggal_selesai']; ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Status Promo</label>
            <select name="status" class="form-select" required>
              <option value="aktif" <?= $data['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
              <option value="nonaktif" <?= $data['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Gambar Promo</label>
            <input type="file" class="form-control" name="gambar">

            <?php if (!empty($data['gambar'])): ?>
              <div class="mt-2">
                <p class="text-muted mb-1">Gambar saat ini:</p>
                <img src="../../../storages/promo/<?= $data['gambar']; ?>" class="preview-img">
              </div>
            <?php endif; ?>
          </div>

        </div>

        <div class="btn-group-bottom">
          <a href="index.php" class="btn-batal">Batal</a>
          <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>

      </form>
    </div>
  </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
