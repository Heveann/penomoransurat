<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomor extends Model
{
    protected $table = 'nomors';
    public $incrementing = false;
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id', 'tahun'
    ];
}
