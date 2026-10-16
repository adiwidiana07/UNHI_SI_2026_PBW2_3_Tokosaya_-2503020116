<?php

namespace App\Providers;

use App\Services\Keranjang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Data keranjang untuk navbar (dipakai di semua halaman depan).
        View::composer('partials.navbar', function ($view) {
            $item = Keranjang::daftarItem();

            $view->with([
                'keranjangItem'  => $item,
                'jumlahKeranjang' => $item->sum('jumlah'),
            ]);
        });
    }
}
