<!-- TOP HEADER (BRANDING & LOGO) -->
<header class="top-header">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="text-center text-md-start">
            <h6 class="header-title-main font-utama mb-0">BALAI KEKARANTINAAN KESEHATAN KELAS II SORONG</h6>
            <div class="header-subtitle">Kementerian Kesehatan Republik Indonesia</div>
        </div>

        <div class="logo-slot d-flex align-items-center justify-content-center gap-3 flex-wrap">
            <img src="{{ asset('images/bkksorong.gif') }}" alt="Logo Kemenkes BKK" title="BKK Sorong" onerror="this.style.display='none'">
            <img src="{{ asset('images/logo-karkes.png') }}" alt="Logo Lambang Karkes" title="Kekarantinaan Kesehatan" onerror="this.style.display='none'">
            <img src="{{ asset('images/logo-tolak-korupsi.gif') }}" alt="Logo Tolak Korupsi" title="Tolak Korupsi" onerror="this.style.display='none'">
        </div>
    </div>
</header>

<!-- NAVIGASI MENU -->
<nav class="navbar navbar-expand-lg navbar-bkk py-1">
    <div class="container">
        <span class="navbar-brand text-white font-utama fs-6 d-lg-none">E-VAKSIN BKK SORONG</span>
        <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="bi bi-list fs-2 text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/') }}"><i class="bi bi-pencil-square me-1"></i> Formulir Pendaftaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://bkksorong.com" target="_blank"><i class="bi bi-info-circle me-1"></i> Profil BKK</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#bantuan"><i class="bi bi-telephone me-1"></i> Kontak Layanan</a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white-50 small">
                <i class="bi bi-shield-check text-success me-1 fs-5"></i> Sistem Resmi Pelayanan Vaksinasi
            </div>
        </div>
    </div>
</nav>

<!-- HERO BANNER -->
<section class="hero-section">
    <div class="container">
        <h2 class="font-utama">Pelayanan Vaksinasi Internasional</h2>
        <p class="mb-0 text-white-50 small">Pendaftaran Daring, Verifikasi Berkas SINKARKES & Penapisan Kelayakan Vaksinasi</p>
    </div>
</section>