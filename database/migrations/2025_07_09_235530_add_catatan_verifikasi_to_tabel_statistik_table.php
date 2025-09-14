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
            $table->text('catatan_verifikasi')->nullable()->after('verifikasi_pst');
        });
    }

    public function down(): void
    {
        Schema::table('tabel_statistik', function (Blueprint $table) {
            $table->dropColumn('catatan_verifikasi');
        });
    }
};
