<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil API Key dari URL
if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode([
        "status" => false,
        "msg" => "API key wajib diisi di URL."
    ]);
    exit;
}
$apikey = $_GET['apikey'];

// Bangun URL
$url = "https://virtusim.com/api/v2/json.php?action=balance_logs&api_key=" . urlencode($apikey);

// CURL request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);

// Handle response
if (!$response) {
    echo json_encode([
        "status" => false,
        "msg" => "Gagal mengambil mutasi"
    ]);
    exit;
}

$data = json_decode($response, true);

if (!$data || $data['status'] != true) {
    echo json_encode([
        "status" => false,
        "msg" => $data['msg'] ?? 'Data tidak ditemukan'
    ]);
    exit;
}

// Ambil 10 mutasi terakhir
$latest = array_slice($data['data'], 0, 10);

echo json_encode([
    "status" => true,
    "total" => count($latest),
    "mutasi" => $latest
]);
