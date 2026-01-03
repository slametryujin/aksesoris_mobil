CREATE DATABASE aksesoris_mobil;
USE aksesoris_mobil;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(100),
password VARCHAR(255)
);

INSERT INTO users VALUES(NULL,'admin@admin.com',MD5('admin123'));

CREATE TABLE produk (
id INT AUTO_INCREMENT PRIMARY KEY,
nama_produk VARCHAR(150),
harga INT,
stok INT,
deskripsi TEXT,
gambar VARCHAR(255)
);

-- sample categories table (optional)
CREATE TABLE kategori (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(100)
);

INSERT INTO kategori (nama) VALUES ('Oli'), ('Saringan'), ('Aksesori'), ('Perawatan');

-- sample products
-- Add kategori support and created_at to produk
DROP TABLE IF EXISTS produk;
CREATE TABLE produk (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama_produk VARCHAR(150),
 harga INT,
 stok INT,
 deskripsi TEXT,
 gambar VARCHAR(255),
 kategori_id INT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample products (kategori_id references kategori table inserted earlier)
INSERT INTO produk (nama_produk,harga,stok,deskripsi,gambar,kategori_id) VALUES
('Oli Mesin 1L',120000,25,'Oli mesin kualitas premium untuk performa maksimal. Cocok untuk mobil bensin dan diesel.','oli1.jpg',1),
('Saringan Udara',45000,40,'Saringan udara berkualitas untuk sirkulasi udara optimal.','saringan1.jpg',2),
('Karpet Mobil',150000,10,'Karpet mobil tahan lama dan mudah dibersihkan.','karpet1.jpg',3);