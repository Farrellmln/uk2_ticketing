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
$q = "SELECT rute.*, transportasi.nama_transportasi, transportasi.jenis
      FROM rute
      LEFT JOIN transportasi ON rute.id_transportasi = transportasi.id_transportasi
      WHERE id_rute = '$id'";
$result = mysqli_query($connect, $q);

if (mysqli_num_rows($result) == 0) {
  echo "
    <script>
      alert('Data rute tidak ditemukan!');
      window.location.href = 'index.php';
    </script>
  ";
  exit;
}

$data = mysqli_fetch_assoc($result);

function formatRupiah($angka) {
  return 'Rp ' . number_format($angka, 0, ',', '.');
}

function formatTanggal($tanggal) {
  return date('d M Y, H:i', strtotime($tanggal));
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
  <div class="page-title">Detail Rute</div>
  <div class="breadcrumb">Dashboard / Rute / Detail</div>

  <div class="card card-custom">
    <div class="card-header-custom">Informasi Rute</div>

    <div class="card-body">
      <div class="detail-container">
        <div>
          <div class="detail-label"><i class="bi bi-train-front"></i> Nama Transportasi</div>
          <div class="detail-value"><?= htmlspecialchars($data['nama_transportasi']); ?></div>
          <hr>
        </div>

        <div>
          <div class="detail-label"><i class="bi bi-tag-fill"></i> Jenis Transportasi</div>
          <div class="detail-value">
            <?php if ($data['jenis'] == 'Pesawat'): ?>
              <span class="badge-jenis badge-pesawat">Pesawat</span>
            <?php else: ?>
              <span class="badge-jenis badge-kereta">Kereta</span>
            <?php endif; ?>
          </div>
          <hr>
        </div>

        <div>
          <div class="detail-label"><i class="bi bi-geo-alt-fill"></i> Asal Keberangkatan</div>
          <div class="detail-value"><?= htmlspecialchars($data['asal']); ?></div>
          <hr>
        </div>

        <div>
          <div class="detail-label"><i class="bi bi-geo-fill"></i> Tujuan</div>
          <div class="detail-value"><?= htmlspecialchars($data['tujuan']); ?></div>
          <hr>
        </div>

        <div>
          <div class="detail-label"><i class="bi bi-cash-stack"></i> Harga Tiket</div>
          <div class="detail-value"><?= formatRupiah($data['harga']); ?></div>
          <hr>
        </div>

        <div>
          <div class="detail-label"><i class="bi bi-clock"></i> Jadwal Berangkat</div>
          <div class="detail-value"><?= formatTanggal($data['jadwal_berangkat']); ?></div>
          <hr>
        </div>

        <div>
          <div class="detail-label"><i class="bi bi-clock-history"></i> Jadwal Tiba</div>
          <div class="detail-value"><?= formatTanggal($data['jadwal_tiba']); ?></div>
          <hr>
        </div>

        <div style="grid-column: span 2;">
          <div class="detail-label"><i class="bi bi-info-circle"></i> Keterangan</div>
          <div class="detail-value">
            Rute ini merupakan perjalanan dari <b><?= htmlspecialchars($data['asal']); ?></b> menuju <b><?= htmlspecialchars($data['tujuan']); ?></b> menggunakan <b><?= htmlspecialchars($data['nama_transportasi']); ?></b>.
          </div>
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
