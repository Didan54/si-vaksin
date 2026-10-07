<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PendaftaranPdfController;
use Illuminate\Support\Facades\Storage;

// Redirect root URL (/) langsung ke halaman formulir pendaftaran
Route::get('/', function () {
    return redirect()->route('pendaftaran.create');
});

// Akses dokumen privat (hanya untuk admin yang login)
Route::middleware(['auth'])->get('/dokumen/{path}', function ($path) {
    $cleanPath = ltrim(str_replace('lampiran_berkas/', '', $path), '/');
    $filePath = 'lampiran_berkas/' . $cleanPath;

    // 1. Cek di direktori privat default (storage/app/lampiran_berkas/...)
    if (Storage::disk('local')->exists($filePath)) {
        return response()->file(storage_path('app/' . $filePath));
    }

    // 2. Cek path langsung jika tersimpan penuh di database
    if (Storage::disk('local')->exists($path)) {
        return response()->file(storage_path('app/' . $path));
    }

    abort(404, 'Dokumen tidak ditemukan atau akses ditolak.');
})->where('path', '.*')->name('dokumen.privat');

// Rute Halaman Formulir Pendaftaran
Route::get('/daftar', [PendaftaranController::class, 'create'])->name('pendaftaran.create');

// Rute Simpan Pendaftaran
Route::post('/daftar', [PendaftaranController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('pendaftaran.store');

// Rute Cetak PDF untuk Admin
Route::get('/admin/pendaftaran/{id}/pdf', [PendaftaranPdfController::class, 'cetak'])->name('admin.pendaftaran.pdf');