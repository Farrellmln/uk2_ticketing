<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// =================== VALIDASI ID ===================
if (!isset($_GET['id'])) {
  echo "<script>alert('ID pemesanan tidak ditemukan!'); window.location.href='index.php';</script>";
  exit;
}

$id = intval($_GET['id']);

// =================== QUERY DATA PEMESANAN ===================
$qPesanan = "
  SELECT 
    p.*, 
    u.nama_lengkap, u.email, u.no_hp,
    r.asal, r.tujuan, r.harga,
    t.nama_transportasi, t.jenis
  FROM pemesanan p
  LEFT JOIN user u ON p.id_user = u.id_user
  LEFT JOIN rute r ON p.id_rute = r.id_rute
  LEFT JOIN transportasi t ON r.id_transportasi = t.id_transportasi
  WHERE p.id_pemesanan = $id
";

$resPesanan = mysqli_query($connect, $qPesanan);

if (mysqli_num_rows($resPesanan) == 0) {
  echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
  exit;
}

$data = mysqli_fetch_assoc($resPesanan);

// =================== PEMBAYARAN TERBARU ===================
$payBaru = null;
$verifBaru = null;
$payLama = null;

$qPayBaru = "
  SELECT 
    pb.*,
    m.jenis AS jenis_pembayaran,
    m.nama_metode,
    pr.nama_promo,
    pr.potongan
  FROM pembayaran pb
  LEFT JOIN metode_pembayaran m ON pb.id_metode = m.id_metode
  LEFT JOIN promo pr ON pb.id_promo = pr.id_promo
  WHERE pb.id_pemesanan = $id
  ORDER BY pb.id_pembayaran DESC
  LIMIT 1
";

$resPayBaru = mysqli_query($connect, $qPayBaru);

if (mysqli_num_rows($resPayBaru) > 0) {

  $payBaru = mysqli_fetch_assoc($resPayBaru);
  $idPayBaru = (int)$payBaru['id_pembayaran'];

  // ===================== VERIFIKASI TERBARU =====================
  $resVerifBaru = mysqli_query($connect, "
      SELECT *
      FROM verifikasi
      WHERE id_pembayaran = $idPayBaru
      ORDER BY id_verifikasi DESC
      LIMIT 1
  ");
  $verifBaru = mysqli_fetch_assoc($resVerifBaru) ?: null;

  // ===================== PEMBAYARAN LAMA (TIDAK VALID) =====================
  $qPayLama = "
    SELECT 
      pb.*,
      m.jenis AS jenis_pembayaran,
      m.nama_metode,
      pr.nama_promo,
      pr.potongan,
      v.status_verifikasi,
      v.catatan,
      v.created_at
    FROM pembayaran pb
    LEFT JOIN metode_pembayaran m ON pb.id_metode = m.id_metode
    LEFT JOIN promo pr ON pb.id_promo = pr.id_promo
    LEFT JOIN verifikasi v ON v.id_pembayaran = pb.id_pembayaran
    WHERE pb.id_pemesanan = $id
      AND pb.id_pembayaran < $idPayBaru
      AND v.status_verifikasi = 'Tidak Valid'
    ORDER BY pb.id_pembayaran DESC
    LIMIT 1
  ";

  $resPayLama = mysqli_query($connect, $qPayLama);
  $payLama = mysqli_fetch_assoc($resPayLama) ?: null;
}

// =================== HELPER ===================
function formatRupiah($angka) {
  if ($angka === null || $angka === "") return "-";
  return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function formatTanggal($tgl) {
  if (!$tgl) return "-";
  return date('d M Y, H:i', strtotime($tgl));
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
    font-size: 1.9rem; font-weight: 600; color: #ffb703; margin-bottom: 8px;
  }
  .breadcrumb { color: #bdbdbd; font-size: 0.95rem; margin-bottom: 25px; }

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
  .card-body { padding: 40px 45px; }

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
  .detail-value { color: #333; font-size: 15px; }
  hr { border-top: 1px solid #eee; margin: 10px 0 0 0; }

  .btn-kembali {
    background-color: #ffb703;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    text-decoration: none;
  }

  .badge-status {
    padding: 6px 14px;
    border-radius: 8px;
    color: #fff;
    font-size: 0.9rem;
  }
  .bg-valid { background:#4caf50; }
  .bg-danger { background:#d32f2f; }
  .bg-menunggu { background:#ff9800; }
  .bg-blue { background:#2196f3; }
  .bg-cancel { background:#6c757d; }
  .bg-diganti { background:#9e9e9e; }

  .bukti-title-small { font-size: 13px; font-weight: 600; color: #888; }
</style>

<div class="content-wrapper">
  <div class="page-title">Detail Pemesanan</div>
  <div class="breadcrumb">Dashboard / Verifikasi / Detail</div>

  <div class="card card-custom">
    <div class="card-header-custom">Informasi Pemesanan</div>

    <div class="card-body">

      <div class="detail-container">

        <!-- Nama Penumpang -->
        <div>
          <div class="detail-label"><i class="bi bi-person-fill"></i> Nama Penumpang</div>
          <div class="detail-value"><?= $data['nama_lengkap']; ?></div>
          <hr>
        </div>

        <!-- Email -->
        <div>
          <div class="detail-label"><i class="bi bi-envelope-fill"></i> Email</div>
          <div class="detail-value"><?= $data['email']; ?></div>
          <hr>
        </div>

        <!-- No HP -->
        <div>
          <div class="detail-label"><i class="bi bi-telephone-fill"></i> No HP</div>
          <div class="detail-value"><?= $data['no_hp']; ?></div>
          <hr>
        </div>

        <!-- Transportasi -->
        <div>
          <div class="detail-label"><i class="bi bi-truck-front-fill"></i> Transportasi</div>
          <div class="detail-value"><?= $data['jenis'] . " - " . $data['nama_transportasi']; ?></div>
          <hr>
        </div>

        <!-- Rute -->
        <div>
          <div class="detail-label"><i class="bi bi-geo-alt-fill"></i> Rute</div>
          <div class="detail-value"><?= $data['asal']; ?> → <?= $data['tujuan']; ?></div>
          <hr>
        </div>

        <!-- Harga -->
        <div>
          <div class="detail-label"><i class="bi bi-cash"></i> Harga</div>
          <div class="detail-value"><?= formatRupiah($data['harga']); ?></div>
          <hr>
        </div>

        <!-- Jumlah Tiket -->
        <div>
          <div class="detail-label"><i class="bi bi-people-fill"></i> Jumlah Tiket</div>
          <div class="detail-value"><?= $data['jumlah_tiket']; ?></div>
          <hr>
        </div>

        <!-- Kursi -->
        <div>
          <div class="detail-label"><i class="bi bi-grid-1x2-fill"></i> Kursi</div>
          <div class="detail-value"><?= $data['kursi'] ?: '-' ?></div>
          <hr>
        </div>

        <!-- Promo -->
        <div>
          <div class="detail-label"><i class="bi bi-ticket-perforated-fill"></i> Promo</div>
          <div class="detail-value">
            <?php if ($payBaru && $payBaru['id_promo']): ?>
              <strong class="text-success"><?= $payBaru['nama_promo']; ?></strong><br>
              <small class="text-muted">Potongan: <?= formatRupiah($payBaru['potongan']); ?></small>
            <?php else: ?>
              <span class="text-muted">Tidak memakai promo</span>
            <?php endif; ?>
          </div>
          <hr>
        </div>

        <!-- Total Setelah Promo -->
        <div>
          <div class="detail-label"><i class="bi bi-cash-stack"></i> Total Setelah Promo</div>
          <div class="detail-value fw-semibold text-primary">
            <?php
              if ($payBaru) {
                $final = $payBaru['final_total_bayar'] ?? $payBaru['total_bayar'];
                echo formatRupiah($final);
              } else echo "-";
            ?>
          </div>
          <hr>
        </div>

        <!-- Total Bayar -->
        <div>
          <div class="detail-label"><i class="bi bi-wallet2"></i> Total Bayar</div>
          <div class="detail-value"><?= formatRupiah($data['total_harga']); ?></div>
          <hr>
        </div>

        <!-- Metode Pembayaran -->
        <div>
          <div class="detail-label"><i class="bi bi-credit-card-2-front-fill"></i> Metode Pembayaran</div>
          <div class="detail-value">
            <?= $payBaru ? ($payBaru['jenis_pembayaran'] . " - " . $payBaru['nama_metode']) : "-" ?>
          </div>
          <hr>
        </div>

        <!-- Status Pembayaran -->
        <div>
          <div class="detail-label"><i class="bi bi-credit-card-fill"></i> Status Pembayaran</div>
          <div class="detail-value">
            <?php
              $sb = $payBaru['status_bayar'] ?? null;

              if ($sb == 'Validasi') echo '<span class="badge-status bg-valid">Validasi</span>';
              elseif ($sb == 'Menunggu Verifikasi') echo '<span class="badge-status bg-menunggu">Menunggu Verifikasi</span>';
              elseif ($sb == 'Tidak Valid') echo '<span class="badge-status bg-danger">Tidak Valid</span>';
              else echo '<span class="badge-status bg-blue">Belum Bayar</span>';
            ?>
          </div>
          <hr>
        </div>

        <!-- Status Verifikasi -->
        <div>
          <div class="detail-label"><i class="bi bi-shield-check"></i> Status Verifikasi</div>
          <div class="detail-value">
            <?php 
              if ($verifBaru && !empty($verifBaru['status_verifikasi'])) {
                $cls = ($verifBaru['status_verifikasi']=='Valid') ? 'bg-valid' : 'bg-danger';
                echo '<span class="badge-status '.$cls.'">'.$verifBaru['status_verifikasi'].'</span>';
              } else {
                echo '<span class="badge-status bg-blue">Belum Diverifikasi</span>';
              }
            ?>
          </div>
          <hr>
        </div>

        <!-- Catatan -->
        <div style="grid-column: span 2;">
          <div class="detail-label"><i class="bi bi-journal-text"></i> Catatan</div>
          <div class="detail-value"><?= $verifBaru['catatan'] ?? 'Tidak ada catatan'; ?></div>
          <hr>
        </div>

        <!-- Bukti Transfer -->
        <div style="grid-column: span 2;">
          <div class="detail-label"><i class="bi bi-image"></i> Bukti Transfer</div>

          <?php if ($payBaru && $payBaru['bukti_transfer']): ?>

            <?php if ($payLama && $payLama['bukti_transfer']): ?>
              <div class="row g-4">

                <!-- Bukti Lama -->
                <div class="col-md-6">
                  <div class="bukti-title-small">Bukti Lama (Tidak Valid / Diganti)</div>
                  <img src="../../../storages/bukti_pembayaran/<?= $payLama['bukti_transfer']; ?>"
                       class="img-fluid rounded border" style="max-height:280px; object-fit:contain; width:100%;">
                  <div class="mt-2 text-muted" style="font-size:13px;">
                    Status: <span class="badge-status bg-danger">Tidak Valid</span><br>
                    <?= $payLama['catatan'] ? 'Catatan: '.$payLama['catatan'].'<br>' : '' ?>
                    <?php if ($payLama && !empty($payLama['created_at'])): ?>
                      Diverifikasi: <?= formatTanggal($payLama['created_at']); ?>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Bukti Baru -->
                <div class="col-md-6">
                  <div class="bukti-title-small">Bukti Terbaru</div>
                  <img src="../../../storages/bukti_pembayaran/<?= $payBaru['bukti_transfer']; ?>"
                       class="img-fluid rounded border" style="max-height:280px; object-fit:contain; width:100%;">
                  <div class="mt-2 text-muted" style="font-size:13px;">
                    Status:
                    <?php if ($verifBaru && !empty($verifBaru['status_verifikasi'])): ?>
                      <?php $cls = ($verifBaru['status_verifikasi']=='Valid') ? 'bg-valid' : 'bg-danger'; ?>
                      <span class="badge-status <?= $cls ?>"><?= $verifBaru['status_verifikasi']; ?></span>
                    <?php else: ?>
                      <span class="badge-status bg-blue">Belum Diverifikasi</span>
                    <?php endif; ?>
                    <br>
                    <?php if ($verifBaru && !empty($verifBaru['created_at'])): ?>
                      Dicek: <?= formatTanggal($verifBaru['created_at']); ?>
                    <?php endif; ?>
                  </div>
                </div>

              </div>

            <?php else: ?>

              <img src="../../../storages/bukti_pembayaran/<?= $payBaru['bukti_transfer']; ?>"
                   class="img-fluid rounded border" style="max-height:280px; object-fit:contain; width:100%;">

            <?php endif; ?>

          <?php else: ?>

            <div class="detail-value text-muted">Belum ada bukti pembayaran.</div>

          <?php endif; ?>

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
