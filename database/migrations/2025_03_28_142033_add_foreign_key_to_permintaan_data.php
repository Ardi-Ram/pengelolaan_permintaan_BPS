<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up()
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            // Pastikan kolom pemilik_data_id ada sebelum menambahkan foreign key
            if (!Schema::hasColumn('permintaan_data', 'pemilik_data_id')) {
                $table->unsignedBigInteger('pemilik_data_id')->nullable()->after('id');
            }

            // Tambahkan foreign key
            $table->foreign('pemilik_data_id')->references('id')->on('pemilik_data')->onDelete('cascade');
        });
    }

    /**
     * Rollback migration.
     */
    public function down()
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->dropForeign(['pemilik_data_id']);
            $table->dropColumn('pemilik_data_id');
        });
    }
};
