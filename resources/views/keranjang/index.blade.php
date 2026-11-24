@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Keranjang</h1>
            <p class="page-intro">Tinjau pilihan buku sebelum menjalankan checkout simulasi.</p>
        </div>
    </div>

    @if (session('checkout_success'))
        <section class="checkout-success" role="status">
            <span class="success-check" aria-hidden="true">&#10003;</span>
            <div>
                <h2>Simulasi checkout berhasil</h2>
                <p>{{ session('checkout_success.judul') }} judul, {{ session('checkout_success.total_item') }} eksemplar tercatat pada sesi ini.</p>
                <p class="receipt-code">Nomor simulasi {{ session('checkout_success.kode') }}</p>
                <p class="checkout-note">Tidak ada pembayaran atau pembelian yang diproses.</p>
            </div>
        </section>
    @endif

    @if (count($items) > 0)
        <div class="cart-heading-row">
            <p class="results-count">{{ $totalItem }} buku dalam keranjang</p>
            <a class="text-action" href="{{ route('buku.catalog') }}">Lanjut lihat katalog</a>
        </div>

        <section class="panel cart-list" aria-label="Buku dalam keranjang">
            @foreach ($items as $item)
                <article class="cart-row">
                    <div class="cart-item-info">
                        <p class="book-category">{{ $item['kategori'] }}</p>
                        <h2>{{ $item['judul'] }}</h2>
                        <p class="book-author">{{ $item['penulis'] }} · {{ $item['stok'] }} tersedia</p>
                    </div>
                    <div class="cart-row-actions">
                        <form class="quantity-form" action="{{ route('keranjang.update', ['kode' => $item['kode']]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label>
                                Jumlah
                                <input type="number" name="jumlah" min="1" max="{{ $item['stok'] }}" value="{{ $item['jumlah_keranjang'] }}" required>
                            </label>
                            <button class="text-action" type="submit">Perbarui</button>
                        </form>
                        <form action="{{ route('keranjang.destroy', ['kode' => $item['kode']]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-action text-danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="checkout-bar">
            <p>Checkout ini hanya simulasi fungsi, tanpa transaksi pembayaran.</p>
            <form action="{{ route('keranjang.checkout') }}" method="POST">
                @csrf
                <button class="button button-primary" type="submit">Simulasikan checkout</button>
            </form>
        </div>
    @else
        <section class="panel catalog-empty">
            <strong>Keranjang masih kosong.</strong>
            <span>Tambahkan buku dari katalog untuk mencoba checkout simulasi.</span>
            <a class="button button-primary" href="{{ route('buku.catalog') }}">Buka katalog</a>
        </section>
    @endif
@endsection