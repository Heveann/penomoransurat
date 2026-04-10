@extends('admin.app-admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Tambah Surat Masuk</h5>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Catat detail surat masuk baru</p>
        </div>
        <a href="{{ route('surat-masuk.index') }}" class="btn"
            style="background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 500; font-size: 0.85rem; padding: 0.5rem 1rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title">Form Data Surat Masuk</div>
        </div>
        <div style="padding: 1.5rem;">
            <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem; color: #334155;">Nomor Surat
                            Masuk <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_surat_masuk" class="form-control" required
                            style="border-radius: 8px; font-size: 0.9rem;" value="{{ old('nomor_surat_masuk') }}">
                        @error('nomor_surat_masuk') <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}
                        </div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem; color: #334155;">Pengirim
                            Instansi/Nama <span class="text-danger">*</span></label>
                        <input type="text" name="pengirim" class="form-control" required
                            style="border-radius: 8px; font-size: 0.9rem;" value="{{ old('pengirim') }}">
                        @error('pengirim') <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem; color: #334155;">Tanggal
                            Surat (dari dokumen) <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_surat" class="form-control" required
                            style="border-radius: 8px; font-size: 0.9rem;" value="{{ old('tanggal_surat') }}">
                        @error('tanggal_surat') <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}
                        </div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem; color: #334155;">Tanggal
                            Diterima <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_terima" class="form-control" required
                            style="border-radius: 8px; font-size: 0.9rem;"
                            value="{{ old('tanggal_terima', date('Y-m-d')) }}">
                        @error('tanggal_terima') <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}
                        </div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 500; font-size: 0.85rem; color: #334155;">Perihal / Isi
                        Ringkas <span class="text-danger">*</span></label>
                    <textarea name="perihal" class="form-control" rows="3" required
                        style="border-radius: 8px; font-size: 0.9rem;">{{ old('perihal') }}</textarea>
                    @error('perihal') <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight: 500; font-size: 0.85rem; color: #334155;">Upload File Scan (Opsional)</label>
                    <div id="dropZone" style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #f8fafc;" onclick="document.getElementById('fileInput').click()">
                        <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem; color: #94a3b8;"></i>
                        <p style="margin: 0.5rem 0 0.25rem; font-weight: 600; color: #334155; font-size: 0.9rem;">Klik di sini atau seret file untuk upload</p>
                        <p style="margin: 0; font-size: 0.78rem; color: #94a3b8;">Format: PDF, JPG, JPEG, PNG &bull; Maks. 5MB</p>
                        <div id="fileNameDisplay" style="margin-top: 0.75rem; display: none;">
                            <span class="badge" style="background: #eff6ff; color: #3b82f6; font-size: 0.8rem; padding: 0.4rem 0.8rem; border-radius: 6px;"><i class="bi bi-file-earmark-check me-1"></i><span id="selectedFileName"></span></span>
                        </div>
                    </div>
                    <input type="file" name="file_scan" id="fileInput" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                    @error('file_scan') <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
                    @enderror
                </div>

                <script>
                    const dropZone = document.getElementById('dropZone');
                    const fileInput = document.getElementById('fileInput');
                    const fileNameDisplay = document.getElementById('fileNameDisplay');
                    const selectedFileName = document.getElementById('selectedFileName');

                    fileInput.addEventListener('change', function() {
                        if (this.files.length > 0) {
                            selectedFileName.textContent = this.files[0].name;
                            fileNameDisplay.style.display = 'block';
                            dropZone.style.borderColor = '#6366f1';
                            dropZone.style.background = '#eef2ff';
                        } else {
                            fileNameDisplay.style.display = 'none';
                            dropZone.style.borderColor = '#cbd5e1';
                            dropZone.style.background = '#f8fafc';
                        }
                    });

                    ['dragenter', 'dragover'].forEach(evt => {
                        dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.style.borderColor = '#6366f1'; dropZone.style.background = '#eef2ff'; });
                    });
                    ['dragleave', 'drop'].forEach(evt => {
                        dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.style.borderColor = '#cbd5e1'; dropZone.style.background = '#f8fafc'; });
                    });
                    dropZone.addEventListener('drop', e => {
                        fileInput.files = e.dataTransfer.files;
                        fileInput.dispatchEvent(new Event('change'));
                    });
                </script>

                <div class="text-end">
                    <button type="submit" class="btn px-4"
                        style="background: #4f46e5; color: white; border-radius: 8px; font-weight: 500; font-size: 0.9rem;">
                        <i class="bi bi-save me-1"></i> Simpan Surat Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection