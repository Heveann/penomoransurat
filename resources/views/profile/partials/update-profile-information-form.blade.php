<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            @if ($user instanceof App\Models\Admin)
                <x-input-label for="nama" :value="__('Nama Pengguna')" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 mb-3 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('nama')" />

                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 mb-3 block w-full" :value="old('nama_lengkap', $user->nama_lengkap ?? '')" autocomplete="nama_lengkap" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('nama_lengkap')" />

                <x-input-label for="alamat" :value="__('Alamat')" />
                <x-text-input id="alamat" name="alamat" type="text" class="mt-1 mb-3 block w-full" :value="old('alamat', $user->alamat ?? '')" autocomplete="alamat" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('alamat')" />

                <x-input-label for="telepon" :value="__('Telepon')" />
                <x-text-input id="telepon" name="telepon" type="text" class="mt-1 mb-3 block w-full" :value="old('telepon', $user->telepon ?? '')" autocomplete="telepon" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('telepon')" />
            @else
                <x-input-label for="name" :value="__('Nama Pengguna')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 mb-3 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('name')" />

                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 mb-3 block w-full" :value="old('nama_lengkap', $user->nama_lengkap ?? '')" autocomplete="nama_lengkap" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('nama_lengkap')" />

                <x-input-label for="alamat" :value="__('Alamat')" />
                <textarea id="alamat" name="alamat" class="form-control mt-1 mb-3" rows="2">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('alamat')" />

                <x-input-label for="telepon" :value="__('Telepon')" />
                <x-text-input id="telepon" name="telepon" type="text" class="mt-1 mb-3 block w-full" :value="old('telepon', $user->telepon ?? '')" autocomplete="telepon" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('telepon')" />

                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 mb-3 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2 mb-3" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-4 mt-3">
            <x-primary-button class="mb-2">{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Profile Information berhasil disimpan!',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    </script>
                @endif
        </div>
    </form>
</section>
