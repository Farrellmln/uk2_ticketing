<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// Ambil semua data transportasi
$qTransportasi = "SELECT * FROM transportasi ORDER BY jenis, nama_transportasi ASC";
$resultTransportasi = mysqli_query($connect, $qTransportasi) or die(mysqli_error($connect));

// Kelompokkan berdasarkan jenis
$transportasi = [
  'Pesawat' => [],
  'Kereta' => []
];
while ($row = mysqli_fetch_assoc($resultTransportasi)) {
  $transportasi[$row['jenis']][] = $row;
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

  .form-label {
    font-weight: 600;
    color: #ffb703;
  }

  .form-control,
  .form-select {
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid #ddd;
    transition: 0.2s;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #ffb703;
    box-shadow: 0 0 4px rgba(255, 183, 3, 0.4);
  }

  .btn-simpan {
    background-color: #ffb703;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    transition: 0.2s;
  }

  .btn-simpan:hover {
    background-color: #f29c02;
    color: #fff;
  }

  .btn-batal {
    background-color: #adb5bd;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
  }

  .btn-batal:hover {
    background-color: #868e96;
    color: #fff;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">Tambah Rute</div>
  <div class="breadcrumb">Dashboard / Rute / Tambah</div>

  <div class="card card-custom">
    <div class="card-header-custom">Form Tambah Rute</div>
    <div class="card-body p-5">
      <form action="../../actions/rute/store.php" method="POST">
        <div class="row mb-3">
          <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Transportasi</label>
            <select id="jenisTransportasi" class="form-select" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="Pesawat">Pesawat</option>
              <option value="Kereta">Kereta</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Nama Transportasi</label>
            <select name="id_transportasi" id="namaTransportasi" class="form-select" required>
              <option value="">-- Pilih Nama Transportasi --</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 mb-3">
            <label class="form-label">Asal Keberangkatan</label>
            <input type="text" name="asal" class="form-control" placeholder="Masukkan kota asal" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Tujuan</label>
            <input type="text" name="tujuan" class="form-control" placeholder="Masukkan kota tujuan" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 mb-3">
            <label class="form-label">Harga Tiket</label>
            <input type="number" name="harga" class="form-control" placeholder="Masukkan harga tiket" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Jadwal Berangkat</label>
            <input type="datetime-local" name="jadwal_berangkat" class="form-control" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Jadwal Tiba</label>
            <input type="datetime-local" name="jadwal_tiba" class="form-control" required>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="index.php" class="btn-batal"><i class="bi bi-x-lg"></i> Batal</a>
          <button type="submit" class="btn-simpan"><i class="bi bi-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const dataTransportasi = <?= json_encode($transportasi); ?>;
  const jenisSelect = document.getElementById("jenisTransportasi");
  const namaSelect = document.getElementById("namaTransportasi");

  jenisSelect.addEventListener("change", function() {
    const jenis = this.value;
    namaSelect.innerHTML = '<option value="">-- Pilih Nama Transportasi --</option>';

    if (jenis && dataTransportasi[jenis]) {
      dataTransportasi[jenis].forEach(item => {
        const option = document.createElement("option");
        option.value = item.id_transportasi;
        option.textContent = item.nama_transportasi;
        namaSelect.appendChild(option);
      });
    }
  });
</script>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
