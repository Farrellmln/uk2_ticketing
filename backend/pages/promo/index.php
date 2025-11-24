<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// Ambil data promo
$qPromo = "
  SELECT *
  FROM promo
  ORDER BY id_promo DESC
";
$result = mysqli_query($connect, $qPromo) or die(mysqli_error($connect));

// alert
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
        font-size: 1.05rem;
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
        min-width: 40px;
    }

    .btn-detail { background-color: #20c997; }
    .btn-detail:hover { background-color: #17a589; }

    .btn-edit { background-color: #ffc107; color: #fff; }
    .btn-edit:hover { background-color: #e0a800; color: #fff; }

    .btn-delete { background-color: #ef5350; }
    .btn-delete:hover { background-color: #e53935; }

    .badge-status {
        border-radius: 20px;
        padding: 6px 14px;
        font-weight: 500;
        color: #fff;
    }
    .bg-aktif { background:#28a745; }
    .bg-nonaktif { background:#6c757d; }

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
    <div class="page-title">Data Promo</div>
    <div class="breadcrumb">Dashboard / Promo</div>

    <?php if ($alert): ?>
        <div class="alert-custom"><?= htmlspecialchars($alert); ?></div>
    <?php endif; ?>

    <div class="card card-custom">
        <div class="card-header-custom">
            <span>Daftar Promo Aktif & Nonaktif</span>
            <a href="create.php" class="btn-tambah">+ Tambah Promo</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="promoTable" class="table table-bordered align-middle text-center mb-0">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Promo</th>
                        <th>Potongan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (mysqli_num_rows($result) > 0):
                        $no = 1;
                        while ($item = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($item['nama_promo']); ?></td>
                                <td>Rp <?= number_format($item['potongan'], 0, ',', '.'); ?></td>
                                <td><?= date('d M Y', strtotime($item['tanggal_mulai'])); ?></td>
                                <td><?= date('d M Y', strtotime($item['tanggal_selesai'])); ?></td>

                                <td>
                                    <?php if ($item['status'] == 'aktif'): ?>
                                        <span class="badge-status bg-aktif">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge-status bg-nonaktif">Nonaktif</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="detail.php?id=<?= $item['id_promo']; ?>" class="btn-action btn-detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="edit.php?id=<?= $item['id_promo']; ?>" class="btn-action btn-edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <a href="../../actions/promo/destroy.php?id=<?= $item['id_promo']; ?>"
                                           onclick="return confirm('Yakin ingin menghapus promo ini?')"
                                           class="btn-action btn-delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data promo.</td>
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
    $(document).ready(function () {
        $('#promoTable').DataTable({
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
