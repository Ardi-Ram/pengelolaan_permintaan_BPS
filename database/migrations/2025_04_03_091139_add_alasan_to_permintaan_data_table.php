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
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->text('alasan')->nullable(); // Menambahkan kolom alasan
        });
    }
    
    public function down()
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->dropColumn('alasan'); // Menghapus kolom alasan jika migrasi dibatalkan
        });
    }
    
};
