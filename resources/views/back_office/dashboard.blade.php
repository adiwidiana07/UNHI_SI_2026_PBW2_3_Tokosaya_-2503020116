@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Selamat datang, {{ auth()->user()->name }}!</h3>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        Anda berhasil masuk ke back office <strong>Wibu Store</strong>.
                        Berikut ringkasan toko saat ini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $ringkasan['produk'] }}</h3>
                    <p>Total Produk</p>
                </div>
                <i class="small-box-icon bi bi-box-seam-fill"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $ringkasan['kategori'] }}</h3>
                    <p>Kategori</p>
                </div>
                <i class="small-box-icon bi bi-tags-fill"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $ringkasan['pesanan_baru'] }}</h3>
                    <p>Pesanan Baru</p>
                </div>
                <i class="small-box-icon bi bi-cart-fill"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $ringkasan['admin_aktif'] }}</h3>
                    <p>Admin Aktif</p>
                </div>
                <i class="small-box-icon bi bi-person-fill-gear"></i>
            </div>
        </div>
    </div>
@endsection