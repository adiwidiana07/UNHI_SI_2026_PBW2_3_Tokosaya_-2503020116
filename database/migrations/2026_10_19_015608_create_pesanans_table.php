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
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_pesanan', 30)->unique();

        // Data pemesan dan pengiriman
        $table->string('nama_pemesan', 100);
        $table->string('email', 100);
        $table->string('telepon', 20);
        $table->text('alamat');
        $table->string('kota', 100);
        $table->string('kode_pos', 10)->nullable();
        $table->text('catatan')->nullable();

        // Perhitungan uang
        $table->decimal('subtotal', 14, 2)->default(0);
        $table->decimal('ongkir', 14, 2)->default(0);
        $table->decimal('total', 14, 2)->default(0);

        $table->enum('status', ['baru', 'diproses', 'dikirim', 'selesai', 'batal'])
              ->default('baru');

        $table->timestamps();

        $table->index('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};