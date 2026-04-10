<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        // Jika yang login adalah User
        if ($user instanceof \App\Models\User) {
            return [
                'name' => ['required', 'string', 'max:255'],
                'nama_lengkap' => ['nullable', 'string', 'max:255'],
                'alamat' => ['nullable', 'string', 'max:255'],
                'telepon' => ['nullable', 'string', 'max:30'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($user->id),
                ],
            ];
        }
        // Jika yang login adalah Admin
        if ($user instanceof \App\Models\Admin) {
            return [
                'nama' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique('admins')->ignore($user->id),
                ],
            ];
        }
        return [];
    }
}
