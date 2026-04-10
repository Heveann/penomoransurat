<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlasifikasisTable extends Migration
{
    public function up()
    {
        Schema::create('klasifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // contoh: 001.2.3
            $table->string('nama');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klasifikasis');
    }
};
