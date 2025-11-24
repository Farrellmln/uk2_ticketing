<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// ===== VALIDASI =====
if (!isset($_GET['id'])) {
  echo "
    <script>
      alert('ID promo tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$id = intval($_GET['id']);

$q = mysqli_query($connect, "SELECT * FROM promo WHERE id_promo = $id");

if (mysqli_num_rows($q) == 0) {
  echo "
    <script>
      alert('Data promo tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$data = mysqli_fetch_assoc($q);

function formatRupiah($angka) {
  return 'Rp ' . number_format($angka, 0, ',', '.');
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
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

  .badge-aktif {
    background-color: #28a745;
    padding: 6px 14px;
    border-radius: 10px;
    font-weight: 600;
    color: #fff;
  }

  .badge-nonaktif {
    background-color: #6c757d;
    padding: 6px 14px;
    border-radius: 10px;
    font-weight: 600;
    color: #fff;
  }

  @media (max-width: 768px) {
    .detail-container {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="content-wrapper">
  <div class="page-title">Detail Promo</div>
  <div class="breadcrumb">Dashboard / Promo / Detail</div>

  <div class="card card-custom">
    <div class="card-header-custom">Informasi Promo</div>

    <div class="card-body">
      <div class="detail-container">

        <!-- Nama Promo -->
        <div>
          <div class="detail-label"><i class="bi bi-ticket-perforated-fill"></i> Nama Promo</div>
          <div class="detail-value"><?= htmlspecialchars($data['nama_promo']); ?></div>
          <hr>
        </div>

        <!-- Potongan -->
        <div>
          <div class="detail-label"><i class="bi bi-cash-stack"></i> Potongan Harga</div>
          <div class="detail-value"><?= formatRupiah($data['potongan']); ?></div>
          <hr>
        </div>

        <!-- Tanggal Mulai -->
        <div>
          <div class="detail-label"><i class="bi bi-calendar-check-fill"></i> Tanggal Mulai</div>
          <div class="detail-value"><?= date('d M Y', strtotime($data['tanggal_mulai'])); ?></div>
          <hr>
        </div>

        <!-- Tanggal Selesai -->
        <div>
          <div class="detail-label"><i class="bi bi-calendar-x-fill"></i> Tanggal Selesai</div>
          <div class="detail-value"><?= date('d M Y', strtotime($data['tanggal_selesai'])); ?></div>
          <hr>
        </div>

        <!-- Status -->
        <div>
          <div class="detail-label"><i class="bi bi-flag-fill"></i> Status</div>
          <div class="detail-value">
            <?php if ($data['status'] == 'aktif'): ?>
              <span class="badge-aktif">Aktif</span>
            <?php else: ?>
              <span class="badge-nonaktif">Nonaktif</span>
            <?php endif; ?>
          </div>
          <hr>
        </div>

        <!-- Gambar Promo -->
        <div style="grid-column: span 2;">
          <div class="detail-label"><i class="bi bi-image"></i> Gambar Promo</div>

          <?php if (!empty($data['gambar'])): ?>
            <img src="../../../storages/promo/<?= $data['gambar']; ?>"
                 class="img-fluid rounded border"
                 style="max-height:350px; object-fit:contain;">
          <?php else: ?>
            <div class="detail-value text-muted">Tidak ada gambar promo.</div>
          <?php endif; ?>

          <hr>
        </div>

      </div>

      <div class="d-flex justify-content-end">
        <a href="index.php" class="btn-kembali">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
      </div>

    </div>
  </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
