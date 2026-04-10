<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeluarsTable extends Migration
{
    public function up()
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klasifikasi_id');
            $table->string('pengolah')->nullable();
            $table->string('jenis_naskah')->nullable();
            $table->string('sifat_naskah')->nullable();
            $table->string('hal')->nullable();
            $table->date('tanggal_ditetapkan');
            $table->date('tanggal_berlaku')->nullable();
            $table->integer('nomor_urut')->default(0);
            $table->string('nomor_surat')->nullable(); // kode/urut/tahun
            $table->timestamps();

            $table->foreign('klasifikasi_id')->references('id')->on('klasifikasis')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_keluars');
    }
};
