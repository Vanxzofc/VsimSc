<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil API Key dari URL
if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode(["status" => false, "msg" => "API key wajib diisi melalui URL."]);
    exit;
}
$apikey = $_GET['apikey'];

// Bangun URL
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) . "&action=services&country=&service=wa";

// CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
curl_close($ch);

// Langsung lempar balik JSON original
echo $response ?: json_encode(["status" => false, "msg" => "Gagal ambil data layanan"]);
