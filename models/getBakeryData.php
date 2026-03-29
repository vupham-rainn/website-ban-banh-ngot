<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "doan_banbanh");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// --- THÔNG TIN TIỆM ---
$bakeryInfo = [
    "name" => "Tiệm Bánh CANVAS",
    "address" => "123 Xa lộ Hà Nội - TP.HCM",
    "open_hours" => "8:00 - 21:00",
    "hotline" => "0397939790",
    "description" => "Chuyên bánh kem, mousse, cookies, pastry và bánh theo mùa."
];

// --- DANH MỤC ---
$categories = [];
$q1 = $conn->query("SELECT category_id, name FROM categories WHERE status = 1");

if ($q1) {
    while ($row = $q1->fetch_assoc()) {
        $categories[] = $row;
    }
}

// --- SẢN PHẨM ---
$products = [];
$q2 = $conn->query("
    SELECT product_id, name, price, sale_price, short_description 
    FROM products 
    WHERE status = 1
");

if ($q2) {
    while ($row = $q2->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

// TRẢ JSON
echo json_encode([
    "store" => $bakeryInfo,
    "categories" => $categories,
    "products" => $products
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
