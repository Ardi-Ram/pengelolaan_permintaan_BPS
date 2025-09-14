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
        Schema::create('pemilik_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('instansi');
            $table->string('email'); // Tambahkan email dengan unique constraint
            $table->string('no_wa'); // Tambahkan nomor WhatsApp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilik_data');
    }
};
