@extends('layouts.app')

@section('title', 'Tambah buku')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Koleksi baru</p>
            <h1>Tambah buku</h1>
            <p class="page-intro">Catat informasi buku dan letak penyimpanannya.</p>
        </div>
    </div>

    <section class="panel">
        @include('buku._form', [
            'buku' => [],
            'formAction' => route('buku.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Simpan buku',
        ])
    </section>
@endsection