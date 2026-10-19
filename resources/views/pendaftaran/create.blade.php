@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Vaksinasi - BKK Sorong')

@section('content')
<div class="card card-custom mx-auto" style="max-width: 900px;">
    
    <div class="card-form-header">
        <h5 class="mb-1 font-utama"><i class="bi bi-file-earmark-medical me-2"></i>Formulir Pendaftaran & Penapisan</h5>
        <p class="mb-0 small text-white-65">Lengkapi data diri sesuai paspor dan periksa kondisi kesehatan Anda.</p>
    </div>

    <div class="card-body p-4">

        <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 1. Data Pelaku Perjalanan (Pemohon) -->
            <h5 class="section-title">1. Data Pelaku Perjalanan (Pemohon)</h5>
            <div class="row g-3 mb-3">
                <!-- Baris 1: Nama Paspor & Nama Tambahan -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Sesuai Paspor <span class="text-danger">*</span></label>
                    <input type="text" 
                        name="nama_paspor" 
                        class="form-control @error('nama_paspor') is-invalid @enderror" 
                        placeholder="Masukkan nama sesuai paspor" 
                        required 
                        value="{{ old('nama_paspor') }}">
                    @error('nama_paspor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Tambahan <span class="text-danger">*</span></label>
                    <input type="text" 
                        name="nama_tambahan" 
                        class="form-control @error('nama_tambahan') is-invalid @enderror" 
                        placeholder="Nama ayah/marga/tambahan" 
                        required 
                        value="{{ old('nama_tambahan') }}">
                    <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Wajib diisi sesuai nama tambahan di paspor (terutama nama 1 suku kata).</small>
                    @error('nama_tambahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Baris 2: Nomor Paspor & Alamat -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Paspor <span class="text-danger">*</span></label>
                    <input type="text" 
                        name="no_paspor" 
                        class="form-control @error('no_paspor') is-invalid @enderror" 
                        placeholder="Nomor paspor pemohon (contoh: C1234567)" 
                        maxlength="25" 
                        required 
                        value="{{ old('no_paspor') }}">
                    @error('no_paspor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                    <input type="text" 
                        name="alamat" 
                        class="form-control @error('alamat') is-invalid @enderror" 
                        placeholder="Alamat lengkap tempat tinggal pemohon" 
                        required 
                        value="{{ old('alamat') }}">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Baris 3: Tanggal Lahir & Jenis Kelamin -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" 
                        name="tanggal_lahir" 
                        class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                        required 
                        value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" onchange="filterGender()" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 2. UNGGAH DOKUMEN PERSYARATAN -->
            <div class="mb-4">
                <h5 class="section-title font-utama">2. Unggah Berkas Persyaratan</h5>
                
                <div class="alert alert-info py-2 px-3 mb-3 small border-0 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                    <div>
                        <strong>Ketentuan Format:</strong> Berkas SINKARKES wajib format <strong>PDF</strong>. Paspor & KTP wajib format gambar/foto (<strong>JPG, JPEG, PNG</strong>). Ukuran maksimal <strong>1 MB</strong> per berkas.
                    </div>
                </div>

                <div class="row g-3">
                    <!-- 1. Tanda Terima SINKARKES (Hanya PDF) -->
                    <div class="col-md-6">
                        <label for="file_sinkarkes_terima" class="form-label fw-semibold">
                            Tanda Terima Pendaftaran SINKARKES <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                            class="form-control @error('file_sinkarkes_terima') is-invalid @enderror" 
                            id="file_sinkarkes_terima" 
                            name="file_sinkarkes_terima" 
                            accept="application/pdf" 
                            onchange="validasiBerkas(this)"
                            required>
                        <div class="form-text small text-muted">Wajib format: PDF (Maks. 1 MB)</div>
                        @error('file_sinkarkes_terima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 2. Formulir Pendaftaran SINKARKES (Hanya PDF) -->
                    <div class="col-md-6">
                        <label for="file_sinkarkes_form" class="form-label fw-semibold">
                            Formulir Pendaftaran SINKARKES <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                            class="form-control @error('file_sinkarkes_form') is-invalid @enderror" 
                            id="file_sinkarkes_form" 
                            name="file_sinkarkes_form" 
                            accept="application/pdf" 
                            onchange="validasiBerkas(this)"
                            required>
                        <div class="form-text small text-muted">Wajib format: PDF (Maks. 1 MB)</div>
                        @error('file_sinkarkes_form')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 3. Paspor Pemohon (Hanya Gambar) -->
                    <div class="col-md-6">
                        <label for="file_paspor" class="form-label fw-semibold">
                            Foto Paspor Pemohon <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                            class="form-control @error('file_paspor') is-invalid @enderror" 
                            id="file_paspor" 
                            name="file_paspor" 
                            accept="image/jpeg,image/png,image/jpg" 
                            onchange="validasiBerkas(this)"
                            required>
                        <div class="form-text small text-muted">Wajib format foto: JPG / JPEG / PNG (Maks. 1 MB)</div>
                        @error('file_paspor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 4. KTP Pemohon (Hanya Gambar) -->
                    <div class="col-md-6">
                        <label for="file_ktp" class="form-label fw-semibold">
                            Foto KTP Pemohon <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                            class="form-control @error('file_ktp') is-invalid @enderror" 
                            id="file_ktp" 
                            name="file_ktp" 
                            accept="image/jpeg,image/png,image/jpg" 
                            onchange="validasiBerkas(this)"
                            required>
                        <div class="form-text small text-muted">Wajib format foto: JPG / JPEG / PNG (Maks. 1 MB)</div>
                        @error('file_ktp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 3. RIWAYAT KARTU VAKSINASI -->
            <h5 class="section-title">3. Riwayat Kartu Vaksinasi</h5>
            <div class="box-info-vaksin mb-4">
                <label class="form-label fw-semibold d-block mb-2">Pilih kartu/sertifikat vaksinasi yang Anda miliki saat ini (centang bila ada):</label>
                <div class="d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="kartu_vaksin[]" 
                               value="e-ICV" 
                               id="cek_eicv" 
                               {{ (is_array(old('kartu_vaksin')) && in_array('e-ICV', old('kartu_vaksin'))) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="cek_eicv">
                            Membawa / Memiliki Sertifikat Elektronik (e-ICV)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="kartu_vaksin[]" 
                               value="ICV" 
                               id="cek_icv" 
                               {{ (is_array(old('kartu_vaksin')) && in_array('ICV', old('kartu_vaksin'))) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="cek_icv">
                            Membawa Kartu / Buku Kuning Fisik (ICV)
                        </label>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">*Biarkan kosong jika belum pernah vaksinasi internasional atau tidak membawa kartu fisik/elektronik.</small>
            </div>

            <!-- 4. PILIH LAYANAN & JADWAL KUNJUNGAN -->
            <h5 class="section-title">4. Layanan & Rencana Kunjungan</h5>
            <p class="text-muted small mb-3">Pilih tanggal rencana kedatangan dan vaksinasi yang ingin diajukan:</p>

            <div class="row g-3 mb-4">
                <div class="col-md-7">
                    <label class="form-label fw-semibold">Rencana Tanggal Pelayanan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white" style="cursor: pointer;" onclick="document.getElementById('tanggal_kunjungan').focus()">
                            <i class="bi bi-calendar2-check text-primary fs-5"></i>
                        </span>
                        <input type="text" 
                               name="tanggal_kunjungan" 
                               id="tanggal_kunjungan" 
                               class="form-control bg-white @error('tanggal_kunjungan') is-invalid @enderror" 
                               placeholder="Pilih tanggal kedatangan Anda..." 
                               required 
                               value="{{ old('tanggal_kunjungan') }}" 
                               style="cursor: pointer;">
                    </div>
                    @error('tanggal_kunjungan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label class="form-label fw-semibold mb-2">Pilihan Jenis Vaksinasi <span class="text-danger">*</span></label>
            <div class="row g-3 mb-2">
                @foreach($daftarVaksin as $vaksin)
                    <div class="col-md-6">
                        <div class="card card-vaksin-item h-100 {{ !$vaksin->is_aktif ? 'vaksin-disabled' : '' }}">
                            <label class="card-body d-flex align-items-center justify-content-between p-3 mb-0 {{ $vaksin->is_aktif ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="checkbox" 
                                           name="vaksin_id[]" 
                                           value="{{ $vaksin->id }}" 
                                           class="form-check-input mt-0" 
                                           {{ !$vaksin->is_aktif ? 'disabled' : '' }} 
                                           {{ (is_array(old('vaksin_id')) && in_array($vaksin->id, old('vaksin_id'))) ? 'checked' : '' }}>
                                    
                                    <span class="fw-bold fs-6 {{ $vaksin->is_aktif ? 'text-dark' : 'text-muted' }}">
                                        {{ $vaksin->nama_vaksin }}
                                    </span>
                                </div>

                                @if(!$vaksin->is_aktif)
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 small">
                                        <i class="bi bi-slash-circle me-1"></i>Vaksin Tidak Tersedia
                                    </span>
                                @endif
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('vaksin_id')
                <div class="text-danger small mt-1 mb-3">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror

            <!-- 5. PENAPISAN KONTRAINDIKASI -->
            <h5 class="section-title mt-4">5. Daftar Tilik Penapisan Kontraindikasi untuk Vaksinasi</h5>
            <p class="text-muted small mb-3">Tentukan kondisi Anda pada butir pemeriksaan berikut secara jujur dan benar.</p>

            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover table-skrining align-middle">
                    <thead>
                        <tr>
                            <th style="width: 7%;" class="text-center">No</th>
                            <th style="width: 65%;">Pertanyaan Penapisan Medis</th>
                            <th style="width: 28%;" class="text-center">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $pertanyaan = [
                                1 => 'Apakah anda sedang sakit hari ini?',
                                2 => 'Apakah anda memiliki alergi terhadap obat-obatan, makanan, komponen vaksin atau lateks?',
                                3 => 'Apakah anda pernah mengalami reaksi alergi berat setelah menerima vaksinasi?',
                                4 => 'Apakah anda memiliki penyakit kronis terkait jantung, paru-paru, asma, ginjal, penyakit metabolik (diabetes), anemia atau penyakit kelainan darah?',
                                5 => 'Apakah anda menderita kanker, leukimia, HIV/AIDS atau gangguan sistem daya tahan tubuh?',
                                6 => 'Dalam 3 bulan terakhir, apakah anda mendapatkan pengobatan yang melemahkan daya tahan tubuh, seperti kortison, prednison, steroid lainnya atau obat anti kanker, atau dalam terapi radiasi?',
                                7 => 'Apakah anda pernah mengalami kejang atau gangguan sistem syaraf lainnya?',
                                8 => 'Apakah anda menerima transfusi darah atau produk darah, atau mendapat terapi Imun (gamma) globulin, atau obat antiviral dalam satu tahun terakhir?',
                                9 => 'Apakah anda mendapatkan vaksinasi dalam 4 minggu terakhir?',
                            ];
                        @endphp

                        @foreach($pertanyaan as $no => $teks)
                            <tr>
                                <td class="text-center fw-bold">{{ $no }}</td>
                                <td>{{ $teks }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="skrining[{{ $no }}]" 
                                                   id="q{{ $no }}_ya" 
                                                   value="Ya" 
                                                   required 
                                                   {{ old('skrining.' . $no) == 'Ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="q{{ $no }}_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="skrining[{{ $no }}]" 
                                                   id="q{{ $no }}_tidak" 
                                                   value="Tidak" 
                                                   required 
                                                   {{ old('skrining.' . $no) == 'Tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="q{{ $no }}_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Baris No 10: Khusus Wanita -->
                        <tr id="baris-q10" style="display: none;" class="table-warning">
                            <td class="text-center fw-bold">10</td>
                            <td>
                                Apakah anda sedang hamil atau berencana untuk hamil dalam 1 bulan ke depan? 
                                <span class="badge bg-danger ms-1">Khusus Wanita</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input radio-q10" 
                                               type="radio" 
                                               name="skrining[10]" 
                                               id="q10_ya" 
                                               value="Ya" 
                                               {{ old('skrining.10') == 'Ya' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="q10_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input radio-q10" 
                                               type="radio" 
                                               name="skrining[10]" 
                                               id="q10_tidak" 
                                               value="Tidak" 
                                               {{ old('skrining.10') == 'Tidak' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="q10_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- SURAT PERNYATAAN PERSETUJUAN VAKSINASI (INFORMED CONSENT) -->
            <div class="card border-0 mb-4 shadow-sm" style="background-color: #f1f5f9; border-radius: 12px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-shield-check text-primary me-2"></i>
                        SURAT PERNYATAAN PERSETUJUAN VAKSINASI
                    </h6>
                    
                    <p class="text-secondary small mb-3" style="line-height: 1.6; text-align: justify;">
                        Saya menyatakan bahwa saya bersedia mengikuti vaksinasi internasional setelah mendapatkan penjelasan dari petugas kesehatan mengenai manfaat, risiko, dan kemungkinan efek samping vaksin. Saya telah memberikan informasi yang benar mengenai kondisi kesehatan dan riwayat saya serta bersedia menjalani pemeriksaan kesehatan sebelum vaksinasi.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Persetujuan ini diberikan terhadap: <span class="text-danger">*</span></label>
                        <select name="persetujuan_untuk" class="form-select @error('persetujuan_untuk') is-invalid @enderror" required>
                            <option value="Saya sendiri" {{ old('persetujuan_untuk') == 'Saya sendiri' ? 'selected' : '' }}>Saya sendiri</option>
                            <option value="Anak saya" {{ old('persetujuan_untuk') == 'Anak saya' ? 'selected' : '' }}>Anak saya</option>
                            <option value="Orangtua saya" {{ old('persetujuan_untuk') == 'Orangtua saya' ? 'selected' : '' }}>Orangtua saya</option>
                        </select>
                        @error('persetujuan_untuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <p class="text-secondary small mb-3" style="line-height: 1.6; text-align: justify;">
                        Dan saya berkomitmen tidak akan memberikan hadiah, barang, uang, atau gratifikasi yang tidak sesuai dengan peraturan perundang-undangan kepada petugas dalam pelayanan vaksinasi.
                    </p>

                    <p class="text-secondary small mb-3" style="line-height: 1.6;">
                        Demikian surat pernyataan ini saya buat dengan sadar dan tanpa paksaan dari pihak mana pun untuk dipergunakan sebagaimana mestinya.
                    </p>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary-subtle">
                        <div class="form-check">
                            <input class="form-check-input @error('is_setuju') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="is_setuju" 
                                   value="1" 
                                   id="checkSetuju" 
                                   required 
                                   {{ old('is_setuju') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark small" for="checkSetuju">
                                Ya, saya setuju
                            </label>
                            @error('is_setuju')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-muted small">
                            Sorong, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid pt-2">
                <button type="submit" class="btn btn-bkk py-2">
                    <i class="bi bi-send me-1"></i> Kirim Formulir Pendaftaran
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL POP-UP SUKSES PENDAFTARAN -->
@if(session('sukses_modal'))
<div class="modal fade" id="modalSuksesDaftar" tabindex="-1" aria-labelledby="modalSuksesLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg text-center p-4" style="border-radius: 16px;">
            <div class="modal-body">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 70px; height: 70px; font-size: 36px;">
                        <i class="bi bi-check-lg"></i>
                    </span>
                </div>

                <h4 class="fw-bold text-dark mb-2">Pendaftaran Terkirim!</h4>
                <p class="text-muted small mb-4">
                    Data Anda telah berhasil dicatat ke sistem. Silakan <strong>tangkap layar (screenshot)</strong> nomor registrasi berikut dan tunjukkan kepada petugas di loket verifikasi:
                </p>

                <!-- Menggunakan data_get agar aman terhadap Array maupun Objek Eloquent -->
                <div class="p-3 mb-3 border rounded-3 bg-light">
                    <div class="text-secondary small fw-semibold text-uppercase">Nomor Registrasi Anda</div>
                    <div class="fw-bold text-success fs-3 tracking-wider py-1">
                        {{ data_get(session('sukses_modal'), 'nomor_registrasi') }}
                    </div>
                    <div class="small text-muted">{{ data_get(session('sukses_modal'), 'nama_paspor') }}</div>
                </div>

                <div class="alert alert-warning small py-2 mb-4 text-start">
                    <i class="bi bi-info-circle-fill me-1"></i> <strong>Catatan:</strong> Verifikasi berkas fisik (Paspor asli & KTP) serta penapisan dokter dilakukan langsung saat Anda tiba di kantor Balai Kekarantinaan Kesehatan Sorong.
                </div>

                <button type="button" 
                    class="btn btn-bkk w-100 py-2 fw-semibold" 
                    data-bs-dismiss="modal"
                    onclick="window.location.href='{{ route('pendaftaran.create') }}'">
                    Tutup & Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@push('styles')
<!-- Hubungkan ke CSS Kalender Terpisah -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/kalender-vaksin.css') }}">
@endpush

@push('scripts')
<!-- Library JS Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
// Fungsi untuk validasi berkas (maks 1MB dan format file)
function validasiBerkas(input) {
    const maxUkuran = 1 * 1024 * 1024; // 1 MB (dalam bytes)

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // 1. Validasi Batas Ukuran 1 MB Langsung Saat Dipilih
        if (file.size > maxUkuran) {
            const ukuranAsliMB = (file.size / (1024 * 1024)).toFixed(2);
            alert(`Ukuran berkas terlalu besar (${ukuranAsliMB} MB)!\nMaksimal ukuran berkas yang diperbolehkan adalah 1 MB. Silakan pilih atau kompres berkas terlebih dahulu.`);
            input.value = '';
            return;
        }

        // 2. Validasi Format Berkas
        const idInput = input.id || input.name;
        const ekstensi = file.name.split('.').pop().toLowerCase();

        if (idInput.includes('sinkarkes')) {
            if (file.type !== 'application/pdf' && ekstensi !== 'pdf') {
                alert('Peringatan: Berkas SINKARKES wajib berupa dokumen PDF!');
                input.value = '';
                return;
            }
        } else if (idInput.includes('paspor') || idInput.includes('ktp')) {
            const formatFoto = ['jpg', 'jpeg', 'png'];
            if (!formatFoto.includes(ekstensi)) {
                alert('Peringatan: Foto Paspor/KTP wajib berformat JPG, JPEG, atau PNG!');
                input.value = '';
                return;
            }
        }

        input.classList.remove('is-invalid');
    }
}

function filterGender() {
    const genderSelect = document.getElementById('jenis_kelamin');
    const barisQ10 = document.getElementById('baris-q10');
    const radioQ10 = document.querySelectorAll('.radio-q10');

    if (!genderSelect || !barisQ10) return;

    if (genderSelect.value === 'P') {
        barisQ10.style.display = 'table-row';
        radioQ10.forEach(r => r.required = true);
    } else {
        barisQ10.style.display = 'none';
        radioQ10.forEach(r => {
            r.required = false;
            r.checked = false;
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    filterGender();

    const daftarLibur = @json($daftarLibur ?? []);
    
    // Titik acuan hari ini (jam dinetralkan ke 00:00:00)
    const hariIni = new Date();
    hariIni.setHours(0, 0, 0, 0);

    flatpickr("#tanggal_kunjungan", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "l, d F Y",
        minDate: "today",
        monthSelectorType: "static",
        disable: [
            // Kunci Sabtu (6) dan Minggu (0)
            function(date) {
                return (date.getDay() === 0 || date.getDay() === 6);
            },
            // Kunci hari libur database admin
            function(date) {
                let d = date.getDate().toString().padStart(2, '0');
                let m = (date.getMonth() + 1).toString().padStart(2, '0');
                let y = date.getFullYear();
                let dateString = `${y}-${m}-${d}`;
                return daftarLibur.includes(dateString);
            }
        ],
        // Pasang class warna khusus untuk tiap sel tanggal
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            const tgl = dayElem.dateObj;
            tgl.setHours(0, 0, 0, 0);

            const d = tgl.getDate().toString().padStart(2, '0');
            const m = (tgl.getMonth() + 1).toString().padStart(2, '0');
            const y = tgl.getFullYear();
            const dateString = `${y}-${m}-${d}`;
            const hari = tgl.getDay();

            if (tgl < hariIni) {
                dayElem.classList.add('tgl-lewat');
            } else if (daftarLibur.includes(dateString)) {
                dayElem.classList.add('tgl-libur-merah');
                dayElem.title = "Libur Resmi / Ketetapan Pemda PBD";
            } else if (hari === 0 || hari === 6) {
                dayElem.classList.add('tgl-weekend');
                dayElem.title = "Libur Akhir Pekan";
            } else {
                dayElem.classList.add('tgl-aktif');
            }
        }
    });

    @if(session('sukses_modal'))
        const modalElement = document.getElementById('modalSuksesDaftar');
        if (modalElement && typeof bootstrap !== 'undefined') {
            const modalSukses = new bootstrap.Modal(modalElement);
            modalSukses.show();

            modalElement.addEventListener('hidden.bs.modal', function () {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                window.location.href = "{{ route('pendaftaran.create') }}";
            });
        }
    @endif

    const firstInvalid = document.querySelector('.is-invalid, .text-danger.small');
    if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        if (typeof firstInvalid.focus === 'function') {
            firstInvalid.focus();
        }
    }
});
</script>
@endpush