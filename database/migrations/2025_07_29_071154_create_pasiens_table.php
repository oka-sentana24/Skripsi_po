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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm', 20)->unique(); // no rekam medis varchar(20) unik
            $table->string('nama', 255);      // varchar(255) nama lengkap pasien
            $table->text('alamat');            // alamat (text)
            $table->date('tanggal_lahir');    // tanggal lahir pasien
            $table->string('no_hp', 20);      // no hp varchar(20)
            $table->string('email', 255);     // email varchar(255)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
