@extends('admin.app-admin')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Edit User</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Perbarui data pengguna: {{ $user->name }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn-action btn-print">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="data-card">
    <div class="data-card-header">
        <div class="data-card-title">
            <i class="bi bi-pencil-square me-2" style="color: #6366f1;"></i>
            Form Edit User
        </div>
    </div>

    <div style="padding: 1.5rem;">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <!-- Username -->
                <div class="col-md-6">
                    <label class="form-label-modern">Username <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input-modern @error('name') is-invalid @enderror" placeholder="Masukkan username" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="col-md-6">
                    <label class="form-label-modern">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="form-input-modern @error('nama_lengkap') is-invalid @enderror" placeholder="Masukkan nama lengkap">
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label-modern">Email <span style="color:#dc2626;">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input-modern @error('email') is-invalid @enderror" placeholder="contoh@email.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="col-md-6">
                    <label class="form-label-modern">Role <span style="color:#dc2626;">*</span></label>
                    <select name="role" class="form-input-modern @error('role') is-invalid @enderror" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Telepon -->
                <div class="col-md-6">
                    <label class="form-label-modern">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" class="form-input-modern @error('telepon') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="col-md-6">
                    <label class="form-label-modern">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}" class="form-input-modern @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label class="form-label-modern">Password Baru</label>
                    <input type="password" name="password" class="form-input-modern @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small style="font-size: 0.75rem; color: #94a3b8;">Kosongkan jika tidak ingin mengubah password</small>
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6">
                    <label class="form-label-modern">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input-modern" placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn-action btn-approve" style="padding: 0.6rem 1.5rem; font-size: 0.875rem;">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
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
