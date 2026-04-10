@extends('admin.app-admin')
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
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Daftar Surat Masuk</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Kelola pencatatan surat masuk instansi</p>
    </div>
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('surat-masuk.create') }}" class="btn" style="background: #4f46e5; color: white; border-radius: 8px; font-weight: 500; font-size: 0.85rem; padding: 0.5rem 1rem;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Surat Masuk
    </a>
    @endif
</div>

<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="data-card-title mb-0">Data Surat Masuk</div>
        <div class="d-flex align-items-center gap-2 flex-grow-1 justify-content-end flex-wrap">
            <select class="form-select form-select-sm" id="tablePerPage" style="width: auto; font-size: 0.8rem; border-color: #e2e8f0;">
                <option value="10">10 baris</option>
                <option value="25" selected>25 baris</option>
                <option value="50">50 baris</option>
                <option value="100">100 baris</option>
            </select>
            <span class="text-muted d-none d-md-inline" id="entriesInfo" style="font-size: 0.8rem; margin-right: 0.5rem;">Menampilkan {{ count($suratMasuks) }} data</span>
            <input type="text" class="search-input" id="searchInput" placeholder="Cari surat..." style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.85rem; font-size: 0.8rem; outline: none; width: 240px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Surat Masuk</th>
                    <th>Tanggal Surat</th>
                    <th>Pengirim</th>
                    <th>Perihal</th>
                    <th>File</th>
                    <th>Status Disposisi</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuks as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><div style="font-weight: 600;">{{ $surat->nomor_surat_masuk }}</div></td>
                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d M Y') }}</td>
                    <td>{{ $surat->pengirim }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($surat->perihal, 30) }}</td>
                    <td>
                        @if($surat->file_scan)
                            <a href="{{ asset('storage/' . $surat->file_scan) }}" target="_blank" class="btn-action" style="background: #f1f5f9; color: #475569;">
                                <i class="bi bi-file-pdf"></i> Lihat
                            </a>
                        @else
                            <span style="font-size: 0.75rem; color: #94a3b8;">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @if($surat->status_disposisi)
                            <span class="badge-status badge-approved" style="background: #eff6ff; color: #2563eb;"><i class="bi bi-diagram-2"></i> Sudah</span>
                        @else
                            <span class="badge-status badge-pending"><i class="bi bi-clock"></i> Belum</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('surat-masuk.show', $surat->id) }}" class="btn-action" style="background: #eff6ff; color: #3b82f6;" title="Detail & Disposisi">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(Auth::user()->role === 'admin')
                            <form action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus surat masuk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-reject" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 2rem; color: #64748b;">Belum ada data surat masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div class="d-flex justify-content-between align-items-center p-3 border-top flex-wrap gap-2">
        <div class="text-muted" style="font-size: 0.8rem;"><span id="pageInfo"></span></div>
        <div class="d-flex gap-2 align-items-center">
            <button class="btn btn-sm btn-outline-secondary" id="btnPrev">Sebelumnya</button>
            <span class="badge text-bg-light border px-2 py-1" style="font-size: 0.8rem; color: #475569;" id="pageNumber">1</span>
            <button class="btn btn-sm btn-outline-secondary" id="btnNext">Selanjutnya</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('.data-table tbody tr:not(.empty-row)');
    const entriesInfo = document.getElementById('entriesInfo');
    
    // Pagination Elements
    const selectPerPage = document.getElementById('tablePerPage');
    const pageInfo = document.getElementById('pageInfo');
    const pageNumberSpan = document.getElementById('pageNumber');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    let limit = parseInt(selectPerPage.value) || 25;
    let currentPage = 1;

    function applyFilter() {
        if(!searchInput) return;
        const val = searchInput.value.toLowerCase().trim();
        let filteredRows = [];

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchSearch = text.includes(val);
            if (matchSearch) {
                filteredRows.push(row);
            }
            row.style.display = 'none'; // hide initially
        });

        const visibleCount = filteredRows.length;
        if(entriesInfo) {
            entriesInfo.textContent = val 
                ? `${visibleCount} dari ${rows.length} data ditemukan` 
                : `Menampilkan ${rows.length} data`;
        }

        const totalPages = Math.ceil(visibleCount / limit) || 1;
        if(currentPage > totalPages) currentPage = totalPages;

        const startIndex = (currentPage - 1) * limit;
        const endIndex = startIndex + limit;

        filteredRows.forEach((row, idx) => {
            if (idx >= startIndex && idx < endIndex) {
                row.style.display = '';
                // Since this table doesn't have an empty-row class explicitly in the loop but uses empty handler, we should check it
                const noCell = row.querySelector('td:first-child');
                if(noCell && !noCell.hasAttribute('colspan')) {
                    noCell.textContent = idx + 1; // Re-numbering
                }
            }
        });

        btnPrev.disabled = currentPage === 1;
        btnNext.disabled = currentPage === totalPages;
        pageNumberSpan.textContent = `Hal ${currentPage} / ${totalPages}`;

        const displayingStart = visibleCount === 0 ? 0 : startIndex + 1;
        const displayingEnd = Math.min(endIndex, visibleCount);
        pageInfo.innerHTML = `Menampilkan ${displayingStart}-${displayingEnd} dari ${visibleCount} baris`;
    }

    if(rows.length > 0) {
        // Initialize counts on load
        applyFilter();

        // Pagination Events
        selectPerPage.addEventListener('change', function() {
            limit = parseInt(this.value);
            currentPage = 1;
            applyFilter();
        });

        btnPrev.addEventListener('click', () => {
            if(currentPage > 1) { currentPage--; applyFilter(); }
        });

        btnNext.addEventListener('click', () => {
             currentPage++; applyFilter();
        });

        searchInput.addEventListener('input', function() {
            currentPage = 1;
            applyFilter();
        });
    }
});
</script>

@endsection
