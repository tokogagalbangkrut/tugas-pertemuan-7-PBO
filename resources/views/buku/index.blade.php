@extends('layouts.app')

@section('title', 'Daftar buku')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Kumpulan Buku Populer</h1>
            <p class="page-intro">20 pilihan buku populer dan best seller yang dikenal pembaca Indonesia.</p>
        </div>
    </div>

    <section class="panel catalog-tools" aria-label="Pencarian dan filter buku">
        <form class="filter-form" action="{{ route('buku.index') }}" method="GET">
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
                <a class="filter-reset" href="{{ route('buku.index') }}">Reset</a>
                <a class="button button-secondary" href="{{ route('buku.export', request()->query()) }}">Ekspor CSV</a>
            </div>
        </form>
    </section>

    <section class="summary-strip" aria-label="Ringkasan inventaris">
        <div class="summary-item">
            <span>Judul tercatat</span>
            <strong>{{ count($daftarBuku) }}</strong>
        </div>
        <div class="summary-item">
            <span>Total eksemplar</span>
            <strong>{{ $totalEksemplar }}</strong>
        </div>
        <div class="summary-item">
            <span>Perlu perhatian</span>
            <strong>{{ $perluPerhatian }}</strong>
        </div>
    </section>

    <p class="results-count">Menampilkan {{ count($hasilBuku) }} dari {{ count($daftarBuku) }} buku</p>

    <section class="panel table-panel" aria-label="Daftar inventaris buku">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama buku</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilBuku as $buku)
                        <tr>
                            <td class="code-cell" data-label="Kode">{{ $buku['kode'] }}</td>
                            <td data-label="Nama buku">
                                <strong>{{ $buku['judul'] }}</strong>
                                @if (! empty($buku['penulis']))
                                    <small class="table-subtext">{{ $buku['penulis'] }}</small>
                                @endif
                            </td>
                            <td data-label="Kategori">{{ $buku['kategori'] }}</td>
                            <td data-label="Lokasi">{{ $buku['lokasi'] }}</td>
                            <td data-label="Jumlah">{{ $buku['jumlah'] }}</td>
                            <td data-label="Kondisi"><span class="condition condition-{{ str($buku['kondisi'])->slug() }}">{{ $buku['kondisi'] }}</span></td>
                            <td data-label="Aksi">
                                <div class="row-actions">
                                    <a class="text-action" href="{{ route('buku.show', ['kode' => $buku['kode']]) }}">Detail</a>
                                    <a class="text-action" href="{{ route('buku.edit', ['kode' => $buku['kode']]) }}">Edit</a>
                                    <form action="{{ route('buku.destroy', ['kode' => $buku['kode']]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-action text-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                @if (count($daftarBuku) === 0)
                                    <strong>Belum ada buku dalam inventaris.</strong>
                                    <span>Tambahkan koleksi pertama untuk mulai mencatatnya.</span>
                                    <a class="text-action" href="{{ route('buku.create') }}">Tambah buku</a>
                                @else
                                    <strong>Tidak ada buku yang cocok.</strong>
                                    <span>Ubah kata pencarian atau filter, lalu coba lagi.</span>
                                    <a class="text-action" href="{{ route('buku.index') }}">Hapus filter</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection