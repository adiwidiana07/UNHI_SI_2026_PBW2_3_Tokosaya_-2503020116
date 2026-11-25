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
        Schema::create('provinsis', function (Blueprint $table) {
            // id memakai nomor dari RajaOngkir, bukan nomor urut buatan sendiri
            $table->unsignedInteger('id')->primary();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('kotas', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('provinsi_id');
            $table->string('nama', 100);
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('provinsis')->cascadeOnDelete();
            $table->index('provinsi_id');
        });

        Schema::create('kecamatans', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('kota_id');
            $table->string('nama', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->timestamps();

            $table->foreign('kota_id')->references('id')->on('kotas')->cascadeOnDelete();
            $table->index('kota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('kotas');
        Schema::dropIfExists('provinsis');
    }
};
