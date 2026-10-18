
# KantongKu

Aplikasi pengelolaan keuangan pribadi berbasis web yang dibangun menggunakan Laravel.

## Fitur
- Pencatatan pemasukan
- Pencatatan pengeluaran
- Pengelolaan transaksi keuangan

## Teknologi
- Laravel
- PHP
- MySQL
- HTML, CSS, dan JavaScript

## Instalasi

1. Clone repository:
   ```bash
   git clone URL_REPOSITORY_KAMU
   ```

2. Masuk ke folder project:
   ```bash
   cd kantongku
   ```

3. Install dependencies:
   ```bash
   composer install
   ```

4. Salin file konfigurasi:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Atur koneksi database di file `.env`.

7. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```