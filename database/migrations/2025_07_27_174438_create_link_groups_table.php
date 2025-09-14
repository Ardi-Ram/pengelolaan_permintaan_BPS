<?php

// database/migrations/2025_07_26_000001_create_link_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('link_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // misal: "Katalog", "Bantuan"
            $table->integer('order')->default(0); // urutan tampil
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_groups');
    }
};
