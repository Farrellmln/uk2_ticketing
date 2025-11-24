<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../app.php';

/*
    ===========================================
    AMBIL PEMBAYARAN TERBARU + CEK PEMBAYARAN LAMA
    ===========================================
*/

$q = "
SELECT 
    p.id_pemesanan,
    u.nama_lengkap AS nama_penumpang,
    t.nama_transportasi,
    t.jenis,
    r.asal,
    r.tujuan,
    p.status_pemesanan,

    -- PEMBAYARAN TERBARU
    pb.id_pembayaran,
    pb.status_bayar,
    pb.bukti_transfer,
    pb.total_bayar,

    -- VERIFIKASI TERBARU PEMBAYARAN TERBARU
    (
        SELECT v2.status_verifikasi 
        FROM verifikasi v2 
        WHERE v2.id_pembayaran = pb.id_pembayaran
        ORDER BY v2.id_verifikasi DESC
        LIMIT 1
    ) AS status_verifikasi,

    -- CEK ADA PEMBAYARAN LAMA YANG TIDAK VALID
    (
        SELECT COUNT(*) 
        FROM pembayaran pbl
        LEFT JOIN verifikasi v3 ON v3.id_pembayaran = pbl.id_pembayaran
        WHERE pbl.id_pemesanan = p.id_pemesanan
        AND v3.status_verifikasi = 'Tidak Valid'
        AND pbl.id_pembayaran < pb.id_pembayaran
    ) AS pembayaran_lama_tidak_valid

FROM pemesanan p
LEFT JOIN user u ON p.id_user = u.id_user
LEFT JOIN rute r ON p.id_rute = r.id_rute
LEFT JOIN transportasi t ON r.id_transportasi = t.id_transportasi

LEFT JOIN pembayaran pb ON pb.id_pembayaran = (
    SELECT id_pembayaran 
    FROM pembayaran 
    WHERE id_pemesanan = p.id_pemesanan
    ORDER BY id_pembayaran DESC
    LIMIT 1
)

ORDER BY p.id_pemesanan DESC
";

$result = mysqli_query($connect, $q) or die(mysqli_error($connect));
$alert = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    .content-wrapper { background:#f8f9fa; min-height:100vh; padding:90px 70px 50px; }
    .page-title { font-size:1.9rem; font-weight:600; color:#ffb703; }
    .breadcrumb { color:#bdbdbd; font-size:0.95rem; margin-bottom:25px; }

    .card-custom { border-radius:12px; background:#fff; box-shadow:0 3px 8px rgba(0,0,0,0.05); }
    .card-header-custom { background:#ffb703; color:#fff; padding:18px 24px; font-weight:600; }

    .badge-status {
        padding:6px 12px;
        border-radius:6px;
        color:#fff;
        font-weight:600;
        display:inline-block;
        min-width:120px;
    }

    .bg-menunggu{background:#ff9800;}
    .bg-verif{background:#2196f3;}
    .bg-valid{background:#4caf50;}
    .bg-belum{background:#9e9e9e;}
    .bg-danger{background:#d32f2f;}
    .bg-cancel{background:#6c757d;}
    .bg-diganti{background:#9e9e9e;}

    .btn-action{ padding:8px 14px; border:none; border-radius:8px; color:#fff; cursor:pointer; }
    .btn-detail{background:#20c997;}
    .btn-verif{background:#4caf50;}
    .btn-hapus{background:#ef5350;}

    .table th { background:#fff2cc; font-weight:600; text-align:center; }
    .table td { text-align:center; vertical-align:middle; }
</style>

<div class="content-wrapper">
    <div class="page-title">Verifikasi & Validasi Pembayaran</div>
    <div class="breadcrumb">Dashboard / Verifikasi</div>

    <?php if($alert): ?>
        <div class="alert alert-warning"><?= $alert ?></div>
    <?php endif; ?>

    <div class="card card-custom">
        <div class="card-header-custom">Daftar Pemesanan</div>

        <div class="card-body">
            <div class="table-responsive">

                <table id="verifTable" class="table table-bordered align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Transportasi</th>
                            <th>Rute</th>
                            <th>Status Pemesanan</th>
                            <th>Status Pembayaran</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

<?php 
if(mysqli_num_rows($result) > 0):
    $no = 1;
    while($row = mysqli_fetch_assoc($result)):
        $sp = $row['status_pemesanan'];
        $sb = $row['status_bayar'];
        $sv = $row['status_verifikasi'];
        $pembayaran_lama_tidak_valid = $row['pembayaran_lama_tidak_valid'];
?>
    <tr>
        <td><?= $no++ ?></td>

        <td><?= $row['jenis'] ?><br><?= $row['nama_transportasi'] ?></td>

        <td><?= $row['asal'] ?> → <?= $row['tujuan'] ?></td>

        <!-- STATUS PEMESANAN -->
        <td>
            <?php
                $clsP = "bg-belum";
                if($sp=="Menunggu Pembayaran") $clsP="bg-menunggu";
                if($sp=="Menunggu Verifikasi") $clsP="bg-verif";
                if($sp=="Dibatalkan") $clsP="bg-cancel";
                if($sp=="Diverifikasi") $clsP="bg-valid";
            ?>
            <span class="badge-status <?= $clsP ?>"><?= $sp ?></span>
        </td>

        <!-- STATUS PEMBAYARAN -->
        <td>
            <?php
                if($sp=="Dibatalkan"){
                    echo '<span class="badge-status bg-cancel">Dibatalkan</span>';
                }
                else if(empty($sb)){
                    echo '<span class="badge-status bg-belum">Tidak Ada Pembayaran</span>';
                }
                else{

                    // PEMBAYARAN DIGANTI (ADA PEMBAYARAN LAMA TIDAK VALID)
                    if($pembayaran_lama_tidak_valid > 0){
                        // TAPI... kalau pembayaran terbaru SUDAH VALID = jangan diganti!
                        if($sv == "Valid"){
                            echo '<span class="badge-status bg-valid">Validasi</span>';
                        } else {
                            echo '<span class="badge-status bg-diganti">Diganti</span>';
                        }
                    }

                    // PEMBAYARAN NORMAL
                    else {
                        $clsB = "bg-belum";
                        if($sb=="Menunggu Verifikasi") $clsB="bg-menunggu";
                        if($sb=="Validasi") $clsB="bg-valid";
                        if($sb=="Tidak Valid") $clsB="bg-danger";
                        echo '<span class="badge-status '.$clsB.'">'.$sb.'</span>';
                    }

                }
            ?>
        </td>

        <!-- STATUS VERIFIKASI -->
        <td>
            <?php
                if($sp=="Dibatalkan"){
                    echo '<span class="badge-status bg-cancel">Dibatalkan</span>';
                }
                else if(empty($sv)){
                    echo '<span class="badge-status bg-belum">Belum Diverifikasi</span>';
                }
                else {
                    $clsV = ($sv=="Valid") ? "bg-valid" : "bg-danger";
                    echo '<span class="badge-status '.$clsV.'">'.$sv.'</span>';
                }
            ?>
        </td>

        <!-- AKSI -->
        <td>
            <div class="d-flex justify-content-center gap-2">

                <a href="detail.php?id=<?= $row['id_pemesanan'] ?>" class="btn-action btn-detail">
                    <i class="bi bi-eye"></i>
                </a>

                <?php if($sp!="Dibatalkan" && in_array($sb,['Menunggu Verifikasi','Tidak Valid'])): ?>
                    <a href="verifikasi_form.php?id_pemesanan=<?= $row['id_pemesanan'] ?>&id_pembayaran=<?= $row['id_pembayaran'] ?>" 
                       class="btn-action btn-verif">
                        <i class="bi bi-check-circle"></i>
                    </a>
                <?php endif; ?>

                <?php if(!empty($row['id_pembayaran']) || $sp=='Dibatalkan'): ?>
                    <a onclick="return confirm('Hapus pesanan & seluruh data terkait?')"
                       href="hapus_verifikasi.php?id_pembayaran=<?= $row['id_pembayaran'] ?>&id_pemesanan=<?= $row['id_pemesanan'] ?>" 
                       class="btn-action btn-hapus">
                       <i class="bi bi-trash3"></i>
                    </a>
                <?php endif; ?>

            </div>
        </td>

    </tr>
<?php 
    endwhile;
endif;
?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#verifTable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3 flex-wrap"l f>t<"d-flex justify-content-between align-items-center mt-3 flex-wrap"i p>',
        lengthMenu:[10,25,50,100],
        pageLength:10,
        ordering:false,
        language:{
            search:"Cari:",
            lengthMenu:"Tampilkan _MENU_ data",
            paginate:{previous:"Sebelumnya",next:"Selanjutnya"},
            info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty:"Tidak ada data tersedia",
            infoFiltered:"(disaring dari _MAX_ total data)"
        }
    });
});
</script>
