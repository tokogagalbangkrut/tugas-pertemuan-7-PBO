# Ruang Baca: Inventaris Buku

Aplikasi Laravel untuk mencatat koleksi buku. Data CRUD disimpan di session, bukan database.

## Persyaratan

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm

## Menjalankan aplikasi

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
npm run dev
```

Pastikan `.env` menggunakan `SESSION_DRIVER=file`. Folder `storage/framework/sessions` harus dapat ditulisi oleh PHP.

Di terminal terpisah, jalankan:

```powershell
php artisan serve
```

Buka `http://127.0.0.1:8000/buku`.

## Route dan file utama

- `routes/web.php`: route bernama untuk daftar, tambah, detail, edit, ubah, dan hapus buku.
- `app/Http/Controllers/BukuController.php`: validasi form dan proses CRUD pada session.
- `resources/views/layouts/app.blade.php`: layout bersama untuk semua halaman.
- `resources/views/buku`: halaman daftar, tambah, edit, detail, dan partial form.

## Pengujian

```powershell
php artisan test
php artisan route:list
```
