<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Ambil API key dari URL
if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode(["status" => false, "msg" => "API Key tidak ditemukan di URL."]);
    exit;
}

$apiKey = $_GET['apikey'];
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apiKey) . "&action=active_order";

// CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'VirtuSIM PHP Client');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Cek hasil
if ($response) {
    $data = json_decode($response, true);
    if (!$data) {
        echo json_encode(["status" => false, "msg" => "Gagal decode JSON dari server.", "raw" => $response]);
    } else {
        echo json_encode($data);
    }
} else {
    echo json_encode([
        "status" => false,
        "msg" => "Gagal menghubungi server VirtuSIM.",
        "error" => $error,
        "http_code" => $httpCode
    ]);
}
