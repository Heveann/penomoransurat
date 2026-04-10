<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeKearsipan extends Model
{
    use HasFactory;

    protected $table = 'kode_kearsipan'; // nama tabel
    protected $fillable = ['kode', 'nama']; // kolom yang boleh diisi
}
