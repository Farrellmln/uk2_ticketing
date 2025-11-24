<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

$qRute = "
  SELECT rute.*, transportasi.nama_transportasi, transportasi.jenis 
  FROM rute 
  JOIN transportasi ON rute.id_transportasi = transportasi.id_transportasi
  ORDER BY rute.id_rute DESC
";
$result = mysqli_query($connect, $qRute) or die(mysqli_error($connect));

// alert sederhana (pesan dari action)
$alert = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

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
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        background-color: #ffffff;
    }

    .card-header-custom {
        background-color: #ffb703;
        color: #ffffff;
        padding: 18px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .btn-tambah {
        background-color: #ffffff;
        color: #ffb703;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s;
    }

    .btn-tambah:hover {
        background-color: #ffb703;
        color: #fff;
    }

    .table th {
        background-color: #fff2cc;
        color: #000;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
        text-align: center;
        color: #000;
    }

    .table tbody tr:hover {
        background-color: #fff8e1;
    }

    .btn-action {
        border: none;
        border-radius: 8px;
        color: #fff;
        padding: 8px 14px;
        font-size: 15px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }

    .btn-detail {
        background-color: #20c997;
    }

    .btn-detail:hover {
        background-color: #17a589;
    }

    .btn-edit {
        background-color: #ffc107;
        color: #fff;
    }

    .btn-edit:hover {
        background-color: #e0a800;
        color: #fff;
    }

    .btn-delete {
        background-color: #ef5350;
    }

    .btn-delete:hover {
        background-color: #e53935;
    }

    .badge-jenis {
        border-radius: 20px;
        padding: 6px 14px;
        font-weight: 500;
        color: #fff;
    }

    .badge-pesawat {
        background-color: #1976d2;
    }

    .badge-kereta {
        background-color: #8e24aa;
    }

    div.dataTables_wrapper {
        padding: 10px 5px;
    }

    div.dataTables_filter label {
        color: #000 !important;
        font-weight: 500;
    }

    div.dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 6px 10px;
        outline: none;
    }

    div.dataTables_length label {
        color: #000 !important;
        font-weight: 500;
    }

    .alert-custom {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        border-radius: 10px;
        padding: 12px 18px;
        margin-bottom: 20px;
        font-weight: 500;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">Data Rute</div>
    <div class="breadcrumb">Dashboard / Rute</div>

    <?php if ($alert): ?>
        <div class="alert-custom"><?= htmlspecialchars($alert); ?></div>
    <?php endif; ?>

    <div class="card card-custom">
        <div class="card-header-custom">
            <span>Daftar Rute Transportasi</span>
            <a href="create.php" class="btn-tambah">+ Tambah Rute</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="ruteTable" class="table table-bordered align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Transportasi</th>
                            <th>Jenis</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Harga</th>
                            <th>Berangkat</th>
                            <th>Tiba</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0):
                            $no = 1;
                            while ($item = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($item['nama_transportasi']); ?></td>
                            <td>
                                <?php if ($item['jenis'] == 'Pesawat'): ?>
                                    <span class="badge-jenis badge-pesawat">Pesawat</span>
                                <?php else: ?>
                                    <span class="badge-jenis badge-kereta">Kereta</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['asal']); ?></td>
                            <td><?= htmlspecialchars($item['tujuan']); ?></td>
                            <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td><?= date('d M Y H:i', strtotime($item['jadwal_berangkat'])); ?></td>
                            <td><?= date('d M Y H:i', strtotime($item['jadwal_tiba'])); ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="detail.php?id=<?= $item['id_rute']; ?>" class="btn-action btn-detail" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $item['id_rute']; ?>" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="../../actions/rute/destroy.php?id=<?= $item['id_rute']; ?>"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')"
                                       class="btn-action btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data rute.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#ruteTable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3 flex-wrap"l f>t<"d-flex justify-content-between align-items-center mt-3 flex-wrap"i p>',
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            },
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(disaring dari _MAX_ total data)"
        },
        ordering: false
    });
});
</script>
