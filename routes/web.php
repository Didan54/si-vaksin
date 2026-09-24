<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Menampilkan form pendaftaran
Route::get('/', [PendaftaranController::class, 'create'])->name('pendaftaran.create');

// Memproses simpan form & unggahan berkas
Route::post('/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

// Halaman bukti sukses pendaftaran
Route::get('/daftar/sukses/{id}', [PendaftaranController::class, 'sukses'])->name('pendaftaran.sukses');
