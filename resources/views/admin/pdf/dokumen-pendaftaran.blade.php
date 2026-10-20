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

    <div class="judul-dokumen">FORMULIR PENDAFTARAN VAKSINASI</div>

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
            <td class="fw-bold">Rencana Kunjungan</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_kunjungan)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Status Berkas</td>
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
            <td>Nomor Paspor</td>
            <td>:</td>
            <td class="fw-bold">{{ $pendaftaran->no_paspor ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Domisili</td>
            <td>:</td>
            <td>{{ $pendaftaran->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}</td>
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

    <div class="page-break"></div>

    <!-- ================= HALAMAN 2: SURAT PERNYATAAN / INFORMED CONSENT TERSENDIRI ================= -->
    <div class="kop-surat">
        <h4>Kementerian Kesehatan Republik Indonesia</h4>
        <h3>BALAI KEKARANTINAAN KESEHATAN KELAS I SORONG</h3>
        <p>Jl. Jend. Sudirman No. 1, Klademak, Sorong - Papua Barat Daya | Telp: (0951) 321855</p>
    </div>

    <div class="judul-dokumen">SURAT PERNYATAAN PERSETUJUAN VAKSINASI<br><span style="font-size: 10pt; font-weight: normal;"></span></div>

    <table style="width: 100%; margin-bottom: 15px; font-size: 10pt;">
        <tr>
            <td style="width: 25%;">No. Registrasi</td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%; font-weight: bold;">{{ $pendaftaran->nomor_registrasi }}</td>
        </tr>
        <tr>
            <td>Nama Pemohon</td>
            <td>:</td>
            <td style="font-weight: bold;">{{ $pendaftaran->nama_paspor }}</td>
        </tr>
        <tr>
            <td>Nomor Paspor</td>
            <td>:</td>
            <td>{{ $pendaftaran->no_paspor ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Alamat Domisili</td>
            <td>:</td>
            <td>{{ $pendaftaran->alamat ?? '-' }}</td>
        </tr>
    </table>

    <div style="font-size: 10pt; line-height: 1.8; text-align: justify; margin-top: 15px;">
        <p>
            Yang bertanda tangan di bawah ini menyatakan dengan sesungguhnya bahwa:
        </p>

        <ol style="margin-left: 20px; padding-left: 0;">
            <li style="margin-bottom: 10px;">
                Saya bersedia mengikuti pelayanan vaksinasi internasional terhadap: <strong>{{ strtoupper($pendaftaran->persetujuan_untuk ?? 'SAYA SENDIRI') }}</strong> setelah menerima penjelasan lengkap dari tenaga kesehatan mengenai manfaat, indikasi, kontraindikasi, serta kemungkinan risiko efek samping vaksin.
            </li>
            <li style="margin-bottom: 10px;">
                Data dan informasi kesehatan serta riwayat yang saya berikan dalam formulir pendaftaran dan penapisan medis adalah data yang sebenar-benarnya sesuai dengan kondisi saat ini.
            </li>
            <li style="margin-bottom: 10px;">
                Saya bersedia mematuhi seluruh alur pemeriksaan fisik dan evaluasi medis yang ditentukan oleh dokter pemeriksa sebelum dilakukannya tindakan vaksinasi.
            </li>
            <li style="margin-bottom: 10px;">
                Saya berkomitmen untuk tidak memberikan imbalan, hadiah, uang, atau gratifikasi dalam bentuk apa pun kepada petugas kesehatan maupun verifikator dalam pelaksanaan pelayanan vaksinasi ini.
            </li>
        </ol>

        <p style="margin-top: 15px;">
            Demikian surat pernyataan persetujuan ini dibuat dengan penuh kesadaran dan tanggung jawab pribadi tanpa ada paksaan dari pihak mana pun untuk digunakan sebagaimana mestinya.
        </p>

        <table style="width: 100%; border: none !important; border-collapse: collapse; margin-top: 35px; background: transparent;">
            <tr style="border: none !important;">
                <!-- Kolom kiri kosong untuk mendorong tanda tangan ke kanan -->
                <td style="width: 55%; border: none !important; background: transparent;"></td>
                
                <!-- Area Tanda Tangan (Sisi Kanan) -->
                <td style="width: 45%; text-align: center; border: none !important; background: transparent; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11pt; line-height: 1.4;">
                    <p style="margin: 0 0 12px 0;">
                        Sorong, {{ \Carbon\Carbon::parse($pendaftaran->signed_at ?? $pendaftaran->tgl_persetujuan ?? now())->locale('id')->isoFormat('D MMMM Y') }}<br>
                        <strong>Pemohon / Yang Menyetujui,</strong>
                    </p>
                    
                    <!-- Gambar QR Code Tanpa Border/Bayangan -->
                    <div style="margin: 8px auto; width: 100px;">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(95)->generate(url('/verifikasi/' . $pendaftaran->nomor_registrasi))) }}" alt="QR Code" style="display: block; width: 100%; height: auto; border: none !important; outline: none !important;">
                    </div>

                    <!-- Identitas Pemohon -->
                    <p style="margin: 10px 0 0 0; font-weight: bold; text-decoration: underline;">
                        {{ $pendaftaran->nama_paspor }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 3: PENAPISAN MEDIS, TANDA VITAL & STOK ================= -->
    <div class="kop-surat">
        <h4>Kementerian Kesehatan Republik Indonesia</h4>
        <h3>BALAI KEKARANTINAAN KESEHATAN KELAS I SORONG</h3>
        <p>Jl. Jend. Sudirman No. 1, Klademak, Sorong - Papua Barat Daya | Telp: (0951) 321855</p>
    </div>

    <div class="judul-dokumen">LEMBAR PENAPISAN KONTRAINDIKASI VAKSINASI</div>

    <table style="width: 100%; margin-bottom: 8px; font-size: 9pt;">
        <tr>
            <td style="width: 18%;">Nama Pemohon</td>
            <td style="width: 2%;">:</td>
            <td style="width: 45%;" class="fw-bold">{{ $pendaftaran->nama_paspor }}</td>
            <td style="width: 12%;">No. Reg</td>
            <td style="width: 2%;">:</td>
            <td style="width: 21%;" class="fw-bold">{{ $pendaftaran->nomor_registrasi }}</td>
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

    <!-- HASIL PEMERIKSAAN FISIK & KELAYAKAN MEDIS -->
    <div style="margin-top: 10px; font-size: 9pt; font-family: Arial, sans-serif;">
        
        <!-- 1. Tanda-Tanda Vital -->
        <table style="width: 100%; border: 1px solid #444; border-collapse: collapse; margin-bottom: 6px;">
            <tr style="background: #f8fafc;">
                <td style="padding: 4px 8px; width: 25%; border-right: 1px solid #444;">
                    <strong>TD :</strong> {{ $pendaftaran->tekanan_darah ? $pendaftaran->tekanan_darah . ' mmHg' : '-' }}
                </td>
                <td style="padding: 4px 8px; width: 25%; border-right: 1px solid #444;">
                    <strong>S :</strong> {{ $pendaftaran->suhu_tubuh ? $pendaftaran->suhu_tubuh . ' °C' : '-' }}
                </td>
                <td style="padding: 4px 8px; width: 25%; border-right: 1px solid #444;">
                    <strong>N :</strong> {{ $pendaftaran->denyut_nadi ? $pendaftaran->denyut_nadi . ' x/mnt' : '-' }}
                </td>
                <td style="padding: 4px 8px; width: 25%;">
                    <strong>Spo2 :</strong> {{ $pendaftaran->spo2 ? $pendaftaran->spo2 . ' %' : '-' }}
                </td>
            </tr>
        </table>

        <!-- 2. Pencatatan Stok Vaksin Offline -->
        <table style="width: 100%; border: 1px solid #444; border-collapse: collapse; margin-bottom: 6px;">
            <tr style="background: #ffffff;">
                <td style="padding: 4px 8px; width: 35%; background: #f1f5f9; font-weight: bold; border-right: 1px solid #444;">
                    Pengambilan Stok Vaksin:
                </td>
                <td style="padding: 4px 8px; width: 65%; font-weight: bold; color: #0f172a;">
                    {{ $pendaftaran->stok_vaksin ? $pendaftaran->stok_vaksin : '(Dicatat via Sinkarkes Online)' }}
                </td>
            </tr>
        </table>

        <!-- 3. Petugas & Dokter Verifikator -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 120px;">Diisi oleh (Petugas)</td>
                            <td style="width: 10px;">:</td>
                            <td><strong>{{ $pendaftaran->nama_petugas ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>{{ $pendaftaran->tgl_petugas ? \Carbon\Carbon::parse($pendaftaran->tgl_petugas)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>

                <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 135px;">Diverifikasi oleh (Dokter)</td>
                            <td style="width: 10px;">:</td>
                            <td><strong>{{ $pendaftaran->nama_dokter ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>{{ $pendaftaran->tgl_dokter ? \Carbon\Carbon::parse($pendaftaran->tgl_dokter)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- 4. Kesimpulan Medis Kelayakan Vaksin -->
        <div style="border: 1px solid #444; padding: 5px 8px; background: #ffffff;">
    <strong>Kesimpulan Medis :</strong> 
    @if(!empty($pendaftaran->nama_dokter) && !empty($pendaftaran->status_kelayakan))
        <span style="font-weight: bold; color: {{ $pendaftaran->status_kelayakan == 'Layak Vaksin' ? '#047857' : '#b91c1c' }};">
            {{ strtoupper($pendaftaran->status_kelayakan == 'Layak Vaksin' ? 'LAYAK DIBERIKAN VAKSINASI' : $pendaftaran->status_kelayakan) }}
        </span>
    @else
        <span style="font-weight: bold; color: #64748b; font-style: italic;">
            MENUNGGU PEMERIKSAAN DOKTER
        </span>
    @endif

    @if($pendaftaran->catatan_dokter)
        <span style="color: #475569; margin-left: 8px;">(Catatan: {{ $pendaftaran->catatan_dokter }})</span>
    @endif
</div>

    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 4: LAMPIRAN PASPOR ================= -->
    <div class="kop-surat">
        <h4>Lampiran Dokumen Pendaftaran</h4>
        <h3>SALINAN / FOTO FISIK PASPOR</h3>
        <p>No. Registrasi: {{ $pendaftaran->nomor_registrasi }} | Pemohon: {{ $pendaftaran->nama_paspor }} ({{ $pendaftaran->no_paspor ?? '-' }})</p>
    </div>

    <div class="lampiran-box">
        @if($pasporBase64)
            <img src="{{ $pasporBase64 }}" class="lampiran-img" alt="Foto Paspor">
        @else
            <p style="color: red; margin-top: 80px;">Berkas fisik paspor berupa format dokumen PDF atau belum diunggah.</p>
        @endif
    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 5: LAMPIRAN KTP ================= -->
    <div class="kop-surat">
        <h4>Lampiran Dokumen Pendaftaran</h4>
        <h3>SALINAN / FOTO FISIK KTP</h3>
        <p>No. Registrasi: {{ $pendaftaran->nomor_registrasi }} | Pemohon: {{ $pendaftaran->nama_paspor }} ({{ $pendaftaran->no_paspor ?? '-' }})</p>
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