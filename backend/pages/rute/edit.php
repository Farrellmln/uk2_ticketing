<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

if (!isset($_GET['id'])) {
  echo "
    <script>
      alert('ID rute tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$id = $_GET['id'];
$q = "SELECT * FROM rute WHERE id_rute = '$id'";
$r = mysqli_query($connect, $q);

if (mysqli_num_rows($r) == 0) {
  echo "
    <script>
      alert('Data rute tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$data = mysqli_fetch_assoc($r);
$transportasi = mysqli_query($connect, "SELECT * FROM transportasi ORDER BY jenis ASC");
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
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
</style>

<div class="content-wrapper">
  <div class="page-title">Edit Data Rute</div>
  <div class="breadcrumb">Dashboard / Rute / Edit</div>

  <div class="card card-custom">
    <div class="card-header-custom">Form Edit Rute</div>
    <div class="card-body">
      <form action="../../actions/rute/update.php" method="POST">
        <input type="hidden" name="id_rute" value="<?= $data['id_rute']; ?>">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Jenis Transportasi</label>
            <select class="form-select" name="id_transportasi" required>
              <option value="">-- Pilih Transportasi --</option>
              <?php while ($t = mysqli_fetch_assoc($transportasi)): ?>
                <option value="<?= $t['id_transportasi']; ?>" <?= $data['id_transportasi'] == $t['id_transportasi'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($t['jenis']); ?> - <?= htmlspecialchars($t['nama_transportasi']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Harga Tiket</label>
            <input type="number" class="form-control" name="harga" value="<?= $data['harga']; ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Asal Keberangkatan</label>
            <input type="text" class="form-control" name="asal" value="<?= htmlspecialchars($data['asal']); ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Tujuan</label>
            <input type="text" class="form-control" name="tujuan" value="<?= htmlspecialchars($data['tujuan']); ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Jadwal Berangkat</label>
            <input type="datetime-local" class="form-control" name="jadwal_berangkat" value="<?= date('Y-m-d\TH:i', strtotime($data['jadwal_berangkat'])); ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Jadwal Tiba</label>
            <input type="datetime-local" class="form-control" name="jadwal_tiba" value="<?= date('Y-m-d\TH:i', strtotime($data['jadwal_tiba'])); ?>" required>
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
