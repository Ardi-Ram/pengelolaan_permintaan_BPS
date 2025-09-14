<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('permintaan_data', function (Blueprint $table) {
            // Tambahkan kolom category_id dulu
            $table->unsignedBigInteger('category_id')->nullable()->after('kode_transaksi');

            // Baru tambahkan foreign key
            $table->foreign('category_id')->references('id')->on('category_data')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
