<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_kearsipan_id','pengolah','jenis_naskah','sifat_naskah','hal',
        'tanggal_ditetapkan','tanggal_berlaku','nomor_urut','nomor_surat','status', 'catatan'
    ];

    public function kodeKearsipan()
    {
        return $this->belongsTo(KodeKearsipan::class, 'kode_kearsipan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
