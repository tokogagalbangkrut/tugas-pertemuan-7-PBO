<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KeranjangController extends Controller
{
    public function index(Request $request): View
    {
        $daftarBuku = $request->session()->get('daftarBuku', []);
        $keranjang = $request->session()->get('keranjang', []);
        $items = [];

        foreach ($keranjang as $kode => $jumlah) {
            if (isset($daftarBuku[$kode])) {
                $items[] = array_merge($daftarBuku[$kode], [
                    'jumlah_keranjang' => (int) $jumlah,
                    'stok' => (int) $daftarBuku[$kode]['jumlah'],
                ]);
            }
        }

        $totalItem = array_sum(array_column($items, 'jumlah_keranjang'));

        return view('keranjang.index', compact('items', 'totalItem'));
    }

    public function store(Request $request, string $kode): RedirectResponse
    {
        $daftarBuku = $request->session()->get('daftarBuku', []);
        if (! isset($daftarBuku[$kode])) {
            return redirect()->route('buku.catalog')->with('error', 'Buku tidak ditemukan.');
        }

        $request->validate(['jumlah' => ['sometimes', 'integer', 'min:1']]);
        $jumlahTambahan = (int) $request->input('jumlah', 1);
        $keranjang = $request->session()->get('keranjang', []);
        $jumlahBaru = ($keranjang[$kode] ?? 0) + $jumlahTambahan;

        if ($jumlahBaru > (int) $daftarBuku[$kode]['jumlah']) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $keranjang[$kode] = $jumlahBaru;
        $request->session()->put('keranjang', $keranjang);

        return back()->with('status', 'Buku ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $kode): RedirectResponse
    {
        $data = $request->validate(['jumlah' => ['required', 'integer', 'min:1']]);
        $daftarBuku = $request->session()->get('daftarBuku', []);
        $keranjang = $request->session()->get('keranjang', []);

        if (! isset($daftarBuku[$kode], $keranjang[$kode])) {
            return redirect()->route('keranjang.index')->with('error', 'Item keranjang tidak ditemukan.');
        }

        if ($data['jumlah'] > (int) $daftarBuku[$kode]['jumlah']) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $keranjang[$kode] = $data['jumlah'];
        $request->session()->put('keranjang', $keranjang);

        return redirect()->route('keranjang.index')->with('status', 'Jumlah buku diperbarui.');
    }

    public function destroy(Request $request, string $kode): RedirectResponse
    {
        $keranjang = $request->session()->get('keranjang', []);
        unset($keranjang[$kode]);
        $request->session()->put('keranjang', $keranjang);

        return redirect()->route('keranjang.index')->with('status', 'Buku dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $daftarBuku = $request->session()->get('daftarBuku', []);
        $keranjang = $request->session()->get('keranjang', []);

        if ($keranjang === []) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong.');
        }

        $totalItem = 0;
        foreach ($keranjang as $kode => $jumlah) {
            if (! isset($daftarBuku[$kode]) || $jumlah > (int) $daftarBuku[$kode]['jumlah']) {
                return redirect()->route('keranjang.index')->with('error', 'Stok buku berubah. Periksa kembali keranjang.');
            }

            $totalItem += (int) $jumlah;
        }

        $request->session()->forget('keranjang');

        return redirect()->route('keranjang.index')->with('checkout_success', [
            'kode' => 'SIM-'.Str::upper(Str::random(8)),
            'total_item' => $totalItem,
            'judul' => count($keranjang),
        ]);
    }
}