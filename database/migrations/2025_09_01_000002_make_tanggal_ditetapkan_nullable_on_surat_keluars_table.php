<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->date('tanggal_ditetapkan')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->date('tanggal_ditetapkan')->nullable(false)->change();
        });
    }
};
