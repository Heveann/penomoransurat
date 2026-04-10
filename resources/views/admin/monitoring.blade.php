@extends('admin.app-admin')

@section('content')

<!-- jQuery & DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
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

<div class="mb-4">
    <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Monitoring Nomor Surat Unit Kerja</h5>
    <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Rekap unit kerja yang telah mendapatkan nomor Surat Keputusan dan Surat Keluar</p>
</div>

<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="data-card-title mb-0">
            <i class="bi bi-building me-2" style="color: #6366f1;"></i>
            Rekap Nomor Surat per Unit Kerja
        </div>
    </div>
    <div class="table-responsive">
        <table class="table data-table table-hover" id="monitoringTable" style="width: 100%;">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>Unit Kerja (Pengolah)</th>
                    <th class="text-center">Surat Keputusan</th>
                    <th class="text-center">Surat Keluar</th>
                    <th class="text-center">Total Nomor Surat</th>
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
    $('#monitoringTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('monitoring.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%', className: 'text-center'},
            {data: 'pengolah', name: 'pengolah'},
            {data: 'surat_keputusan', name: 'surat_keputusan', className: 'text-center'},
            {data: 'surat_keluar', name: 'surat_keluar', className: 'text-center'},
            {data: 'total', name: 'total', className: 'text-center', searchable: false}
        ],
        language: {
            processing: "<div class='text-primary fw-bold'><i class='bi bi-arrow-repeat'></i> Memuat data...</div>",
            search: "",
            searchPlaceholder: "Cari unit kerja...",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            emptyTable: "<i class='bi bi-inbox' style='font-size: 1.5rem; display: block; margin-bottom: 0.5rem; color: #94a3b8;'></i>Belum ada data pengajuan surat.",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

@endsection
