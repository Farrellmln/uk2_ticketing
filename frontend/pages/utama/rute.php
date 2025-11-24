<?php
include '../../app.php';

// === QUERY REKOMENDASI PESAWAT ===
$query_pesawat = "
SELECT r.*, t.nama_transportasi, t.jenis 
FROM rute r 
JOIN transportasi t ON r.id_transportasi = t.id_transportasi 
WHERE t.jenis = 'pesawat'
ORDER BY r.jadwal_berangkat ASC 
LIMIT 3";
$res_pesawat = mysqli_query($connect, $query_pesawat);

// === QUERY REKOMENDASI KERETA ===
$query_kereta = "
SELECT r.*, t.nama_transportasi, t.jenis 
FROM rute r 
JOIN transportasi t ON r.id_transportasi = t.id_transportasi 
WHERE t.jenis = 'kereta'
ORDER BY r.jadwal_berangkat ASC 
LIMIT 3";
$res_kereta = mysqli_query($connect, $query_kereta);
?>

<!-- ========================================================== -->
<!-- SECTION CARI RUTE - STYLE PREMIUM MIRIP PROFIL -->
<section id="rute" class="py-5 section-bg">
  
  <!-- Glow Dekorasi -->
  <div class="glow-left"></div>
  <div class="glow-right"></div>

  <div class="container">

    <div class="text-center mb-5">
      <h4 class="fw-bold text-primary mb-1">Rute & Pemesanan</h4>
      <h2 class="fw-bold" style="color:#004c84;">Temukan Perjalananmu Sekarang</h2>
      <p class="text-muted mt-2">Cari tiket dari berbagai rute, jadwal, dan transportasi favoritmu</p>
    </div>

    <div class="card shadow-lg border-0 rute-card px-4 py-5">

      <form 
        class="row g-4 align-items-end justify-content-center"
        action="<?= isset($_SESSION['id_user']) ? 'rute-hasil.php' : 'javascript:alertLogin()' ?>"
        method="get">

        <div class="col-md-5 col-lg-3">
          <label class="form-label fw-semibold text-dark">Dari</label>
          <input type="text" name="asal" class="form-control form-control-lg" placeholder="Kota asal" required>
        </div>

        <div class="col-md-5 col-lg-3">
          <label class="form-label fw-semibold text-dark">Ke</label>
          <input type="text" name="tujuan" class="form-control form-control-lg" placeholder="Kota tujuan" required>
        </div>

        <div class="col-md-5 col-lg-3">
          <label class="form-label fw-semibold text-dark">Tanggal Berangkat</label>
          <input type="date" name="tanggal" class="form-control form-control-lg" required>
        </div>

        <div class="col-md-5 col-lg-2">
          <label class="form-label fw-semibold text-dark">Transportasi</label>
          <select name="jenis" class="form-select form-select-lg" required>
            <option value="">Pilih</option>
            <option value="pesawat">Pesawat</option>
            <option value="kereta">Kereta Api</option>
          </select>
        </div>

        <div class="col-12 text-center mt-4">
          <button type="submit" class="btn btn-warning text-white fw-semibold px-5 py-3 rounded-3 shadow">
            <i class="bi bi-search me-2"></i> Cari Tiket
          </button>
        </div>
      </form>

    </div>
  </div>
</section>

<!-- ========================================================== -->
<!-- SECTION REKOMENDASI TIKET (DIBERIKAN BACKGROUND SAMA) -->
<section id="rekomendasi" class="py-5 section-bg">

  <!-- Glow Sama Seperti Atas -->
  <div class="glow-left"></div>
  <div class="glow-right"></div>

  <div class="container">

    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Rekomendasi Tiket</h2>
      <p class="text-muted">Pilihan terbaik yang disesuaikan untuk kamu ✈️🚆</p>
    </div>

    <!-- ======== PESAWAT ======== -->
    <div class="mb-4">
      <h5 class="fw-bold text-warning"><i class="bi bi-airplane"></i> Tiket Pesawat</h5>
    </div>

    <div class="row g-4 mb-5">
      <?php if (mysqli_num_rows($res_pesawat) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($res_pesawat)): ?>
          <div class="col-md-4">
            <div class="card rekom-card h-100">
              <div class="card-body">

                <h5 class="fw-bold text-primary mb-2">
                  <?= htmlspecialchars($row['asal']); ?> → <?= htmlspecialchars($row['tujuan']); ?>
                </h5>

                <p class="text-muted mb-1">
                  <i class="bi bi-calendar-event me-2"></i><?= date("d M Y", strtotime($row['jadwal_berangkat'])); ?>
                </p>

                <p class="text-muted mb-1">
                  <i class="bi bi-airplane me-2"></i><?= ucfirst($row['nama_transportasi']); ?>
                </p>

                <p class="fw-semibold text-dark mb-3">Harga: Rp <?= number_format($row['harga'],0,',','.'); ?></p>

                <?php if (!isset($_SESSION['id_user'])): ?>
                  <a href="javascript:alertLogin()" class="btn btn-warning text-white w-100 fw-semibold rounded-3">Login untuk Memesan</a>
                <?php else: ?>
                  <a href="../pesan/index.php?id_rute=<?= $row['id_rute']; ?>" class="btn btn-warning text-white w-100 fw-semibold rounded-3">Pesan Sekarang</a>
                <?php endif; ?>

              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted">Belum ada tiket pesawat tersedia.</p>
      <?php endif; ?>
    </div>

    <!-- ======== KERETA ======== -->
    <div class="mb-4">
      <h5 class="fw-bold text-warning"><i class="bi bi-train-front"></i> Tiket Kereta Api</h5>
    </div>

    <div class="row g-4">
      <?php if (mysqli_num_rows($res_kereta) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($res_kereta)): ?>
          <div class="col-md-4">
            <div class="card rekom-card h-100">
              <div class="card-body">

                <h5 class="fw-bold text-primary mb-2">
                  <?= htmlspecialchars($row['asal']); ?> → <?= htmlspecialchars($row['tujuan']); ?>
                </h5>

                <p class="text-muted mb-1">
                  <i class="bi bi-calendar-event me-2"></i><?= date("d M Y", strtotime($row['jadwal_berangkat'])); ?>
                </p>

                <p class="text-muted mb-1">
                  <i class="bi bi-train-front me-2"></i><?= ucfirst($row['nama_transportasi']); ?>
                </p>

                <p class="fw-semibold text-dark mb-3">Harga: Rp <?= number_format($row['harga'],0,',','.'); ?></p>

                <?php if (!isset($_SESSION['id_user'])): ?>
                  <a href="javascript:alertLogin()" class="btn btn-warning text-white w-100 fw-semibold rounded-3">Login untuk Memesan</a>
                <?php else: ?>
                  <a href="../pesan/index.php?id_rute=<?= $row['id_rute']; ?>" class="btn btn-warning text-white w-100 fw-semibold rounded-3">Pesan Sekarang</a>
                <?php endif; ?>

              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted">Belum ada tiket kereta tersedia.</p>
      <?php endif; ?>
    </div>

  </div>
</section>

<script>
function alertLogin() {
    alert('Anda harus login terlebih dahulu!');
}
</script>

<!-- ========================================================== -->
<!-- STYLE PREMIUM -->
<style>
/* HALAMAN TIDAK BISA KEGESER */
html, body {
  width:100%;
  overflow-x:hidden !important;
}

/* Background Template Glow */
.section-bg {
  position:relative;
  overflow:hidden !important;
}

/* Glow Kiri & Kanan */
.glow-left, .glow-right {
  position:absolute;
  width:220px;
  height:220px;
  border-radius:50%;
  filter:blur(120px);
  opacity:.45;
  z-index:0;
}

.glow-left {
  background:#f9b233;
  top:0;
  left:0;
}

.glow-right {
  background:#004c84;
  bottom:0;
  right:0;
}

/* Card Pencarian */
.rute-card {
  border-radius:22px;
  background:#ffffffd9;
  backdrop-filter:blur(8px);
  z-index:2;
}

/* Style Input */
#rute .form-control, #rute .form-select {
  border:1.6px solid #dfe6ef;
  padding:12px 16px;
  border-radius:14px;
}

#rute .form-control:focus, #rute .form-select:focus {
  border-color:#f9b233;
  box-shadow:0 0 0 .2rem rgba(249,178,51,.3);
}

/* Tombol */
#rute .btn-warning {
  background:#f9b233;
}
#rute .btn-warning:hover {
  background:#e3a728;
}

/* Kartu Rekomendasi */
.rekom-card {
  border-radius:18px;
  border:1px solid #eef2f7;
  padding:22px;
  background:#ffffffd9;
  backdrop-filter:blur(6px);
  box-shadow:0 4px 14px rgba(0,0,0,0.05);
  transition:.25s ease;
}

.rekom-card:hover {
  transform:translateY(-6px);
  box-shadow:0 10px 22px rgba(0,0,0,.1);
}
</style>
