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
        Schema::table('hasil_olahan', function (Blueprint $table) {
            $table->enum('verifikasi_hasil', ['valid', 'tidak_valid'])->nullable()->after('path_file');
            $table->text('catatan_verifikasi')->nullable()->after('verifikasi_hasil');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_olahan', function (Blueprint $table) {
            $table->dropColumn(['verifikasi_hasil', 'catatan_verifikasi']);
        });
    }
};
