<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PendaftaranPdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Menampilkan form pendaftaran
Route::get('/', [PendaftaranController::class, 'create'])->name('pendaftaran.create');

// Memproses simpan form & unggahan berkas
Route::post('/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

// Rute Cetak PDF untuk Admin
Route::get('/admin/pendaftaran/{id}/pdf', [PendaftaranPdfController::class, 'cetak'])->name('admin.pendaftaran.pdf');