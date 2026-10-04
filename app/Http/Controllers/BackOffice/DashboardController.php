<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $ringkasan = [
            'produk'       => Produk::count(),
            'kategori'     => Kategori::count(),
            'produk_aktif' => Produk::where('status', 'aktif')->count(),
            'admin_aktif'  => User::where('role', 'admin')->count(),
        ];

        return view('back_office.dashboard', compact('ringkasan'));
    }
}
