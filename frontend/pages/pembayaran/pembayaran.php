<?php
include '../../app.php';
include '../../partials/header.php';

// Ambil id pemesanan
$id_pemesanan = intval($_GET['id_pemesanan'] ?? 0);

// Ambil data pesanan + user + rute
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

$res  = mysqli_query($connect, $q);
$data = mysqli_fetch_assoc($res);

if (!$data) {
  echo "<script>alert('Data pemesanan tidak ditemukan!'); history.back();</script>";
  exit;
}

// ================= Cek pembayaran terbaru =================
$pay = mysqli_query($connect, "
  SELECT pb.*, pr.nama_promo, pr.potongan
  FROM pembayaran pb
  LEFT JOIN promo pr ON pb.id_promo = pr.id_promo
  WHERE pb.id_pemesanan = $id_pemesanan
  ORDER BY pb.id_pembayaran DESC
  LIMIT 1
");
$pembayaran = mysqli_fetch_assoc($pay) ?: null;

// ================= Cek status verifikasi terakhir =================
$cek_verif = mysqli_query($connect, "
    SELECT status_verifikasi
    FROM verifikasi
    WHERE id_pembayaran = " . intval($pembayaran['id_pembayaran'] ?? 0) . "
    ORDER BY id_verifikasi DESC LIMIT 1
");
$verifikasi   = mysqli_fetch_assoc($cek_verif) ?: null;
$status_verif = $verifikasi['status_verifikasi'] ?? null;

// Status pembayaran
$status = $pembayaran['status_bayar'] ?? "Belum Bayar";

// Ambil metode pembayaran
$bank    = mysqli_query($connect, "SELECT * FROM metode_pembayaran WHERE jenis = 'Bank'");
$ewallet = mysqli_query($connect, "SELECT * FROM metode_pembayaran WHERE jenis = 'E-Wallet'");
$cash    = mysqli_query($connect, "SELECT * FROM metode_pembayaran WHERE jenis = 'Cash'");

// ===================== PROMO AKTIF =====================
$promoList = [];
$promoRes = mysqli_query($connect, "
    SELECT *
    FROM promo
    WHERE status = 'aktif'
      AND tanggal_mulai <= NOW()
      AND tanggal_selesai >= NOW()
    ORDER BY tanggal_mulai ASC
");
while ($rowPromo = mysqli_fetch_assoc($promoRes)) {
  $promoList[] = $rowPromo;
}

// total asli dari pemesanan
$total_asli = (int)$data['total_harga'];

// ==== promo & total berdasarkan pembayaran terakhir (kalau ada) ====
$selectedPromoId = 0;
$potongan_promo  = 0;

if ($pembayaran) {
  $selectedPromoId = (int)($pembayaran['id_promo'] ?? 0);
  $potongan_promo  = (int)($pembayaran['potongan'] ?? 0);
}

if ($potongan_promo > 0) {
  $final_total = $total_asli - $potongan_promo;
  if ($final_total < 0) $final_total = 0;
} else {
  $final_total = $total_asli;
}
?>

<main class="py-4" style="background: #f9fbff; min-height: calc(100vh - 120px);">
  <div class="container">

    <!-- Tombol kembali -->
    <div class="align-self-start mb-3">
      <button onclick="history.back()" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
        <i class="bi bi-arrow-left me-2"></i> Kembali
      </button>
    </div>

    <!-- STEP -->
    <div class="d-flex justify-content-center mb-4 align-items-center gap-3">

      <div class="d-flex flex-column align-items-center">
        <span class="step-circle active-step">1</span>
        <span class="step-label">Pemesanan</span>
      </div>

      <div class="step-line"></div>

      <div class="d-flex flex-column align-items-center">
        <span class="step-circle active-step">2</span>
        <span class="step-label">Pembayaran</span>
      </div>

      <div class="step-line"></div>

      <div class="d-flex flex-column align-items-center">
        <?php $step3 = ($status == "Validasi") ? "active-step" : ""; ?>
        <span class="step-circle <?= $step3 ?>">3</span>
        <span class="step-label">Selesai</span>
      </div>

    </div>

    <div class="row g-4">

      <!-- =================== RINGKASAN PESANAN =================== -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <h5 class="fw-bold text-primary mb-3">Ringkasan Pesanan</h5>

          <p class="mb-1">
            <strong><?= htmlspecialchars($data['asal']); ?></strong>
            →
            <strong><?= htmlspecialchars($data['tujuan']); ?></strong>
          </p>
          <p class="text-muted mb-1">
            <?= date("d M Y", strtotime($data['jadwal_berangkat'])); ?>
          </p>
          <p class="text-muted mb-1">
            <?= htmlspecialchars($data['nama_transportasi']); ?> (<?= htmlspecialchars($data['jenis']); ?>)
          </p>

          <hr>

          <p class="text-muted mb-1">Jumlah Tiket</p>
          <p class="fw-semibold mb-3"><?= (int)$data['jumlah_tiket']; ?></p>

          <p class="text-muted mb-1">Kursi</p>
          <p class="fw-semibold mb-3"><?= $data['kursi'] ?: '-'; ?></p>

          <p class="text-muted mb-1">Subtotal</p>
          <p class="fw-semibold mb-2" id="subtotalText">
            Rp <?= number_format($total_asli, 0, ',', '.'); ?>
          </p>

          <p class="text-muted mb-1">Promo</p>
          <p class="fw-semibold mb-2 text-danger" id="promoText">
            - Rp <?= number_format($potongan_promo, 0, ',', '.'); ?>
          </p>

          <p class="text-muted mb-1">Total Pembayaran</p>
          <div class="d-flex flex-column align-items-start">
            <?php if ($potongan_promo > 0): ?>
              <span class="text-danger small text-decoration-line-through" id="originalTotalText">
                Rp <?= number_format($total_asli, 0, ',', '.'); ?>
              </span>
            <?php else: ?>
              <span class="text-danger small text-decoration-line-through d-none" id="originalTotalText">
                Rp <?= number_format($total_asli, 0, ',', '.'); ?>
              </span>
            <?php endif; ?>

            <h4 class="text-success fw-bold mb-0" id="finalTotalText">
              Rp <?= number_format($final_total, 0, ',', '.'); ?>
            </h4>
          </div>

        </div>
      </div>

      <!-- =================== FORM PEMBAYARAN + PROMO =================== -->
      <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4 p-4">

          <h4 class="fw-bold text-primary mb-4">Metode Pembayaran</h4>

          <?php
          // PROMO HANYA MUNCUL SAAT: Belum Bayar / Tidak Valid / Diganti
          $bolehPromo = (
            $status == "Belum Bayar" ||
            $status_verif == "Tidak Valid" ||
            $status == "Diganti"
          );
          ?>

          <?php if ($bolehPromo): ?>

            <?php if (count($promoList) > 0): ?>
              <div class="alert alert-warning mb-4">
                <strong>Promo Tersedia!</strong><br>
                Pilih salah satu promo di bawah untuk mendapatkan potongan harga.
              </div>

              <div class="mb-4">
                <label class="fw-semibold mb-2">Pilih Promo</label>
                <select name="id_promo" id="id_promo_select" class="form-select form-select-lg">
                  <option value="">-- Tidak pakai promo --</option>
                  <?php foreach ($promoList as $p): ?>
                    <option
                      value="<?= (int)$p['id_promo']; ?>"
                      data-potongan="<?= (int)$p['potongan']; ?>"
                      <?= $selectedPromoId == (int)$p['id_promo'] ? 'selected' : '' ?>
                    >
                      <?= htmlspecialchars($p['nama_promo']); ?>
                      (Potongan Rp <?= number_format($p['potongan'], 0, ',', '.'); ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

            <?php endif; ?>

          <?php endif; ?>

          <!-- STATUS NOTIF -->
          <?php if ($status_verif == "Tidak Valid"): ?>
            <div class="alert alert-danger fw-semibold">
              Status: Tidak Valid — Silakan unggah ulang bukti pembayaran.
            </div>
          <?php elseif ($status == "Validasi"): ?>
            <div class="alert alert-success fw-semibold">
              Status: Pembayaran Valid ✔
            </div>
            <div class="text-center mt-4 mb-4">
              <h5 class="text-success fw-bold">Pembayaran sudah divalidasi 🎉</h5>
              <p class="text-muted">Silakan hadir sesuai jadwal keberangkatan.</p>

              <a href="cetak_tiket.php?id_pemesanan=<?= $id_pemesanan; ?>"
                 class="btn btn-primary px-5 py-3 rounded-3 fw-semibold mt-3"
                 target="_blank">
                <i class="bi bi-printer-fill me-2"></i> Cetak Tiket
              </a>
            </div>

          <?php elseif ($status == "Menunggu Verifikasi"): ?>
            <div class="alert alert-info fw-semibold">Status: Menunggu Verifikasi Admin</div>
          <?php elseif ($status == "Belum Bayar"): ?>
            <div class="alert alert-secondary fw-semibold">Status: Belum Bayar</div>
          <?php elseif ($status == "Diganti"): ?>
            <div class="alert alert-warning fw-semibold">Status: Pembayaran Sebelumnya Diganti</div>
          <?php endif; ?>

          <!-- FORM KIRIM / KIRIM ULANG -->
          <?php if ($status == "Belum Bayar" || $status_verif == "Tidak Valid" || $status == "Diganti"): ?>

            <?php
            $action_file = ($status_verif == "Tidak Valid" || $status == "Diganti")
              ? "pembayaran_proses_ulang.php"
              : "pembayaran_proses.php";

            $button_text = ($status_verif == "Tidak Valid" || $status == "Diganti")
              ? "Kirim Ulang Pembayaran"
              : "Kirim Pembayaran";
            ?>

            <form action="<?= $action_file; ?>" method="POST" enctype="multipart/form-data" id="formPembayaran">

              <input type="hidden" name="id_pemesanan" value="<?= $id_pemesanan; ?>">
              <input type="hidden" name="total_bayar" value="<?= $total_asli; ?>">

              <!-- total setelah promo (default = final_total yang sekarang) -->
              <input type="hidden" name="final_total_bayar" id="final_total_bayar" value="<?= $final_total; ?>">

              <!-- promo yang dipakai sekarang -->
              <input type="hidden" name="id_promo" id="id_promo_hidden" value="<?= $selectedPromoId ?: ''; ?>">

              <input type="hidden" name="id_pembayaran_lama" value="<?= $pembayaran['id_pembayaran'] ?? 0; ?>">

              <label class="fw-semibold mb-2">Pilih Jenis Pembayaran</label>
              <select name="jenis" id="jenis" class="form-select form-select-lg mb-3" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Bank">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet</option>
                <option value="Cash">Cash</option>
              </select>

              <div id="bank-section" class="mb-3" style="display:none;">
                <label class="fw-semibold">Pilih Bank</label>
                <select name="metode_bank" class="form-select form-select-lg">
                  <option value="">-- Pilih Bank --</option>
                  <?php mysqli_data_seek($bank, 0);
                  while ($b = mysqli_fetch_assoc($bank)): ?>
                    <option value="<?= $b['id_metode']; ?>"><?= $b['nama_metode']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div id="ewallet-section" class="mb-3" style="display:none;">
                <label class="fw-semibold">Pilih E-Wallet</label>
                <select name="metode_ewallet" class="form-select form-select-lg">
                  <option value="">-- Pilih E-Wallet --</option>
                  <?php mysqli_data_seek($ewallet, 0);
                  while ($e = mysqli_fetch_assoc($ewallet)): ?>
                    <option value="<?= $e['id_metode']; ?>"><?= $e['nama_metode']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div id="cash-section" class="mb-3" style="display:none;">
                <label class="fw-semibold">Pembayaran Cash</label>
                <select name="metode_cash" class="form-select form-select-lg">
                  <?php mysqli_data_seek($cash, 0);
                  while ($c = mysqli_fetch_assoc($cash)): ?>
                    <option value="<?= $c['id_metode']; ?>"><?= $c['nama_metode']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <label class="fw-semibold mb-2">Upload Bukti Pembayaran</label>
              <input type="file" name="bukti_transfer" class="form-control form-control-lg rounded-3 mb-4">

              <button class="btn btn-warning text-white px-5 py-3 rounded-3 fw-semibold">
                <?= $button_text; ?>
              </button>

            </form>

          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>
</main>

<script>
  // ======= TOGGLE METODE PEMBAYARAN =======
  const jenisSelect = document.getElementById('jenis');
  if (jenisSelect) {
    jenisSelect.addEventListener('change', function() {
      document.getElementById('bank-section').style.display   = this.value === 'Bank' ? 'block' : 'none';
      document.getElementById('ewallet-section').style.display = this.value === 'E-Wallet' ? 'block' : 'none';
      document.getElementById('cash-section').style.display    = this.value === 'Cash' ? 'block' : 'none';
    });
  }

  // ======= PROMO & HARGA CORET =======
  const baseTotal        = <?= $total_asli; ?>;
  const currentPotongan  = <?= $potongan_promo; ?>;
  const selectedPromoId  = <?= $selectedPromoId; ?>;

  const promoSelect      = document.getElementById('id_promo_select');
  const promoHidden      = document.getElementById('id_promo_hidden');
  const finalTotalInput  = document.getElementById('final_total_bayar');

  const promoText        = document.getElementById('promoText');
  const originalTotalTxt = document.getElementById('originalTotalText');
  const finalTotalTxt    = document.getElementById('finalTotalText');

  function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function applyPromoFromSelect() {
    if (!promoSelect) return;

    const selected = promoSelect.options[promoSelect.selectedIndex];
    const potongan = parseInt(selected.dataset.potongan || '0', 10);

    let potonganDipakai = potongan;
    if (potonganDipakai > baseTotal) potonganDipakai = baseTotal;

    const totalAkhir = baseTotal - potonganDipakai;

    // update teks promo
    if (potonganDipakai > 0) {
      promoText.textContent = '- ' + formatRupiah(potonganDipakai);
      originalTotalTxt.classList.remove('d-none');
    } else {
      promoText.textContent = '- Rp 0';
      originalTotalTxt.classList.add('d-none');
    }

    // update total bayar
    finalTotalTxt.textContent = formatRupiah(totalAkhir);

    // update hidden input untuk dikirim ke backend
    finalTotalInput.value = totalAkhir;
    promoHidden.value     = promoSelect.value || '';
  }

  if (promoSelect) {
    // Pastikan option yang sesuai promo lama terpilih (backup kalau HTML selected gagal)
    if (selectedPromoId) {
      for (let i = 0; i < promoSelect.options.length; i++) {
        if (parseInt(promoSelect.options[i].value) === selectedPromoId) {
          promoSelect.selectedIndex = i;
          break;
        }
      }
    }

    // jalankan sekali di awal supaya hidden input sinkron
    applyPromoFromSelect();

    // jalankan lagi setiap user ganti promo
    promoSelect.addEventListener('change', applyPromoFromSelect);
  }
</script>

<style>
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

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
