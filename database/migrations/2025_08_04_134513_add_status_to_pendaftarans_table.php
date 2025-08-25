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
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_verifikasi', // Baru dibuat dari janji temu atau oleh admin
                'terverifikasi',
                'diperiksa',   // Menunggu admin untuk konfirmasi kehadiran
                'selesai',              // Sudah diverifikasi, masuk antrean
                'batal',     // Sudah dipanggil dan sedang diperiksa
            ])->default('menunggu_verifikasi')->after('id'); // Sesuaikan posisi kolom jika perlu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        });
    }
};
