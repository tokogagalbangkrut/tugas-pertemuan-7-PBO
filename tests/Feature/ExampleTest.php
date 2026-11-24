<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('buku.index'));
    }

    public function test_first_visit_seeds_twenty_popular_books(): void
    {
        $this->get(route('buku.index'))
            ->assertOk()
            ->assertSee('Kumpulan Buku Populer')
            ->assertSee('Laskar Pelangi')
            ->assertSee('Sebuah Seni untuk Bersikap Bodo Amat')
            ->assertSessionHas('daftarBuku', fn (array $daftarBuku): bool => count($daftarBuku) === 20);
    }

    public function test_catalog_can_search_filter_and_sort_books(): void
    {
        $daftarBuku = [
            'BK-001' => [
                'kode' => 'BK-001',
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'kategori' => 'Novel',
                'lokasi' => 'Rak A1',
                'jumlah' => 5,
                'kondisi' => 'Baik',
                'deskripsi' => 'Kisah persahabatan.',
            ],
            'BK-002' => [
                'kode' => 'BK-002',
                'judul' => 'Sang Pemimpi',
                'penulis' => 'Andrea Hirata',
                'kategori' => 'Novel',
                'lokasi' => 'Rak A1',
                'jumlah' => 3,
                'kondisi' => 'Baik',
                'deskripsi' => 'Kisah tiga sahabat.',
            ],
            'BK-003' => [
                'kode' => 'BK-003',
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'kategori' => 'Novel sejarah',
                'lokasi' => 'Rak B1',
                'jumlah' => 4,
                'kondisi' => 'Baik',
                'deskripsi' => 'Kisah sejarah.',
            ],
        ];

        $this->withSession(['daftarBuku' => $daftarBuku])
            ->get(route('buku.index', [
                'q' => 'Andrea',
                'kategori' => 'Novel',
                'sort' => 'judul_desc',
            ]))
            ->assertOk()
            ->assertSeeInOrder(['Sang Pemimpi', 'Laskar Pelangi'])
            ->assertDontSee('Bumi Manusia')
            ->assertSee('Menampilkan 2 dari 3 buku');
    }

    public function test_catalog_can_be_exported_as_csv(): void
    {
        $this->withSession(['daftarBuku' => []])
            ->get(route('buku.export'))
            ->assertDownload('inventaris-buku.csv');
    }

    public function test_cart_add_and_simulated_checkout_do_not_change_stock(): void
    {
        $buku = [
            'kode' => 'BK-001',
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'kategori' => 'Novel',
            'lokasi' => 'Rak A1',
            'jumlah' => 5,
            'kondisi' => 'Baik',
            'deskripsi' => 'Kisah persahabatan.',
        ];
        $session = ['daftarBuku' => ['BK-001' => $buku], 'keranjang' => []];

        $this->withSession($session)
            ->post(route('keranjang.store', ['kode' => 'BK-001']), ['jumlah' => 2])
            ->assertRedirect()
            ->assertSessionHas('keranjang.BK-001', 2);

        $this->withSession(['daftarBuku' => ['BK-001' => $buku], 'keranjang' => ['BK-001' => 2]])
            ->post(route('keranjang.checkout'))
            ->assertRedirect(route('keranjang.index'))
            ->assertSessionMissing('keranjang')
            ->assertSessionHas('checkout_success', fn (array $hasil): bool => $hasil['total_item'] === 2)
            ->assertSessionHas('daftarBuku.BK-001.jumlah', 5);
    }

    public function test_cart_cannot_exceed_available_stock(): void
    {
        $buku = [
            'kode' => 'BK-001',
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'kategori' => 'Novel',
            'lokasi' => 'Rak A1',
            'jumlah' => 2,
            'kondisi' => 'Baik',
            'deskripsi' => 'Kisah persahabatan.',
        ];

        $this->withSession(['daftarBuku' => ['BK-001' => $buku], 'keranjang' => []])
            ->post(route('keranjang.store', ['kode' => 'BK-001']), ['jumlah' => 3])
            ->assertRedirect()
            ->assertSessionHas('error', 'Jumlah melebihi stok yang tersedia.')
            ->assertSessionHas('keranjang', []);
    }

    public function test_buku_crud_uses_session_and_named_routes(): void
    {
        $kode = 'BK-001';
        $buku = [
            'kode' => $kode,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'kategori' => 'Novel',
            'lokasi' => 'Rak A1',
            'jumlah' => '4',
            'kondisi' => 'Baik',
            'deskripsi' => 'Koleksi bacaan.',
        ];

        $this->withSession(['daftarBuku' => []])
            ->post(route('buku.store'), $buku)
            ->assertRedirect(route('buku.index'))
            ->assertSessionHas('daftarBuku.BK-001.judul', 'Laskar Pelangi');

        $session = ['daftarBuku' => [$kode => $buku]];

        $this->withSession($session)
            ->get(route('buku.index'))
            ->assertOk()
            ->assertSee('Laskar Pelangi')
            ->assertSee('BK-001');

        $this->withSession($session)
            ->get(route('buku.show', ['kode' => $kode]))
            ->assertOk()
            ->assertSee('Rak A1');

        $this->withSession($session)
            ->get(route('buku.edit', ['kode' => $kode]))
            ->assertOk()
            ->assertSee('value="Laskar Pelangi"', false);

        $buku['judul'] = 'Laskar Pelangi Edisi Baru';
        $this->withSession($session)
            ->put(route('buku.update', ['kode' => $kode]), $buku)
            ->assertRedirect(route('buku.index'))
            ->assertSessionHas('daftarBuku.BK-001.judul', 'Laskar Pelangi Edisi Baru');

        $updatedSession = ['daftarBuku' => [$kode => $buku]];
        $this->withSession($updatedSession)
            ->delete(route('buku.destroy', ['kode' => $kode]))
            ->assertRedirect(route('buku.index'))
            ->assertSessionHas('daftarBuku', []);
    }

    public function test_missing_buku_returns_to_the_list_with_a_message(): void
    {
        $this->withSession(['daftarBuku' => []])
            ->get(route('buku.show', ['kode' => 'TIDAK-ADA']))
            ->assertRedirect(route('buku.index'))
            ->assertSessionHas('status', 'Buku tidak ditemukan.');
    }
}
