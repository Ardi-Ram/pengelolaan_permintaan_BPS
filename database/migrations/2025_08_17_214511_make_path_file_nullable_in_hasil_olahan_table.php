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
        Schema::table('hasil_olahan', function (Blueprint $table) {
            $table->string('path_file')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('hasil_olahan', function (Blueprint $table) {
            $table->string('path_file')->nullable(false)->change();
        });
    }
};
