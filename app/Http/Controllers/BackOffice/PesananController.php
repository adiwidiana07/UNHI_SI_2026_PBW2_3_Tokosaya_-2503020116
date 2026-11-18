<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $pesanan->load('detail.produk', 'pembayaran');

        return view('back_office.pesanan.show', compact('pesanan'));
    }

    public function ubahStatus(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'status' => ['required', 'in:baru,diproses,dikirim,selesai,batal'],
        ]);

        // Aturan 1: pesanan yang sudah batal tidak dihidupkan lagi —
        // stoknya sudah dilepas dan bisa sudah dibeli orang lain
        if ($pesanan->status === 'batal' && $data['status'] !== 'batal') {
            return back()->with('gagal', 'Pesanan yang sudah batal tidak bisa diaktifkan kembali.');
        }

        // Aturan 2: barang tidak dikirim sebelum uangnya masuk
        if (in_array($data['status'], ['dikirim', 'selesai'], true)
            && ! $pesanan->sudahLunas()) {
            return back()->with('gagal',
                'Pesanan ini belum lunas, jadi belum bisa ditandai ' . $data['status'] . '.');
        }

        // Aturan 3: pembeli yang sedang memegang nomor VA masih bisa membayar.
        // Tunggu sampai Midtrans mengabarkan kedaluwarsa
        if ($data['status'] === 'batal' && $pesanan->status_bayar === 'pending') {
            return back()->with('gagal',
                'Pembayaran pesanan ini masih berjalan. Tunggu sampai lunas atau kedaluwarsa.');
        }

        DB::transaction(function () use ($pesanan, $data) {
            // Kunci baris agar tidak bertabrakan dengan webhook yang datang bersamaan
            $pesanan = Pesanan::lockForUpdate()->findOrFail($pesanan->id);
            $pesanan->status = $data['status'];

            // Pembatalan oleh admin juga melepas stok — lewat method yang sama dengan webhook
            if ($data['status'] === 'batal') {
                $pesanan->kembalikanStok();
            }

            $pesanan->save();
        });

        return back()->with('sukses', 'Status pesanan berhasil diperbarui.');
    }
}
