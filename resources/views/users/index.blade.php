@extends('admin.app-admin')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Manajemen User</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Daftar pengguna yang terdaftar pada sistem</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-action btn-approve" style="padding: 0.55rem 1.1rem; font-size: 0.85rem;">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<!-- Data Table -->
<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="data-card-title mb-0">
            <i class="bi bi-people me-2" style="color: #6366f1;"></i>
            Daftar Pengguna Sistem
            <span class="badge-status badge-approved ms-2">{{ $users->count() }} User</span>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table data-table table-hover" id="usersTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengguna</th>
                    <th>Kontak</th>
                    <th>Role</th>
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
    $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
            {data: 'name', name: 'name'},
            {data: 'email_telepon', name: 'email', orderable: false},
            {data: 'role', name: 'role'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ],
        language: {
            processing: "<div class='text-primary fw-bold'><i class='bi bi-arrow-repeat'></i> Memuat data...</div>",
            search: "",
            searchPlaceholder: "Cari pengguna...",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            emptyTable: "<i class='bi bi-people' style='font-size: 1.5rem; display: block; margin-bottom: 0.5rem; color: #94a3b8;'></i>Belum ada user terdaftar.",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus user ini?',
            text: `User akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`.form-delete-${id}`).submit();
            }
        });
    });
});
</script>

@endsection
