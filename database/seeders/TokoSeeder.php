<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TokoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 3 kategori sesuai isi gambar yang ada (Figure, Manga, Merchandise & Gaming)
        $kategoris = [
            [
                'nama_kategori' => 'Figure',
                'deskripsi' => 'Koleksi action figure dan scale figure anime original untuk dipajang maupun dikoleksi.',
            ],
            [
                'nama_kategori' => 'Manga',
                'deskripsi' => 'Komik manga dan manhwa resmi, cocok untuk dibaca maupun dikoleksi full set.',
            ],
            [
                'nama_kategori' => 'Merchandise & Gaming',
                'deskripsi' => 'Merchandise dan aksesori gaming edisi terbatas dari judul anime populer.',
            ],
        ];

        $kategoriId = [];
        foreach ($kategoris as $k) {
            $kategori = Kategori::updateOrCreate(
                ['nama_kategori' => $k['nama_kategori']],
                ['slug' => Str::slug($k['nama_kategori']), 'deskripsi' => $k['deskripsi']]
            );
            $kategoriId[$k['nama_kategori']] = $kategori->id;
        }

        // 2. Gambar disesuaikan isinya dengan nama produk.
        //    Sumber = public/assets/img (ikut push), disalin ke
        //    storage/app/public/produk agar dibuka via /storage/produk/xxx
        File::ensureDirectoryExists(storage_path('app/public/produk'));

        $produks = [
            [
                'nama_produk' => 'RX-78-2 Gundam Ver. Ka Figure',
                'kategori' => 'Figure',
                'kode_produk' => 'FIG-GDM-001',
                'deskripsi' => 'Figure Gundam RX-78-2 Ver. Ka dengan box art Kanagawa Wave yang ikonik dan detail armor tajam. Cocok untuk kolektor Gunpla maupun pajangan meja, box-nya aman untuk koleksi MIB.',
                'harga' => 850000,
                'harga_coret' => 999000,
                'stok' => 12,
                'berat' => 800,
                'status' => 'nonaktif', // pembuktian saringan status
                'sumber' => '13c60544-bd70-4c85-903a-1d9169f0dd70.png',
                'gambar' => 'produk/figure-gundam-rx-78-2-ver-ka.png',
            ],
            [
                'nama_produk' => 'Alya Figure 1/7 Scale Bunny',
                'kategori' => 'Figure',
                'kode_produk' => 'FIG-ALY-002',
                'deskripsi' => 'Scale figure Alya 1/7 kostum bunny dari Alya Sometimes Hides Feelings in Russian. Detail rambut perak dan base catur merahnya mewah, cocok jadi pusat pajangan rak Anda.',
                'harga' => 1650000,
                'harga_coret' => 1899000,
                'stok' => 5,
                'berat' => 900,
                'status' => 'aktif',
                'sumber' => 'cat2.png',
                'gambar' => 'produk/figure-alya-bunny-17-scale.png',
            ],
            [
                'nama_produk' => 'Naruto Uzumaki Rasengan Figure',
                'kategori' => 'Figure',
                'kode_produk' => 'FIG-NRT-003',
                'deskripsi' => 'Figure Naruto dengan efek Rasengan biru transparan dan pose menyerang yang dinamis. Base puing Konoha dan nameplate-nya kokoh, wajib untuk penggemar Naruto Shippuden.',
                'harga' => 1250000,
                'harga_coret' => 1499000,
                'stok' => 8,
                'berat' => 900,
                'status' => 'aktif',
                'sumber' => 'fc3b402d-ee4d-4cff-b14a-b6daefb8beb0.png',
                'gambar' => 'produk/figure-naruto-rasengan.png',
            ],
            [
                'nama_produk' => 'Ichigo Kurosaki Substitute Soul Reaper Figure',
                'kategori' => 'Figure',
                'kode_produk' => 'FIG-ICH-004',
                'deskripsi' => 'Figure Ichigo Kurosaki dengan jubah shihakusho berkibar dan efek Getsuga yang dramatis. Detail pedang Zangetsu tajam dan base reruntuhan Soul Society sangat sinematik.',
                'harga' => 1450000,
                'harga_coret' => null,
                'stok' => 6,
                'berat' => 950,
                'status' => 'aktif',
                'sumber' => 'ChatGPT Image 17 Sep 2026, 00.39.26.png',
                'gambar' => 'produk/figure-ichigo-kurosaki.png',
            ],
            [
                'nama_produk' => 'Manga Dragon Ball Full Color Set',
                'kategori' => 'Manga',
                'kode_produk' => 'MGS-DBZ-001',
                'deskripsi' => 'Set manga Dragon Ball full color dengan art Akira Toriyama yang tajam dan kertas premium. Cerita Goku dari kecil sampai dewasa nagih dibaca ulang dan bagus untuk koleksi rak.',
                'harga' => 990000,
                'harga_coret' => 1150000,
                'stok' => 10,
                'berat' => 2000,
                'status' => 'aktif',
                'sumber' => '9799ca51-74fd-4eca-8abc-011ed535c23b.png',
                'gambar' => 'produk/manga-dragon-ball-full-color-set.png',
            ],
            [
                'nama_produk' => 'Manga Vagabond VizBig Edition',
                'kategori' => 'Manga',
                'kode_produk' => 'MGS-VGB-002',
                'deskripsi' => 'Edisi VizBig Vagabond 3-in-1 dengan art Takehiko Inoue yang legendaris dan kertas tebal. Kisah Musashi yang filosofis cocok untuk kolektor manga seinen dewasa.',
                'harga' => 450000,
                'harga_coret' => null,
                'stok' => 7,
                'berat' => 1200,
                'status' => 'aktif',
                'sumber' => 'ddaca159-2f72-44df-9203-2ac6ef4d6132.png',
                'gambar' => 'produk/manga-vagabond-vizbig.png',
            ],
            [
                'nama_produk' => 'Manga Berserk Vol. 1-10 Set',
                'kategori' => 'Manga',
                'kode_produk' => 'MGS-BSK-003',
                'deskripsi' => 'Set 10 volume awal Berserk karya Kentaro Miura dengan cover dan spine yang serasi di rak. Cerita Guts yang kelam dan epik wajib dimiliki penggemar dark fantasy.',
                'harga' => 1200000,
                'harga_coret' => 1350000,
                'stok' => 4,
                'berat' => 2500,
                'status' => 'aktif',
                'sumber' => 'ChatGPT Image 17 Sep 2026, 00.58.25.png',
                'gambar' => 'produk/manga-berserk-vol-1-10-set.png',
            ],
            [
                'nama_produk' => 'Chainsaw Man Limited Edition Game Controller',
                'kategori' => 'Merchandise & Gaming',
                'kode_produk' => 'MRC-CSM-001',
                'deskripsi' => 'Controller edisi terbatas Chainsaw Man dengan art Denji dan gantungan Pochita. Grip bertekstur nyaman untuk sesi main lama, lengkap dengan dock dan box kolektor.',
                'harga' => 899000,
                'harga_coret' => 1099000,
                'stok' => 15,
                'berat' => 500,
                'status' => 'aktif',
                'sumber' => 'fb5ef4c1-8785-4509-a1b7-7eb1ba6445ee.png',
                'gambar' => 'produk/merchandise-chainsaw-man-controller.png',
            ],
            [
                'nama_produk' => 'Death Note PS5 Limited Edition Bundle',
                'kategori' => 'Merchandise & Gaming',
                'kode_produk' => 'MRC-DTN-002',
                'deskripsi' => 'Bundle PS5 edisi Death Note dengan art Light dan Ryuk plus kaset game terbaru. Unit resmi dengan garansi, cocok untuk gamer sekaligus kolektor memorabilia anime.',
                'harga' => 7899000,
                'harga_coret' => 8499000,
                'stok' => 3,
                'berat' => 4500,
                'status' => 'aktif',
                'sumber' => 'cat3.png',
                'gambar' => 'produk/merchandise-death-note-ps5-bundle.png',
            ],
        ];

        // Salin file yang namanya sudah disesuaikan dengan produk
        foreach ($produks as $p) {
            $src = public_path('assets/img/' . $p['sumber']);
            $dst = storage_path('app/public/' . $p['gambar']);
            if (is_file($src) && ! is_file($dst)) {
                File::copy($src, $dst);
            }
        }

        // Simpan / perbarui produk (8 aktif + 1 nonaktif).
        // Gambar upload manual dipertahankan: bila produk sudah ada dan file
        // gambarnya masih ada di storage, jangan timpa dengan gambar seeder.
        foreach ($produks as $p) {
            $existing = Produk::where('nama_produk', $p['nama_produk'])->first();
            $gambar = $p['gambar'];
            if ($existing && $existing->gambar && $existing->gambar !== $p['gambar']
                && is_file(storage_path('app/public/' . $existing->gambar))) {
                $gambar = $existing->gambar;
            }
            $values = [
                'kategori_id' => $kategoriId[$p['kategori']],
                'kode_produk' => $p['kode_produk'],
                'deskripsi' => $p['deskripsi'],
                'harga' => $p['harga'],
                'harga_coret' => $p['harga_coret'],
                'stok' => $p['stok'],
                'berat' => $p['berat'],
                'gambar' => $gambar,
                'status' => $p['status'],
            ];
            if ($existing) {
                $existing->update($values);
            } else {
                $values['nama_produk'] = $p['nama_produk'];
                $values['slug'] = Str::slug($p['nama_produk']);
                Produk::create($values);
            }
        }

        $kategoriBaru = collect($kategoris)->pluck('nama_kategori')->all();
        Kategori::whereNotIn('nama_kategori', $kategoriBaru)
            ->whereDoesntHave('produks')
            ->delete();
    }
}
