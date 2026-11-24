<form action="{{ $formAction }}" method="POST" class="book-form">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="form-grid">
        <label>
            Kode buku
            <input name="kode" value="{{ old('kode', $buku['kode'] ?? '') }}" maxlength="30" required>
        </label>
        <label>
            Judul buku
            <input name="judul" value="{{ old('judul', $buku['judul'] ?? '') }}" maxlength="150" required>
        </label>
        <label>
            Penulis
            <input name="penulis" value="{{ old('penulis', $buku['penulis'] ?? '') }}" maxlength="100">
        </label>
        <label>
            Kategori
            <input name="kategori" value="{{ old('kategori', $buku['kategori'] ?? '') }}" maxlength="80" required>
        </label>
        <label>
            Lokasi penyimpanan
            <input name="lokasi" value="{{ old('lokasi', $buku['lokasi'] ?? '') }}" maxlength="100" required>
        </label>
        <label>
            Jumlah
            <input type="number" name="jumlah" min="0" value="{{ old('jumlah', $buku['jumlah'] ?? 0) }}" required>
        </label>
        <label>
            Kondisi
            <select name="kondisi" required>
                <option value="">Pilih kondisi</option>
                @foreach (['Baik', 'Perlu Perbaikan', 'Rusak'] as $kondisi)
                    <option value="{{ $kondisi }}" @selected(old('kondisi', $buku['kondisi'] ?? '') === $kondisi)>{{ $kondisi }}</option>
                @endforeach
            </select>
        </label>
        <label class="field-wide">
            Deskripsi
            <textarea name="deskripsi" rows="4" maxlength="1000">{{ old('deskripsi', $buku['deskripsi'] ?? '') }}</textarea>
        </label>
    </div>

    <div class="form-actions">
        <a class="button button-secondary" href="{{ route('buku.index') }}">Batal</a>
        <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
    </div>
</form>