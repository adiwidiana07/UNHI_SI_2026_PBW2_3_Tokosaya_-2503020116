<?php

namespace App\Http\Controllers;

use App\Data\ProdukDummy;

class ProdukController extends Controller
{
    public function index()
    {
        $daftarProduk = ProdukDummy::semua();

        return view('produk.index', compact('daftarProduk'));
    }

    public function show(int $id)
    {
        $produk = ProdukDummy::cari($id);

        abort_if($produk === null, 404);

        return view('produk.show', compact('produk'));
    }
}