<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'nama_lengkap' => 'Admin Utama Sistem',
                'password' => Hash::make('12345'),
                'role' => 'admin'
            ]
        );

        // Pimpinan
        User::updateOrCreate(
            ['email' => 'pimpinan@gmail.com'],
            [
                'name' => 'Pimpinan',
                'nama_lengkap' => 'Pimpinan Instansi',
                'password' => Hash::make('12345'),
                'role' => 'pimpinan'
            ]
        );

        // User
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Biasa',
                'nama_lengkap' => 'User Biasa Sistem',
                'password' => Hash::make('12345'),
                'role' => 'user'
            ]
        );
    }
}
