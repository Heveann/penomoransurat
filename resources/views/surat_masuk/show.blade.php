@extends(in_array(Auth::user()->role, ['admin', 'pimpinan']) ? 'admin.app-admin' : 'user.app-user')
@section('content')

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#6366f1', timer: 2500, timerProgressBar: true });
    });
</script>
@endif

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Detail Surat Masuk & Disposisi</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Lihat rincian surat dan perintah disposisi</p>
    </div>
    @php
        $backRoute = in_array(Auth::user()->role, ['admin', 'pimpinan']) ? route('surat-masuk.index') : route('disposisi.index');
    @endphp
    <a href="{{ $backRoute }}" class="btn" style="background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 500; font-size: 0.85rem; padding: 0.5rem 1rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Info Surat -->
    <div class="col-lg-6">
        <div class="data-card mb-4">
            <div class="data-card-header">
                <div class="data-card-title">Informasi Surat Masuk</div>
            </div>
            <div style="padding: 1.5rem;">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td width="35%" style="color: #64748b; font-size: 0.85rem;">Nomor Surat</td>
                        <td style="font-weight: 600; color: #0f172a;">: {{ $suratMasuk->nomor_surat_masuk }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 0.85rem;">Pengirim</td>
                        <td style="font-weight: 600; color: #0f172a;">: {{ $suratMasuk->pengirim }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 0.85rem;">Tanggal Surat</td>
                        <td style="font-weight: 500; color: #0f172a;">: {{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 0.85rem;">Tanggal Terima</td>
                        <td style="font-weight: 500; color: #0f172a;">: {{ \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 0.85rem;">Perihal</td>
                        <td style="font-weight: 500; color: #0f172a;">: {{ $suratMasuk->perihal }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 0.85rem;">File Dokumen</td>
                        <td>: 
                            @if($suratMasuk->file_scan)
                                <a href="{{ asset('storage/' . $suratMasuk->file_scan) }}" target="_blank" class="badge rounded-pill" style="background: #eff6ff; color: #2563eb; text-decoration: none; padding: 0.4rem 0.8rem; font-weight: 500;">
                                    <i class="bi bi-file-earmark-pdf"></i> Lihat Lampiran
                                </a>
                            @else
                                <span class="text-muted" style="font-size: 0.85rem;">(Tidak ada lampiran)</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'pimpinan']))
        <!-- Form Tambah Disposisi -->
        <div class="data-card mb-4" style="border-top: 4px solid #4f46e5;">
            <div class="data-card-header" style="background: rgba(79, 70, 229, 0.03);">
                <div class="data-card-title" style="color: #4f46e5;"><i class="bi bi-plus-circle me-1"></i> Buat Disposisi Baru</div>
            </div>
            <div style="padding: 1.5rem;">
                <form action="{{ route('disposisi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="surat_masuk_id" value="{{ $suratMasuk->id }}">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem;">Tujuan (Pegawai / User) <span class="text-danger">*</span></label>
                        <select name="penerima_id" class="form-select" required style="border-radius: 8px; font-size: 0.9rem;">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem;">Catatan / Instruksi Disposisi <span class="text-danger">*</span></label>
                        <textarea name="catatan" rows="3" class="form-control" required style="border-radius: 8px; font-size: 0.9rem;" placeholder="Misal: Segera tindak lanjuti dan laporkan hasilnya by besok."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem;">Batas Waktu pengerjaan (Opsional)</label>
                        <input type="date" name="tenggat_waktu" class="form-control" style="border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn" style="background: #4f46e5; color: white; border-radius: 8px; font-weight: 500; font-size: 0.85rem;">
                            <i class="bi bi-send me-1"></i> Kirim Disposisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Kolom Kanan: Riwayat Disposisi -->
    <div class="col-lg-6">
        <div class="data-card h-100">
            <div class="data-card-header">
                <div class="data-card-title">Riwayat Disposisi</div>
            </div>
            <div style="padding: 1.5rem;">
                
                @forelse($suratMasuk->disposisis as $disposisi)
                <div class="p-3 mb-3" style="border-radius: 10px; border: 1px solid #e2e8f0; background: #f8fafc; position: relative;">
                    <!-- Badge Status -->
                    <div style="position: absolute; top: 12px; right: 12px;">
                        @if($disposisi->status === 'selesai')
                            <span class="badge rounded-pill" style="background: #10b981; font-weight: 500;"><i class="bi bi-check-circle"></i> Selesai</span>
                        @elseif($disposisi->status === 'dikerjakan')
                            <span class="badge rounded-pill" style="background: #3b82f6; font-weight: 500;"><i class="bi bi-arrow-repeat"></i> Diproses</span>
                        @else
                            <span class="badge rounded-pill" style="background: #f59e0b; font-weight: 500;"><i class="bi bi-clock"></i> Pending</span>
                        @endif
                    </div>
                    
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">
                        <i class="bi bi-calendar-event me-1"></i> {{ $disposisi->created_at->translatedFormat('d M Y, H:i') }}
                    </div>
                    <div style="margin-bottom: 0.5rem;">
                        <span style="font-weight: 600; color: #0f172a; font-size: 0.9rem;">Dari:</span> <span style="font-size: 0.9rem;">{{ $disposisi->pemberi->name ?? 'Unknown' }}</span><br>
                        <span style="font-weight: 600; color: #0f172a; font-size: 0.9rem;">Kepada:</span> <span style="font-size: 0.9rem; color: #4f46e5; font-weight: 600;">{{ $disposisi->penerima->name ?? 'Unknown' }}</span>
                    </div>
                    
                    <div style="background: white; border-left: 3px solid #4f46e5; padding: 0.5rem 0.75rem; border-radius: 4px; font-size: 0.85rem; color: #334155; margin-bottom: 0.5rem;">
                        {{ $disposisi->catatan }}
                    </div>

                    @if($disposisi->tenggat_waktu)
                    <div style="font-size: 0.75rem; color: #ef4444; font-weight: 500;">
                        <i class="bi bi-alarm"></i> Deadline: {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d F Y') }}
                    </div>
                    @endif

                    <!-- Jika ada laporan hasil -->
                    @if($disposisi->laporan_hasil)
                    <div class="mt-3 pt-2" style="border-top: 1px dashed #cbd5e1;">
                        <div style="font-size: 0.8rem; font-weight: 600; color: #10b981; margin-bottom: 0.25rem;">Laporan Hasil:</div>
                        <div style="font-size: 0.85rem; color: #334155; background: #ecfdf5; padding: 0.5rem; border-radius: 6px;">
                            {{ $disposisi->laporan_hasil }}
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center" style="padding: 3rem 1rem;">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2" style="font-size: 0.85rem;">Belum ada disposisi untuk surat ini.</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>
</div>

@endsection
