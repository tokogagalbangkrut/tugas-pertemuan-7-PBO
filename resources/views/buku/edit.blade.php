@extends('layouts.app')

@section('title', 'Edit buku')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Perbarui koleksi</p>
            <h1>Edit buku</h1>
            <p class="page-intro">Ubah data untuk {{ $buku['judul'] }}.</p>
        </div>
    </div>

    <section class="panel">
        @include('buku._form', [
            'formAction' => route('buku.update', ['kode' => $buku['kode']]),
            'formMethod' => 'PUT',
            'submitLabel' => 'Simpan perubahan',
        ])
    </section>
@endsection