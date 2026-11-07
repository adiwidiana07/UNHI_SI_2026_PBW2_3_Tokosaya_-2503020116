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
    Schema::table('pesanans', function (Blueprint $table) {

        // Status uang — terpisah dari kolom status yang mengurus pengiriman
        $table->enum('status_bayar', [
            'belum_bayar', 'pending', 'lunas', 'gagal', 'kedaluwarsa',
        ])->default('belum_bayar')->after('status');

        // order_id yang kita kirim ke Midtrans (paling panjang 50 karakter).
        // Biasanya sama dengan kode_pesanan, tetapi bisa berbeda saat pembeli
        // mencoba membayar ulang — lihat penjelasan di H.4
        $table->string('midtrans_order_id', 50)->nullable()->unique()->after('status_bayar');

        // Alamat halaman bayar terakhir dan batas waktunya.
        // Dipakai lagi selama masih berlaku — lihat penjelasan di H.4
        $table->string('midtrans_redirect_url')->nullable()->after('midtrans_order_id');
        $table->timestamp('batas_bayar')->nullable()->after('midtrans_redirect_url');

        $table->string('metode_bayar', 40)->nullable()->after('batas_bayar');
        $table->timestamp('dibayar_pada')->nullable()->after('metode_bayar');

        // Penanda agar stok tidak dikembalikan dua kali
        $table->boolean('stok_dikembalikan')->default(false)->after('dibayar_pada');

        $table->index('status_bayar');
    });
}

public function down(): void
{
    Schema::table('pesanans', function (Blueprint $table) {
        $table->dropIndex(['status_bayar']);
        $table->dropColumn([
            'status_bayar', 'midtrans_order_id', 'midtrans_redirect_url', 'batas_bayar',
            'metode_bayar', 'dibayar_pada', 'stok_dikembalikan',
        ]);
    });
}

};
