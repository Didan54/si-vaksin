<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen Digital - SI-VAKSIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .card-srikandi { 
            border: none; 
            border-radius: 14px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07); 
            overflow: hidden; 
        }
        .header-instansi { 
            background-color: #ffffff; 
            padding: 26px 20px 18px; 
            border-bottom: 1px solid #edf2f7; 
        }
        .instansi-sub {
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.6px;
        }
        .instansi-main {
            color: #495057;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .status-badge { 
            background-color: #d1e7dd; 
            color: #0f5132; 
            border: 1px solid #badbcc; 
            font-size: 13px; 
            font-weight: 600; 
            padding: 8px 18px; 
            border-radius: 50px; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
        }
        .data-label { 
            font-size: 11.5px; 
            color: #6c757d; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            margin-bottom: 2px; 
        }
        .data-val { 
            font-size: 14px; 
            font-weight: 600; 
            color: #212529; 
            margin-bottom: 12px; 
        }
    </style>
</head>
<body class="py-4">
    <div class="container" style="max-width: 650px;">
        <div class="card card-srikandi bg-white">
            
            <!-- Header Instansi (Latar Putih & Teks Abu-abu Elegan) -->
            <div class="header-instansi text-center">
                <img src="{{ asset('images/bkksorong.gif') }}" 
                    alt="Logo BKK Sorong" 
                    class="mb-2" 
                    style="height: 65px; width: auto; object-fit: contain;"
                    onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b2/Garuda_Pancasila_Coat_of_Arms_of_Indonesia.png'; this.style.height='50px';">
                
                <h6 class="instansi-main fw-bold text-uppercase mb-0 mt-1" style="font-weight: 700; line-height: 1.4;">
                    Balai Kekarantinaan Kesehatan<br>Kelas II Sorong
                </h6>
            </div>

            <div class="card-body p-4">
                @if($data && $data->signature_hash)
                    <!-- Lencana Keabsahan -->
                    <div class="text-center mb-3">
                        <span class="status-badge">
                            <i class="bi bi-patch-check-fill fs-5 text-success"></i>
                            Pernyataan Sah & Tercatat Resmi di Basis Data SI-VAKSIN
                        </span>
                    </div>

                    <!-- Judul Dokumen -->
                    <div class="text-center mb-4">
                        <div class="data-label">Nama Dokumen</div>
                        <h6 class="fw-bold text-dark text-uppercase px-3 mb-0" style="font-size: 14.5px; line-height: 1.4;">
                            Lembar Persetujuan Tindakan Medis (Informed Consent)<br>Vaksinasi Internasional
                        </h6>
                    </div>

                    <!-- Rincian Identitas Persetujuan -->
                    <div class="border rounded-3 p-3 bg-light mb-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="data-label">Disetujui Oleh</div>
                                <div class="data-val">{{ $data->nama_paspor }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="data-label">Kapasitas Pemohon</div>
                                <div class="data-val">{{ $data->kapasitas_pemohon }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="data-label">Nomor Registrasi</div>
                                <div class="data-val text-primary font-monospace">{{ $data->nomor_registrasi }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="data-label">Waktu Persetujuan</div>
                                <div class="data-val">
                                    {{ \Carbon\Carbon::parse($data->signed_at)->locale('id')->isoFormat('dddd, D MMMM Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($data->signed_at)->format('H:i') }} WIT</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="data-label">Instansi Penerima Pernyataan</div>
                                <div class="data-val mb-0">Balai Kekarantinaan Kesehatan Kelas II Sorong</div>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex align-items-center gap-2 pt-1 text-success">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span class="small fw-semibold">Terverifikasi menyetujui komitmen integritas & bebas suap/gratifikasi.</span>
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted text-center mb-0">
                        Halaman ini memvalidasi keabsahan dokumen persetujuan tindakan vaksinasi internasional secara elektronik oleh BKK Kelas II Sorong.
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger display-4"></i>
                        <h5 class="fw-bold mt-3 text-danger">Dokumen Tidak Ditemukan</h5>
                        <p class="text-muted small">
                            Nomor registrasi <code>{{ $nomor_registrasi }}</code> tidak valid atau belum terdaftar dalam sistem SI-VAKSIN.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="card-footer bg-light text-center py-2 text-muted" style="font-size: 11px;">
                &copy; {{ date('Y') }} Balai Kekarantinaan Kesehatan Kelas II Sorong — Kementerian Kesehatan RI
            </div>
        </div>
    </div>
</body>
</html>