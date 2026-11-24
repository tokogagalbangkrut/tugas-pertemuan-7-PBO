<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $daftarBuku = $this->daftarBuku($request);
        $totalEksemplar = array_sum(array_map(
            fn (array $buku): int => (int) $buku['jumlah'],
            $daftarBuku
        ));
        $perluPerhatian = count(array_filter(
            $daftarBuku,
            fn (array $buku): bool => $buku['kondisi'] !== 'Baik'
        ));

        return view('buku.index', compact('daftarBuku', 'totalEksemplar', 'perluPerhatian'));
    }

    public function create(Request $request): View
    {
        $this->daftarBuku($request);

        return view('buku.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:30'],
            'judul' => ['required', 'string', 'max:150'],
            'penulis' => ['nullable', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:80'],
            'lokasi' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:Baik,Perlu Perbaikan,Rusak'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        $daftarBuku = $this->daftarBuku($request);
        if (array_key_exists($data['kode'], $daftarBuku)) {
            return back()->withErrors(['kode' => 'Kode buku sudah digunakan.'])->withInput();
        }

        $daftarBuku[$data['kode']] = $data;
        $request->session()->put('daftarBuku', $daftarBuku);

        return redirect()->route('buku.index')->with('status', 'Buku berhasil ditambahkan.');
    }

    public function show(Request $request, string $kode): View|RedirectResponse
    {
        $buku = $this->findBuku($request, $kode);
        if ($buku === null) {
            return $this->notFound();
        }

        return view('buku.show', compact('buku'));
    }

    public function edit(Request $request, string $kode): View|RedirectResponse
    {
        $buku = $this->findBuku($request, $kode);
        if ($buku === null) {
            return $this->notFound();
        }

        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, string $kode): RedirectResponse
    {
        $daftarBuku = $this->daftarBuku($request);
        if (! array_key_exists($kode, $daftarBuku)) {
            return $this->notFound();
        }

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:30'],
            'judul' => ['required', 'string', 'max:150'],
            'penulis' => ['nullable', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:80'],
            'lokasi' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:Baik,Perlu Perbaikan,Rusak'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['kode'] !== $kode && array_key_exists($data['kode'], $daftarBuku)) {
            return back()->withErrors(['kode' => 'Kode buku sudah digunakan.'])->withInput();
        }

        unset($daftarBuku[$kode]);
        $daftarBuku[$data['kode']] = $data;
        $request->session()->put('daftarBuku', $daftarBuku);

        return redirect()->route('buku.index')->with('status', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Request $request, string $kode): RedirectResponse
    {
        $daftarBuku = $this->daftarBuku($request);
        if (! array_key_exists($kode, $daftarBuku)) {
            return $this->notFound();
        }

        unset($daftarBuku[$kode]);
        $request->session()->put('daftarBuku', $daftarBuku);

        return redirect()->route('buku.index')->with('status', 'Buku berhasil dihapus.');
    }

    private function findBuku(Request $request, string $kode): ?array
    {
        return $this->daftarBuku($request)[$kode] ?? null;
    }

    private function daftarBuku(Request $request): array
    {
        if (! $request->session()->has('daftarBuku')) {
            $bukuAwal = [
                ['kode' => 'BK-001', 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'kategori' => 'Novel', 'lokasi' => 'Rak A1', 'jumlah' => 5, 'kondisi' => 'Baik', 'deskripsi' => 'Kisah persahabatan dan perjuangan anak-anak Belitung dalam mengejar pendidikan.'],
                ['kode' => 'BK-002', 'judul' => 'Sang Pemimpi', 'penulis' => 'Andrea Hirata', 'kategori' => 'Novel', 'lokasi' => 'Rak A1', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Perjalanan tiga sahabat yang menjaga mimpi mereka di tengah keterbatasan.'],
                ['kode' => 'BK-003', 'judul' => 'Edensor', 'penulis' => 'Andrea Hirata', 'kategori' => 'Novel', 'lokasi' => 'Rak A1', 'jumlah' => 3, 'kondisi' => 'Baik', 'deskripsi' => 'Petualangan dan pencarian jati diri yang membawa Ikal menjelajahi Eropa.'],
                ['kode' => 'BK-004', 'judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'kategori' => 'Novel sejarah', 'lokasi' => 'Rak B1', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Kisah Minke tentang pendidikan, cinta, dan ketidakadilan pada masa kolonial.'],
                ['kode' => 'BK-005', 'judul' => 'Negeri 5 Menara', 'penulis' => 'Ahmad Fuadi', 'kategori' => 'Novel', 'lokasi' => 'Rak A2', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Enam santri belajar tentang persahabatan, disiplin, dan keberanian bermimpi.'],
                ['kode' => 'BK-006', 'judul' => 'Ayat-Ayat Cinta', 'penulis' => 'Habiburrahman El Shirazy', 'kategori' => 'Novel religi', 'lokasi' => 'Rak A2', 'jumlah' => 3, 'kondisi' => 'Baik', 'deskripsi' => 'Kisah mahasiswa Indonesia di Kairo yang menghadapi pilihan cinta dan keyakinan.'],
                ['kode' => 'BK-007', 'judul' => '5 cm', 'penulis' => 'Donny Dhirgantoro', 'kategori' => 'Novel', 'lokasi' => 'Rak A2', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Lima sahabat menguji persahabatan dan tekad dalam perjalanan menuju Mahameru.'],
                ['kode' => 'BK-008', 'judul' => 'Perahu Kertas', 'penulis' => 'Dewi Lestari', 'kategori' => 'Novel romansa', 'lokasi' => 'Rak A3', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Kugy dan Keenan menempuh jalan masing-masing sambil menjaga impian kreatif mereka.'],
                ['kode' => 'BK-009', 'judul' => 'Dilan: Dia adalah Dilanku Tahun 1990', 'penulis' => 'Pidi Baiq', 'kategori' => 'Novel romansa', 'lokasi' => 'Rak A3', 'jumlah' => 5, 'kondisi' => 'Baik', 'deskripsi' => 'Kisah cinta remaja Milea dan Dilan dengan latar Bandung pada tahun 1990.'],
                ['kode' => 'BK-010', 'judul' => 'Hujan', 'penulis' => 'Tere Liye', 'kategori' => 'Novel fiksi ilmiah', 'lokasi' => 'Rak A3', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Lail menjalani masa depan yang berubah oleh bencana, teknologi, dan kenangan.'],
                ['kode' => 'BK-011', 'judul' => 'Bumi', 'penulis' => 'Tere Liye', 'kategori' => 'Fantasi', 'lokasi' => 'Rak C1', 'jumlah' => 5, 'kondisi' => 'Baik', 'deskripsi' => 'Petualangan Raib, Seli, dan Ali membuka pintu menuju dunia paralel.'],
                ['kode' => 'BK-012', 'judul' => 'Bulan', 'penulis' => 'Tere Liye', 'kategori' => 'Fantasi', 'lokasi' => 'Rak C1', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Kelanjutan petualangan tiga sahabat dalam misi di dunia paralel.'],
                ['kode' => 'BK-013', 'judul' => 'Pulang', 'penulis' => 'Tere Liye', 'kategori' => 'Novel aksi', 'lokasi' => 'Rak C1', 'jumlah' => 3, 'kondisi' => 'Baik', 'deskripsi' => 'Perjalanan hidup Bujang yang tumbuh dari kehidupan sederhana menuju dunia yang keras.'],
                ['kode' => 'BK-014', 'judul' => 'Laut Bercerita', 'penulis' => 'Leila S. Chudori', 'kategori' => 'Novel sejarah', 'lokasi' => 'Rak B2', 'jumlah' => 4, 'kondisi' => 'Perlu Perbaikan', 'deskripsi' => 'Kisah keluarga dan aktivis mahasiswa yang terdampak gejolak politik Indonesia.'],
                ['kode' => 'BK-015', 'judul' => 'Cantik Itu Luka', 'penulis' => 'Eka Kurniawan', 'kategori' => 'Novel', 'lokasi' => 'Rak B2', 'jumlah' => 3, 'kondisi' => 'Baik', 'deskripsi' => 'Kisah lintas generasi yang memadukan sejarah, kehidupan keluarga, dan realisme magis.'],
                ['kode' => 'BK-016', 'judul' => 'Ronggeng Dukuh Paruk', 'penulis' => 'Ahmad Tohari', 'kategori' => 'Novel sastra', 'lokasi' => 'Rak B2', 'jumlah' => 3, 'kondisi' => 'Baik', 'deskripsi' => 'Kisah Srintil dan kehidupan sebuah desa yang berubah oleh pergolakan zaman.'],
                ['kode' => 'BK-017', 'judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'kategori' => 'Pengembangan diri', 'lokasi' => 'Rak D1', 'jumlah' => 5, 'kondisi' => 'Baik', 'deskripsi' => 'Pengantar praktis filsafat Stoa untuk menghadapi hal-hal yang berada di luar kendali.'],
                ['kode' => 'BK-018', 'judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'kategori' => 'Pengembangan diri', 'lokasi' => 'Rak D1', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Panduan membangun kebiasaan melalui perubahan kecil yang dilakukan secara konsisten.'],
                ['kode' => 'BK-019', 'judul' => 'Sapiens: Riwayat Singkat Umat Manusia', 'penulis' => 'Yuval Noah Harari', 'kategori' => 'Sejarah populer', 'lokasi' => 'Rak D2', 'jumlah' => 3, 'kondisi' => 'Perlu Perbaikan', 'deskripsi' => 'Tinjauan populer tentang perkembangan manusia dari masa awal hingga dunia modern.'],
                ['kode' => 'BK-020', 'judul' => 'Sebuah Seni untuk Bersikap Bodo Amat', 'penulis' => 'Mark Manson', 'kategori' => 'Pengembangan diri', 'lokasi' => 'Rak D2', 'jumlah' => 4, 'kondisi' => 'Baik', 'deskripsi' => 'Refleksi tentang memilih prioritas dan menghadapi keterbatasan hidup secara realistis.'],
            ];

            $request->session()->put('daftarBuku', array_column($bukuAwal, null, 'kode'));
        }

        return $request->session()->get('daftarBuku', []);
    }

    private function notFound(): RedirectResponse
    {
        return redirect()->route('buku.index')->with('status', 'Buku tidak ditemukan.');
    }
}