# LuxeAuto Parts - Premium Automotive Accessories

A premium online shop for luxury automotive accessories, inspired by Mercedes-Benz design aesthetics. Built with HTML, CSS, PHP native, and MySQL.

## Fitur

### Fitur User (Pelanggan):
- Halaman Home dengan banner promo
- Halaman Produk dengan list produk, gambar, harga, stok
- Detail Produk
- Keranjang Belanja
- Checkout sederhana
- Kontak & Tentang Toko

### Fitur Admin:
- Login Admin
- Dashboard Admin dengan statistik
- CRUD Produk (tambah, edit, hapus produk)
- Manajemen Stok
- Melihat Pesanan dan detail pesanan

## Struktur Folder

```
aksesoris_mobil/
├── config.php          # Konfigurasi database
├── database.sql        # Schema database dan data sample
├── index.php           # Halaman home
├── products.php        # Halaman list produk
├── product_detail.php  # Halaman detail produk
├── cart.php            # Halaman keranjang belanja
├── checkout.php        # Halaman checkout
├── order_success.php   # Halaman sukses pesanan
├── contact.php         # Halaman kontak dan tentang
├── css/
│   └── style.css       # Styling website
├── js/
│   └── script.js       # JavaScript untuk interaktivitas
├── images/             # Folder untuk gambar produk
├── admin/
│   ├── login.php       # Login admin
│   ├── dashboard.php   # Dashboard admin
│   ├── products.php    # Kelola produk admin
│   ├── orders.php      # Lihat pesanan admin
│   ├── order_detail.php # Detail pesanan admin
│   └── logout.php      # Logout admin
└── README.md           # Dokumentasi proyek
```

## Setup

1. **Persiapan Database:**
   - Buat database MySQL baru
   - Import file `database.sql` untuk membuat tabel dan data sample

2. **Konfigurasi Database:**
   - Edit file `config.php` dan sesuaikan dengan setting database Anda:
     ```php
     $host = 'localhost';
     $dbname = 'aksesoris_mobil';
     $username = 'root'; // Ganti sesuai username database Anda
     $password = ''; // Ganti sesuai password database Anda
     ```

3. **Menjalankan Website:**
   - Pastikan XAMPP atau web server PHP lainnya sudah berjalan
   - Akses website melalui browser: `http://localhost/aksesoris_mobil/`

4. **Login Admin:**
   - Username: `admin`
   - Password: `password`
   - URL: `http://localhost/aksesoris_mobil/admin/login.php`

## Teknologi yang Digunakan

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7+ (native, tanpa framework)
- **Database:** MySQL
- **Styling:** Responsive design dengan CSS modern

## Penjelasan Halaman

### Halaman User:
- **index.php:** Halaman utama dengan banner dan produk unggulan
- **products.php:** List semua produk dengan fitur pencarian
- **product_detail.php:** Detail produk individual
- **cart.php:** Keranjang belanja dengan session
- **checkout.php:** Form checkout untuk informasi pengiriman
- **order_success.php:** Konfirmasi pesanan berhasil
- **contact.php:** Informasi kontak dan tentang toko

### Halaman Admin:
- **admin/login.php:** Form login admin
- **admin/dashboard.php:** Dashboard dengan statistik penjualan
- **admin/products.php:** CRUD untuk mengelola produk
- **admin/orders.php:** List semua pesanan
- **admin/order_detail.php:** Detail pesanan individual

## Data Sample

Database sudah dilengkapi dengan 10 produk sample:
1. Oli Mesin Mobil
2. Busi NGK
3. Filter Oli
4. Aki Mobil
5. Velg Racing
6. Ban Mobil
7. Kampas Rem
8. Shockbreaker
9. Lampu LED
10. Spion Mobil

## Catatan

- Website ini menggunakan session untuk keranjang belanja
- Admin login menggunakan autentikasi sederhana (username: admin, password: password)
- Untuk production, sebaiknya gunakan hashing password dan validasi input yang lebih ketat
- Gambar produk disimpan di folder `images/` (perlu ditambahkan secara manual)

## Lisensi

Proyek ini dibuat untuk tujuan edukasi dan demonstrasi.
