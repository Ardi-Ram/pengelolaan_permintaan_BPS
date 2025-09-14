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
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('kategori_id');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            //
        });
    }
};
