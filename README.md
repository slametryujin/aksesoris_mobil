# Toko Aksesoris Mobil (PHP + MySQL)

Simple demo website for selling car accessories (built for XAMPP / local development).

Setup steps (macOS / XAMPP):

1. Copy this project to your XAMPP htdocs folder, for example:

   /Applications/XAMPP/xamppfiles/htdocs/aksesoris_mobil

2. Start MySQL and Apache using XAMPP control panel.

3. Import the database:

   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create database or import `database.sql` which will create `aksesoris_mobil` and tables.

4. Ensure `config/db.php` credentials match your environment (default is root with no password).

5. Put sample images into `assets/img/` matching names in `database.sql` (e.g., `oli1.jpg`, `saringan1.jpg`, `karpet1.jpg`) so demo products show images.

6. Open the site:

   - Public: http://localhost/aksesoris_mobil/public
   - Admin: http://localhost/aksesoris_mobil/admin/login.php

   Default admin credentials from `database.sql`:
   email: admin@admin.com
   password: admin123

Notes & next steps:
- This is a simple example for local development. For production, secure passwords (use password_hash), validate file uploads thoroughly, and avoid MD5.
- You can extend categories, search, pagination, and cart/checkout features.
 - Search, category filter, and pagination are available on the public product listing.
