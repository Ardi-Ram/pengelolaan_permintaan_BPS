<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAlasanNullableInPermintaanDataTable extends Migration
{
    public function up()
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->string('alasan')->nullable()->change(); // cukup ini
        });
    }

    public function down()
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->string('alasan')->nullable()->change(); // cukup ini
        });
    }
}
