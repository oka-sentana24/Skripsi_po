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
        Schema::create('tindakan_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tindakan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('jenis_layanans')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_layanan');
    }
};
