<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        $daftarProduk = Produk::with('kategori')
                              ->where('status', 'aktif')
                              ->latest()
                              ->paginate(12);

        $daftarKategori = Kategori::orderBy('nama_kategori')->get();

        return view('user_front.produk.index', compact('daftarProduk', 'daftarKategori'));
    }

    public function show(Produk $produk)
    {
        // Produk yang dinonaktifkan tidak boleh dibuka pembeli
        abort_if($produk->status !== 'aktif', 404);

        $produk->load('kategori');

        return view('user_front.produk.show', compact('produk'));
    }
}