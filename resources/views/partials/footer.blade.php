<footer id="bantuan" class="footer-wrapper position-relative overflow-hidden">
    
    <!-- ORNAMEN SUDUT: Dibuat menyatu di pojok kiri bawah tanpa terpotong garis copyright -->
    <div class="footer-ornamen-bg">
        <img src="{{ asset('images/Logo-Pojok.png') }}" 
             alt="Ornamen Kemenkes" 
             onerror="this.src='{{ asset('images/logo-pojok.jpeg') }}'">
    </div>

    <div class="container py-4 position-relative" style="z-index: 2;">
        <div class="row g-4">
            
            <!-- Kolom 1: Alamat & Kontak (col-lg-5 col-md-5) -->
            <div class="col-lg-5 col-md-5 col-12">
                <h6 class="text-uppercase font-utama">Balai Kekarantinaan Kesehatan Sorong</h6>
                <p class="small text-white-50 mb-3">
                    Unit Pelaksana Teknis Kementerian Kesehatan Republik Indonesia yang bertugas melaksanakan pencegahan masuk dan keluarnya penyakit potensial wabah dan karantina kesehatan.
                </p>
                <div class="small">
                    <p class="mb-1"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Jl. Jenderal Sudirman Lorong Marcopolo No. 1, Kota Sorong</p>
                    <p class="mb-1"><i class="bi bi-telephone-fill text-success me-2"></i>(0951) 334412</p>
                    <p class="mb-0"><i class="bi bi-envelope-fill text-info me-2"></i>kespel.sorong@gmail.com</p>
                </div>
            </div>

            <!-- Kolom 2: Tautan Terkait (col-lg-3 col-md-3) -->
            <div class="col-lg-3 col-md-3 col-12">
                <h6 class="text-uppercase font-utama">Layanan & Tautan</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                    <li><a href="https://bkksorong.com" target="_blank"><i class="bi bi-globe me-1"></i> Website Resmi BKK Sorong</a></li>
                    <li><a href="https://www.bkksorong.com/ppid/" target="_blank"><i class="bi bi-globe me-1"></i> Website PPID</a></li>
                    <li><a href="https://sinkarkes.kemkes.go.id/vaksinasi_int/vaksinasi_int_public/add" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i> Portal SINKARKES</a></li>
                    <li><a href="https://kemkes.go.id" target="_blank"><i class="bi bi-building me-1"></i> Kemenkes RI</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Media Sosial (col-lg-4 col-md-4) -->
            <div class="col-lg-4 col-md-4 col-12">
                <h6 class="text-uppercase font-utama">Media Sosial & Bantuan</h6>
                <p class="small text-white-50 mb-3">Saluran resmi informasi vaksinasi dan kekarantinaan:</p>
                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <a href="https://www.instagram.com/bkksorong" class="social-icon" title="Instagram" target="_blank"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.youtube.com/@bkksorong" class="social-icon" title="YouTube" target="_blank"><i class="bi bi-youtube"></i></a>
                    <a href="https://api.whatsapp.com/send?phone=6282199369946" target="_blank" class="social-icon bg-success text-white" title="WhatsApp Pelayanan"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

        </div>

        <!-- BARIS RESMI: Core Values ASN di Sisi Kanan -->
        <div class="row pt-3 mt-3 align-items-center border-top" style="border-color: rgba(255, 255, 255, 0.12) !important;">
            <div class="col-12 d-flex justify-content-center justify-content-md-end align-items-center gap-3 flex-wrap">
                <img src="{{ asset('images/Logo-BerAKHLAK-white.png') }}" 
                     alt="Logo BerAKHLAK" 
                     title="BerAKHLAK" 
                     style="height: 42px; width: auto; object-fit: contain;" 
                     onerror="this.style.display='none'">

                <img src="{{ asset('images/logo-bameba-white.png') }}" 
                     alt="Logo Bangga Melayani Bangsa" 
                     title="#BanggaMelayaniBangsa" 
                     style="height: 42px; width: auto; object-fit: contain;" 
                     onerror="this.style.display='none'">
            </div>
        </div>
    </div>

    <!-- Baris Bawah Hak Cipta -->
    <div class="footer-bottom text-center text-white-50 position-relative" style="z-index: 2;">
        <div class="container">
            Copyright &copy; {{ date('Y') }} Balai Kekarantinaan Kesehatan Kelas II Sorong. All Rights Reserved.
        </div>
    </div>
</footer>