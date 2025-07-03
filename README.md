# Toko Online CodeIgniter 4

Proyek ini adalah platform toko online yang dibangun menggunakan [CodeIgniter 4](https://codeigniter.com/). Sistem ini menyediakan berbagai fungsionalitas untuk toko online, termasuk manajemen produk, keranjang belanja, sistem transaksi, dan panel admin.

## Daftar Isi

- [Fitur](#fitur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Struktur Proyek](#struktur-proyek)

## Fitur

- **Katalog Produk**
  - Tampilan produk lengkap dengan gambar dan deskripsi.
  - Pencarian produk berdasarkan nama atau kategori.
- **Keranjang Belanja**
  - Menambahkan produk ke keranjang dengan harga asli dan diskon yang berlaku.
  - Mengubah jumlah produk di keranjang dengan perhitungan ulang harga dan diskon.
  - Menghapus produk dari keranjang.
- **Sistem Diskon**
  - Diskon harian yang diterapkan per item jika tanggal diskon sesuai dengan tanggal hari ini.
  - Diskon hanya berlaku untuk pengguna dengan role tertentu (misalnya admin).
  - Diskon ditampilkan secara jelas di UI, termasuk halaman keranjang dan header.
- **Sistem Transaksi**
  - Proses checkout dengan penghitungan total harga, ongkos kirim, dan diskon.
  - Penyimpanan riwayat transaksi dan detail produk yang dibeli.
- **Panel Admin**
  - Manajemen produk (CRUD).
  - Manajemen kategori produk.
  - Manajemen diskon (CRUD).
  - Laporan transaksi dan export data ke PDF.
- **Sistem Autentikasi**
  - Login dan registrasi pengguna.
  - Manajemen role pengguna (admin, guest, dll).
  - Pembatasan akses berdasarkan role.
- **UI Responsif**
  - Menggunakan template NiceAdmin untuk tampilan yang modern dan responsif.

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Web server (misalnya XAMPP dengan Apache dan MySQL)
- Database MySQL atau MariaDB

## Instalasi

1. **Clone repository ini**
   ```bash
   git clone [URL repository]
   cd belajar-ci-tugas
   ```
2. **Install dependensi menggunakan Composer**
   ```bash
   composer install
   ```
3. **Konfigurasi database**
   - Jalankan Apache dan MySQL melalui XAMPP.
   - Buat database baru dengan nama `db_ci4` menggunakan phpMyAdmin atau tool lain.
   - Salin file `.env` dari tutorial resmi CodeIgniter 4 dan sesuaikan konfigurasi database di dalamnya.
4. **Jalankan migrasi database**
   ```bash
   php spark migrate
   ```
5. **Seeder data awal**
   ```bash
   php spark db:seed ProductSeeder
   php spark db:seed UserSeeder
   ```
6. **Jalankan server development**
   ```bash
   php spark serve
   ```
7. **Akses aplikasi**
   Buka browser dan akses `http://localhost:8080` untuk melihat aplikasi berjalan.

## Struktur Proyek

Proyek ini menggunakan arsitektur MVC (Model-View-Controller) dari CodeIgniter 4 dengan struktur sebagai berikut:

- **app/Controllers**  
  Berisi logika aplikasi dan penanganan request HTTP.  
  Contoh file penting:  
  - `AuthController.php` : Autentikasi pengguna (login, logout).  
  - `ProdukController.php` : Manajemen produk.  
  - `TransaksiController.php` : Proses transaksi dan keranjang belanja.  
  - `Diskon.php` : Manajemen diskon dan pengaturan diskon harian.

- **app/Models**  
  Berisi model untuk interaksi dengan database.  
  Contoh file penting:  
  - `ProductModel.php` : Model produk.  
  - `UserModel.php` : Model pengguna.  
  - `DiskonModel.php` : Model diskon.  
  - `TransactionModel.php` dan `TransactionDetailModel.php` : Model transaksi dan detail transaksi.

- **app/Views**  
  Berisi template dan komponen UI yang ditampilkan ke pengguna.  
  Contoh file penting:  
  - `v_produk.php` : Tampilan daftar produk.  
  - `v_keranjang.php` : Halaman keranjang belanja.  
  - `v_diskon.php` : Halaman manajemen diskon.  
  - `components/` : Komponen UI seperti header, sidebar, dll.

- **app/Config**  
  Berisi konfigurasi aplikasi, termasuk routing, database, dan filter.

- **public/**  
  Folder publik yang berisi file index.php sebagai entry point aplikasi, serta aset seperti gambar dan CSS/JS.

- **writable/**  
  Folder untuk menyimpan cache, logs, dan file yang dapat ditulis oleh aplikasi.

---

Jika ada pertanyaan atau butuh bantuan lebih lanjut, silakan hubungi pengembang atau lihat dokumentasi CodeIgniter 4.
