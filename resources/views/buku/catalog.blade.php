@extends('layouts.app')

@section('title', 'Katalog buku')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Katalog Buku</h1>
            <p class="page-intro">Jelajahi koleksi dan pilih buku untuk keranjang simulasi.</p>
        </div>
    </div>

    <section class="panel catalog-tools" aria-label="Pencarian katalog buku">
        <form class="filter-form" action="{{ route('buku.catalog') }}" method="GET">
            <label class="filter-search">
                Cari buku
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Judul, penulis, kode, atau lokasi">
            </label>
            <label>
                Kategori
                <select name="kategori">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Kondisi
                <select name="kondisi">
                    <option value="">Semua kondisi</option>
                    @foreach (['Baik', 'Perlu Perbaikan', 'Rusak'] as $kondisi)
                        <option value="{{ $kondisi }}" @selected(request('kondisi') === $kondisi)>{{ $kondisi }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Urutkan
                <select name="sort">
                    <option value="default" @selected(request('sort', 'default') === 'default')>Urutan awal</option>
                    <option value="judul_asc" @selected(request('sort') === 'judul_asc')>Judul A-Z</option>
                    <option value="judul_desc" @selected(request('sort') === 'judul_desc')>Judul Z-A</option>
                    <option value="jumlah_desc" @selected(request('sort') === 'jumlah_desc')>Jumlah terbesar</option>
                    <option value="jumlah_asc" @selected(request('sort') === 'jumlah_asc')>Jumlah terkecil</option>
                </select>
            </label>
            <div class="filter-actions">
                <button class="button button-primary" type="submit">Terapkan</button>
                <a class="filter-reset" href="{{ route('buku.catalog') }}">Reset</a>
            </div>
        </form>
    </section>

    <p class="results-count">Menampilkan {{ count($hasilBuku) }} buku</p>

    @if (count($hasilBuku) > 0)
        <section class="catalog-grid" aria-label="Daftar buku">
            @foreach ($hasilBuku as $buku)
                <article class="book-card">
                    <div class="book-cover">
                        @if ($buku['cover_url'])
                            <img
                                class="book-cover-image"
                                src="{{ $buku['cover_url'] }}"
                                alt="Sampul {{ $buku['judul'] }}"
                                loading="lazy"
                                onerror="this.hidden=true; this.nextElementSibling.hidden=false"
                            >
                        @endif
                        <div class="book-cover-fallback cover-tone-{{ $loop->iteration % 5 }}" @if ($buku['cover_url']) hidden @endif>
                            <span class="cover-category">{{ $buku['kategori'] }}</span>
                            <strong>{{ $buku['judul'] }}</strong>
                            <small>{{ $buku['penulis'] }}</small>
                        </div>
                    </div>
                    <div class="book-card-body">
                        <p class="book-category">{{ $buku['kategori'] }}</p>
                        <h2>{{ $buku['judul'] }}</h2>
                        <p class="book-author">{{ $buku['penulis'] ?: 'Penulis tidak dicantumkan' }}</p>
                        <p class="book-stock">
                            @if ((int) $buku['jumlah'] > 0)
                                Tersedia {{ $buku['jumlah'] }} eksemplar
                            @else
                                Stok habis
                            @endif
                        </p>
                        <div class="book-card-actions">
                            <a class="book-detail-link" href="{{ route('buku.show', ['kode' => $buku['kode']]) }}">Lihat detail</a>
                            <form action="{{ route('keranjang.store', ['kode' => $buku['kode']]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="jumlah" value="1">
                                <button class="button button-primary" type="submit" @disabled((int) $buku['jumlah'] < 1)>
                                    {{ (int) $buku['jumlah'] > 0 ? 'Tambah ke keranjang' : 'Stok habis' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @else
        <section class="panel catalog-empty">
            <strong>Buku tidak ditemukan.</strong>
            <span>Coba kata pencarian atau filter yang berbeda.</span>
            <a class="text-action" href="{{ route('buku.catalog') }}">Reset pencarian</a>
        </section>
    @endif
@endsection