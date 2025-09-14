<?php

// database/migrations/2025_07_26_000002_create_links_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_group_id')->constrained()->onDelete('cascade');
            $table->string('label');          // Nama tautan
            $table->string('url');            // URL tujuan
            $table->integer('order')->default(0); // urutan tampil dalam group
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
