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
        Schema::create('janji_temu_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('janji_temu_id')->constrained()->onDelete('cascade');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('janji_temu_layanan');
    }
};
