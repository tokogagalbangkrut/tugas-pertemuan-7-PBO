<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku Top 10 Indonesia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .buku-card {
            border: 1px solid #e2e8f0;
            border-left: 5px solid #3498db;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #fff;
            transition: transform 0.2s;
        }
        .buku-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .buku-card h3 {
            margin: 0 0 8px 0;
        }
        .buku-card h3 a {
            text-decoration: none;
            color: #2980b9;
        }
        .buku-card h3 a:hover {
            text-decoration: underline;
        }
        .penulis {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-bottom: 10px;
            font-style: italic;
        }
        .deskripsi {
            margin: 0 0 10px 0;
            line-height: 1.5;
        }
        .meta {
            font-size: 0.8em;
            color: #95a5a6;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Top 10 Buku Populer di Indonesia</h1>
        <p>Menampilkan total <strong>{{ count($daftarBuku) }}</strong> koleksi buku populer:</p>

        @foreach ($daftarBuku as $buku)
            <div class="buku-card">
                <h3>
                    <a href="{{ route('buku.show', $buku['id']) }}">
                        {{ $loop->iteration }}. {{ $buku['judul'] }}
                    </a>
                </h3>
                <div class="penulis">Penulis: {{ $buku['penulis'] }}</div>
                <p class="deskripsi">{{ $buku['deskripsi'] }}</p>
                <div class="meta">ID Sistem: {{ $buku['id'] }}</div>
            </div>
        @endforeach
    </div>

</body>
</html>