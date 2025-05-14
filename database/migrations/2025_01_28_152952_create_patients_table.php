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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rekam_medik')->unique();  // Menambahkan nomor rekam medik
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['pria', 'wanita']);
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('nomor_telepon');
            $table->string('nomor_ktp')->unique();
            $table->text('riwayat_penyakit')->nullable();  // Menambahkan riwayat penyakit
            $table->text('alergi')->nullable();  // Menambahkan alergi
            $table->string('nama_kontak_darurat')->nullable();  // Menambahkan nama kontak darurat
            $table->string('hubungan_darurat')->nullable();  // Menambahkan hubungan dengan kontak darurat
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');  // Menambahkan status pasien
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
