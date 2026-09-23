<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" />
    <title>Login Admin | Wibu Store</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/tailstore/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tailstore/css/custom.css') }}">
</head>

<body class="bg-gray-dark">
    <div class="min-h-screen flex items-center justify-center px-6 py-10">
        <div class="w-full" style="max-width: 28rem;">
            <!-- Kartu login -->
            <div class="bg-white p-6 rounded-lg shadow-lg">

                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Wibu Store" class="h-16 w-auto">
                </div>

                <h1 class="text-2xl font-bold text-gray-dark text-center">Masuk Admin</h1>
                <p class="text-gray-txt text-center mt-2 mb-8">Silakan masuk untuk mengakses dashboard Wibu Store.</p>

                <form method="POST" action="{{ route('back-office.login.authenticate') }}" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-dark mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="Masukkan email"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-dark mb-1">Password</label>
                        <input type="password" id="password" name="password" required
                            placeholder="Masukkan password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('password') border-red-400 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-txt">
                            <input type="checkbox" name="remember" id="remember" class="mr-2">
                            Ingat saya
                        </label>
                        <a href="{{ route('home') }}" class="text-sm font-semibold text-primary hover:text-primary">Kembali ke toko</a>
                    </div>

                    <div>
                        <button type="submit"
                            class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold py-3 px-4 rounded-full w-full focus:outline-none">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-sm text-gray-txt mt-6">
                &copy; {{ date('Y') }} Wibu Store. Hak cipta dilindungi.
            </p>
        </div>
    </div>
</body>

</html>