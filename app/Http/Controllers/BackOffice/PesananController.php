<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $daftarPesanan = Pesanan::withCount('detail')
                                ->withSum('detail', 'jumlah')
                                ->latest()
                                ->paginate(15);

        return view('back_office.pesanan.index', compact('daftarPesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load('detail.produk');

        return view('back_office.pesanan.show', compact('pesanan'));
    }

    public function ubahStatus(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'status' => ['required', 'in:baru,diproses,dikirim,selesai,batal'],
        ]);

        $pesanan->update($data);

        return back()->with('sukses', 'Status pesanan berhasil diperbarui.');
    }
}