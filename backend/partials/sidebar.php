<!-- SIDEBAR GOTICKET FINAL -->
<body>
  <div class="page-wrapper" id="main-wrapper"
       data-layout="vertical" data-navbarbg="skin6"
       data-sidebartype="full" data-sidebar-position="fixed"
       data-header-position="fixed">

    <!-- SIDEBAR -->
    <aside class="left-sidebar" id="goticket-sidebar" aria-label="Sidebar Goticket">
      <div class="sidebar-inner">

        <!-- brand / logo -->
        <div class="brand-logo d-flex align-items-center justify-content-center">
          <a href="../dashboard/index.php" class="text-nowrap logo-img d-flex align-items-center">
            <img src="../../../storages/navbar/goticket.png"
                 class="goticket-logo"
                 alt="Goticket" />
          </a>
        </div>

        <!-- navigation -->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav" class="menu-list">

            <li class="nav-small-cap">
              <span class="hide-menu">Menu Utama</span>
            </li>

            <!-- Dashboard -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="../dashboard/index.php">
                <span class="icon-wrap"><i class="ti ti-layout-dashboard"></i></span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>

            <!-- HANYA UNTUK ADMIN -->
            <?php if ($_SESSION['role'] === "Administrator") : ?>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../registrasi_petugas/index.php">
                <span class="icon-wrap"><i class="ti ti-user-plus"></i></span>
                <span class="hide-menu">Registrasi Petugas</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../transportasi/index.php">
                <span class="icon-wrap"><i class="ti ti-car"></i></span>
                <span class="hide-menu">Transportasi</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../rute/index.php">
                <span class="icon-wrap"><i class="ti ti-map"></i></span>
                <span class="hide-menu">Rute</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../promo/index.php">
                <span class="icon-wrap"><i class="ti ti-map"></i></span>
                <span class="hide-menu">Promo</span>
              </a>
            </li>

            <?php endif; ?>
            <!-- END ADMIN ONLY -->

            <li class="sidebar-item">
              <a class="sidebar-link" href="../verifikasi_validasi/index.php">
                <span class="icon-wrap"><i class="ti ti-checks"></i></span>
                <span class="hide-menu">Verifikasi & Validasi</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../generate_laporan/index.php">
                <span class="icon-wrap"><i class="ti ti-report"></i></span>
                <span class="hide-menu">Generate Laporan</span>
              </a>
            </li>

          </ul>
        </nav>

        <!-- logout area BOTTOM FIX -->
        <div class="sidebar-footer">
          <a class="sidebar-link logout-link" href="../../../frontend/pages/auth/logout.php">
            <span class="icon-wrap"><i class="ti ti-logout"></i></span>
            <span class="hide-menu">Logout</span>
          </a>
        </div>

      </div>
    </aside>
    <!-- END SIDEBAR -->

<style>
/* sidebar wrapper */
.left-sidebar {
  width: 260px;
  background: #fff;
  border-right: 1px solid #eef2f7;
  position: fixed;
  top: 0; left: 0; bottom: 0;
  z-index: 1000;
  display: flex;
  flex-direction: column;
}

/* agar logout bisa turun ke bawah */
.sidebar-inner {
  display: flex;
  flex-direction: column;
  height: 100%;
}

/* logo */
.brand-logo {
  padding: 25px 20px 14px;
  border-bottom: 1px solid #f1f5f9;
  text-align: center;
}
.goticket-logo { width: 200px; }

/* menu scroll */
.sidebar-nav {
  flex: 1; /* >>> ini yang mendorong logout ke bawah */
  padding: 20px 12px;
  overflow-y: auto;
}

.menu-list .nav-small-cap {
  margin: 4px 12px 14px;
  color: #f9b233;
  font-size: 12px;
  font-weight: 700;
}

.sidebar-item { margin-bottom: 6px; }

.sidebar-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 12px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 8px;
  color: #333;
  transition: .2s;
}

.icon-wrap {
  width: 34px; height: 34px;
  border-radius: 8px;
  font-size: 18px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#3a3a3a;
}

/* ACTIVE GOTICKET */
.sidebar-link.active,
.sidebar-link:hover {
  background: #f9b233 !important;
  color: #fff !important;
}
.sidebar-link.active .icon-wrap,
.sidebar-link:hover .icon-wrap {
  color:#fff !important;
}

/* logout */
.sidebar-footer {
  padding: 16px;
  border-top: 1px solid #f1f5f9;
}

.sidebar-footer .logout-link:hover {
  background: rgba(224,36,36,0.15);
}

.main-content, .page-content {
  margin-left: 260px;
  padding: 22px;
}
</style>

<script>
(function() {
    const links = document.querySelectorAll('#sidebarnav a.sidebar-link');

    // ambil folder halaman aktif sekarang, contoh:
    // /backend/pages/rute/edit.php --> "rute"
    let pathParts = window.location.pathname.split("/");
    let currentFolder = pathParts[pathParts.length - 2];  

    links.forEach(a => {
        let href = a.getAttribute("href");

        // bersihkan ../ agar konsisten
        href = href.replace(/\.\.\//g, "");

        // contoh href menjadi: "rute/index.php"
        let folder = href.split("/")[0];

        // kalau folder sama → aktifkan
        if (folder === currentFolder) {
            a.classList.add("active");
        }
    });
})();
</script>

