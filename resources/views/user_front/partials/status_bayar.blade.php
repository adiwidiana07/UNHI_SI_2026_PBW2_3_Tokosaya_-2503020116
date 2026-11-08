@php
    [$label, $jenis] = $pesanan->labelBayar();

    $kelas = match ($jenis) {
        'success' => 'penanda-lunas',
        'warning' => 'penanda-tunggu',
        'danger'  => 'penanda-gagal',
        default   => 'penanda-netral',
    };
@endphp

<span class="penanda {{ $kelas }}">{{ $label }}</span>
