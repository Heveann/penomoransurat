<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        // Untuk admin, gunakan field 'nama', untuk user gunakan 'name'
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Untuk user
        if ($user instanceof \App\Models\User) {
            $user->fill($data);
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        }
        // Untuk admin
        if ($user instanceof \App\Models\Admin) {
            $user->fill([
                'nama' => $data['nama'] ?? $user->nama,
                'email' => $data['email'] ?? $user->email,
            ]);
        }
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Handle avatar upload and update.
     */
    public function avatar(Request $request): RedirectResponse
    {
        $user = $request->user();
        // Jika ada cropped_avatar (base64 dari cropper), gunakan itu
        if ($request->filled('cropped_avatar')) {
            $data = $request->input('cropped_avatar');
            // Format: data:image/png;base64,xxxx
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // png, jpg, jpeg, etc
                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    return Redirect::route('profile.edit')->withErrors(['avatar' => 'Format gambar tidak didukung.']);
                }
                $data = base64_decode($data);
                if ($data === false) {
                    return Redirect::route('profile.edit')->withErrors(['avatar' => 'Gagal decode gambar.']);
                }
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $type;
                $path = 'avatars/' . $filename;
                Storage::disk('public')->put($path, $data);
                $user->avatar_url = '/storage/' . $path;
                $user->save();
                return Redirect::route('profile.edit')->with('status', 'avatar-updated');
            } else {
                return Redirect::route('profile.edit')->withErrors(['avatar' => 'Format gambar tidak valid.']);
            }
        }
        // Jika tidak ada cropped_avatar, fallback ke upload file biasa
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);
        $avatar = $request->file('avatar');
        $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
        $path = $avatar->storeAs('avatars', $filename, 'public');
        $user->avatar_url = '/storage/' . $path;
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }
}
