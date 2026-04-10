@extends('admin.app-admin')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Tambah User</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Buat akun pengguna baru</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn-action btn-print">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="data-card">
    <div class="data-card-header">
        <div class="data-card-title">
            <i class="bi bi-person-plus me-2" style="color: #6366f1;"></i>
            Form Tambah User
        </div>
    </div>

    <div style="padding: 1.5rem;">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <!-- Username -->
                <div class="col-md-6">
                    <label class="form-label-modern">Username <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input-modern @error('name') is-invalid @enderror" placeholder="Masukkan username" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="col-md-6">
                    <label class="form-label-modern">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-input-modern @error('nama_lengkap') is-invalid @enderror" placeholder="Masukkan nama lengkap">
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label-modern">Email <span style="color:#dc2626;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input-modern @error('email') is-invalid @enderror" placeholder="contoh@email.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="col-md-6">
                    <label class="form-label-modern">Role <span style="color:#dc2626;">*</span></label>
                    <select name="role" class="form-input-modern @error('role') is-invalid @enderror" required>
                        <option value="">Pilih Role</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Telepon -->
                <div class="col-md-6">
                    <label class="form-label-modern">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" class="form-input-modern @error('telepon') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="col-md-6">
                    <label class="form-label-modern">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" class="form-input-modern @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label class="form-label-modern">Password <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="password" class="form-input-modern @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6">
                    <label class="form-label-modern">Konfirmasi Password <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input-modern" placeholder="Ulangi password" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn-action btn-approve" style="padding: 0.6rem 1.5rem; font-size: 0.875rem;">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn-action" style="background: #f1f5f9; color: #475569; padding: 0.6rem 1.5rem; font-size: 0.875rem;">
                    <i class="bi bi-x-lg"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label-modern {
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
        display: block;
    }
    .form-input-modern {
        width: 100%;
        padding: 0.6rem 0.85rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #1e293b;
        background: #fff;
        transition: all 0.2s ease;
    }
    .form-input-modern:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .form-input-modern::placeholder {
        color: #94a3b8;
    }
    .form-input-modern.is-invalid {
        border-color: #dc2626;
    }
</style>

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'error', title: 'Validasi Gagal!', text: 'Silakan periksa kembali form yang diisi.', confirmButtonColor: '#6366f1' });
});
</script>
@endif

@endsection
