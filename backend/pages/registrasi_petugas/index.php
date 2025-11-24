<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

// Ambil data khusus role Administrator & Petugas
$qUser = "SELECT * FROM user WHERE role IN ('Administrator','Petugas') ORDER BY id_user DESC";
$result = mysqli_query($connect, $qUser) or die(mysqli_error($connect));
?>

<!-- Bootstrap Icons -->
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
        margin-bottom: 25px;
    }

    .card-custom {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        background-color: #ffffff;
    }

    .card-header-custom {
        background-color: #ffb703;
        color: #ffffff;
        padding: 18px 24px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-tambah {
        background-color: #ffffff;
        color: #ffb703;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        border: none;
    }

    .btn-tambah:hover {
        background-color: #ffe08a;
    }

    .table th {
        background-color: #fff2cc;
        font-weight: 600;
        color: #000;
        text-align: center;
    }

    .table td {
        text-align: center;
    }

    .badge-role {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        color: white;
    }

    .r-admin {
        background-color: #1976d2;
    }

    .r-petugas {
        background-color: #4caf50;
    }

    .btn-action {
        border: none;
        border-radius: 8px;
        color: #fff;
        padding: 8px 14px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .btn-detail { background:#20c997; }
    .btn-edit { background:#ffc107; }
    .btn-delete { background:#ef5350; }
</style>

<div class="content-wrapper">
    <div class="page-title">Data Petugas & Admin</div>
    <div class="breadcrumb">Dashboard / User / Petugas & Admin</div>

    <div class="card card-custom">
        <div class="card-header-custom">
            <span>Daftar User Petugas & Admin</span>
            <a href="create.php" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah User
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0):
                            $no = 1;
                            while ($item = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($item['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($item['username']); ?></td>
                            <td><?= $item['email'] ?: '-' ?></td>
                            <td><?= $item['no_hp'] ?: '-' ?></td>

                            <td>
                                <?php if ($item['role'] == 'Administrator'): ?>
                                    <span class="badge-role r-admin">Administrator</span>
                                <?php else: ?>
                                    <span class="badge-role r-petugas">Petugas</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="detail.php?id=<?= $item['id_user']; ?>" 
                                       class="btn-action btn-detail">
                                       <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="edit.php?id=<?= $item['id_user']; ?>" 
                                       class="btn-action btn-edit">
                                       <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <a onclick="return confirm('Hapus user ini?')" 
                                       href="../../actions/registrasi_petugas/destroy.php?id=<?= $item['id_user']; ?>" 
                                       class="btn-action btn-delete">
                                       <i class="bi bi-trash"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>
<?php include '../../partials/script.php'; ?>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#userTable').DataTable({
        dom: '<"d-flex justify-content-between mb-3"l f>t<"d-flex justify-content-between mt-3"i p>',
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "Sebelumnya", next: "Selanjutnya" },
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
        }
    });
});
</script>

