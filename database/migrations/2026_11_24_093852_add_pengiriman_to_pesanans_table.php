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
               Schema::table('pesanans', function (Blueprint $table) {
        $table->string('provinsi', 100)->nullable()->after('alamat');
        $table->string('kecamatan', 100)->nullable()->after('kota');
        $table->unsignedInteger('kecamatan_id')->nullable()->after('kecamatan');

        $table->string('kurir', 20)->nullable()->after('kode_pos');
        $table->string('layanan_kurir', 50)->nullable()->after('kurir');
        $table->string('estimasi_kirim', 50)->nullable()->after('layanan_kurir');
        $table->unsignedInteger('berat_total')->default(0)->after('estimasi_kirim');
    });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            
    Schema::table('pesanans', function (Blueprint $table) {
        $table->dropColumn([
            'provinsi', 'kecamatan', 'kecamatan_id',
            'kurir', 'layanan_kurir', 'estimasi_kirim', 'berat_total',
        ]);
    });
        });
    }
};
