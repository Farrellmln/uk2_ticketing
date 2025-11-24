<?php
include '../../app.php';

// Ambil ID rute dari URL
$id_rute = isset($_GET['id_rute']) ? intval($_GET['id_rute']) : 0;

// Ambil data rute + transportasi (sekaligus kapasitas)
$q = "SELECT r.*, t.nama_transportasi, t.jenis, t.id_transportasi, t.kapasitas
      FROM rute r 
      JOIN transportasi t ON r.id_transportasi = t.id_transportasi 
      WHERE r.id_rute = $id_rute";

$res  = mysqli_query($connect, $q);
$rute = mysqli_fetch_assoc($res);
?>

<main id="form-pesan"
  style="padding-top: 60px; padding-bottom: 60px; background:#f9fbff;">

  <div class="container d-flex flex-column align-items-center">

    <!-- TOMBOL KEMBALI -->
    <div class="align-self-start mb-3">
      <button onclick="history.back()" 
        class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
        <i class="bi bi-arrow-left me-2"></i> Kembali
      </button>
    </div>

    <?php if ($rute): ?>
      <?php
        $idTransportasi = (int)$rute['id_transportasi'];
        $jenisTransport = $rute['jenis'];
        $kapasitas      = (int)$rute['kapasitas'];

        // Ambil status kursi dari DB
        $kursiStatus = [];
        $qKursi = mysqli_query(
          $connect,
          "SELECT nomor_kursi, status_kursi 
           FROM kursi 
           WHERE id_transportasi = $idTransportasi"
        );
        while ($k = mysqli_fetch_assoc($qKursi)) {
          $kursiStatus[$k['nomor_kursi']] = $k['status_kursi'];
        }

        // Tentukan layout
        if ($jenisTransport === 'Pesawat') {
          $rowsPesan = 30;
          $cols      = ['A','B','C','D','E','F','G','H','I','J']; // 3–4–3
        } else {
          // Kereta 2–2
          $cols      = ['A','B','C','D'];
          $rowsPesan = max(1, (int)ceil($kapasitas / 4));
        }
      ?>

      <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5"
        style="max-width: 850px; width: 100%;">

        <h3 class="text-primary fw-bold mb-4 text-center">Form Pemesanan Tiket</h3>

        <!-- Info Rute -->
        <div class="mb-4 text-center">
          <h5 class="fw-semibold text-dark mb-1">
            <?= htmlspecialchars($rute['asal']); ?> → <?= htmlspecialchars($rute['tujuan']); ?>
          </h5>

          <p class="text-muted mb-0">
            <i class="bi bi-calendar-event me-1"></i>
            <?= date("d M Y", strtotime($rute['jadwal_berangkat'])); ?> |
            <i class="bi bi-geo-alt me-1"></i>
            <?= ucfirst($rute['nama_transportasi']); ?> (<?= ucfirst($rute['jenis']); ?>)
          </p>

          <p class="fw-semibold text-warning mt-2">
            Harga: Rp <?= number_format($rute['harga'], 0, ',', '.'); ?>
          </p>

          <p class="text-muted small mt-2 mb-0">
            Jika pesan <strong>1 tiket</strong>, kamu bisa pilih kursi sendiri.  
            Jika pesan <strong>&gt; 1 tiket</strong>, kursi akan diatur otomatis.
          </p>
        </div>

        <!-- Form -->
        <form action="form_pesan_proses.php" method="post" id="formPemesanan" class="row g-3">

          <input type="hidden" name="id_rute" value="<?= $rute['id_rute']; ?>">
          <input type="hidden" name="harga" value="<?= $rute['harga']; ?>">
          <input type="hidden" name="id_transportasi" value="<?= $idTransportasi; ?>">
          <!-- kursi yang dipilih (kalau 1 tiket) -->
          <input type="hidden" name="kursi" id="inputKursi">

          <div class="col-md-12">
            <label class="form-label fw-semibold">Jumlah Tiket</label>
            <input type="number"
              name="jumlah_tiket"
              id="jumlahTiket"
              min="1"
              max="10"
              class="form-control form-control-lg rounded-3"
              required>
          </div>

          <!-- DENAH KURSI (hanya muncul kalau jumlah_tiket = 1) -->
          <div class="col-12 mt-3" id="seatWrapper" style="display:none;">
            <label class="form-label fw-semibold mb-2">Pilih Kursi</label>

            <!-- Legend -->
            <div class="d-flex align-items-center gap-3 mb-2 small">
              <span><span class="seat-box seat-free"></span> Kosong</span>
              <span><span class="seat-box seat-taken"></span> Terisi / Dipesan</span>
              <span><span class="seat-box seat-selected"></span> Kursi Pilihanmu</span>
            </div>

            <div class="seat-grid-wrapper">
              <?php for ($row = 1; $row <= $rowsPesan; $row++): ?>
                <div class="seat-row">
                  <span class="seat-row-label"><?= $row ?></span>

                  <?php foreach ($cols as $idx => $col): 
                      $kode  = $row . $col;
                      $st    = $kursiStatus[$kode] ?? 'kosong';
                      $taken = ($st === 'dipesan' || $st === 'terverifikasi');
                  ?>
                    <button type="button"
                      class="seat-btn <?= $taken ? 'seat-taken' : 'seat-free'; ?>"
                      data-seat="<?= $kode; ?>"
                      <?= $taken ? 'disabled' : ''; ?>>
                      <?= $col ?>
                    </button>

                    <?php
                      // Tambah aisle: pesawat 3–4–3 → setelah C dan setelah G
                      if ($jenisTransport === 'Pesawat' && in_array($idx, [2, 6])) {
                        echo '<span class="seat-aisle"></span>';
                      }
                      // Kereta 2–2 → setelah B
                      if ($jenisTransport !== 'Pesawat' && $idx == 1) {
                        echo '<span class="seat-aisle"></span>';
                      }
                    ?>
                  <?php endforeach; ?>
                </div>
              <?php endfor; ?>
            </div>
          </div>

          <div class="col-12 text-center mt-4">
            <button type="submit"
              class="btn btn-warning text-white px-5 py-3 fw-semibold rounded-3 shadow-sm">
              <i class="bi bi-check-circle me-2"></i> Pesan Sekarang
            </button>
          </div>

        </form>

      </div>

    <?php else: ?>
      <div class="alert alert-danger text-center">Data rute tidak ditemukan.</div>
    <?php endif; ?>

  </div>
</main>

<?php if (isset($_SESSION['id_user'])): ?>
<!-- ... (bagian "Pesanan Kamu Sebelumnya" punyamu tetap, tidak aku ubah) ... -->
<?php endif; ?>

<style>
  #form-pesan {
    background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%);
  }

  form .form-control {
    border: 1.5px solid #e0e6ed;
    transition: all 0.3s ease;
  }

  form .form-control:focus {
    border-color: #f9b233;
    box-shadow: 0 0 0 0.25rem rgba(249, 178, 51, 0.2);
  }

  .btn-warning {
    background-color: #f9b233;
  }

  /* ===== DENAH KURSI ===== */
  .seat-grid-wrapper {
    background:#f5f7ff;
    border-radius:16px;
    padding:16px 20px;
    max-height:420px;
    overflow-y:auto;
    border:1px solid #e0e6ed;
  }

  .seat-row {
    display:flex;
    align-items:center;
    margin-bottom:4px;
  }

  .seat-row-label {
    width:32px;
    text-align:right;
    font-size:12px;
    color:#888;
    margin-right:8px;
  }

  .seat-btn {
    width:32px;
    height:32px;
    border-radius:6px;
    border:1px solid #d0d7e2;
    font-size:12px;
    margin:2px;
    padding:0;
  }

  .seat-aisle {
    display:inline-block;
    width:16px;
  }

  .seat-free {
    background:#ffffff;
  }

  .seat-taken {
    background:#d0d7e2;
    border-color:#b5bdca;
    color:#777;
  }

  .seat-selected {
    background:#f9b233;
    border-color:#f29b1f;
    color:#fff;
  }

  .seat-btn:disabled {
    cursor:not-allowed;
  }

  .seat-box {
    display:inline-block;
    width:18px;
    height:12px;
    border-radius:3px;
    margin-right:4px;
    border:1px solid #d0d7e2;
  }
  .seat-box.seat-free { background:#fff; }
  .seat-box.seat-taken { background:#d0d7e2; }
  .seat-box.seat-selected { background:#f9b233; border-color:#f29b1f; }
</style>

<script>
// Elemen utama
const jumlahInput = document.getElementById('jumlahTiket');
const seatWrapper = document.getElementById('seatWrapper');
const kursiInput  = document.getElementById('inputKursi');
const form        = document.getElementById('formPemesanan');

// Toggle denah kursi
if (jumlahInput) {
  jumlahInput.addEventListener('input', () => {
    if (jumlahInput.value === '1') {
      seatWrapper.style.display = 'block';
    } else {
      seatWrapper.style.display = 'none';
      kursiInput.value = '';
      document.querySelectorAll('.seat-btn').forEach(btn => 
        btn.classList.remove('seat-selected')
      );
    }
  });
}

// Pilih kursi
document.querySelectorAll('.seat-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    if (jumlahInput.value !== '1') {
      alert('Denah kursi hanya untuk pemesanan 1 tiket.');
      return;
    }
    document.querySelectorAll('.seat-btn').forEach(b => 
      b.classList.remove('seat-selected')
    );
    btn.classList.add('seat-selected');
    kursiInput.value = btn.dataset.seat;
  });
});

// Validasi sebelum submit
form.addEventListener('submit', (e) => {
  if (jumlahInput.value === '1' && !kursiInput.value) {
    e.preventDefault();
    alert('Silakan pilih kursi terlebih dahulu.');
  }
});
</script>
