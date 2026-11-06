<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class HalamanController extends Controller
{
    public function home()
    {
        $produkPopuler = Produk::with('kategori')
                               ->where('status', 'aktif')
                               ->latest()
                               ->take(4)
                               ->get();

        return view('user_front.home', compact('produkPopuler'));
    }

    public function kontak()
    {
        return view('user_front.kontak');
    }
}