<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabelStatistikTable extends Migration
{
    public function up(): void
    {
        Schema::create('tabel_statistik', function (Blueprint $table) {
            $table->id();

            $table->string('judul');
            $table->text('deskripsi');

            // Relasi ke kategori
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('category_data')->onDelete('cascade');

            // Relasi ke subject
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            // Petugas PST (yang membuat permintaan dan publish)
            $table->unsignedBigInteger('petugas_pst_id');
            $table->foreign('petugas_pst_id')->references('id')->on('users')->onDelete('cascade');

            // Pengolah (yang mengolah dan mengisi link hasil)
            $table->unsignedBigInteger('pengolah_id')->nullable();
            $table->foreign('pengolah_id')->references('id')->on('users')->onDelete('set null');

            $table->text('link_hasil')->nullable();
            $table->text('link_publish')->nullable();

            $table->enum('status', ['antrian', 'proses', 'menunggu publish', 'published'])->default('antrian');
            $table->date('deadline')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_statistik');
    }
}
