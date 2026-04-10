<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            // Rename klasifikasi_id to kode_kearsipan_id
            $table->unsignedBigInteger('kode_kearsipan_id')->nullable()->after('id');
            // Optional: drop old foreign key and column
            $table->dropForeign(['klasifikasi_id']);
            $table->dropColumn('klasifikasi_id');
            $table->foreign('kode_kearsipan_id')->references('id')->on('kode_kearsipan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropForeign(['kode_kearsipan_id']);
            $table->dropColumn('kode_kearsipan_id');
            $table->unsignedBigInteger('klasifikasi_id')->nullable()->after('id');
            $table->foreign('klasifikasi_id')->references('id')->on('klasifikasis')->onDelete('cascade');
        });
    }
};
