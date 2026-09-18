<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="icon" href="{{ asset('assets/tailstore/img/template-logo.png') }}" />
    <title>@yield('title', 'Tokosaya')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/tailstore/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tailstore/css/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tailstore/css/custom.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Header -->
    @include('partials.navbar')

    <!-- Main content -->
    <main>
        @yield('content')
    </main>


    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('assets/tailstore/js/swiper-bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('assets/tailstore/js/script.js') }}"></script>
    @stack('scripts')

</body>
</html>