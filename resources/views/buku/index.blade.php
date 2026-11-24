@extends('layouts.app')

@section('title', 'Daftar buku')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Kumpulan Buku Populer</h1>
            <p class="page-intro">20 pilihan buku populer dan best seller yang dikenal pembaca Indonesia.</p>
        </div>
    </div>

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
                    @forelse ($daftarBuku as $buku)
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
                                <strong>Belum ada buku dalam inventaris.</strong>
                                <span>Tambahkan koleksi pertama untuk mulai mencatatnya.</span>
                                <a class="text-action" href="{{ route('buku.create') }}">Tambah buku</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection