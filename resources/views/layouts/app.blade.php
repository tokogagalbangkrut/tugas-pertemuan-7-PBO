<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventaris Buku') | Ruang Baca</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('buku.catalog') }}">
                <strong>Ruang Baca</strong>
            </a>
            <nav aria-label="Navigasi utama">
                <a href="{{ route('buku.catalog') }}" @if (request()->routeIs('buku.catalog')) aria-current="page" @endif>Katalog</a>
                <a href="{{ route('buku.index') }}" @if (request()->routeIs('buku.index')) aria-current="page" @endif>Inventaris</a>
                <a href="{{ route('keranjang.index') }}" @if (request()->routeIs('keranjang.*')) aria-current="page" @endif>
                    Keranjang <span class="cart-count">{{ array_sum(session('keranjang', [])) }}</span>
                </a>
                @if (request()->routeIs('buku.index', 'buku.create', 'buku.edit'))
                    <a class="nav-add" href="{{ route('buku.create') }}">Tambah buku</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="page-shell">
        @if (session('status'))
            <div class="notice" role="status">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="notice notice-error" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice notice-error" role="alert">
                <strong>Periksa kembali isian:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">Ruang Baca, inventaris koleksi</footer>
</body>
</html>