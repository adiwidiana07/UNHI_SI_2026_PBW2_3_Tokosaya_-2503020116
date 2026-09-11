<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Beranda');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});
