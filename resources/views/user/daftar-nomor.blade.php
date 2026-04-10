@extends('user.app-user')
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

    /* Badge Status */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.7rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-approved { background: #ecfdf5; color: #059669; }
    .badge-rejected { background: #fef2f2; color: #dc2626; }
    .badge-pending  { background: #fffbeb; color: #d97706; }
    .badge-revisi   { background: #fef08a; color: #854d0e; }

    /* Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .btn-print   { background: #eff6ff; color: #2563eb; }
    .btn-print:hover   { background: #dbeafe; color: #1d4ed8; }
    .btn-delete  { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover  { background: #fee2e2; color: #b91c1c; }
    .btn-edit    { background: #fef08a; color: #854d0e; }
    .btn-edit:hover    { background: #fde047; color: #713f12; }

    /* Export button */
    .btn-export-custom {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
        color: #64748b !important;
        font-weight: 600 !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 0.8rem !important;
        background: #fff !important;
        transition: all 0.2s ease !important;
    }
    .btn-export-custom:hover {
        border-color: #cbd5e1 !important;
        color: #475569 !important;
        background: #f8fafc !important;
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

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#6366f1', timer: 2500, timerProgressBar: true });
});
</script>
@endif

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Daftar Nomor</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Riwayat pengajuan nomor surat Anda</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle btn-export-custom" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-upload me-1"></i>Export
            </button>
            <ul class="dropdown-menu shadow-lg border-0" aria-labelledby="dropdownExport" style="border-radius: 12px; padding: 8px;">
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.daftar-nomor.cetak') }}" target="_blank" style="border-radius: 8px; padding: 10px 16px; font-weight: 500;"><i class="bi bi-file-earmark-pdf" style="color:#ef4444;font-size:1rem;"></i> Cetak PDF</a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.daftar-nomor.excel') }}" style="border-radius: 8px; padding: 10px 16px; font-weight: 500;"><i class="bi bi-file-earmark-excel" style="color:#22c55e;font-size:1rem;"></i> Ekspor Excel</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="data-card">
    <div class="data-card-header">
        <div class="data-card-title">
            <i class="bi bi-file-earmark-text me-2" style="color: #6366f1;"></i>
            Daftar Nomor Surat
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs" id="filterTabs">
        <button class="filter-tab active" data-filter="all">
            Semua <span class="tab-count" id="countAll">{{ $countAll ?? 0 }}</span>
        </button>
        <button class="filter-tab" data-filter="pending">
            Pending <span class="tab-count" id="countPending">{{ $countPending ?? 0 }}</span>
        </button>
        <button class="filter-tab" data-filter="disetujui">
            Disetujui <span class="tab-count" id="countApproved">{{ $countApproved ?? 0 }}</span>
        </button>
        <button class="filter-tab" data-filter="revisi">
            Revisi <span class="tab-count" id="countRevisi">{{ $countRevisi ?? 0 }}</span>
        </button>
        <button class="filter-tab" data-filter="ditolak">
            Ditolak <span class="tab-count" id="countRejected">{{ $countRejected ?? 0 }}</span>
        </button>
    </div>

    <div class="table-responsive">
        <table class="table data-table table-hover" id="suratTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode & Nama</th>
                    <th>Nomor Surat</th>
                    <th>Jenis Naskah</th>
                    <th>Tanggal</th>
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

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ===== DATATABLES INITIALIZATION =====
    let statusFilter = 'all';

    let table = $('#suratTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('user.daftar-nomor') }}",
            data: function (d) {
                d.status = statusFilter;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
            {data: 'kode_kearsipan_id', name: 'kodeKearsipan.kode'},
            {data: 'nomor_surat_format', name: 'nomor_surat'},
            {data: 'jenis_naskah', name: 'jenis_naskah'},
            {data: 'tanggal_ditetapkan', name: 'tanggal_ditetapkan'},
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
            emptyTable: "<i class='bi bi-inbox' style='font-size: 1.5rem; display: block; margin-bottom: 0.5rem; color: #94a3b8;'></i>Belum ada pengajuan nomor surat.",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    const tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            statusFilter = tab.dataset.filter;
            table.draw();
        });
    });

    // ===== DELETE CONFIRMATION (Event Delegation) =====
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`.form-delete-${id}`).submit();
            }
        });
    });

    // ===== SHOW CATATAN (Event Delegation) =====
    $(document).on('click', '.btn-catatan', function() {
        const catatan = $(this).data('catatan');
        const type = $(this).data('type');
        
        Swal.fire({
            title: 'Alasan ' + type,
            html: `<div style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; text-align: left; font-size: 0.95rem; color: #334155;">${catatan}</div>`,
            icon: type === 'Penolakan' ? 'error' : 'warning',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#6366f1'
        });
    });
});
</script>

@endsection