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
        Schema::table('janji_temus', function (Blueprint $table) {
            $table->enum('status', ['dijadwalkan', 'diproses', 'hadir', 'selesai', 'tidak_hadir', 'dibatalkan'])->default('dijadwalkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('janji_temus', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
