<?php

namespace App\Data;

class ProdukDummy
{
    /** @var array<int, array<string, mixed>> */
    private static array $produk = [
        [
            'id' => 1,
            'nama' => 'RX-78-2 Gundam E.F.S.F Figure',
            'kategori' => 'Figure, Gunpla',
            'harga' => 89.99,
            'harga_asli' => 109.99,
            'gambar' => 'assets/img/13c60544-bd70-4c85-903a-1d9169f0dd70.png',
            'is_populer' => true,
            'deskripsi' => 'Action figure Gundam RX-78-2 skala detail dengan beam rifle, shield E.F.S.F., dan display base eksklusif.',
        ],
        [
            'id' => 2,
            'nama' => 'Pokemon Pikachu & Lizardon Figure Set',
            'kategori' => 'Figure',
            'harga' => 59.99,
            'harga_asli' => null,
            'gambar' => 'assets/img/2ff5216c-4d4d-45e6-81ab-c4662b10bea4.png',
            'is_populer' => true,
            'deskripsi' => 'Set figure Pikachu, Lizardon (Charizard), dan Lucario plus kartu TCG Pikachu, Charizard, dan Mewtwo.',
        ],
        [
            'id' => 3,
            'nama' => 'Dragon Ball Jump Comics Set Vol 1-34',
            'kategori' => 'Manga',
            'harga' => 129.99,
            'harga_asli' => 149.99,
            'gambar' => 'assets/img/9799ca51-74fd-4eca-8abc-011ed535c23b.png',
            'is_populer' => true,
            'deskripsi' => 'Komik Dragon Ball karya Akira Toriyama lengkap volume 1-34 edisi Jump Comics berbahasa Jepang.',
        ],
        [
            'id' => 4,
            'nama' => 'Ichigo Kurosaki Soul Reaper Figure - Bleach',
            'kategori' => 'Figure',
            'harga' => 74.99,
            'harga_asli' => 89.99,
            'gambar' => 'assets/img/ChatGPT Image 17 Sep 2026, 00.39.26.png',
            'is_populer' => true,
            'deskripsi' => 'Statue Ichigo Kurosaki mode Substitute Soul Reaper dengan efek Getsuga dan diorama reruntuhan Soul Society.',
        ],
        [
            'id' => 5,
            'nama' => 'Uzumaki Naruto Rasengan Figure',
            'kategori' => 'Figure',
            'harga' => 69.99,
            'harga_asli' => 79.99,
            'gambar' => 'assets/img/fc3b402d-ee4d-4cff-b14a-b6daefb8beb0.png',
            'is_populer' => false,
            'deskripsi' => 'Figure Naruto Uzumaki pose Rasengan dengan efek chakra biru transparan dan base puing Konoha.',
        ],
        [
            'id' => 6,
            'nama' => 'Berserk Manga Set Vol 1-10 - Kentaro Miura',
            'kategori' => 'Manga',
            'harga' => 119.99,
            'harga_asli' => 139.99,
            'gambar' => 'assets/img/ChatGPT Image 17 Sep 2026, 00.58.25.png',
            'is_populer' => false,
            'deskripsi' => 'Manga Berserk karya Kentaro Miura volume 1-10 edisi Young Animal Comics berbahasa Jepang.',
        ],
        [
            'id' => 7,
            'nama' => 'Vagabond VizBig Set Vol 1-12 - Takehiko Inoue',
            'kategori' => 'Manga',
            'harga' => 139.99,
            'harga_asli' => null,
            'gambar' => 'assets/img/ddaca159-2f72-44df-9203-2ac6ef4d6132.png',
            'is_populer' => false,
            'deskripsi' => 'Manga Vagabond karya Takehiko Inoue volume 1-12 edisi VizBig tentang perjalanan Miyamoto Musashi.',
        ],
        [
            'id' => 8,
            'nama' => 'Chainsaw Man Limited Edition Game Controller',
            'kategori' => 'Accessories, Gaming',
            'harga' => 79.99,
            'harga_asli' => 99.99,
            'gambar' => 'assets/img/fb5ef4c1-8785-4509-a1b7-7eb1ba6445ee.png',
            'is_populer' => true,
            'deskripsi' => 'Game controller edisi terbatas Chainsaw Man motif Denji dengan dock stand, gantungan kunci Pochita, dan grip bertekstur.',
        ],
    ];

    /**
     * Ambil semua produk.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function semua(): array
    {
        return self::$produk;
    }

    /**
     * Ambil produk populer.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function populer(): array
    {
        return array_values(array_filter(
            self::$produk,
            fn (array $produk): bool => $produk['is_populer']
        ));
    }

    /**
     * Ambil produk terbaru.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function terbaru(): array
    {
        return array_values(array_filter(
            self::$produk,
            fn (array $produk): bool => ! $produk['is_populer']
        ));
    }

    /**
     * Cari produk berdasarkan id.
     *
     * @return array<string, mixed>|null
     */
    public static function cari(int $id): ?array
    {
        foreach (self::$produk as $produk) {
            if ($produk['id'] === $id) {
                return $produk;
            }
        }

        return null;
    }
}
