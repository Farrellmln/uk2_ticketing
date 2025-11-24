<section style="padding-top: 7rem;" id="beranda">
  <div class="bg-holder"
       style="background-image:url(../../template/public/assets/img/hero/hero-bg.svg);
              background-repeat:no-repeat;
              background-size:cover;
              background-position:center;">
  </div>

  <div class="container">
    <div class="row align-items-center">

      <!-- Gambar Hero -->
      <div class="col-md-5 col-lg-6 order-0 order-md-1 text-end">
        <img class="pt-7 pt-md-0 hero-img" 
             src="../../../storages/navbar/hero-img.png" 
             alt="hero-header" 
             style="max-width: 100%; height: auto;" />
      </div>

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

<style>
  /* Hero Section Styling */
  #beranda {
    position: relative;
    overflow: hidden;
  }

  #beranda .hero-title {
    font-size: 4.5rem; /* ✅ Lebih besar dari sebelumnya */
    line-height: 1.25;
    letter-spacing: -0.5px;
  }

  #beranda p {
    font-size: 1.05rem;
    color: #555;
  }

  /* Responsif */
  @media (max-width: 992px) {
    #beranda .hero-title {
      font-size: 2.6rem;
    }
  }

  @media (max-width: 768px) {
    #beranda .hero-title {
      font-size: 2.2rem;
    }
    #beranda p {
      font-size: 0.95rem;
    }
  }

  /* Tombol Hover */
  .btn-warning:hover {
    background-color: #f7a900 !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
  }
</style>
