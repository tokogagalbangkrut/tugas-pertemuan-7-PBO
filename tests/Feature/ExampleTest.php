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
            ->assertSee('Sebuah Seni untuk Bersikap Bodo Amat');

        $this->assertCount(20, session('daftarBuku'));
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
