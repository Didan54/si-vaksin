<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function show($nomor_registrasi)
    {
        $data = Pendaftaran::where('nomor_registrasi', $nomor_registrasi)->first();

        return view('verifikasi.verifikasi-style', compact('data', 'nomor_registrasi'));
    }
}
