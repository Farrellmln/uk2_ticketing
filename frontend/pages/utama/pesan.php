<section id="pesan" class="py-5 position-relative">

  <!-- Glow kiri kanan -->
  <div class="pesan-glow-left"></div>
  <div class="pesan-glow-right"></div>

  <div class="container position-relative" style="z-index: 2;">

    <!-- JUDUL -->
    <div class="text-center mb-5">
      <h3 class="fw-bold" style="color:#f9b233;">Hubungi Kami</h3>
      <p class="text-muted">Masukan, kritik, dan pesan Anda sangat berarti bagi kami</p>
    </div>

    <!-- 3 CARD INFORMASI -->
    <div class="row g-4 mb-5">

      <!-- Card Telp -->
      <div class="col-md-4">
        <div class="card pesan-card shadow-sm border-0 p-4 h-100">
          <h5 class="fw-bold text-dark mb-2">Telepon</h5>
          <p class="mb-1"><strong>Telp :</strong> (0293) 4903350</p>
          <p class="text-muted small mb-1">Senin - Minggu : 00.00 - 24.00</p>
        </div>
      </div>

      <!-- Card WA -->
      <div class="col-md-4">
        <div class="card pesan-card shadow-sm border-0 p-4 h-100">
          <h5 class="fw-bold text-dark mb-2">WhatsApp</h5>
          <p class="mb-1"><strong>WA :</strong> +6285727167740</p>
          <p class="text-muted small">Hotline</p>
        </div>
      </div>

      <!-- Card Email -->
      <div class="col-md-4">
        <div class="card pesan-card shadow-sm border-0 p-4 h-100">
          <h5 class="fw-bold text-dark mb-2">Email</h5>
          <p class="mb-1"><strong>Email :</strong> info@Goticket.com</p>
          <p class="text-muted small">Email resmi pelayanan</p>
        </div>
      </div>

    </div>

    <!-- FORM MASUKAN + TOMBOL -->
    <!-- <div class="card pesan-form-card shadow border-0 p-4 p-md-5">
      <form class="row g-3"> -->

        <!-- INPUT MEMANJANG -->
        <!-- <div class="col-md-10">
          <textarea class="form-control form-control-lg pesan-input"
            rows="3"
            placeholder="Tulis pesan atau masukan Anda di sini...">
          </textarea>
        </div> -->

        <!-- TOMBOL -->
        <!-- <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn pesan-btn w-100 py-3 text-white fw-semibold">
            Kirim
          </button>
        </div>

      </form>
    </div> -->

  </div>
</section>


<style>
  /* Background serasi */
  #pesan {
    background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%);
    overflow: hidden;
  }

  /* Glow kiri kanan */
  .pesan-glow-left,
  .pesan-glow-right {
    position: absolute;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    filter: blur(140px);
    opacity: 0.45;
    z-index: 1;
  }
  .pesan-glow-left {
    background: #f9b233;
    top: -20px;
    left: -80px;
  }
  .pesan-glow-right {
    background: #004c84;
    bottom: 0;
    right: -80px;
  }

  /* Card style */
  .pesan-card,
  .pesan-form-card {
    border-radius: 18px;
    background: #ffffffdd;
    backdrop-filter: blur(4px);
    border: 1px solid #eef2f7;
  }

  /* Input styling */
  .pesan-input {
    border-radius: 14px;
    border: 1.5px solid #e0e6ed;
  }
  .pesan-input:focus {
    border-color: #f9b233 !important;
    box-shadow: 0 0 0 .2rem rgba(249, 178, 51, 0.25) !important;
  }

  /* Button */
  .pesan-btn {
    background: #f9b233;
    border-radius: 12px;
    border: none;
  }
  .pesan-btn:hover {
    background: #e3a728 !important;
    transform: translateY(-2px);
    transition: 0.25s ease;
  }
</style>
