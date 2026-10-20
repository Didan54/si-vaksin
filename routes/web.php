<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PendaftaranPdfController;
use App\Http\Controllers\VerifikasiController;

// 1. Redirect root URL langsung ke halaman formulir pendaftaran
Route::redirect('/', '/daftar');

// 2. Akses dokumen privat (khusus admin terautentikasi)
Route::middleware(['auth'])
    ->get('/dokumen/{path}', [PendaftaranController::class, 'dokumenPrivat'])
    ->where('path', '.*')
    ->name('dokumen.privat');

// 3. Baca dan bersihkan notifikasi per pendaftaran untuk admin
Route::middleware(['auth'])
    ->get('/admin/baca-notif/{nomor_registrasi}', [PendaftaranController::class, 'bacaNotif'])
    ->name('admin.notif.baca');

// 4. Rute Formulir & Simpan Pendaftaran
Route::get('/daftar', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/daftar', [PendaftaranController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('pendaftaran.store');

// 5. Rute Cetak Dokumen PDF
Route::get('/admin/pendaftaran/{id}/pdf', [PendaftaranPdfController::class, 'cetak'])->name('admin.pendaftaran.pdf');

// 6.Verif Persetujuan
Route::get('/verifikasi/{nomor_registrasi}', [VerifikasiController::class, 'show'])
    ->name('verifikasi.dokumen');