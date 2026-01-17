<?php
include 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message_text = $_POST['message'];

    // In a real application, you would send an email or save to database
    // For now, just show a success message
    $message = 'Terima kasih atas pesan Anda. Kami akan segera menghubungi Anda!';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak & Tentang - Aksesoris Mobil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>Aksesoris Mobil</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Produk</a></li>
                    <li><a href="cart.php">Keranjang</a></li>
                    <li><a href="contact.php">Kontak</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="contact">
            <div class="container">
                <h2>Kontak Kami</h2>
                <?php if ($message): ?>
                    <p class="success-message"><?php echo $message; ?></p>
                <?php endif; ?>
                <div class="contact-content">
                    <div class="contact-info">
                        <h3>Informasi Kontak</h3>
                        <p><strong>Alamat:</strong> Jl. Raya Mobil No. 123, Jakarta</p>
                        <p><strong>Telepon:</strong> (021) 123-4567</p>
                        <p><strong>Email:</strong> info@aksesorismobil.com</p>
                        <p><strong>Jam Operasional:</strong> Senin - Sabtu, 08:00 - 17:00</p>
                    </div>
                    <form method="post" class="contact-form" onsubmit="return validateContactForm()">
                        <h3>Kirim Pesan</h3>
                        <div class="form-group">
                            <label for="name">Nama:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subjek:</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Pesan:</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="about">
            <div class="container">
                <h2>Tentang Kami</h2>
                <p>Aksesoris Mobil adalah toko online terpercaya yang menyediakan berbagai aksesoris mobil berkualitas tinggi. Kami berkomitmen untuk memberikan pelayanan terbaik dan produk original dengan harga kompetitif.</p>
                <p>Dengan pengalaman bertahun-tahun di industri otomotif, kami memahami kebutuhan pelanggan dan selalu berusaha memberikan yang terbaik untuk kendaraan Anda.</p>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Aksesoris Mobil. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
    <script src="js/chatbot.js"></script>
