<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    /** Daftar produk */
    public function index()
    {
        $daftarProduk = Produk::with(['kategori' => fn ($q) => $q->withCount('produks')])
            ->latest()
            ->paginate(10);

        return view('back_office.produk.index', compact('daftarProduk'));
    }

    /** Formulir tambah */
    public function create()
    {
        $daftarKategori = Kategori::orderBy('nama_kategori')->get();

        return view('back_office.produk.create', compact('daftarKategori'));
    }

    /** Menyimpan produk baru */
    public function store(Request $request)
    {
        $data = $this->periksaIsian($request, null);

        // Simpan gambar bila ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $data['slug'] = Str::slug($data['nama_produk']) . '-' . Str::random(5);

        Produk::create($data);

        return redirect()
            ->route('back-office.produk.index')
            ->with('sukses', 'Produk berhasil ditambahkan.');
    }

    /** Formulir edit */
    public function edit(Produk $produk)
    {
        $daftarKategori = Kategori::orderBy('nama_kategori')->get();

        return view('back_office.produk.edit', compact('produk', 'daftarKategori'));
    }

    /** Menyimpan perubahan */
    public function update(Request $request, Produk $produk)
    {
        $data = $this->periksaIsian($request, $produk);

        if ($request->hasFile('gambar')) {

            // Hapus gambar lama supaya tidak menumpuk
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()
            ->route('back-office.produk.index')
            ->with('sukses', 'Produk berhasil diperbarui.');
    }

    /** Menghapus produk */
    public function destroy(Produk $produk)
    {
        // Hapus gambarnya juga, jangan tinggalkan sampah
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()
            ->route('back-office.produk.index')
            ->with('sukses', 'Produk berhasil dihapus.');
    }

    /**
     * Aturan validasi dipakai oleh store dan update,
     * jadi ditulis sekali saja di sini.
     */
    private function periksaIsian(Request $request, ?Produk $produk = null): array
    {
        // Kosongkan kode_produk jadi null agar tidak dianggap duplikat saat unique check
        if (! $request->filled('kode_produk')) {
            $request->merge(['kode_produk' => null]);
        }

        return $request->validate([
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'nama_produk'  => [
                'required',
                'string',
                'max:200',
                Rule::unique('produks', 'nama_produk')->ignore($produk?->id),
            ],
            'kode_produk'  => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('produks', 'kode_produk')->ignore($produk?->id),
            ],
            'deskripsi'    => ['nullable', 'string'],
            'harga'        => ['required', 'numeric', 'min:0'],
            'harga_coret'  => ['nullable', 'numeric', 'min:0', 'gt:harga'],
            'stok'         => ['required', 'integer', 'min:0'],
            'berat'        => ['required', 'integer', 'min:0'],
            'status'       => ['required', 'in:aktif,nonaktif'],
            'gambar'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.unique'   => 'Produk dengan nama itu sudah ada.',
            'nama_produk.max'      => 'Nama produk maksimal 200 karakter.',
            'kode_produk.unique'   => 'Kode produk ini sudah dipakai produk lain.',
            'kode_produk.max'      => 'Kode produk maksimal 50 karakter.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'harga_coret.gt'       => 'Harga coret harus lebih besar dari harga jual.',
            'gambar.image'         => 'Berkas yang diunggah harus berupa gambar.',
            'gambar.max'           => 'Ukuran gambar maksimal 2 MB.',
        ]);
    }
}