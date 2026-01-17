<?php
/**
 * Chatbot API Handler
 * AI-powered customer service chatbot for LuxeAuto Parts
 */

error_reporting(0);
ini_set('display_errors', 0);

while (ob_get_level()) ob_end_clean();

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();
include_once 'config.php';

$input = json_decode(file_get_contents('php://input'), true);
$message = isset($input['message']) ? trim($input['message']) : '';
$action = isset($input['action']) ? $input['action'] : 'chat';

$response = [
    'success' => true,
    'reply' => '',
    'quick_replies' => [],
    'product' => null
];

function getCartCount() {
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}

function getCartTotal() {
    global $pdo;
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        if (isset($products[$id])) {
            $total += $products[$id] * $qty;
        }
    }
    return $total;
}

function searchProducts($query) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, description, price, stock, image FROM products WHERE name LIKE ? OR description LIKE ? LIMIT 5");
    $stmt->execute(['%' . $query . '%', '%' . $query . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, description, price, stock, image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function processMessage($message) {
    global $pdo;
    $message = strtolower(trim($message));
    $cartCount = getCartCount();
    $cartTotal = getCartTotal();
    
    if (preg_match('/^(halo|hai|helo|hello|hi|hey|pagi|siang|sore|malam)$/', $message)) {
        return [
            'reply' => "Halo! Senang melihat Anda di LuxeAuto Parts. Saya adalah asisten virtual yang siap membantu Anda dengan produk, pesanan, atau pertanyaan lainnya. Ada yang bisa saya bantu?",
            'quick_replies' => [
                ['label' => 'Lihat Produk', 'action' => 'products'],
                ['label' => 'Cari Produk', 'action' => 'search'],
                ['label' => 'Bantuan Cart', 'action' => 'cart_help'],
                ['label' => 'Cara Checkout', 'action' => 'checkout_help']
            ]
        ];
    }
    
    if (preg_match('/(produk|cari|carikan|lihat|apa|tampil)/', $message)) {
        $searchTerms = preg_replace('/^(cari|tampil|lihat|apa|tunjukan)/i', '', $message);
        $searchTerms = trim($searchTerms);
        if (!empty($searchTerms) && strlen($searchTerms) > 2) {
            $products = searchProducts($searchTerms);
            if (!empty($products)) {
                $productList = "Saya menemukan beberapa produk untuk \"$searchTerms\":\n\n";
                foreach ($products as $p) {
                    $stockStatus = $p['stock'] > 0 ? 'Tersedia' : 'Habis';
                    $productList .= "- *{$p['name']}* - Rp " . number_format($p['price'], 0, ',', '.') . " ($stockStatus)\n";
                }
                return [
                    'reply' => $productList,
                    'quick_replies' => array_map(function($p) {
                        return ['label' => substr($p['name'], 0, 20) . '...', 'action' => 'product_' . $p['id']];
                    }, array_slice($products, 0, 3))
                ];
            }
        }
        return [
            'reply' => "Silakan browse produk kami di halaman Products. Ingin mencari produk tertentu atau melihat kategori?",
            'quick_replies' => [
                ['label' => 'Lihat Semua Produk', 'action' => 'products'],
                ['label' => 'Cari Produk', 'action' => 'search']
            ]
        ];
    }
    
    if (preg_match('/(harga|berapa|biaya)/', $message)) {
        $products = searchProducts($message);
        if (!empty($products)) {
            $productList = "Berikut informasi harga:\n\n";
            foreach ($products as $p) {
                $productList .= "- *{$p['name']}*: Rp " . number_format($p['price'], 0, ',', '.') . "\n";
            }
            return ['reply' => $productList, 'quick_replies' => []];
        }
        return ['reply' => "Untuk mengetahui harga produk tertentu, ketik nama produk. Contoh: \"harga oli mesin\"", 'quick_replies' => []];
    }
    
    if (preg_match('/(cart|keranjang|troli)/', $message)) {
        if ($cartCount > 0) {
            return [
                'reply' => "Keranjang belanja Anda memiliki *$cartCount item* dengan total Rp " . number_format($cartTotal, 0, ',', '.') . ".",
                'quick_replies' => [
                    ['label' => 'Lihat Keranjang', 'action' => 'view_cart'],
                    ['label' => 'Checkout Sekarang', 'action' => 'checkout'],
                    ['label' => 'Tambah Produk', 'action' => 'products']
                ]
            ];
        }
        return [
            'reply' => "Keranjang Anda masih kosong. Silakan browse produk kami dan tambahkan ke keranjang.",
            'quick_replies' => [['label' => 'Lihat Produk', 'action' => 'products']]
        ];
    }
    
    if (preg_match('/(gagal|tidak bisa|tidak berhasil|error|masalah)/', $message)) {
        return [
            'reply' => "Troubleshoot langkah-langkah:\n\n1. Refresh halaman dan coba lagi\n2. Cek koneksi internet\n3. Pastikan produk masih tersedia\n4. Hapus cache browser\n5. Coba browser lain\n\nJika masih bermasalah, hubungi customer service.",
            'quick_replies' => [
                ['label' => 'Coba Lagi', 'action' => 'retry'],
                ['label' => 'Hubungi CS', 'action' => 'contact']
            ]
        ];
    }
    
    if (preg_match('/(checkout|bayar|pembayaran|transaksi)/', $message)) {
        return [
            'reply' => "Panduan Checkout:\n\n1. Review keranjang belanja\n2. Klik \"Proceed to Checkout\"\n3. Isi data pengiriman (nama, email, telepon, alamat)\n4. Klik \"Pesan Sekarang\"\n5. Selesaikan pembayaran\n\nPesanan akan diproses setelah pembayaran terkonfirmasi.",
            'quick_replies' => [
                ['label' => 'Ke Checkout', 'action' => 'checkout_link'],
                ['label' => 'Metode Pembayaran', 'action' => 'payment_methods']
            ]
        ];
    }
    
    if (preg_match('/(bayar|payment|metode|transfer|qris|debit|kredit|invoice)/', $message)) {
        return [
            'reply' => "Metode Pembayaran:\n\n- Transfer Bank (BCA, Mandiri, BNI, BRI)\n- QRIS (QR code all payment)\n- Kartu Debit/Kredit (Visa, Mastercard)\n- Invoice/Bayar Nanti (untuk perusahaan)\n\nPembayaran diproses otomatis 24/7.",
            'quick_replies' => [
                ['label' => 'Ke Checkout', 'action' => 'checkout_link'],
                ['label' => 'Cara Bayar QRIS', 'action' => 'qris_help']
            ]
        ];
    }
    
    if (preg_match('/(qris|scan)/', $message)) {
        return [
            'reply' => "Cara Bayar QRIS:\n\n1. Selesaikan checkout\n2. Pilih \"QRIS\" sebagai metode pembayaran\n3. Akan muncul kode QR di layar\n4. Buka aplikasi bank/e-wallet\n5. Scan kode QR\n6. Konfirmasi pembayaran\n\nQRIS berlaku 24 jam.",
            'quick_replies' => [['label' => 'Ke Checkout', 'action' => 'checkout_link']]
        ];
    }
    
    if (preg_match('/(network|internet|koneksi)/', $message)) {
        return [
            'reply' => "Troubleshoot Koneksi:\n\n1. Cek signal WiFi/mobile data\n2. Refresh halaman (F5)\n3. Matikan VPN jika digunakan\n4. Hapus cache browser\n5. Coba browser berbeda\n\nPastikan koneksi stabil saat checkout.",
            'quick_replies' => [['label' => 'Coba Lagi', 'action' => 'retry']]
        ];
    }
    
    if (preg_match('/(server|500|404|invalid)/', $message)) {
        return [
            'reply' => "Masalah Teknis:\n\nJika Anda melihat error \"Invalid server response\" atau error 500:\n\n1. Tunggu 1-2 menit, lalu refresh\n2. Clear browser cache\n3. Coba perangkat/browser berbeda\n4. Hubungi CS jika berlanjut\n\nMohon maaf atas ketidaknyamanannya.",
            'quick_replies' => [
                ['label' => 'Coba Lagi', 'action' => 'retry'],
                ['label' => 'Hubungi CS', 'action' => 'contact']
            ]
        ];
    }
    
    if (preg_match('/(kirim|pengiriman|ongkir)/', $message)) {
        return [
            'reply' => "Informasi Pengiriman:\n\n- Gratis ongkir untuk pembelian Rp 500rb+\n- Area pengiriman: Seluruh Indonesia\n- Estimasi: Jawa 2-4 hari, Luar Jawa 4-7 hari\n- Partner: JNE, J&T, SiCepat\n\nNo. resi dikirim via email.",
            'quick_replies' => [['label' => 'Ke Checkout', 'action' => 'checkout_link']]
        ];
    }
    
    if (preg_match('/(kontak|cs|whatsapp|telp|hubungi)/', $message)) {
        return [
            'reply' => "Hubungi Kami:\n\n- WhatsApp: +62 812-3456-7890\n- Email: info@luxeautoparts.com\n- Live Chat: Klik ikon chat di pojok kanan\n\nJam Operasional: 08.00 - 21.00 WIB",
            'quick_replies' => [['label' => 'Kirim Pesan', 'action' => 'whatsapp']]
        ];
    }
    
    if (preg_match('/(faq|tanya|bantuan|help)/', $message)) {
        return [
            'reply' => "FAQ:\n\n1. Produk bergaransi? Ya, 1 tahun\n2. Bisa return? Ya, dalam 7 hari jika rusak\n3. Cara pesan? Pilih produk -> Cart -> Checkout\n4. Aman bayar online? Ya, terenkripsi\n5. Pengiriman? 2-7 hari kerja",
            'quick_replies' => [
                ['label' => 'Cara Pesan', 'action' => 'howto_order'],
                ['label' => 'Lihat Produk', 'action' => 'products']
            ]
        ];
    }
    
    if (preg_match('/(return|refund|kembali|garansi)/', $message)) {
        return [
            'reply' => "Pengembalian & Garansi:\n\n- Maksimal 7 hari setelah terima\n- Produk harus dalam kondisi semula\n- Sertakan kemasan & nota\n- Garansi pabrik 1-2 tahun\n\nHubungi CS untuk proses pengembalian.",
            'quick_replies' => [['label' => 'Hubungi CS', 'action' => 'contact']]
        ];
    }
    
    if (preg_match('/(bagaimana|cara|how to|step|langkah)/', $message)) {
        return [
            'reply' => "Cara Belanja:\n\n1. Pilih produk yang diinginkan\n2. Klik \"Add to Cart\"\n3. Review keranjang\n4. Klik \"Proceed to Checkout\"\n5. Isi data pengiriman\n6. Pilih metode pembayaran\n7. Klik \"Pesan Sekarang\"\n8. Selesaikan pembayaran",
            'quick_replies' => [
                ['label' => 'Lihat Produk', 'action' => 'products'],
                ['label' => 'Ke Checkout', 'action' => 'checkout_link']
            ]
        ];
    }
    
    if (preg_match('/(terima kasih|makasih|thank|suks)/', $message)) {
        return ['reply' => "Sama-sama! Senang bisa membantu. Selamat berbelanja di LuxeAuto Parts!", 'quick_replies' => []];
    }
    
    if (preg_match('/(dadah|byeboke|bye|tinggal)/', $message)) {
        return ['reply' => "Sampai jumpa kembali! Terima kasih telah mengunjungi LuxeAuto Parts.", 'quick_replies' => []];
    }
    
    $products = searchProducts($message);
    if (!empty($products)) {
        return [
            'reply' => "Mungkin maksud Anda:\n\n" . implode("\n", array_map(function($p) {
                return "- *{$p['name']}* - Rp " . number_format($p['price'], 0, ',', '.');
            }, array_slice($products, 0, 3))),
            'quick_replies' => []
        ];
    }
    
    return [
        'reply' => "Maaf, saya belum memahami. Saya bisa membantu dengan:\n- Informasi produk & harga\n- Masalah keranjang belanja\n- Panduan checkout & pembayaran\n- Pertanyaan umum\n\nSilakan pilih menu di bawah atau hubungi CS.",
        'quick_replies' => [
            ['label' => 'Lihat Produk', 'action' => 'products'],
            ['label' => 'Bantuan Cart', 'action' => 'cart_help'],
            ['label' => 'Hubungi CS', 'action' => 'contact']
        ]
    ];
}

if ($action === 'product') {
    $productId = isset($input['product_id']) ? (int)$input['product_id'] : 0;
    $product = getProduct($productId);
    if ($product) {
        $response['reply'] = "*{$product['name']}*\n\nDeskripsi: {$product['description']}\n\nHarga: Rp " . number_format($product['price'], 0, ',', '.') . "\nStok: " . ($product['stock'] > 0 ? "{$product['stock']} unit" : "Maaf, stok habis");
        $response['product'] = $product;
        $response['quick_replies'] = [
            ['label' => 'Tambah ke Cart', 'action' => 'add_cart_' . $product['id']],
            ['label' => 'Lihat Produk Lain', 'action' => 'products']
        ];
    } else {
        $response['reply'] = 'Produk tidak ditemukan.';
        $response['quick_replies'] = [['label' => 'Lihat Semua Produk', 'action' => 'products']];
    }
} elseif ($action === 'quick_reply') {
    $replyAction = isset($input['reply_action']) ? $input['reply_action'] : '';
    switch ($replyAction) {
        case 'products':
            $response['reply'] = 'Silakan browse koleksi produk kami di halaman Products.';
            break;
        case 'cart_help':
            $cartCount = getCartCount();
            $cartTotal = getCartTotal();
            $response['reply'] = $cartCount > 0 
                ? "Keranjang Anda: *$cartCount item*, Rp " . number_format($cartTotal, 0, ',', '.')
                : "Keranjang kosong. Tambahkan produk untuk memulai!";
            $response['quick_replies'] = [['label' => 'Lihat Keranjang', 'action' => 'view_cart']];
            break;
        case 'checkout_help':
            $response['reply'] = "Panduan checkout:\n1. Review keranjang\n2. Klik Checkout\n3. Isi data pengiriman\n4. Pilih pembayaran\n5. Pesan Sekarang";
            break;
        case 'payment_methods':
            $response['reply'] = "Metode pembayaran: Transfer Bank, QRIS, Kartu Debit/Kredit, Invoice.";
            break;
        case 'contact':
            $response['reply'] = "Hubungi Kami:\n- WhatsApp: +62 812-3456-7890\n- Email: info@luxeautoparts.com\n\nJam: 08.00 - 21.00 WIB";
            break;
        case 'faq':
            $response['reply'] = "FAQ:\n1. Garansi 1 tahun\n2. Return 7 hari\n3. Pengiriman 2-7 hari\n\nAda yang bisa bantu?";
            break;
        case 'retry':
            $response['reply'] = 'Silakan coba lagi. Jika masalah berlanjut, hubungi CS.';
            break;
        case 'home':
            $response['reply'] = 'Kembali ke beranda. Ada yang bisa saya bantu?';
            break;
        default:
            $result = processMessage($replyAction);
            $response['reply'] = $result['reply'];
            $response['quick_replies'] = $result['quick_replies'];
    }
} else {
    $result = processMessage($message);
    $response['reply'] = $result['reply'];
    $response['quick_replies'] = $result['quick_replies'];
}

echo json_encode($response);
exit;

