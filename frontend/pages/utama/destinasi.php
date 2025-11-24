<!-- ============================================-->
<!-- DESTINASI -->
<section id="destination" class="pt-5 position-relative">

  <!-- Glow Dekorasi -->
  <div class="glow-dest-left"></div>
  <div class="glow-dest-right"></div>

  <div class="container position-relative" style="z-index:2;">

    <div class="text-center mb-5">
      <h5 class="text-secondary">
        Nikmati Liburanmu Bersama Keluarga, Teman, dan Saudaramu
      </h5>
      <h2 class="fw-bold" style="color:#004c84;">Top Destinasi</h2>
    </div>

    <div class="row g-4">

      <!-- Semarang -->
      <div class="col-md-4">
        <div class="card dest-card overflow-hidden shadow-lg">

          <div class="dest-img-wrap">
            <img src="../../../storages/navbar/semarang.jpg"
                 alt="Semarang"
                 class="card-img-top dest-img"/>
          </div>

          <div class="card-body py-4 px-3">
            <h4 class="text-secondary fw-medium mb-2">
              Semarang
            </h4>

            <div class="text-warning fs-5 d-flex align-items-center">
              ★★★★☆
              <span class="text-muted ms-1 small">(4.0)</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Bali -->
      <div class="col-md-4">
        <div class="card dest-card overflow-hidden shadow-lg">

          <div class="dest-img-wrap">
            <img src="../../../storages/navbar/bali.jpeg"
                 alt="Bali"
                 class="card-img-top dest-img" />
          </div>

          <div class="card-body py-4 px-3">
            <h4 class="text-secondary fw-medium mb-2">
              Bali
            </h4>

            <div class="text-warning fs-5 d-flex align-items-center">
              ★★★★★
              <span class="text-muted ms-1 small">(5.0)</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Surabaya -->
      <div class="col-md-4">
        <div class="card dest-card overflow-hidden shadow-lg">

          <div class="dest-img-wrap">
            <img src="../../../storages/navbar/surabaya.jpg"
                 alt="Surabaya"
                 class="card-img-top dest-img" />
          </div>

          <div class="card-body py-4 px-3">
            <h4 class="text-secondary fw-medium mb-2">
              Surabaya
            </h4>

            <div class="text-warning fs-5 d-flex align-items-center">
              ★★★★☆
              <span class="text-muted ms-1 small">(4.3)</span>
            </div>

          </div>

        </div>
      </div>

    </div>

  </div>
</section>

<!-- STYLE -->
<style>
  /* Background serasi dengan halaman profil & rute */
  #destination {
    background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%);
    padding-bottom: 70px;
    overflow: hidden;
  }

  /* Glow kiri kanan */
  .glow-dest-left, .glow-dest-right {
    position:absolute;
    width:260px;
    height:260px;
    border-radius:50%;
    filter: blur(130px);
    opacity: .45;
    z-index:1;
  }
  .glow-dest-left { background:#f9b233; top:0; left:-80px; }
  .glow-dest-right { background:#004c84; bottom:0; right:-80px; }

  /* Card */
  .dest-card {
    border-radius:18px;
    border:1px solid #eef2f7;
    transition: .3s ease;
    background:#ffffffd9;
    backdrop-filter: blur(5px);
  }

  .dest-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 26px rgba(0,0,0,0.12);
  }

  /* Image wrap */
  .dest-img-wrap {
    height:250px;
    overflow:hidden;
  }
  .dest-img {
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.4s ease;
  }
  .dest-card:hover .dest-img {
    transform: scale(1.07);
  }

  /* Rating size fix */
  .small { font-size: 14.5px !important; }
</style>
