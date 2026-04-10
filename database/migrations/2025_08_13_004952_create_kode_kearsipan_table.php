<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kode_kearsipan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50);  // contoh: 000.5.2.6
            $table->string('nama');      // contoh: Bimbingan Konsultasi Kearsipan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kode_kearsipan');
    }
};
