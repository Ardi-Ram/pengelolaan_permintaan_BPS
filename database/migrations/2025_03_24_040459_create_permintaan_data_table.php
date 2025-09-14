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
        Schema::create('permintaan_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petugas_pst_id');
            $table->unsignedBigInteger('pengolah_id')->nullable();
            $table->string('judul_permintaan');
            $table->text('deskripsi');
            $table->string('kode_transaksi')->nullable();
            $table->enum('status', ['antrian', 'proses', 'selesai'])->default('antrian');
            $table->string('upload_path')->nullable();
            $table->timestamps();

            // Foreign keys

            $table->foreign('petugas_pst_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pengolah_id')->references('id')->on('users')->onDelete('set null');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_data');
    }
   

};
