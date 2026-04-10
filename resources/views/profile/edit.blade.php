@extends(Auth::user()->role === 'user' ? 'user.app-user' : 'admin.app-admin')
@section('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Card 1: Avatar, Tanggal Daftar, Login Terakhir -->
                <div class="col-md-4">
                    <div class="card border rounded mb-4 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <span class="fw-bold"><i class="bi bi-person-circle me-2"></i>Avatar</span>
                        </div>
                        <div class="card-body text-center p-3" style="min-height:unset;">
                            @if (session('status') === 'avatar-updated')
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Avatar berhasil diganti!',
                                        confirmButtonText: 'OK',
                                        timer: 2000,
                                        timerProgressBar: true
                                    });
                                </script>
                            @endif
                            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mb-3 w-100" id="avatarForm">
                                @csrf
                                <div class="d-flex flex-column align-items-center">
                                    <img id="avatarPreview"
                                         src="{{ (!empty($user->avatar_url) && file_exists(public_path($user->avatar_url))) 
                                            ? asset($user->avatar_url) . '?v=' . uniqid() 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&size=180' }}"
                                         class="mb-2 rounded-circle"
                                         alt="Avatar"
                                         style="width:140px;height:140px;object-fit:cover;">
                                    <button type="button" id="showAvatarInput" class="btn btn-sm btn-primary mb-1">Ganti Avatar</button>
                                    <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                           class="form-control mb-1" style="max-width:180px;display:none;">
                                    <input type="hidden" name="cropped_avatar" id="croppedAvatarInput">
                                    <button type="submit" id="submitAvatarBtn" class="btn btn-sm btn-success mb-1" style="display:none;">Simpan</button>
                                </div>
                            </form>
                            <!-- Modal Cropper -->
                            <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="false">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="cropperModalLabel">Crop Foto Profil</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                    <div class="modal-body text-center" style="position:relative;z-index:2;max-width:100%;overflow:auto;">
                                    <img id="cropperImage" src="" alt="Preview" style="max-width:100%;max-height:350px;display:block;margin:auto;z-index:2;">
                                    </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-primary" id="cropImageBtn">Crop & Simpan</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- Cropper.js CSS -->
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
                            <style>
                            /* Modal cropper responsif dan gambar tidak melebihi modal */
                            #cropperModal .modal-dialog {
                                max-width: 480px;
                                width: 95vw;
                            }
                            #cropperModal .modal-body {
                                max-width: 100%;
                                overflow-x: auto;
                            }
                            #cropperImage {
                                max-width: 100%;
                                max-height: 350px;
                                width: auto;
                                height: auto;
                                z-index: 1 !important;
                            }
                            .modal-footer, .modal-header {
                                position: relative;
                                z-index: 1056;
                            }
                            
                            /* Pastikan modal-footer dan modal-header selalu di atas cropper */
                            .modal-footer, .modal-header {
                                position: relative;
                                z-index: 1056;
                            }
                            /* Pastikan cropper image tidak menutupi tombol modal */
                            #cropperImage {
                                z-index: 1 !important;
                            }
                            </style>
                            <!-- Bootstrap JS -->
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                            <!-- Cropper.js JS -->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                            <script>
                            let cropper;
                            const cropperImage = document.getElementById('cropperImage');
                            const cropperModalEl = document.getElementById('cropperModal');
                            const cropperModal = new bootstrap.Modal(cropperModalEl);

                            // Tombol "Ganti Avatar" -> klik input file
                            document.getElementById('showAvatarInput').addEventListener('click', function () {
                                document.getElementById('avatarInput').click();
                            });

                            // Saat pilih file

                            document.getElementById('avatarInput').addEventListener('change', function (e) {
                                const [file] = e.target.files;
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function (evt) {
                                        cropperImage.onload = function() {
                                            cropperModal.show();
                                        };
                                        cropperImage.src = evt.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });

                            // Inisialisasi cropper hanya setelah modal benar-benar tampil dan gambar sudah siap
                            cropperModalEl.addEventListener('shown.bs.modal', function () {
                                if (cropper) { cropper.destroy(); cropper = null; }
                                if (cropperImage.src) {
                                    setTimeout(function() {
                                        cropper = new Cropper(cropperImage, {
                                            aspectRatio: 1,
                                            viewMode: 1,
                                            autoCropArea: 1,
                                            responsive: true
                                        });
                                    }, 100); // beri jeda agar modal benar-benar siap
                                }
                            });

                            // Saat modal ditutup -> destroy cropper
                            cropperModalEl.addEventListener('hidden.bs.modal', function () {
                                if (cropper) {
                                    cropper.destroy();
                                    cropper = null;
                                }
                                cropperImage.src = '';
                            });

                            // Tombol crop
                            document.getElementById('cropImageBtn').addEventListener('click', function () {
                                if (cropper) {
                                    cropper.getCroppedCanvas({ width: 300, height: 300 }).toBlob(function (blob) {
                                        let url = URL.createObjectURL(blob);
                                        document.getElementById('avatarPreview').src = url;
                                        let reader = new FileReader();
                                        reader.onloadend = function () {
                                            document.getElementById('croppedAvatarInput').value = reader.result;
                                            document.getElementById('submitAvatarBtn').style.display = 'inline-block';
                                        };
                                        reader.readAsDataURL(blob);
                                    }, 'image/png');
                                    cropperModal.hide();
                                }
                            });

                            // Hide tombol submit sebelum crop
                            window.addEventListener('DOMContentLoaded', function() {
                                document.getElementById('submitAvatarBtn').style.display = 'none';
                            });
                            </script>
                            <hr class="mt-2 mb-2">
                            <div class="mb-1 text-start w-100">
                                <i class="bi bi-calendar3"></i>
                                <span class="fw-semibold">Tanggal Daftar :</span>
                                <span>{{ $user->created_at ? $user->created_at->format('d-m-Y H:i:s') : '-' }}</span>
                            </div>
                            <div class="mb-1 text-start w-100">
                                <i class="bi bi-clock-history"></i>
                                <span class="fw-semibold">Login Terakhir :</span>
                                <span>{{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i:s') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2: Profile Information -->
                <div class="col-md-8">
                    <div class="card border rounded mb-4 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <span class="fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Ubah Profile</span>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                    <!-- Card 3: Update Password -->
                    <div class="card border rounded mb-4 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <span class="fw-bold"><i class="bi bi-key me-2"></i>Ubah Kata Sandi</span>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                    <!-- Card 4: Delete Account -->
                    <div class="card border rounded mb-4 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <span class="fw-bold"><i class="bi bi-trash me-2"></i>Hapus Akun</span>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
