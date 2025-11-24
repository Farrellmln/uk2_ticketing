<?php
include '../../app.php';
include '../../partials/header.php';

// Ambil ID pemesanan
$id_pemesanan = isset($_GET['id_pemesanan']) ? intval($_GET['id_pemesanan']) : 0;

// Query detail lengkap
$q = "SELECT 
        p.*, 
        r.asal, r.tujuan, r.jadwal_berangkat, r.harga, 
        t.nama_transportasi, t.jenis,
        u.nama_lengkap, u.email, u.no_hp
      FROM pemesanan p
      JOIN rute r ON p.id_rute = r.id_rute
      JOIN transportasi t ON r.id_transportasi = t.id_transportasi
      JOIN user u ON p.id_user = u.id_user
      WHERE p.id_pemesanan = $id_pemesanan";

$res = mysqli_query($connect, $q);
$data = mysqli_fetch_assoc($res);
?>

<main class="py-4" style="background:#f9fbff; min-height:calc(100vh - 120px);">
  <div class="container">

    <!-- Tombol kembali -->
    <div class="align-self-start mb-3">
      <button onclick="history.back()"
        class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
        <i class="bi bi-arrow-left me-2"></i> Kembali
      </button>
    </div>

    <!-- STEP (SAMA PERSIS DENGAN PEMBAYARAN) -->
    <div class="d-flex justify-content-center mb-4 align-items-center gap-3">

      <div class="d-flex flex-column align-items-center">
        <span class="step-circle active-step">1</span>
        <span class="step-label">Pemesanan</span>
      </div>

      <div class="step-line"></div>

      <div class="d-flex flex-column align-items-center">
        <span class="step-circle">2</span>
        <span class="step-label">Pembayaran</span>
      </div>

      <div class="step-line"></div>

      <div class="d-flex flex-column align-items-center">
        <span class="step-circle">3</span>
        <span class="step-label">Selesai</span>
      </div>

    </div>


    <?php if ($data): ?>

      <div class="card-custom">

        <h3 class="text-primary fw-bold mb-4 text-center">Detail Pemesanan Tiket</h3>

        <!-- Info Rute -->
        <div class="mb-4 text-center">
          <h5 class="fw-semibold text-dark mb-1">
            <?= htmlspecialchars($data['asal']); ?> → <?= htmlspecialchars($data['tujuan']); ?>
          </h5>
          <p class="text-muted">
            <i class="bi bi-calendar-event"></i>
            <?= date("d M Y", strtotime($data['jadwal_berangkat'])); ?> |
            <?= $data['nama_transportasi']; ?> (<?= $data['jenis']; ?>)
          </p>

          <p class="fw-semibold text-warning mb-0">
            Harga per Tiket: Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
          </p>
        </div>

        <!-- STATUS -->
        <?php
          $status = $data['status_pemesanan'];
          $badge = "secondary";
          if ($status == "Menunggu Pembayaran") $badge = "warning";
          if ($status == "Menunggu Verifikasi") $badge = "info";
          if ($status == "Diverifikasi") $badge = "primary";
          if ($status == "Selesai") $badge = "success";
          if ($status == "Dibatalkan") $badge = "danger";
        ?>

        <div class="text-center mb-4">
          <span class="badge bg-<?= $badge ?> px-4 py-2" style="font-size:1rem;">
            <?= $status ?>
          </span>
        </div>

        <!-- Detail Pemesan -->
        <div class="row g-3">

          <div class="col-md-6">
            <p class="text-muted mb-1">Nama Pemesan</p>
            <h6 class="fw-semibold"><?= $data['nama_lengkap']; ?></h6>
          </div>

          <div class="col-md-6">
            <p class="text-muted mb-1">Email</p>
            <h6 class="fw-semibold"><?= $data['email']; ?></h6>
          </div>

          <div class="col-md-6">
            <p class="text-muted mb-1">Telepon</p>
            <h6 class="fw-semibold"><?= $data['no_hp']; ?></h6>
          </div>

          <div class="col-md-6">
            <p class="text-muted mb-1">Jumlah Tiket</p>
            <h6 class="fw-semibold"><?= $data['jumlah_tiket']; ?></h6>
          </div>

          <!-- Kursi -->
          <div class="col-md-12 mt-2">
            <p class="text-muted mb-1">Kursi</p>
            <div class="kursi-box fw-semibold">
              <?= $data['kursi'] ? $data['kursi'] : '-' ?>
            </div>
          </div>

        </div>

        <!-- Tombol -->
        <div class="text-center mt-4">
          <?php if ($status != "Dibatalkan"): ?>
            <a href="../pembayaran/pembayaran.php?id_pemesanan=<?= $data['id_pemesanan']; ?>"
              class="btn btn-warning text-white px-5 py-3 fw-semibold rounded-3 shadow-sm">
              <i class="bi bi-credit-card me-2"></i> Lanjut ke Pembayaran
            </a>
          <?php else: ?>
            <div class="alert alert-danger fw-semibold">
              Pesanan ini telah dibatalkan.
            </div>
          <?php endif; ?>
        </div>

      </div>

    <?php else: ?>
      <div class="alert alert-danger text-center">
        Data pemesanan tidak ditemukan.
      </div>
    <?php endif; ?>

  </div>
</main>

<?php include '../../partials/footer.php'; ?>
<?php include '../../partials/script.php'; ?>


<!-- STYLE SAMA PERSIS DENGAN PEMBAYARAN -->
<style>
  .card-custom {
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    max-width: 820px;
    margin: auto;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  }

  .kursi-box {
    background: #f5f7ff;
    padding: 12px 18px;
    border-radius: 12px;
    border: 1px solid #e0e6ed;
    font-size: 15px;
    display: inline-block;
  }

  /* STEP PEMBAYARAN — COPY EXACT */
  .step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #dcdcdc;
    color: #666;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 700;
    font-size: 16px;
  }

  .active-step {
    background: #f9b233 !important;
    color: white !important;
  }

  .step-label {
    font-size: 14px;
    margin-top: 6px;
    font-weight: 600;
    color: #444;
  }

  .step-line {
    width: 80px;
    height: 3px;
    background: #e0e0e0;
    border-radius: 5px;
  }
</style>
