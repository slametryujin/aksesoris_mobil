-- Database schema for Aksesoris Mobil online shop

CREATE DATABASE IF NOT EXISTS aksesoris_mobil;
USE aksesoris_mobil;

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    customer_address TEXT,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample products
INSERT INTO products (name, description, price, stock, image) VALUES
('Oli Mesin Mobil', 'Oli mesin berkualitas tinggi untuk performa optimal kendaraan', 150000, 50, 'oli.jpg'),
('Busi NGK', 'Busi mobil original NGK untuk pengapian yang baik', 75000, 100, 'busi.jpg'),
('Filter Oli', 'Filter oli yang efektif menyaring kotoran dan debu', 50000, 75, 'filter_oli.jpg'),
('Aki Mobil', 'Aki mobil dengan kapasitas tinggi dan daya tahan lama', 500000, 20, 'aki.jpg'),
('Velg Racing', 'Velg racing sporty untuk tampilan mobil yang agresif', 2500000, 10, 'velg.jpg'),
('Ban Mobil', 'Ban mobil all season dengan grip yang baik', 800000, 30, 'ban.jpg'),
('Kampas Rem', 'Kampas rem berkualitas untuk pengereman yang aman', 200000, 40, 'kampas_rem.jpg'),
('Shockbreaker', 'Shockbreaker untuk kenyamanan berkendara', 600000, 15, 'shockbreaker.jpg'),
('Lampu LED', 'Lampu LED untuk penerangan yang terang dan hemat energi', 150000, 60, 'lampu_led.jpg'),
('Spion Mobil', 'Spion mobil dengan desain modern dan fungsional', 300000, 25, 'spion.jpg');
