<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

if (!isset($_GET['id'])) {
  echo "
    <script>
      alert('ID transportasi tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$id = $_GET['id'];
$q = "SELECT * FROM transportasi WHERE id_transportasi = '$id'";
$result = mysqli_query($connect, $q);

if (mysqli_num_rows($result) == 0) {
  echo "
    <script>
      alert('Data transportasi tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$data = mysqli_fetch_assoc($result);
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
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    background-color: #ffffff;
  }

  .card-header-custom {
    background-color: #ffb703;
    color: #ffffff;
    padding: 18px 24px;
    font-weight: 600;
  }

  .card-body {
    padding: 35px 40px 30px 40px;
  }

  .detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px 60px;
    margin-bottom: 35px;
  }

  .detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  .detail-label {
    font-weight: 600;
    color: #333;
    font-size: 15px;
  }

  .detail-value {
    color: #444;
    font-size: 15px;
  }

  .btn-kembali {
    background-color: #ffb703;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 22px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
  }

  .btn-kembali:hover {
    background-color: #f29c02;
    color: #fff;
  }

  .badge-jenis {
    border-radius: 20px;
    padding: 6px 14px;
    font-weight: 500;
    color: #fff;
    display: inline-block;
    text-align: center;
    min-width: 80px;
  }

  .badge-pesawat {
    background-color: #1976d2;
  }

  .badge-kereta {
    background-color: #8e24aa;
  }

  @media (max-width: 768px) {
    .detail-container {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="content-wrapper">
  <div class="page-title">Detail Transportasi</div>
  <div class="breadcrumb">Dashboard / Transportasi / Detail</div>

  <div class="card card-custom">
    <div class="card-header-custom">Informasi Transportasi</div>

    <div class="card-body">
      <div class="detail-container">
        <div class="detail-item">
          <span class="detail-label">Nama Transportasi</span>
          <span class="detail-value"><?= htmlspecialchars($data['nama_transportasi']); ?></span>
        </div>

        <div class="detail-item">
          <span class="detail-label">Jenis</span>
          <span class="detail-value">
            <?php if ($data['jenis'] == 'Pesawat'): ?>
              <span class="badge-jenis badge-pesawat">Pesawat</span>
            <?php else: ?>
              <span class="badge-jenis badge-kereta">Kereta</span>
            <?php endif; ?>
          </span>
        </div>

        <div class="detail-item">
          <span class="detail-label">Kode Transportasi</span>
          <span class="detail-value"><?= htmlspecialchars($data['kode']); ?></span>
        </div>

        <div class="detail-item">
          <span class="detail-label">Kapasitas</span>
          <span class="detail-value"><?= htmlspecialchars($data['kapasitas']); ?> Kursi</span>
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
