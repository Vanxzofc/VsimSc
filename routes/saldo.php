<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil API key dari URL
if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode(["status" => false, "msg" => "API Key wajib diisi melalui URL."]);
    exit;
}
$apikey = $_GET['apikey'];

// Bangun URL API
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) . "&action=balance";

// Kirim request ke VirtuSIM
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
curl_close($ch);

// Tampilkan hasil atau fallback error
echo $response ?: json_encode(["status" => false, "msg" => "Gagal mengambil saldo dari server."]);
