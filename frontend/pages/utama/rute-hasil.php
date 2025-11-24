<?php
include '../../partials/header.php';
include '../../app.php';
?>

<!-- ============================= -->
<!-- HASIL PENCARIAN TIKET -->
<!-- ============================= -->

<style>
  /* Background dibuat sama seperti halaman form_pesan */
  body {
      background: #f7f9fc !important; 
  }
</style>

<main id="hasil-rute" 
      style="padding-top: 0px; padding-bottom: 80px; min-height: 100vh;">
      <!-- ↑ padding-top: jarak aman dari navbar -->
      <!-- ↑ min-height: supaya footer selalu terlihat -->

  <div class="container">

    <!-- Tombol Kembali -->
    <div class="mb-4">
      <button onclick="history.back()" 
              class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
        <i class="bi bi-arrow-left me-2"></i> Kembali
      </button>
    </div>

    <div class="text-center mb-5">
      <h3 class="fw-bold text-primary">Hasil Pencarian Tiket</h3>
      <p class="text-muted">Berikut adalah tiket yang tersedia sesuai pencarianmu</p>
    </div>

    <?php
    // Ambil input
    $asal = $_GET['asal'] ?? '';
    $tujuan = $_GET['tujuan'] ?? '';
    $tanggal = $_GET['tanggal'] ?? '';
    $jenis = $_GET['jenis'] ?? '';

    if ($asal && $tujuan && $tanggal && $jenis) {

        $query = "
        SELECT r.*, t.nama_transportasi, t.jenis 
        FROM rute r 
        JOIN transportasi t ON r.id_transportasi = t.id_transportasi
        WHERE r.asal LIKE ? 
          AND r.tujuan LIKE ?
          AND DATE(r.jadwal_berangkat) = ?
          AND t.jenis LIKE ?
        ";

        $stmt = $connect->prepare($query);
        $asal_like = "%$asal%";
        $tujuan_like = "%$tujuan%";
        $jenis_like = "%$jenis%";
        $stmt->bind_param("ssss", $asal_like, $tujuan_like, $tanggal, $jenis_like);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):

            echo '<div class="row justify-content-center g-4">';

            while ($row = $result->fetch_assoc()):
              ?>

              <div class="col-md-8 col-lg-6">
                <!-- Card Tiket -->
                <div class="card border-0 shadow-lg rounded-4 p-4 bg-white">
                  <h4 class="fw-bold text-primary mb-2">
                    <?= htmlspecialchars($row['asal']); ?> → <?= htmlspecialchars($row['tujuan']); ?>
                  </h4>

                  <p class="text-muted mb-1">
                    <i class="bi bi-calendar-event me-2"></i>
                    <?= date("d M Y", strtotime($row['jadwal_berangkat'])); ?>
                  </p>

                  <p class="text-muted mb-1">
                    <i class="bi bi-train-front me-2"></i>
                    <?= ucfirst($row['nama_transportasi']); ?> (<?= ucfirst($row['jenis']); ?>)
                  </p>

                  <p class="fw-semibold text-dark mb-4">
                    Harga: Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                  </p>

                  <a href="../pesan/index.php?id_rute=<?= $row['id_rute']; ?>" 
                     class="btn btn-warning text-white fw-semibold w-100 py-2 rounded-3">
                    Pesan Sekarang
                  </a>
                </div>
              </div>

              <?php
            endwhile;

            echo '</div>';

        else:
            echo '<div class="alert alert-warning text-center rounded-3">Tidak ada tiket ditemukan.</div>';
        endif;

    } else {
        echo '<div class="alert alert-danger text-center rounded-3">Masukkan semua data pencarian terlebih dahulu.</div>';
    }
    ?>

  </div>
</main>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
