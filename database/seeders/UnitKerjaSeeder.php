<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;

class UnitKerjaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Kabid Prasarana',
            'Kabid Tanaman Pangan dan Holtikultura',
            'Kabid Perkebunan',
            'Kepala Balai Pertanian',
            'Kepala Pengawasan dan Sertifikasi Benih',
            'Kepala Balai Benih Pertanian Wilayah Semarang',
            'Kepala Balai Benih Pertanian Wilayah Surakarta',
            'Kepala Balai Benih Pertanian Wilayah Banyumas',
            'Kepala Balai Mekanisasi dan Modernisasi Pertanian',
            'Kepala Balai Perlindungan Tanaman',
            'Kasubbag Program',
            'Kasubbag Keuangan',
            'Kasubbag Umum dan Kepegawaian',
            'Kasi Prasarana',
            'Kasi Sarana',
            'Kasi Alat Mesin',
        ];

        foreach ($data as $nama) {
            UnitKerja::firstOrCreate(['nama' => $nama]);
        }
    }
}
