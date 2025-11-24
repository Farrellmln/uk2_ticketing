<?php
// file: generate_laporan_ticketing.php
// letakkan di folder yang sama dengan partials/header.php etc
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// --- input filter (escape dulu) ---
$dari_raw   = $_GET['dari']   ?? '';
$sampai_raw = $_GET['sampai'] ?? '';
$type_raw   = $_GET['type']   ?? 'pemesanan'; // default laporan pemesanan

$dari   = $dari_raw ? mysqli_real_escape_string($connect, $dari_raw) : '';
$sampai = $sampai_raw ? mysqli_real_escape_string($connect, $sampai_raw) : '';
$type   = in_array($type_raw, ['pemesanan','pembayaran','verifikasi','pendapatan']) ? $type_raw : 'pemesanan';

// build where date clause (use appropriate date column per report later)
$dateFilterSQL = '';
if ($dari && $sampai) {
    // We'll substitute the date column in each query as needed.
    // Keep $dari and $sampai for use below.
}

// helper for human title
$titles = [
  'pemesanan'  => 'Laporan Pemesanan',
  'pembayaran' => 'Laporan Pembayaran',
  'verifikasi' => 'Laporan Verifikasi Pembayaran',
  'pendapatan' => 'Laporan Pendapatan'
];
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
/* Match style with Verifikasi/Rute (yellow theme) */
.content-wrapper {
  background-color: #f8f9fa;
  min-height: 100vh;
  padding: 90px 70px 50px;
}
.page-title { font-size:1.9rem; font-weight:600; color:#ffb703; margin-bottom:8px; }
.breadcrumb { color:#bdbdbd; margin-bottom:25px; }
.card-custom { border-radius:12px; overflow:hidden; box-shadow:0 3px 8px rgba(0,0,0,0.05); background:#fff; }
.card-header-custom { background:#ffb703; color:#fff; padding:18px 24px; font-weight:600; display:flex; justify-content:space-between; align-items:center; }
.table th { background-color:#fff2cc; color:#000; font-weight:600; text-align:center; vertical-align:middle; }
.table td { vertical-align:middle; text-align:center; color:#000; }
.table tbody tr:hover { background-color:#fff8e1; }
.badge-status { padding:6px 10px; border-radius:6px; color:#fff; display:inline-block; min-width:120px; font-weight:600; }
.bg-menunggu { background:#ff9800; }
.bg-valid { background:#4caf50; }
.bg-belum { background:#9e9e9e; }
.bg-danger { background:#d32f2f; }
.bg-cancel { background:#6c757d; }

/* Datatables buttons */
.dt-buttons { display:flex; justify-content:flex-end; gap:8px; margin-bottom:15px; flex-wrap:wrap; }
.dt-button { border:none !important; border-radius:8px !important; color:#fff !important; padding:8px 14px !important; font-weight:500 !important; display:inline-flex !important; align-items:center; gap:6px; }
.buttons-pdf { background-color:#dc3545 !important; }
.buttons-excel { background-color:#198754 !important; }
.buttons-csv { background-color:#6f42c1 !important; }
.buttons-print { background-color:#212529 !important; }
.buttons-copy { background-color:#0078d7 !important; }

.filter-row .form-label { font-weight:600; color:#333; }
</style>

<div class="content-wrapper">
  <div class="page-title">Generate Laporan Sistem Ticketing</div>
  <div class="breadcrumb">Dashboard / Laporan / Generate Laporan</div>

  <div class="card card-custom">
    <div class="card-header-custom">
      <span><?= $titles[$type] ?></span>
      <div>
        <!-- quick links or future buttons -->
      </div>
    </div>

    <div class="card-body">

      <!-- FILTER -->
      <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end filter-row">
          <div class="col-md-3">
            <label class="form-label">Tipe Laporan</label>
            <select name="type" class="form-select">
              <option value="pemesanan"  <?= $type==='pemesanan' ? 'selected' : '' ?>>Pemesanan</option>
              <option value="pembayaran" <?= $type==='pembayaran' ? 'selected' : '' ?>>Pembayaran</option>
              <option value="verifikasi" <?= $type==='verifikasi' ? 'selected' : '' ?>>Verifikasi</option>
              <option value="pendapatan" <?= $type==='pendapatan' ? 'selected' : '' ?>>Pendapatan</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="dari" class="form-control" value="<?= htmlspecialchars($dari_raw) ?>">
          </div>

          <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="sampai" class="form-control" value="<?= htmlspecialchars($sampai_raw) ?>">
          </div>

          <div class="col-md-2 text-center">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
          </div>
        </div>
      </form>

      <!-- TABLE -->
      <div class="table-responsive">
        <?php
        // Build query per report type (apply date filter where sensible)
        if ($type === 'pemesanan') {
            // use p.tanggal_pesan for filter
            $where = [];
            if ($dari && $sampai) {
                $where[] = "p.tanggal_pesan BETWEEN '{$dari} 00:00:00' AND '{$sampai} 23:59:59'";
            }
            $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $sql = "
              SELECT p.id_pemesanan, p.tanggal_pesan, u.nama_lengkap, t.nama_transportasi, t.jenis,
                     r.asal, r.tujuan, p.jumlah_tiket, p.total_harga, p.status_pemesanan
              FROM pemesanan p
              LEFT JOIN user u ON p.id_user = u.id_user
              LEFT JOIN rute r ON p.id_rute = r.id_rute
              LEFT JOIN transportasi t ON r.id_transportasi = t.id_transportasi
              {$whereSQL}
              ORDER BY p.tanggal_pesan DESC
            ";

            $res = mysqli_query($connect, $sql);
            ?>

            <table id="reportTable" class="table table-bordered align-middle text-center mb-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Pemesanan</th>
                  <th>Tanggal Pesan</th>
                  <th>Penumpang</th>
                  <th>Transportasi</th>
                  <th>Rute</th>
                  <th>Jumlah Tiket</th>
                  <th>Total Harga</th>
                  <th>Status Pemesanan</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1;
                while ($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $row['id_pemesanan'] ?></td>
                  <td><?= date('d M Y H:i', strtotime($row['tanggal_pesan'])) ?></td>
                  <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                  <td><?= htmlspecialchars($row['jenis']) ?><br><?= htmlspecialchars($row['nama_transportasi']) ?></td>
                  <td><?= htmlspecialchars($row['asal']) ?> → <?= htmlspecialchars($row['tujuan']) ?></td>
                  <td><?= (int)$row['jumlah_tiket'] ?></td>
                  <td>Rp <?= number_format($row['total_harga'] ?? 0,0,',','.') ?></td>
                  <td>
                    <?php
                      $sp = $row['status_pemesanan'];
                      $cls = 'bg-belum';
                      if ($sp === 'Menunggu Pembayaran') $cls = 'bg-menunggu';
                      if ($sp === 'Menunggu Verifikasi') $cls = 'bg-menunggu';
                      if ($sp === 'Diverifikasi') $cls = 'bg-valid';
                      if ($sp === 'Dibatalkan') $cls = 'bg-cancel';
                      echo '<span class="badge-status '.$cls.'">'.htmlspecialchars($sp).'</span>';
                    ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>

        <?php
        } elseif ($type === 'pembayaran') {
            // filter on pembayaran.tanggal_bayar
            $where = [];
            if ($dari && $sampai) {
                $where[] = "pb.tanggal_bayar BETWEEN '{$dari} 00:00:00' AND '{$sampai} 23:59:59'";
            }
            $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $sql = "
              SELECT pb.id_pembayaran, pb.tanggal_bayar, pb.total_bayar, pb.metode_pembayaran, pb.status_bayar,
                     pb.bukti_transfer, p.id_pemesanan, u.nama_lengkap, r.asal, r.tujuan, t.nama_transportasi
              FROM pembayaran pb
              LEFT JOIN pemesanan p ON pb.id_pemesanan = p.id_pemesanan
              LEFT JOIN user u ON p.id_user = u.id_user
              LEFT JOIN rute r ON p.id_rute = r.id_rute
              LEFT JOIN transportasi t ON r.id_transportasi = t.id_transportasi
              {$whereSQL}
              ORDER BY pb.tanggal_bayar DESC
            ";
            $res = mysqli_query($connect, $sql);
            ?>

            <table id="reportTable" class="table table-bordered align-middle text-center mb-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Pembayaran</th>
                  <th>Tanggal Bayar</th>
                  <th>ID Pemesanan</th>
                  <th>Penumpang</th>
                  <th>Transportasi / Rute</th>
                  <th>Metode</th>
                  <th>Total Bayar</th>
                  <th>Status Bayar</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1;
                while ($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $row['id_pembayaran'] ?></td>
                  <td><?= date('d M Y H:i', strtotime($row['tanggal_bayar'])) ?></td>
                  <td><?= $row['id_pemesanan'] ?></td>
                  <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                  <td><?= htmlspecialchars($row['nama_transportasi']) ?> <br> <?= htmlspecialchars($row['asal']) ?> → <?= htmlspecialchars($row['tujuan']) ?></td>
                  <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                  <td>Rp <?= number_format($row['total_bayar'] ?? 0,0,',','.') ?></td>
                  <td>
                    <?php
                      $sb = $row['status_bayar'];
                      $cls = 'bg-belum';
                      if ($sb === 'Menunggu Verifikasi') $cls = 'bg-menunggu';
                      if ($sb === 'Validasi') $cls = 'bg-valid';
                      if ($sb === 'Belum Bayar') $cls = 'bg-danger';
                      echo '<span class="badge-status '.$cls.'">'.htmlspecialchars($sb).'</span>';
                    ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>

        <?php
        } elseif ($type === 'verifikasi') {
            // use verifikasi.tanggal_verifikasi (or verifikasi.created_at) - include pembayaran and petugas
            $where = [];
            if ($dari && $sampai) {
                $where[] = "v.tanggal_verifikasi BETWEEN '{$dari} 00:00:00' AND '{$sampai} 23:59:59'";
            }
            $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $sql = "
              SELECT v.id_verifikasi, v.tanggal_verifikasi, v.status_verifikasi, v.catatan, v.created_at,
                     pb.id_pembayaran, p.id_pemesanan, u.nama_lengkap AS penumpang, pet.nama_lengkap AS petugas,
                     pb.total_bayar, r.asal, r.tujuan, t.nama_transportasi
              FROM verifikasi v
              LEFT JOIN pembayaran pb ON v.id_pembayaran = pb.id_pembayaran
              LEFT JOIN pemesanan p ON pb.id_pemesanan = p.id_pemesanan
              LEFT JOIN user u ON p.id_user = u.id_user
              LEFT JOIN user pet ON v.id_petugas = pet.id_user
              LEFT JOIN rute r ON p.id_rute = r.id_rute
              LEFT JOIN transportasi t ON r.id_transportasi = t.id_transportasi
              {$whereSQL}
              ORDER BY v.tanggal_verifikasi DESC
            ";
            $res = mysqli_query($connect, $sql);
            ?>

            <table id="reportTable" class="table table-bordered align-middle text-center mb-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Verifikasi</th>
                  <th>Tanggal Verifikasi</th>
                  <th>ID Pembayaran</th>
                  <th>ID Pemesanan</th>
                  <th>Penumpang</th>
                  <th>Petugas</th>
                  <th>Status Verifikasi</th>
                  <th>Catatan</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1;
                while ($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $row['id_verifikasi'] ?></td>
                  <td><?= date('d M Y H:i', strtotime($row['tanggal_verifikasi'])) ?></td>
                  <td><?= $row['id_pembayaran'] ?></td>
                  <td><?= $row['id_pemesanan'] ?></td>
                  <td><?= htmlspecialchars($row['penumpang']) ?></td>
                  <td><?= htmlspecialchars($row['petugas'] ?? '-') ?></td>
                  <td>
                    <?php
                      $sv = $row['status_verifikasi'];
                      $cls = 'bg-belum';
                      if ($sv === 'Valid') $cls = 'bg-valid';
                      if ($sv === 'Tidak Valid') $cls = 'bg-danger';
                      echo '<span class="badge-status '.$cls.'">'.htmlspecialchars($sv).'</span>';
                    ?>
                  </td>
                  <td style="text-align:left;max-width:300px;"><?= nl2br(htmlspecialchars($row['catatan'])) ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>

        <?php
        } else { // pendapatan
            // aggregate total pendapatan grouped by date (use pembayaran.tanggal_bayar)
            $where = [];
            if ($dari && $sampai) {
                $where[] = "pb.tanggal_bayar BETWEEN '{$dari} 00:00:00' AND '{$sampai} 23:59:59'";
            }
            $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $sql = "
              SELECT DATE(pb.tanggal_bayar) AS tanggal, 
                     COUNT(pb.id_pembayaran) AS jumlah_transaksi,
                     SUM(pb.total_bayar) AS total_pendapatan
              FROM pembayaran pb
              {$whereSQL}
              GROUP BY DATE(pb.tanggal_bayar)
              ORDER BY DATE(pb.tanggal_bayar) DESC
            ";
            $res = mysqli_query($connect, $sql);
            // compute grand total
            $grandSQL = "
              SELECT SUM(pb.total_bayar) AS grand_total
              FROM pembayaran pb
              " . ($where ? 'WHERE ' . implode(' AND ', $where) : '');
            $gres = mysqli_query($connect, $grandSQL);
            $grow = mysqli_fetch_assoc($gres);
            ?>

            <table id="reportTable" class="table table-bordered align-middle text-center mb-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Jumlah Transaksi</th>
                  <th>Total Pendapatan</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; $total_all = 0;
                while ($row = mysqli_fetch_assoc($res)): 
                  $total_all += $row['total_pendapatan']; ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                  <td><?= (int)$row['jumlah_transaksi'] ?></td>
                  <td>Rp <?= number_format($row['total_pendapatan'] ?? 0,0,',','.') ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3">Grand Total</th>
                  <th>Rp <?= number_format($grow['grand_total'] ?? $total_all,0,',','.') ?></th>
                </tr>
              </tfoot>
            </table>

        <?php } // end type handling ?>
      </div>

    </div>
  </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>

<!-- SCRIPTS DATATABLES + BUTTONS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  // DataTable init for whatever table is present
  var table = $('#reportTable').DataTable({
    dom: '<"d-flex justify-content-between align-items-center mb-3 flex-wrap"Bf>t<"d-flex justify-content-between align-items-center mt-3 flex-wrap"i p>',
    buttons: [
      { extend: 'print', text: '<i class="bi bi-printer"></i> Print', title: $('title').text() || 'Laporan' },
      { extend: 'pdfHtml5', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', title: $('title').text() || 'Laporan', orientation: 'landscape', pageSize: 'A4' },
      { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel"></i> Excel', title: $('title').text() || 'Laporan' },
      { extend: 'csvHtml5', text: '<i class="bi bi-filetype-csv"></i> CSV', title: $('title').text() || 'Laporan' },
      { extend: 'copyHtml5', text: '<i class="bi bi-files"></i> Copy', title: $('title').text() || 'Laporan' }
    ],
    pageLength: 10,
    lengthMenu: [5,10,25,50,100],
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      paginate: { previous: "Sebelumnya", next: "Selanjutnya" },
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      infoEmpty: "Tidak ada data",
      infoFiltered: "(disaring dari _MAX_ total data)"
    },
    ordering: false
  });

  // style small fix for buttons (optional)
  $('.dt-button').addClass('btn btn-sm');

  // If table is not present (no results), avoid errors:
  if (!$('#reportTable').length) return;
});
</script>
