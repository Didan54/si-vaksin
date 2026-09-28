<div class="flex items-center gap-3 py-1">
    <!-- Gambar Logo BKK / Kemenkes -->
    <img src="{{ asset('images/bkksorong.gif') }}" 
         alt="Logo BKK" 
         class="h-9 w-auto object-contain drop-shadow-sm"
         onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">

    <!-- Ikon Fallback (Muncul otomatis jika file gambar logo-bkk.png belum ditaruh) -->
    <div id="logo-fallback" style="display: none;" class="h-9 w-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
    </div>

    <!-- Teks Branding Instansi -->
    <div class="flex flex-col text-left">
        <span class="font-extrabold text-base tracking-wide text-teal-700 leading-none">
            SI-VAKSIN
        </span>
    </div>
</div>