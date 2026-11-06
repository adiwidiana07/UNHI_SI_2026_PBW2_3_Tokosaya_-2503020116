<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BahasaController extends Controller
{
    /**
     * Daftar bahasa yang didukung.
     * Hanya kode di sini yang boleh diterima.
     */
    public const BAHASA_TERSEDIA = ['id', 'en'];

    public function ganti(Request $request, string $kode)
    {
        // Tolak kode bahasa yang tidak ada dalam daftar
        if (! in_array($kode, self::BAHASA_TERSEDIA, true)) {
            abort(404);
        }

        $request->session()->put('bahasa', $kode);

        // Kembalikan pengunjung ke halaman sebelumnya
        return redirect()->back();
    }
}
