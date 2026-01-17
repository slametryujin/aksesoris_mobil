<?php
// Cart API - Self-contained, pure JSON only

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
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

$response = ['success' => false, 'message' => '', 'cart_count' => 0];

if ($action === 'add') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        $response['success'] = true;
        $response['message'] = 'Item added to cart';
        $response['cart_count'] = array_sum($_SESSION['cart']);
    } else {
        $response['message'] = 'Invalid product ID';
    }
}
elseif ($action === 'get_count') {
    $response['success'] = true;
    $response['message'] = 'OK';
    $response['cart_count'] = array_sum($_SESSION['cart']);
}
elseif ($action === 'remove') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    $response['success'] = true;
    $response['message'] = 'Item removed';
    $response['cart_count'] = array_sum($_SESSION['cart']);
}
elseif ($action === 'update') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    
    if ($product_id > 0) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    $response['success'] = true;
    $response['message'] = 'Cart updated';
    $response['cart_count'] = array_sum($_SESSION['cart']);
}
elseif ($action === 'clear') {
    $_SESSION['cart'] = [];
    $response['success'] = true;
    $response['message'] = 'Cart cleared';
    $response['cart_count'] = 0;
}
elseif ($action === 'get') {
    $response['success'] = true;
    $response['message'] = 'OK';
    $response['cart_count'] = array_sum($_SESSION['cart']);
    $response['items'] = [];
    $response['total'] = 0;
}
else {
    $response['message'] = 'Invalid action';
}

echo json_encode($response);
exit;

