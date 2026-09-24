@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - BKK Sorong')

@section('content')
<div class="card card-custom mx-auto text-center p-4 my-4" style="max-width: 600px;">
    <div class="card-body py-4">
        
        <!-- Ikon Sukses -->
        <div class="text-success mb-3">
            <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: var(--bkk-toska);"></i>
        </div>

        <h4 class="font-utama fw-bold mb-2" style="color: var(--bkk-navy);">Pendaftaran Berhasil Dikirim!</h4>
        <p class="text-muted small mb-4">
            Data Anda telah tersimpan ke dalam sistem. Silakan simpan / tangkap layar (*screenshot*) nomor registrasi berikut untuk ditunjukkan kepada petugas saat verifikasi berkas di kantor Balai Kekarantinaan Kesehatan Sorong.
        </p>
        
        <!-- Box Nomor Registrasi -->
        <div class="p-3 mb-4 rounded-3 border" style="background-color: #E6F5F4; border-color: var(--bkk-border) !important;">
            <div class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Nomor Registrasi Anda</div>
            <div class="fs-3 fw-bold font-utama" style="color: var(--bkk-toska-dark); letter-spacing: 1.5px;">
                {{ $pendaftaran->nomor_registrasi }}
            </div>
        </div>

        <!-- Rangkuman Data Singkat -->
        <div class="text-start bg-light p-3 rounded-3 mb-4 small border">
            <div class="row mb-1">
                <div class="col-5 text-muted">Nama Pemohon:</div>
                <div class="col-7 fw-semibold">{{ $pendaftaran->nama_paspor }}</div>
            </div>
            @if($pendaftaran->nama_tambahan)
            <div class="row mb-1">
                <div class="col-5 text-muted">Nama Tambahan:</div>
                <div class="col-7">{{ $pendaftaran->nama_tambahan }}</div>
            </div>
            @endif
            <div class="row mb-1">
                <div class="col-5 text-muted">Tanggal Pendaftaran:</div>
                <div class="col-7">{{ $pendaftaran->created_at->translatedFormat('d F Y - H:i') }} WIT</div>
            </div>
            <div class="row">
                <div class="col-5 text-muted">Status Awal:</div>
                <div class="col-7">
                    <span class="badge bg-warning text-dark">{{ $pendaftaran->status_pendaftaran }}</span>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('pendaftaran.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Form Baru
            </a>
            <button onclick="window.print()" class="btn btn-bkk">
                <i class="bi bi-printer me-1"></i> Cetak Bukti
            </button>
        </div>

    </div>
</div>
@endsection