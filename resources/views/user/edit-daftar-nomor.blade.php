@extends('user.app-user')

@section('content')

<style>
    /* ===== FORM PAGE STYLES ===== */
    .form-page-header { margin-bottom: 1.5rem; }
    .form-page-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #0f172a;
        margin: 0;
    }
    .form-page-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    /* Breadcrumb */
    .breadcrumb-clean {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
    }
    .breadcrumb-clean li + li::before {
        content: '/';
        color: #cbd5e1;
        margin-right: 0.5rem;
    }
    .breadcrumb-clean a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb-clean a:hover { color: #4f46e5; }
    .breadcrumb-clean .current { color: #94a3b8; font-weight: 500; }

    /* Card */
    .form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .form-card-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 1.25rem 1.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-card-header i { color: rgba(255,255,255,0.8); font-size: 1.15rem; }
    .form-card-header-title {
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: -0.02em;
    }

    .form-card-body { padding: 1.75rem; }

    /* Sections */
    .form-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-section-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-section-title i { color: #6366f1; font-size: 1rem; }

    /* Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .form-grid.single { grid-template-columns: 1fr; }
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
    }

    /* Labels */
    .form-label-clean {
        display: block;
        font-size: 0.8rem;
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.4rem;
    }
    .form-label-clean .req { color: #ef4444; margin-left: 2px; }

    /* Inputs */
    .input-clean {
        width: 100%;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 0.85rem;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        -webkit-appearance: none;
    }
    .input-clean:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .input-clean::placeholder { color: #94a3b8; }
    textarea.input-clean { min-height: 100px; resize: vertical; }
    select.input-clean { cursor: pointer; }

    /* Select2 Override */
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single {
        background: #fff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        min-height: 42px !important;
        height: 42px !important;
        padding: 0 0.85rem !important;
        display: flex !important;
        align-items: center !important;
        font-size: 0.875rem !important;
        transition: border-color 0.2s, box-shadow 0.2s !important;
    }
    .select2-container--focus .select2-selection--single,
    .select2-container--open .select2-selection--single {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1) !important;
    }
    .select2-container .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 0.875rem !important;
        padding-left: 0 !important;
    }
    .select2-container .select2-selection__arrow { height: 100% !important; right: 10px !important; }
    .select2-dropdown {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
        z-index: 9999 !important;
        max-width: 400px !important;
    }
    .select2-search__field {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.85rem !important;
    }
    .select2-search__field:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.1) !important;
        outline: none !important;
    }
    .select2-results__option {
        font-size: 0.85rem;
        padding: 0.6rem 0.85rem;
        color: #334155;
    }
    .select2-results__option--highlighted { background: #f1f5f9 !important; color: #1e293b !important; }
    .select2-results__option--selected { background: #6366f1 !important; color: #fff !important; }
    .select2-results { max-height: 250px !important; overflow-y: auto !important; }

    /* Buttons */
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(99,102,241,0.25);
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(99,102,241,0.35);
    }
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.7rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #334155; }

    /* Loading */
    .btn-loading { position: relative; color: transparent !important; pointer-events: none; }
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px; height: 16px;
        top: 50%; left: 50%;
        margin: -8px 0 0 -8px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="form-page-header">
        <ul class="breadcrumb-clean mb-2">
            <li><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Dashboard</a></li>
            <li><span class="current">Permintaan Nomor</span></li>
        </ul>
        <h5 class="form-page-title">Revisi Permintaan Nomor</h5>
        <p class="form-page-subtitle">Perbaiki formulir di bawah ini untuk mengajukan kembali nomor surat</p>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-file-earmark-text"></i>
            <span class="form-card-header-title">Form Revisi Surat</span>
        </div>

        @if($request->catatan)
        <div class="alert alert-warning m-3 border-0 shadow-sm" style="background-color:#fef9c3; color:#854d0e;">
            <i class="bi bi-exclamation-triangle me-2"></i> <strong>Catatan Revisi:</strong><br/>
            {{ $request->catatan }}
        </div>
        @endif

        <div class="form-card-body pt-0">
            <form action="{{ route('user.daftar-nomor.update', $request->id) }}" method="POST" id="form-surat">
                @csrf
                @method('PUT')

                <!-- Section: Informasi Dokumen -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="bi bi-info-circle"></i>
                        Informasi Dokumen
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="form-label-clean">
                                Klasifikasi <span class="req">*</span>
                            </label>
                            <select name="kode_kearsipan_id" id="kode_kearsipan" class="input-clean">
                                <option value="" disabled>Pilih Klasifikasi</option>
                                @foreach($kodeKearsipan as $item)
                                    <option value="{{ $item->id }}" {{ old('kode_kearsipan_id', $request->kode_kearsipan_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->kode }} - {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label-clean">
                                Jenis Naskah <span class="req">*</span>
                            </label>
                            <select class="input-clean" id="jenis_naskah" name="jenis_naskah" required>
                                <option value="" disabled>Pilih Jenis Naskah...</option>
                                @foreach($jenisNaskah as $jenis)
                                    <option value="{{ $jenis->nama }}" {{ old('jenis_naskah', $request->jenis_naskah) == $jenis->nama ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-grid" style="margin-top: 1.25rem;">
                        <div>
                            <label class="form-label-clean">
                                Pengolah (Unit Kerja) <span class="req">*</span>
                            </label>
                            <select name="pengolah" id="pengolah" class="input-clean" required>
                                <option value="" disabled selected>Pilih Unit Kerja</option>
                                @foreach($unitKerjas as $unit)
                                    <option value="{{ $unit->nama }}" {{ old('pengolah', $request->pengolah) == $unit->nama ? 'selected' : '' }}>
                                        {{ $unit->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label-clean">
                                Sifat Naskah <span class="req">*</span>
                            </label>
                            <select name="sifat_naskah" id="sifat_naskah" class="input-clean">
                                <option value="" disabled>Pilih Sifat Naskah</option>
                                <option value="Biasa" {{ old('sifat_naskah', $request->sifat_naskah) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="Penting" {{ old('sifat_naskah', $request->sifat_naskah) == 'Penting' ? 'selected' : '' }}>Penting</option>
                                <option value="Segera" {{ old('sifat_naskah', $request->sifat_naskah) == 'Segera' ? 'selected' : '' }}>Segera</option>
                                <option value="Rahasia" {{ old('sifat_naskah', $request->sifat_naskah) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Informasi Tanggal -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="bi bi-calendar-event"></i>
                        Informasi Tanggal
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="form-label-clean">
                                Tanggal Ditetapkan <span class="req">*</span>
                            </label>
                            <input type="date" class="input-clean" name="tanggal_ditetapkan" id="tanggal_ditetapkan" value="{{ old('tanggal_ditetapkan', $request->tanggal_ditetapkan ? \Carbon\Carbon::parse($request->tanggal_ditetapkan)->format('Y-m-d') : date('Y-m-d')) }}">
                        </div>

                        <div>
                            <label class="form-label-clean">
                                Tanggal Berlaku
                            </label>
                            <input type="date" class="input-clean" name="tanggal_berlaku" value="{{ old('tanggal_berlaku', $request->tanggal_berlaku ? \Carbon\Carbon::parse($request->tanggal_berlaku)->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                <!-- Section: Konten Surat -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="bi bi-file-richtext"></i>
                        Konten Surat
                    </div>

                    <div class="form-grid single">
                        <div>
                            <label class="form-label-clean">
                                Hal <span class="req">*</span>
                            </label>
                            <textarea class="input-clean" name="hal" rows="4" placeholder="Masukkan hal/perihal surat..." required>{{ old('hal', $request->hal) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-3 pt-2">
                    <button type="button" class="btn-cancel" id="btn-batal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-submit" id="btn-simpan">
                        <i class="bi bi-check-lg"></i> Simpan Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for Klasifikasi
    $('#kode_kearsipan').select2({
        placeholder: 'Pilih Klasifikasi',
        width: '100%',
        dropdownAutoWidth: false,
        dropdownParent: $(document.body),
        templateResult: function(option) {
            if (!option.id) return option.text;
            var text = option.text;
            if (text.length > 60) text = text.substring(0, 60) + '...';
            return $('<span title="' + option.text + '">' + text + '</span>');
        },
        templateSelection: function(option) {
            if (!option.id) return option.text;
            var text = option.text;
            if (text.length > 50) text = text.substring(0, 50) + '...';
            return $('<span title="' + option.text + '">' + text + '</span>');
        }
    });

    // Initialize Select2 for Pengolah — dropdownParent forces it to open downward
    $('#pengolah').select2({
        placeholder: 'Pilih Unit Kerja',
        width: '100%',
        dropdownAutoWidth: false,
        dropdownParent: $(document.body),
    });

    // Initialize Select2 for Jenis Naskah (no search box)
    $('#jenis_naskah').select2({
        placeholder: 'Pilih Jenis Naskah...',
        width: '100%',
        dropdownAutoWidth: false,
        dropdownParent: $(document.body),
        minimumResultsForSearch: Infinity,
    });

    // Initialize Select2 for Sifat Naskah (no search box)
    $('#sifat_naskah').select2({
        placeholder: 'Pilih Sifat Naskah',
        width: '100%',
        dropdownAutoWidth: false,
        dropdownParent: $(document.body),
        minimumResultsForSearch: Infinity,
    });

    // Toggle tanggal_ditetapkan based on jenis_naskah
    function toggleTanggalDitetapkanField() {
        var jenis = $("select[name='jenis_naskah']").val();
        var tanggal = $("#tanggal_ditetapkan");
        var label = tanggal.closest('div').find('.form-label-clean');

        if (jenis === 'Surat Keputusan') {
            tanggal.attr('required', true);
            tanggal.val('');
            if (label.find('.req').length === 0) {
                label.append(' <span class="req">*</span>');
            }
        } else if (jenis === 'Surat Keluar') {
            tanggal.removeAttr('required');
            tanggal.val('');
            label.find('.req').remove();
        } else {
            tanggal.removeAttr('required');
            tanggal.val('');
            label.find('.req').remove();
        }
    }

    $("select[name='jenis_naskah']").on('change', toggleTanggalDitetapkanField);
    toggleTanggalDitetapkanField();

    // Let form submit normally.
    $('#form-surat').on('submit', function() {
        $('#btn-simpan').addClass('btn-loading').prop('disabled', true);
    });

    // Cancel button
    $('#btn-batal').on('click', function() {
        Swal.fire({
            title: 'Yakin ingin membatalkan?',
            text: 'Data yang sudah diisi akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan',
            cancelButtonText: 'Tidak',
            confirmButtonColor: '#ef4444',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
    });
});
</script>

@endsection