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
                    <input type="text" name="nama_paspor" class="form-control" placeholder="Masukkan nama sesuai paspor" required value="{{ old('nama_paspor') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Tambahan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tambahan" class="form-control" placeholder="Nama ayah/marga/tambahan" required value="{{ old('nama_tambahan') }}">
                    <div class="form-text small text-muted">Wajib diisi sesuai nama tambahan di paspor (terutama nama dengan 1 suku kata).</div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota kelahiran" required value="{{ old('tempat_lahir') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required onchange="filterGender()">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- 2. UNGGAH BERKAS PERSYARATAN -->
            <h5 class="section-title">2. Unggah 4 Berkas Persyaratan</h5>
            <p class="text-muted small mb-3">Format: <strong>PDF, JPG, JPEG, PNG</strong> (Maksimal 1 MB per berkas).</p>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanda Terima Pendaftaran SINKARKES <span class="text-danger">*</span></label>
                    <input type="file" name="file_sinkarkes_terima" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Formulir Pendaftaran SINKARKES <span class="text-danger">*</span></label>
                    <input type="file" name="file_sinkarkes_form" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Paspor Pemohon <span class="text-danger">*</span></label>
                    <input type="file" name="file_paspor" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">KTP Pemohon <span class="text-danger">*</span></label>
                    <input type="file" name="file_ktp" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>

            <!-- 3. RIWAYAT KARTU VAKSINASI -->
            <h5 class="section-title">3. Riwayat Kartu Vaksinasi Internasional</h5>
            <div class="box-info-vaksin mb-4">
                <label class="form-label fw-semibold d-block mb-2">Pilih kartu/sertifikat vaksinasi yang Anda miliki saat ini (centang bila ada):</label>
                <div class="d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kartu_vaksin[]" value="e-ICV" id="cek_eicv">
                        <label class="form-check-label fw-semibold" for="cek_eicv">
                            Membawa / Memiliki Sertifikat Elektronik (e-ICV)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kartu_vaksin[]" value="ICV" id="cek_icv">
                        <label class="form-check-label fw-semibold" for="cek_icv">
                            Membawa Kartu / Buku Kuning Fisik (ICV)
                        </label>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">*Biarkan kosong jika belum pernah vaksinasi internasional atau tidak membawa kartu.</small>
            </div>

            <!-- 4. PILIH LAYANAN VAKSINASI (TEPAT SEBELUM SKRINING) -->
            <h5 class="section-title">4. Pilih Layanan Vaksinasi</h5>
            <p class="text-muted small mb-3">Pilih jenis vaksinasi internasional yang ingin Anda ajukan permohonannya:</p>

            <div class="row g-3 mb-4">
                @foreach($daftarVaksin as $vaksin)
                    <div class="col-md-6">
                        <div class="card card-vaksin-item h-100 {{ !$vaksin->is_aktif ? 'vaksin-disabled' : '' }}">
                            <label class="card-body d-flex align-items-center justify-content-between p-3 mb-0 {{ $vaksin->is_aktif ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="radio" 
                                           name="vaksin_id" 
                                           value="{{ $vaksin->id }}" 
                                           class="form-check-input mt-0" 
                                           {{ !$vaksin->is_aktif ? 'disabled' : '' }} 
                                           {{ (old('vaksin_id') == $vaksin->id) ? 'checked' : '' }} 
                                           required>
                                    
                                    <span class="fw-bold fs-6 {{ $vaksin->is_aktif ? 'text-dark' : 'text-muted' }}">
                                        {{ $vaksin->nama_vaksin }}
                                    </span>
                                </div>

                                <!-- Indikator Khusus Saat Ditutup Admin -->
                                @if(!$vaksin->is_aktif)
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 small">
                                        <i class="bi bi-slash-circle me-1"></i>Pelayanan Ditutup Sementara
                                    </span>
                                @endif
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('vaksin_id')
                <div class="alert alert-danger py-2 small mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                </div>
            @enderror

            <!-- 5. PENAPISAN KONTRAINDIKASI -->
            <h5 class="section-title">5. Daftar Tilik Penapisan Kontraindikasi untuk Vaksinasi</h5>
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
                        <tr>
                            <td class="text-center fw-bold">1</td>
                            <td>Apakah anda sedang sakit hari ini</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[1]" id="q1_ya" value="Ya" required>
                                        <label class="form-check-label" for="q1_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[1]" id="q1_tidak" value="Tidak">
                                        <label class="form-check-label" for="q1_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">2</td>
                            <td>Apakah anda memiliki alergi terhadap obat-obatan, makanan, komponen vaksin atau lateks ?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[2]" id="q2_ya" value="Ya" required>
                                        <label class="form-check-label" for="q2_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[2]" id="q2_tidak" value="Tidak">
                                        <label class="form-check-label" for="q2_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">3</td>
                            <td>Apakah anda pernah mengalami reaksi alergi berat setelah menerima vaksinasi?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[3]" id="q3_ya" value="Ya" required>
                                        <label class="form-check-label" for="q3_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[3]" id="q3_tidak" value="Tidak">
                                        <label class="form-check-label" for="q3_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">4</td>
                            <td>Apakah anda memiliki penyakit kronis terkait jantung, paru-paru, asma, ginjal, penyakit metabolik (diabetes), anemia atau penyakit kelainan darah?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[4]" id="q4_ya" value="Ya" required>
                                        <label class="form-check-label" for="q4_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[4]" id="q4_tidak" value="Tidak">
                                        <label class="form-check-label" for="q4_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">5</td>
                            <td>Apakah anda menderita kanker, leukimia, HIV/AIDS atau gangguan sistem daya tahan tubuh?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[5]" id="q5_ya" value="Ya" required>
                                        <label class="form-check-label" for="q5_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[5]" id="q5_tidak" value="Tidak">
                                        <label class="form-check-label" for="q5_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">6</td>
                            <td>Dalam 3 bulan terakhir, apakah anda mendapatkan pengobatan yang melemahkan daya tahan tubuh, seperti kortison, prednison, steroid lainnya atau obat anti kanker, atau dalam terapi radiasi?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[6]" id="q6_ya" value="Ya" required>
                                        <label class="form-check-label" for="q6_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[6]" id="q6_tidak" value="Tidak">
                                        <label class="form-check-label" for="q6_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">7</td>
                            <td>Apakah anda pernah mengalami kejang atau gangguan sistem syaraf lainnya?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[7]" id="q7_ya" value="Ya" required>
                                        <label class="form-check-label" for="q7_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[7]" id="q7_tidak" value="Tidak">
                                        <label class="form-check-label" for="q7_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">8</td>
                            <td>Apakah anda menerima transfusi darah atau produk darah, atau mendapat terapi Imun (gamma) globulin, atau obat antiviral dalam satu tahun terakhir?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[8]" id="q8_ya" value="Ya" required>
                                        <label class="form-check-label" for="q8_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[8]" id="q8_tidak" value="Tidak">
                                        <label class="form-check-label" for="q8_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold">9</td>
                            <td>Apakah anda mendapatkan vaksinasi dalam 4 minggu terakhir?</td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[9]" id="q9_ya" value="Ya" required>
                                        <label class="form-check-label" for="q9_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="skrining[9]" id="q9_tidak" value="Tidak">
                                        <label class="form-check-label" for="q9_tidak">Tidak</label>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris No 10: Khusus Perempuan -->
                        <tr id="baris-q10" style="display: none;" class="table-warning">
                            <td class="text-center fw-bold">10</td>
                            <td>
                                Apakah anda sedang hamil atau berencana untuk hamil dalam 1 bulan ke depan? 
                                <span class="badge bg-danger ms-1">Khusus Wanita</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input radio-q10" type="radio" name="skrining[10]" id="q10_ya" value="Ya">
                                        <label class="form-check-label" for="q10_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input radio-q10" type="radio" name="skrining[10]" id="q10_tidak" value="Tidak">
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
</script>
@endpush