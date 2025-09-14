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
        Schema::create('kunjungan_customer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permintaan_data_id');
            $table->unsignedBigInteger('pemilik_id');
            $table->string('nama_customer');
            $table->string('email');
            $table->timestamps();

            // Foreign key
            $table->foreign('permintaan_data_id')->references('id')->on('permintaan_data')->onDelete('cascade');
            $table->foreign('pemilik_id')->references('id')->on('pemilik_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_customer');
    }
};
