@extends('layouts.app')

@section('title', 'Detail buku')

@section('content')
	<div class="page-heading">
		<div>
			<p class="eyebrow">Kode buku {{ $buku['kode'] }}</p>
			<h1>{{ $buku['judul'] }}</h1>
			<p class="page-intro">Informasi lengkap buku yang tersimpan di inventaris.</p>
		</div>
		<a class="button button-secondary" href="{{ route('buku.index') }}">Kembali</a>
	</div>

	<section class="panel detail-panel">
		<dl class="detail-grid">
			<div><dt>Kode</dt><dd>{{ $buku['kode'] }}</dd></div>
			<div><dt>Judul buku</dt><dd>{{ $buku['judul'] }}</dd></div>
			<div><dt>Penulis</dt><dd>{{ $buku['penulis'] ?: '-' }}</dd></div>
			<div><dt>Kategori</dt><dd>{{ $buku['kategori'] }}</dd></div>
			<div><dt>Lokasi</dt><dd>{{ $buku['lokasi'] }}</dd></div>
			<div><dt>Jumlah</dt><dd>{{ $buku['jumlah'] }} eksemplar</dd></div>
			<div><dt>Kondisi</dt><dd>{{ $buku['kondisi'] }}</dd></div>
			<div class="field-wide"><dt>Deskripsi</dt><dd>{{ $buku['deskripsi'] ?: 'Tidak ada deskripsi.' }}</dd></div>
		</dl>
		<div class="form-actions">
			<a class="button button-primary" href="{{ route('buku.edit', ['kode' => $buku['kode']]) }}">Edit buku</a>
		</div>
	</section>
@endsection