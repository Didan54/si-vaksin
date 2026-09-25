<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berkas Pendaftaran - {{ $pendaftaran->nomor_registrasi }}</title>
    <!-- Menghubungkan CSS Eksternal via path server publik -->
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/pdf-pendaftaran.css') }}">
</head>
<body>

    <!-- ================= HALAMAN 1: FORMULIR PENDAFTARAN ================= -->
    <div class="kop-surat">
        <h4>Kementerian Kesehatan Republik Indonesia</h4>
        <h3>BALAI KEKARANTINAAN KESEHATAN KELAS I SORONG</h3>
        <p>Jl. Jend. Sudirman No. 1, Klademak, Sorong - Papua Barat Daya | Telp: (0951) 321855</p>
    </div>

    <div class="judul-dokumen">FORMULIR PENDAFTARAN VAKSINASI INTERNASIONAL</div>

    <table class="data-table">
        <tr>
            <td style="width: 32%;" class="fw-bold">No. Registrasi Sistem</td>
            <td style="width: 3%;">:</td>
            <td style="width: 65%; font-weight: bold; color: #0b5ed7;">{{ $pendaftaran->nomor_registrasi }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Tanggal Pengajuan</td>
            <td>:</td>
            <td>{{ $pendaftaran->created_at->translatedFormat('d F Y - H:i') }} WIT</td>
        </tr>
        <tr>
            <td class="fw-bold">Status Verifikasi</td>
            <td>:</td>
            <td><span class="badge">{{ $pendaftaran->status_pendaftaran }}</span></td>
        </tr>
    </table>

    <div class="fw-bold" style="margin-bottom: 6px; border-bottom: 1px solid #ddd; padding-bottom: 3px;">A. DATA IDENTITAS PEMOHON</div>
    <table class="data-table">
        <tr>
            <td style="width: 32%;">Nama Sesuai Paspor</td>
            <td style="width: 3%;">:</td>
            <td style="width: 65%;" class="fw-bold">{{ $pendaftaran->nama_paspor }}</td>
        </tr>
        <tr>
            <td>Nama Tambahan</td>
            <td>:</td>
            <td>{{ $pendaftaran->nama_tambahan ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIK (KTP)</td>
            <td>:</td>
            <td>{{ $pendaftaran->nik }}</td>
        </tr>
        <tr>
            <td>Nomor Paspor</td>
            <td>:</td>
            <td class="fw-bold">{{ $pendaftaran->no_paspor }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $pendaftaran->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
    </table>

    <div class="fw-bold" style="margin-top: 15px; margin-bottom: 6px; border-bottom: 1px solid #ddd; padding-bottom: 3px;">B. LAYANAN VAKSINASI & KARTU</div>
    <table class="data-table">
        <tr>
            <td style="width: 32%;">Vaksinasi yang Diajukan</td>
            <td style="width: 3%;">:</td>
            <td style="width: 65%;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($pendaftaran->vaksins as $v)
                        <li class="fw-bold">{{ $v->nama_vaksin }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td>Riwayat Kartu Vaksin</td>
            <td>:</td>
            <td>
                @if(!empty($pendaftaran->kartu_vaksin))
                    {{ implode(', ', $pendaftaran->kartu_vaksin) }}
                @else
                    Belum Memiliki / Tidak Membawa
                @endif
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center;">
                Sorong, {{ now()->translatedFormat('d F Y') }}<br>
                Petugas Verifikator Loket,<br><br><br><br>
                ( .................................................... )
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 2: PENAPISAN MEDIS ================= -->
    <div class="kop-surat">
        <h4>Kementerian Kesehatan Republik Indonesia</h4>
        <h3>BALAI KEKARANTINAAN KESEHATAN KELAS I SORONG</h3>
        <p>Jl. Jend. Sudirman No. 1, Klademak, Sorong - Papua Barat Daya | Telp: (0951) 321855</p>
    </div>

    <div class="judul-dokumen">LEMBAR PENAPISAN KONTRAINDIKASI VAKSINASI</div>

    <table style="width: 100%; margin-bottom: 10px; font-size: 10pt;">
        <tr>
            <td style="width: 20%;">Nama Pemohon</td>
            <td style="width: 3%;">:</td>
            <td style="width: 45%;" class="fw-bold">{{ $pendaftaran->nama_paspor }}</td>
            <td style="width: 12%;">No. Reg</td>
            <td style="width: 3%;">:</td>
            <td style="width: 17%;" class="fw-bold">{{ $pendaftaran->nomor_registrasi }}</td>
        </tr>
    </table>

    <table class="border-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 76%;">Daftar Tilik Pertanyaan Skrining Kesehatan</th>
                <th style="width: 18%;">Jawaban Pemohon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pertanyaanSkrining as $no => $pertanyaan)
                @php
                    $jawaban = $pendaftaran->data_skrining[$no] ?? '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $no }}</td>
                    <td>{{ $pertanyaan }}</td>
                    <td class="text-center fw-bold" style="{{ $jawaban == 'Ya' ? 'color: red;' : '' }}">
                        {{ $jawaban }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px; border: 1px solid #444; padding: 10px; font-size: 10pt;">
        <div class="fw-bold" style="text-decoration: underline; margin-bottom: 4px;">KESIMPULAN DOKTER / PETUGAS SCREENING:</div>
        <p style="margin: 0;">[ &nbsp; ] <strong>LAYAK</strong> diberikan vaksinasi.</p>
        <p style="margin: 0;">[ &nbsp; ] <strong>DITUNDA</strong> karena alasan medis: ....................................................................................</p>
        <p style="margin: 0;">[ &nbsp; ] <strong>TIDAK LAYAK / KONTRAINDIKASI</strong>.</p>
    </div>

    <table style="width: 100%; margin-top: 25px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                Tanda Tangan Pemohon,<br><br><br><br>
                ( <strong>{{ $pendaftaran->nama_paspor }}</strong> )
            </td>
            <td style="width: 50%; text-align: center;">
                Dokter Pemeriksa Klinik,<br><br><br><br>
                ( .................................................... )
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 3: LAMPIRAN PASPOR ================= -->
    <div class="kop-surat">
        <h4>Lampiran Dokumen Pendaftaran</h4>
        <h3>SALINAN / FOTO FISIK PASPOR</h3>
        <p>No. Registrasi: {{ $pendaftaran->nomor_registrasi }} | Pemohon: {{ $pendaftaran->nama_paspor }} ({{ $pendaftaran->no_paspor }})</p>
    </div>

    <div class="lampiran-box">
        @if($pasporBase64)
            <img src="{{ $pasporBase64 }}" class="lampiran-img" alt="Foto Paspor">
        @else
            <p style="color: red; margin-top: 80px;">Berkas fisik paspor berupa format dokumen PDF atau belum diunggah.</p>
        @endif
    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 4: LAMPIRAN KTP ================= -->
    <div class="kop-surat">
        <h4>Lampiran Dokumen Pendaftaran</h4>
        <h3>SALINAN / FOTO FISIK KTP</h3>
        <p>No. Registrasi: {{ $pendaftaran->nomor_registrasi }} | NIK: {{ $pendaftaran->nik }}</p>
    </div>

    <div class="lampiran-box">
        @if($ktpBase64)
            <img src="{{ $ktpBase64 }}" class="lampiran-img" alt="Foto KTP">
        @else
            <p style="color: red; margin-top: 80px;">Berkas fisik KTP berupa format dokumen PDF atau belum diunggah.</p>
        @endif
    </div>

</body>
</html>