<?php
include '../../app.php';

// =====================
// NOTIFIKASI (LIMIT 3)
// =====================
$notif = mysqli_query($connect, "
  SELECT p.id_pemesanan, p.tanggal_pesan, r.asal, r.tujuan, u.nama_lengkap
  FROM pemesanan p
  LEFT JOIN pembayaran pay ON pay.id_pemesanan = p.id_pemesanan
  LEFT JOIN rute r ON r.id_rute = p.id_rute
  LEFT JOIN user u ON u.id_user = p.id_user
  WHERE p.status_pemesanan = 'Menunggu Verifikasi'
     OR pay.status_bayar = 'Menunggu Verifikasi'
  ORDER BY p.tanggal_pesan DESC
  LIMIT 3
");
$jumlah_notif = mysqli_num_rows($notif);

// =====================
// SESSION USER
// =====================
$nama_lengkap = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$email        = $_SESSION['email'] ?? '-';
$level        = $_SESSION['role'] ?? '-';

// =====================
// KONVERSI ROLE
// =====================
$level = strtolower($level);

if ($level == 'administrator') $role_display = 'Administrator';
elseif ($level == 'petugas')   $role_display = 'Petugas';
elseif ($level == 'penumpang') $role_display = 'Penumpang';
else                           $role_display = ucfirst($level);

?>

<!-- CUSTOM CSS FIX -->
<style>
  /* Hilangkan caret / segitiga */
  #notifDropdown::after,
  #profileDropdown::after {
      display: none !important;
  }

  /* Posisi badge notif dibawah icon */
  .notif-badge-fix {
      top: 18px !important;
      left: 18px !important;
  }
</style>


<!--  Main wrapper -->
<div class="body-wrapper">
  <header class="app-header" style="background:#ffffff; border-bottom:1px solid #eee;">
    <nav class="navbar navbar-expand-lg navbar-light">

      <ul class="navbar-nav">
        <li class="nav-item d-block d-xl-none">
          <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
      </ul>

      <!-- RIGHT SIDE -->
      <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">


          <!-- NOTIF MESSAGE -->
          <li class="nav-item dropdown ms-3">
            <a class="nav-link nav-icon-hover"
               href="#"
               id="notifDropdown"
               data-bs-toggle="dropdown"
               aria-expanded="false">

              <div class="position-relative">
                <i class="ti ti-mail" style="font-size:22px; color:#FFC107;"></i>

                <?php if ($jumlah_notif > 0): ?>
                <span class="position-absolute badge rounded-pill bg-danger notif-badge-fix"
                      style="font-size:10px;">
                  <?= $jumlah_notif ?>
                </span>
                <?php endif; ?>
              </div>

            </a>

            <!-- DROPDOWN NOTIF -->
            <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                aria-labelledby="notifDropdown"
                style="width:290px; border-radius:12px;">

              <li class="dropdown-header fw-bold text-warning px-3 pt-2 pb-1">
                Pesan Verifikasi
              </li>
              <li><hr class="dropdown-divider"></li>

              <?php if ($jumlah_notif > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($notif)): ?>
                <li>
                  <a href="../verifikasi_validasi/detail.php?id=<?= $row['id_pemesanan'] ?>"
                     class="dropdown-item d-flex align-items-start py-2">

                    <div class="icon bg-warning text-white rounded-circle d-flex
                      align-items-center justify-content-center me-2"
                      style="width:35px;height:35px;">
                      <i class="ti ti-mail"></i>
                    </div>

                    <div>
                      <div class="fw-semibold text-dark small">
                        Pemesanan #<?= $row['id_pemesanan'] ?> - <?= $row['nama_lengkap'] ?>
                      </div>
                      <div class="text-muted" style="font-size:11px;">
                        <?= date('d M Y', strtotime($row['tanggal_pesan'])) ?>
                        • <?= $row['asal'] ?> → <?= $row['tujuan'] ?>
                      </div>
                    </div>

                  </a>
                </li>
                <?php endwhile; ?>
              <?php else: ?>
                <li class="text-center text-muted py-2" style="font-size:13px;">
                  Tidak ada pesan baru
                </li>
              <?php endif; ?>

            </ul>
          </li>


          <!-- PROFILE DROPDOWN -->
          <li class="nav-item dropdown ms-3">

            <a class="nav-link nav-icon-hover"
               href="#" id="profileDropdown"
               data-bs-toggle="dropdown"
               aria-expanded="false">

              <div class="rounded-circle d-flex align-items-center justify-content-center"
                style="width:35px; height:35px; background:#FFC107;">
                <i class="ti ti-user" style="color:white; font-size:18px;"></i>
              </div>

            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                aria-labelledby="profileDropdown"
                style="border-radius:12px; width:230px;">

              <li class="p-3 d-flex align-items-center">
                  <div class="rounded-circle d-flex align-items-center justify-content-center"
                      style="width:45px; height:45px; background:#FFC107;">
                    <i class="ti ti-user" style="color:white; font-size:22px;"></i>
                  </div>

                  <div class="ms-2">
                    <div class="fw-bold" style="font-size:14px;">
                        <?= $nama_lengkap ?>
                    </div>
                    <div class="text-muted" style="font-size:12px;">
                        <?= $role_display ?>
                    </div>
                  </div>
              </li>

            </ul>
          </li>

        </ul>
      </div>

    </nav>
  </header>
