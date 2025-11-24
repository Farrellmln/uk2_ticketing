<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<body>
  <main class="main" id="top">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar" id="navbar">
      <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center" href="#beranda">
          <img src="../../../storages/navbar/goticket.png" alt="Goticket Logo" class="logo-goticket" />
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

          <ul class="navbar-nav align-items-lg-center font-base">
            <li class="nav-item px-3"><a class="nav-link fw-medium" href="#beranda">Beranda</a></li>
            <li class="nav-item px-3"><a class="nav-link fw-medium" href="#rute">Rute</a></li>
            <li class="nav-item px-3"><a class="nav-link fw-medium" href="#destination">Destination</a></li>
            <li class="nav-item px-3"><a class="nav-link fw-medium" href="#pesan">Masukan</a></li>
            <li class="nav-item px-3"><a class="nav-link fw-medium" href="../profil/index.php">Profil</a></li>

            <!-- Jika belum login -->
            <?php if (!isset($_SESSION['id_user'])): ?>
                <li class="nav-item px-3">
                  <a class="btn btn-outline-dark fw-medium" href="../auth/login.php">Sign Up</a>
                </li>
            <?php endif; ?>

          </ul>

        </div>

      </div>
    </nav>
  </main>

  <style>
    /* === NAVBAR === */
    .custom-navbar {
      background-color: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(0px);
      transition: all 0.4s ease;
      padding: 15px 0;
      height: 90px;
      display: flex;
      align-items: center;
      z-index: 999;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .custom-navbar.scrolled {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(15px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .logo-goticket {
      height: 65px !important;
      width: auto;
      transition: all 0.3s ease;
    }

    .custom-navbar.scrolled .logo-goticket {
      height: 55px !important;
    }

    .navbar-nav .nav-item .nav-link {
      font-size: 16px;
      color: #2b2b2b;
      padding: 10px 15px;
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-item .nav-link:hover {
      color: #f9b233;
    }

    .navbar-nav .btn {
      font-size: 15px;
      padding: 6px 16px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .navbar-toggler {
      border: none;
      outline: none;
      padding: 0;
      margin-left: auto;
    }

    .custom-toggler {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: auto;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=UTF8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(0,0,0,0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    /* === MOBILE NAV === */
    @media (max-width: 991.98px) {
      .navbar-collapse {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding: 20px;
        text-align: left;
      }

      .navbar-nav .nav-item {
        margin: 10px 0;
      }

      .navbar-nav .nav-item .nav-link {
        font-size: 17px;
        color: #2b2b2b;
      }

      .navbar-nav .btn {
        margin-top: 10px;
        color: #2b2b2b;
        border-color: #2b2b2b;
      }

      .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
    }
  </style>

  <script>
    // Efek blur on scroll
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.custom-navbar');
      if (window.scrollY > 30) navbar.classList.add('scrolled');
      else navbar.classList.remove('scrolled');
    });

    // === AUTO CLOSE NAVBAR SAAT MENU DIKLIK (MOBILE ONLY) ===
    document.querySelectorAll('.nav-link').forEach(item => {
      item.addEventListener('click', function () {
        const toggler = document.querySelector('.navbar-toggler');
        const collapse = document.querySelector('#navbarSupportedContent');

        // Auto close hanya jika toggler terlihat (berarti mobile)
        if (window.getComputedStyle(toggler).display !== 'none') {
          let bsCollapse = bootstrap.Collapse.getInstance(collapse);
          if (!bsCollapse) {
            bsCollapse = new bootstrap.Collapse(collapse);
          }
          bsCollapse.hide();
        }
      });
    });
  </script>

</body>
