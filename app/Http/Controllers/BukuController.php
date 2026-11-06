<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    public function index()
    {
        $daftarBuku = [
            [
                'id' => 1,
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'deskripsi' => 'Mengisahkan perjuangan 10 anak dari keluarga kurang mampu di Belitung untuk mendapatkan pendidikan layak di sekolah yang nyaris ditutup.'
            ],
            [
                'id' => 2,
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'deskripsi' => 'Mahakarya berlatar era kolonial Belanda yang menceritakan kisah Minke, pemuda pribumi cerdas yang berjuang melawan diskriminasi dan ketidakadilan.'
            ],
            [
                'id' => 3,
                'judul' => 'Cantik Itu Luka',
                'penulis' => 'Eka Kurniawan',
                'deskripsi' => 'Novel realisme magis yang memadukan sejarah, tragedi keluarga, dan kritik sosial berlatar masa kolonial hingga kemerdekaan Indonesia.'
            ],
            [
                'id' => 4,
                'judul' => 'Laut Bercerita',
                'penulis' => 'Leila S. Chudori',
                'deskripsi' => 'Mengangkat kisah kelam kekejaman politik era 1990-an mengenai para aktivis mahasiswa yang diculik dan dihilangkan paksa.'
            ],
            [
                'id' => 5,
                'judul' => '5 cm',
                'penulis' => 'Donny Dhirgantoro',
                'deskripsi' => 'Kisah inspiratif petualangan lima sahabat yang mendaki puncak tertinggi di Pulau Jawa (Mahameru) untuk menguji persahabatan dan impian mereka.'
            ],
            [
                'id' => 6,
                'judul' => 'Ronggeng Dukuh Paruk',
                'penulis' => 'Ahmad Tohari',
                'deskripsi' => 'Trilogi novel klasik yang menyoroti kehidupan penari ronggeng di pedesaan Jawa dan pergolakan sosial-politik pasca-kejadian 1965.'
            ],
            [
                'id' => 7,
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'deskripsi' => 'Kisah enam santri dari berbagai daerah di pesantren Madani yang dipersatukan oleh impian dan semangat "Man Jadda Wajada".'
            ],
            [
                'id' => 8,
                'judul' => 'Saman',
                'penulis' => 'Ayu Utami',
                'deskripsi' => 'Novel pelopor gerakan sastra wangi yang berani mengeksplorasi tema kebebasan, hak asasi, isu sosial-politik, dan seksualitas.'
            ],
            [
                'id' => 9,
                'judul' => 'Perahu Kertas',
                'penulis' => 'Dewi Lestari (Dee)',
                'deskripsi' => 'Kisah romansa dan pencarian jati diri antara dua anak muda yang memiliki impian serta kecintaan mendalam pada seni dan cerita.'
            ],
            [
                'id' => 10,
                'judul' => 'Tenggelamnya Kapal Van Der Wijck',
                'penulis' => 'Buya Hamka',
                'deskripsi' => 'Kisah cinta tragis antara Zainuddin dan Hayati yang terhalang oleh aturan adat dan perbedaan status sosial Minangkabau masa lalu.'
            ]
        ];

        return view('buku.index', compact('daftarBuku'));
    }

    public function show(string $id)
    {
        return view('buku.show', compact('id'));
    }
}