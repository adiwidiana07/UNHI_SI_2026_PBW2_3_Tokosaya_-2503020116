<?php

namespace App\Http\Controllers;

use App\Services\Keranjang;

class CheckoutController extends Controller
{
    public function index()
    {
        $daftarItem = Keranjang::daftarItem();

        // Tidak boleh checkout kalau keranjang kosong
        if ($daftarItem->isEmpty()) {
            return redirect()
                ->route('keranjang.index')
                ->with('gagal', 'Keranjang Anda masih kosong.');
        }

        $totalHarga = Keranjang::totalHarga();

        return view('user_front.checkout.index', compact('daftarItem', 'totalHarga'));
    }
}