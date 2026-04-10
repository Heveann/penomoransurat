<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToSuratKeluarsTable extends Migration
{
	public function up()
	{
		Schema::table('surat_keluars', function (Blueprint $table) {
			if (!Schema::hasColumn('surat_keluars', 'status')) {
				$table->string('status')->default('selesai');
			}
		});
	}

	public function down()
	{
		Schema::table('surat_keluars', function (Blueprint $table) {
			$table->dropColumn('status');
		});
	}
}

