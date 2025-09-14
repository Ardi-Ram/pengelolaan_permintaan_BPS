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
        Schema::create('micro_data_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('micro_data_id')->constrained('micro_data')->onDelete('cascade');
            $table->string('judul'); // nama dataset, seperti "Long Form SP2020"
            $table->string('level_penyajian')->nullable(); // misal: Kabupaten, Desa
            $table->decimal('harga', 15, 2)->nullable(); // opsional, bisa 0 juga
            $table->string('ukuran_file')->nullable(); // misal: "2.99 GB"
            $table->string('link')->nullable(); // link ke eksternal BPS
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('micro_data_items');
    }
};
