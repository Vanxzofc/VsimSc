<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil API key dari URL
if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode(["status" => false, "msg" => "API key wajib diisi di URL."]);
    exit;
}
$apikey = $_GET['apikey'];

// Ambil parameter GET
$service = $_GET['service'] ?? null;
$operator = $_GET['operator'] ?? 'any';

if (!$service) {
    echo json_encode(["status" => false, "msg" => "Parameter 'service' dibutuhkan"]);
    exit;
}

// Build URL
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) .
       "&action=order&service=" . urlencode($service) .
       "&operator=" . urlencode($operator);

// CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

// Validasi dan echo hasil
if ($response) {
    $data = json_decode($response, true);
    if (!$data) {
        echo json_encode(["status" => false, "msg" => "Gagal decode JSON", "raw" => $response]);
    } else {
        echo json_encode($data);
    }
} else {
    echo json_encode([
        "status" => false,
        "msg" => "CURL error: $err",
        "http_code" => $httpCode
    ]);
}
