<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pendaftaran Vaksinasi Internasional - BKK Sorong')</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts (Nunito untuk VAG Rounded fallback) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- File CSS Eksternal -->
    <link rel="stylesheet" href="{{ asset('css/style-vaksin.css') }}?v={{ time() }}">
</head>
<body>

    <!-- Header & Navbar -->
    @include('partials.header')

    <!-- Konten Halaman -->
    <main class="container my-4 pb-4">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Stack untuk skrip JavaScript tambahan dari halaman -->
    @stack('scripts')
</body>
</html>