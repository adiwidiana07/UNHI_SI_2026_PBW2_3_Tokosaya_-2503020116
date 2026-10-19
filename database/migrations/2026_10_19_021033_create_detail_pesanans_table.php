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
    Schema::create('detail_pesanans', function (Blueprint $table) {
        $table->id();

        // Kalau pesanan dihapus, rinciannya ikut terhapus
        $table->foreignId('pesanan_id')
              ->constrained('pesanans')
              ->cascadeOnDelete();

        // Kalau produk dihapus, kolom ini jadi kosong
        // tetapi riwayatnya tetap utuh berkat salinan di bawah
        $table->foreignId('produk_id')
              ->nullable()
              ->constrained('produks')
              ->nullOnDelete();

        // ---- Salinan data saat pesanan dibuat ----
        $table->string('nama_produk', 200);
        $table->decimal('harga', 12, 2);
        $table->integer('jumlah');
        $table->decimal('subtotal', 14, 2);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
