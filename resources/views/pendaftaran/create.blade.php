@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Vaksinasi - BKK Sorong')

@section('content')
<div class="card card-custom mx-auto" style="max-width: 900px;">
    
    <div class="card-form-header">
        <h5 class="mb-1 font-utama"><i class="bi bi-file-earmark-medical me-2"></i>Formulir Pendaftaran & Penapisan</h5>
        <p class="mb-0 small text-white-50">Lengkapi data diri sesuai paspor dan periksa kondisi kesehatan Anda.</p>
    </div>

    <div class="card-body p-4">

        <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 1. DATA IDENTITAS PEMOHON -->
            <h5 class="section-title">1. Data Pelaku Perjalanan (Pemohon)</h5>
            
            <div class="row g-3 mb-3">
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
                    <div class="form-text small text-muted">Wajib diisi sesuai nama tambahan di paspor (terutama nama 1 suku kata).</div>
                    @error('nama_tambahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- NIK & NOMOR PASPOR -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="nik" 
                           class="form-control @error('nik') is-invalid @enderror" 
                           placeholder="16 digit NIK sesuai KTP" 
                           maxlength="16" 
                           pattern="[0-9]{16}" 
                           title="Harap masukkan 16 digit angka NIK"
                           required 
                           value="{{ old('nik') }}">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="tempat_lahir" 
                           class="form-control @error('tempat_lahir') is-invalid @enderror" 
                           placeholder="Kota kelahiran" 
                           required 
                           value="{{ old('tempat_lahir') }}">
                    @error('tempat_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required onchange="filterGender()">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 2. UNGGAH BERKAS PERSYARATAN -->
            <h5 class="section-title">2. Unggah 4 Berkas Persyaratan</h5>
            <p class="text-muted small mb-3">Format berkas: <strong>PDF, JPG, JPEG, PNG</strong> (Maksimal 5 MB per dokumen).</p>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanda Terima Pendaftaran SINKARKES <span class="text-danger">*</span></label>
                    <input type="file" 
                           name="file_sinkarkes_terima" 
                           class="form-control @error('file_sinkarkes_terima') is-invalid @enderror" 
                           required 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('file_sinkarkes_terima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Formulir Pendaftaran SINKARKES <span class="text-danger">*</span></label>
                    <input type="file" 
                           name="file_sinkarkes_form" 
                           class="form-control @error('file_sinkarkes_form') is-invalid @enderror" 
                           required 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('file_sinkarkes_form')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Paspor Pemohon <span class="text-danger">*</span></label>
                    <input type="file" 
                           name="file_paspor" 
                           class="form-control @error('file_paspor') is-invalid @enderror" 
                           required 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('file_paspor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">KTP Pemohon <span class="text-danger">*</span></label>
                    <input type="file" 
                           name="file_ktp" 
                           class="form-control @error('file_ktp') is-invalid @enderror" 
                           required 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('file_ktp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 3. RIWAYAT KARTU VAKSINASI -->
            <h5 class="section-title">3. Riwayat Kartu Vaksinasi Internasional</h5>
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

            <!-- 4. PILIH LAYANAN VAKSINASI (BISA PILIH LEBIH DARI 1) -->
            <h5 class="section-title">4. Pilih Layanan Vaksinasi</h5>
            <p class="text-muted small mb-3">Pilih satu atau beberapa jenis vaksinasi internasional yang ingin diajukan (bisa dicentang lebih dari satu):</p>

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
                <!-- Ikon Centang -->
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 70px; height: 70px; font-size: 36px;">
                        <i class="bi bi-check-lg"></i>
                    </span>
                </div>

                <h4 class="fw-bold text-dark mb-2">Pendaftaran Terkirim!</h4>
                <p class="text-muted small mb-4">
                    Data Anda telah berhasil dicatat ke sistem. Silakan <strong>tangkap layar (screenshot)</strong> nomor registrasi berikut dan tunjukkan kepada petugas di loket verifikasi:
                </p>

                <!-- Box Nomor Registrasi -->
                <div class="p-3 mb-3 border rounded-3 bg-light">
                    <div class="text-secondary small fw-semibold text-uppercase">Nomor Registrasi Anda</div>
                    <div class="fw-bold text-success fs-3 tracking-wider py-1">
                        {{ session('sukses_modal')['nomor_registrasi'] }}
                    </div>
                    <div class="small text-muted">{{ session('sukses_modal')['nama_paspor'] }}</div>
                </div>

                <div class="alert alert-warning small py-2 mb-4 text-start">
                    <i class="bi bi-info-circle-fill me-1"></i> <strong>Catatan:</strong> Verifikasi berkas fisik (Paspor asli & KTP) serta penapisan dokter dilakukan langsung saat Anda tiba di kantor Balai Kekarantinaan Kesehatan Sorong.
                </div>

                <button type="button" class="btn btn-bkk w-100 py-2 fw-semibold" data-bs-dismiss="modal">
                    Tutup & Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function filterGender() {
    const gender = document.getElementById('jenis_kelamin').value;
    const barisQ10 = document.getElementById('baris-q10');
    const radioQ10 = document.querySelectorAll('.radio-q10');

    if (gender === 'P') {
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

    // 1. TAMBAHKAN BAGIAN INI: Buka pop-up otomatis setelah pendaftaran berhasil dikirim
    @if(session('sukses_modal'))
        const modalElement = document.getElementById('modalSuksesDaftar');
        if (modalElement) {
            const modalSukses = new bootstrap.Modal(modalElement);
            modalSukses.show();
        }
    @endif

    // 2. Otomatis geser (scroll) dan beri fokus ke input pertama yang terdeteksi error
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