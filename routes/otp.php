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

// Ambil ID order
$orderId = $_GET['id'] ?? null;
if (!$orderId) {
    echo json_encode(["status" => false, "msg" => "Parameter 'id' wajib diisi."]);
    exit;
}

// Bangun URL API VirtuSIM
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) .
       "&action=status&id=" . urlencode($orderId);

// CURL request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

// Handle response
if ($response) {
    $data = json_decode($response, true);
    if (!$data) {
        echo json_encode(["status" => false, "msg" => "Gagal decode response JSON."]);
    } else {
        // Bisa diolah lagi di sini kalau mau custom output
        echo json_encode($data);
    }
} else {
    echo json_encode(["status" => false, "msg" => "CURL Error: $err"]);
}
?>
