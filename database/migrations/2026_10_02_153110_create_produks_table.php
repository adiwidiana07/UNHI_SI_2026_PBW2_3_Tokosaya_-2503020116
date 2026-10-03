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
    Schema::create('produks', function (Blueprint $table) {
        $table->id();

        // Penghubung ke tabel kategoris
        $table->foreignId('kategori_id')
              ->constrained('kategoris')
              ->onDelete('restrict');

        $table->string('nama_produk', 200);
        $table->string('slug', 220)->unique();
        $table->string('kode_produk', 50)->nullable();
        $table->text('deskripsi')->nullable();

        $table->decimal('harga', 12, 2)->default(0);
        $table->decimal('harga_coret', 12, 2)->nullable();

        $table->integer('stok')->default(0);
        $table->integer('berat')->default(0)->comment('gram, untuk cek ongkir Minggu 10');

        $table->string('gambar')->nullable();
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
