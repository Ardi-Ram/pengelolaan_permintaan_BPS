<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tabel_statistik', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('pengolah_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_statistik', function (Blueprint $table) {
            //
        });
    }
};
