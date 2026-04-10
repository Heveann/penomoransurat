<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'kode', // Contoh: 001.2.3
        'nama', // Nama klasifikasi
    ];

    // Relasi: Satu klasifikasi memiliki banyak surat keluar
    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
