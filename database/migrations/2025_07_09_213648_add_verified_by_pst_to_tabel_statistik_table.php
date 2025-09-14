<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tabel_statistik', function (Blueprint $table) {
            $table->boolean('verifikasi_pst')->nullable(); // true = valid, false = tidak valid
            $table->timestamp('verified_at')->nullable();
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
