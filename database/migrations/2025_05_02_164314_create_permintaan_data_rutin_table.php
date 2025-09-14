<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('permintaan_data_rutin', function (Blueprint $table) {
            $table->id();
            $table->string('kode_permintaan')->unique(); // <-- ini
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategori_id')->constrained('category_data')->onDelete('cascade');
            $table->foreignId('pengolah_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_dibuat');
            $table->enum('status', ['antrian', 'proses', 'selesai'])->default('antrian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_data_rutin');
    }
};
