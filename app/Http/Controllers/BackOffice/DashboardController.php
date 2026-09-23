<?php

namespace App\Http\Controllers\BackOffice;

use App\Data\ProdukDummy;
use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $ringkasan = [
            'produk' => count(ProdukDummy::semua()),
            'kategori' => 3,
            'pesanan_baru' => 0,
            'admin_aktif' => User::where('role', 'admin')->count(),
        ];

        return view('back_office.dashboard', compact('ringkasan'));
    }
}
