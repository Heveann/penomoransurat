@extends('admin.app-admin')
@section('content')

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery & DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e2e8f0;
        padding: 0 1.5rem;
    }
    .filter-tab {
        padding: 0.75rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
        font-family: 'Inter', sans-serif;
    }
    .filter-tab:hover { color: #334155; }
    .filter-tab.active {
        color: #6366f1;
        border-bottom-color: #6366f1;
    }
    .filter-tab .tab-count {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.15rem 0.5rem;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        min-width: 22px;
        text-align: center;
    }
    .filter-tab.active .tab-count {
        background: #eef2ff;
        color: #6366f1;
    }

    /* Override DataTables Styles to Match Current Minimalist Tailwind Theme */
    div.dataTables_wrapper div.dataTables_length select {
        width: auto;
        display: inline-block;
        border-radius: 8px;
        border-color: #e2e8f0;
        font-size: 0.8rem;
        padding: 0.35rem 2rem 0.35rem 0.75rem;
        box-shadow: none;
        outline: none;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        padding: 0.4rem 0.85rem 0.4rem 2.25rem;
        font-size: 0.85rem;
        margin-left: 0.5rem;
        outline: none;
        box-shadow: none;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 0.75rem center;
    }
    div.dataTables_wrapper div.dataTables_filter input:focus {
        border-color: #6366f1;
    }
    
    table.dataTable.table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #f8fafc;
    }
    table.dataTable > thead > tr > th {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
    }
    table.dataTable > tbody > tr > td {
        padding: 0.85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .dataTables_info, .dataTables_paginate {
        font-size: 0.85rem;
        padding-top: 0.5rem;
        color: #64748b;
    }
    .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
    }
    .page-link {
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .page-link:hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }
    .dataTables_wrapper {
        padding: 1.5rem;
    }
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Permintaan Nomor</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
            {{ Auth::user()->role === 'pimpinan' ? 'Daftar surat yang telah disetujui' : 'Kelola seluruh pengajuan nomor surat' }}
        </p>
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#6366f1', timer: 2500, timerProgressBar: true });
        });
    </script>
@endif
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#6366f1' });
        });
    </script>
@endif

<!-- Data Table -->
<div class="data-card">
    <div class="data-card-header">
        <div class="data-card-title">
            <i class="bi bi-file-earmark-text me-2" style="color: #6366f1;"></i>
            Daftar Nomor Surat
        </div>
    </div>

    <!-- Filter Tabs -->
    @php
        $countAll = $surat->count();
        $countApproved = $surat->where('status', 'disetujui')->count();
        $countRejected = $surat->where('status', 'ditolak')->count();
        $countRevisi = $surat->where('status', 'revisi')->count();
        $countPending = $surat->filter(fn($s) => $s->status === 'pending')->count();
    @endphp
    <div class="filter-tabs" id="filterTabs">
        <button class="filter-tab active" data-filter="all">
            Semua <span class="tab-count">{{ $countAll }}</span>
        </button>
        @if(Auth::user()->role !== 'pimpinan')
        <button class="filter-tab" data-filter="pending">
            Pending <span class="tab-count">{{ $countPending }}</span>
        </button>
        @endif
        <button class="filter-tab" data-filter="disetujui">
            Disetujui <span class="tab-count">{{ $countApproved }}</span>
        </button>
        <button class="filter-tab" data-filter="ditolak">
            Ditolak <span class="tab-count">{{ $countRejected }}</span>
        </button>
        @if(Auth::user()->role !== 'pimpinan')
        <button class="filter-tab" data-filter="revisi">
            Revisi <span class="tab-count">{{ $countRevisi }}</span>
        </button>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table data-table table-hover" id="suratTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengaju</th>
                    <th>Kode & Nama</th>
                    <th>Jenis Naskah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Diisi oleh Yajra DataTables -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Details -->
@foreach($surat as $req)
    @if($req->status !== 'disetujui' && $req->status !== 'ditolak')
    <div class="modal fade" id="modalDetail-{{ $req->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
          <div class="modal-header border-0 bg-light">
              <h5 class="modal-title fs-6 fw-bold text-dark"><i class="bi bi-file-text me-2 text-primary"></i>Detail Inputan Surat</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 bg-white">
              <div class="row g-4">
                  <div class="col-md-6">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Unit Kerja (Pengolah)</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem;">{{ $req->pengolah ?? '-' }}</div>
                  </div>
                  <div class="col-md-6">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Kode & Klasifikasi Kearsipan</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem;">
                          <span class="badge bg-light text-dark border">{{ $req->kodeKearsipan->kode ?? '-' }}</span> 
                          {{ $req->kodeKearsipan->nama ?? '-' }}
                      </div>
                  </div>
                  <div class="col-md-6">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Naskah</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem;">{{ $req->jenis_naskah }}</div>
                  </div>
                  <div class="col-md-6">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Sifat Naskah</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem;">{{ $req->sifat_naskah }}</div>
                  </div>
                  <div class="col-md-12">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Hal / Perihal</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem; background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                          {{ $req->hal ?? '-' }}
                      </div>
                  </div>
                  <div class="col-md-6">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Ditetapkan</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem;">
                          {{ $req->tanggal_ditetapkan ? \Carbon\Carbon::parse($req->tanggal_ditetapkan)->translatedFormat('d F Y') : '-' }}
                      </div>
                  </div>
                  <div class="col-md-6">
                      <label class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Berlaku</label>
                      <div style="font-weight: 500; color: #334155; font-size: 0.95rem;">
                          {{ $req->tanggal_berlaku ? \Carbon\Carbon::parse($req->tanggal_berlaku)->translatedFormat('d F Y') : '-' }}
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer border-0 bg-light justify-content-between">
              <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
              <div class="d-flex gap-2">
                  <form action="{{ route('surat.revisi', $req->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <input type="hidden" name="catatan" class="input-catatan">
                      <button type="button" class="btn btn-warning text-dark btn-sm px-4" data-action="revisi">Revisi</button>
                  </form>
                  <form action="{{ route('surat.reject', $req->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <input type="hidden" name="catatan" class="input-catatan">
                      <button type="button" class="btn btn-danger btn-sm px-4" data-action="reject">Tolak</button>
                  </form>
                  <form action="{{ route('surat.approve', $req->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <button type="button" class="btn btn-success btn-sm px-4" data-action="approve">Setujui</button>
                  </form>
              </div>
          </div>
        </div>
      </div>
    </div>
    @endif
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ===== FILTER TABS & DATATABLES INITIALIZATION =====
    let statusFilter = 'all';

    let table = $('#suratTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('surat.index') }}",
            data: function (d) {
                d.status = statusFilter;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
            {data: 'pengaju', name: 'user.name'},
            {data: 'kode_nama', name: 'kodeKearsipan.kode'},
            {data: 'jenis_naskah', name: 'jenis_naskah'},
            {data: 'status', name: 'status'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ],
        language: {
            processing: "<div class='text-primary fw-bold'><i class='bi bi-arrow-repeat'></i> Memuat data...</div>",
            search: "",
            searchPlaceholder: "Cari data...",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            emptyTable: "<i class='bi bi-inbox' style='font-size: 1.5rem; display: block; margin-bottom: 0.5rem; color: #94a3b8;'></i>Belum ada data surat.",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Custom Tabs Logic Update DataTables
    const tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            statusFilter = tab.dataset.filter;
            table.draw();
        });
    });

    // ===== SWEETALERT APPROVE =====
    document.querySelectorAll('[data-action="approve"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Setujui surat ini?',
                text: 'Nomor surat akan digenerate secara otomatis.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-check-lg me-1"></i> Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#94a3b8',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // ===== SWEETALERT REJECT =====
    document.querySelectorAll('[data-action="reject"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const parentModal = this.closest('.modal');
            Swal.fire({
                target: parentModal ? parentModal : 'body',
                title: 'Tolak surat ini?',
                text: 'Berikan alasan kenapa surat ditolak.',
                input: 'textarea',
                inputPlaceholder: 'Tuliskan catatan penolakan...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-x-lg me-1"></i> Ya, Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8',
                reverseButtons: true,
                focusCancel: true,
                preConfirm: (text) => {
                    if (!text) Swal.showValidationMessage('Catatan alasan wajib diisi!');
                    return text;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.querySelector('.input-catatan').value = result.value;
                    form.submit();
                }
            });
        });
    });

    // ===== SWEETALERT REVISI =====
    document.querySelectorAll('[data-action="revisi"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const parentModal = this.closest('.modal');
            Swal.fire({
                target: parentModal ? parentModal : 'body',
                title: 'Kembalikan untuk Revisi?',
                text: 'Berikan alasan apa yang perlu diperbaiki.',
                input: 'textarea',
                inputPlaceholder: 'Tuliskan catatan revisi...',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-pencil-square me-1"></i> Kirim Revisi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#eab308',
                cancelButtonColor: '#94a3b8',
                reverseButtons: true,
                focusCancel: true,
                preConfirm: (text) => {
                    if (!text) Swal.showValidationMessage('Catatan alasan wajib diisi!');
                    return text;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.querySelector('.input-catatan').value = result.value;
                    form.submit();
                }
            });
        });
    });
});
</script>

@endsection
