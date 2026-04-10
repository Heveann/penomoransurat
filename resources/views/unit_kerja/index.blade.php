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
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Pastikan form diisi dengan benar.', confirmButtonColor: '#ef4444' });
});
</script>
@endif

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
    <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Data Unit Kerja (Pengolah)</h5>
    <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Kelola master data Unit Kerja untuk form pengajuan surat</p>
</div>

<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="data-card-title mb-0"><i class="bi bi-building me-2" style="color: #6366f1;"></i> Daftar Unit Kerja</div>
        <div class="d-flex align-items-center gap-2 flex-grow-1 justify-content-end flex-wrap">
            <button class="btn-action btn-approve mb-0" data-bs-toggle="modal" data-bs-target="#modalTambahUnit" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table data-table table-hover" id="unitTable" style="width: 100%;">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="80%">Nama Unit Kerja / Pengolah</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Diisi oleh Yajra -->
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambahUnit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header border-0 bg-primary text-white"><h5 class="modal-title fs-6"><i class="bi bi-plus-circle me-2"></i>Tambah Unit Kerja</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('unit-kerja.store') }}" method="POST">
          <div class="modal-body p-4 bg-light">
              @csrf
              <div class="mb-3"><label class="form-label form-label-sm">Nama Unit Kerja <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" required></div>
          </div>
          <div class="modal-footer border-0 bg-white"><button type="submit" class="btn btn-primary btn-sm px-3">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEditUnit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header border-0 bg-success text-white"><h5 class="modal-title fs-6"><i class="bi bi-pencil-square me-2"></i>Edit Unit Kerja</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form action="" method="POST" id="formEditUnit">
          <div class="modal-body p-4 bg-light">
              @csrf @method('PUT')
              <div class="mb-3"><label class="form-label form-label-sm">Nama Unit Kerja <span class="text-danger">*</span></label><input type="text" name="nama" id="editNamaVal" class="form-control" required></div>
          </div>
          <div class="modal-footer border-0 bg-white"><button type="submit" class="btn btn-success btn-sm px-3">Update</button></div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#unitTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('unit-kerja.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
            {data: 'nama', name: 'nama'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center'}
        ],
        language: {
            processing: "<div class='text-primary fw-bold'><i class='bi bi-arrow-repeat'></i> Memuat data...</div>",
            search: "",
            searchPlaceholder: "Cari unit kerja...",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            emptyTable: "Belum ada unit kerja.",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Populate Edit Modals (using Event Delegation)
    $(document).on('click', '.btn-edit-unit', function() {
        document.getElementById('editNamaVal').value = $(this).data('nama');
        document.getElementById('formEditUnit').action = `/unit-kerja/${$(this).data('id')}`;
    });

    // Delete Buttons (using Event Delegation)
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        Swal.fire({
            title: 'Hapus data ini?',
            text: `Data "${nama}" akan terhapus dari sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`.form-delete-${id}`).submit();
            }
        });
    });
});
</script>

@endsection
