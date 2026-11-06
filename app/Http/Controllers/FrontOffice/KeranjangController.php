<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    /** Menampilkan halaman keranjang */
    public function index()
    {
        $daftarItem = Keranjang::daftarItem();
        $totalHarga = Keranjang::totalHarga();
        $totalItem  = Keranjang::totalItem();

        return view('user_front.keranjang.index', compact('daftarItem', 'totalHarga', 'totalItem'));
    }

    /** Menambahkan produk ke keranjang */
    public function tambah(Request $request, Produk $produk)
    {
        // Produk nonaktif tidak boleh dibeli
        abort_if($produk->status !== 'aktif', 404);

        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        // Hitung jumlah yang sudah ada di keranjang
        $sudahAda = Keranjang::isi()[$produk->id] ?? 0;
        $total    = $sudahAda + $data['jumlah'];

        if ($total > $produk->stok) {
            return back()->with('gagal',
                'Stok tidak mencukupi. Tersedia ' . $produk->stok . ' buah, '
                . 'sedangkan di keranjang Anda sudah ada ' . $sudahAda . ' buah.');
        }

        Keranjang::tambah($produk->id, $data['jumlah']);

        return redirect()
            ->route('keranjang.index')
            ->with('sukses', $produk->nama_produk . ' berhasil ditambahkan ke keranjang.');
    }

    /** Mengubah jumlah sebuah item */
    public function ubah(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:0'],
        ]);

        if ($data['jumlah'] > $produk->stok) {
            return back()->with('gagal',
                'Jumlah melebihi stok. Tersedia ' . $produk->stok . ' buah.');
        }

        Keranjang::ubah($produk->id, $data['jumlah']);

        return back()->with('sukses', 'Jumlah pesanan berhasil diperbarui.');
    }

    /** Menghapus satu item */
    public function hapus(Produk $produk)
    {
        Keranjang::hapus($produk->id);

        return back()->with('sukses', $produk->nama_produk . ' dihapus dari keranjang.');
    }

    /** Mengosongkan seluruh keranjang */
    public function kosongkan()
    {
        Keranjang::kosongkan();

        return back()->with('sukses', 'Keranjang berhasil dikosongkan.');
    }
}