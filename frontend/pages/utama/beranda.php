<?php include '../../app.php'; ?>


<section style="padding-top: 7rem;" id="beranda">
  <div class="bg-holder"
       style="background-image:url(../../template/public/assets/img/hero/hero-bg.svg);
              background-repeat:no-repeat;
              background-size:cover;
              background-position:center;">
  </div>

  <div class="container">
    <div class="row align-items-center">

      <!-- SLIDER HERO (GAMBAR DIGANTI SLIDER) -->
      <div class="col-md-5 col-lg-6 order-0 order-md-1 text-end">

        <div id="heroSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
          <div class="carousel-inner">

            <!-- Slide 1 — Foto utama -->
            <div class="carousel-item active">
              <img src="../../../storages/navbar/hero-img.png"
                   class="d-block w-100"
                   style="max-width:100%; height:auto;"
                   alt="Hero Utama">
            </div>

            <!-- Slide Promo Dari Database -->
            <?php
            $promoQuery = mysqli_query($connect, "SELECT gambar FROM promo ORDER BY id_promo DESC");
            while ($p = mysqli_fetch_assoc($promoQuery)):
              if (!empty($p['gambar'])):
            ?>
              <div class="carousel-item">
                <img src="../../../storages/promo/<?= $p['gambar']; ?>"
                     class="d-block w-100"
                     style="max-width:100%; height:auto;"
                     alt="Promo Slider">
              </div>
            <?php endif; endwhile; ?>

          </div>

          <!-- Navigasi -->
          <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>

          <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>

        </div>
      </div>
      <!-- END SLIDER -->

      <!-- Teks Hero -->
      <div class="col-md-7 col-lg-6 text-md-start text-center py-6">
        <h4 class="fw-bold text-primary mb-3">Hai kamu, rencana mau kemana nih?</h4>
        <h1 class="hero-title fw-bolder text-dark mb-4">
          Temukan Tiket Pesawat & Kereta<br class="d-none d-lg-block" />
          dengan Mudah
        </h1>
        <p class="mb-5 fw-medium text-secondary">
          Nikmati pengalaman memesan tiket transportasi dengan cepat dan praktis.
          Semua perjalanan kamu bisa diatur dari satu tempat.
        </p>

        <!-- Tombol CTA -->
        <div class="text-center text-md-start mt-4">
          <a class="btn btn-warning btn-lg px-4 fw-semibold shadow-sm" 
             href="#rute" 
             role="button"
             style="background-color:#f9b233; border:none; color:#fff;">
            Mulai Cari Tiket
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Tambahan CSS agar tidak nabrak -->
<style>
  /* Hero Section Styling */
  #beranda {
    position: relative;
    overflow: hidden;
  }

  #beranda .hero-title {
    font-size: 4.5rem;
    line-height: 1.25;
    letter-spacing: -0.5px;
  }

  #beranda p {
    font-size: 1.05rem;
    color: #555;
  }

  /* Slider Image */
  #heroSlider img {
    object-fit: contain;
    padding-top: 0rem;
  }

  @media(max-width: 992px) {
    #beranda .hero-title {
      font-size: 2.6rem;
    }
  }

  @media(max-width: 768px){
    #heroSlider img {
      padding-top: 1rem;
    }
    #beranda .hero-title {
      font-size: 2.2rem;
    }
  }

  .btn-warning:hover {
    background-color: #f7a900 !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
  }
</style>
