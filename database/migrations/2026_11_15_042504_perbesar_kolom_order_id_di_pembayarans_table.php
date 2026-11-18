<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * order_id di notifikasi Midtrans tidak selalu milik kita. Notifikasi uji
     * dari dashboard, misalnya, memakai order_id seperti
     * "payment_notif_test_M660784217_67f6ad4e-0ffe-4938-86db-180aa3f72b35"
     * (68 karakter) sehingga VARCHAR(60) memicu SQLSTATE 22001.
     */
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('order_id', 255)->change();
        });
    }

    public function down(): void
    {
        // Mengecilkan kolom akan memotong (atau menolak) order_id panjang
        // yang sudah tercatat. Lebih aman berhenti daripada merusak data.
        $terpanjang = (int) DB::table('pembayarans')
            ->selectRaw('MAX(LENGTH(order_id)) AS panjang')
            ->value('panjang');

        if ($terpanjang > 60) {
            throw new RuntimeException(
                "Tidak dapat mengembalikan order_id ke 60 karakter: ada data sepanjang {$terpanjang} karakter."
            );
        }

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('order_id', 60)->change();
        });
    }
};
