<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiagaTabelDataTable extends Migration
{
    public function up(): void
    {
        Schema::create('siaga_tabel_data', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_publikasi');
            $table->string('judul_publikasi');
            $table->string('nomor_tabel');
            $table->string('judul_tabel');
            $table->string('nomor_halaman');

            $table->unsignedBigInteger('petugas_pst_id')->nullable();
            $table->foreign('petugas_pst_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('pengolah_id');
            $table->foreign('pengolah_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('link_output')->nullable();

            $table->enum('status', ['belum ditugaskan', 'ditugaskan', 'rilis'])->default('belum ditugaskan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siaga_tabel_data');
    }
}
