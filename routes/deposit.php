<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil API Key dari URL
if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode(["status" => false, "msg" => "API key wajib diisi di URL."]);
    exit;
}
$apikey = $_GET['apikey'];

// Ambil parameter
$method = $_GET['method'] ?? '20'; // default QRIS
$amount = $_GET['amount'] ?? null;
$phone  = $_GET['phone'] ?? null;

if (!$amount || !$phone) {
    echo json_encode(["status" => false, "msg" => "Parameter 'amount' dan 'phone' wajib diisi."]);
    exit;
}

// Build URL
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) .
       "&action=deposit" .
       "&method=" . urlencode($method) .
       "&amount=" . urlencode($amount) .
       "&phone=" . urlencode($phone);

// CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

// Output
if ($response) {
    $data = json_decode($response, true);
    if (!$data) {
        echo json_encode(["status" => false, "msg" => "Gagal decode JSON"]);
    } else {
        echo json_encode($data);
    }
} else {
    echo json_encode(["status" => false, "msg" => "CURL Error: $err"]);
}
