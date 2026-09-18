<?php

namespace App\Http\Controllers;

use App\Data\ProdukDummy;

class HalamanController extends Controller
{
    public function home()
    {
        $produkPopuler = ProdukDummy::populer();
        $produkTerbaru = ProdukDummy::terbaru();

        return view('home', compact('produkPopuler', 'produkTerbaru'));
    }

    public function kontak()
    {
        return view('kontak');
    }
}