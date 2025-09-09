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

// Ambil ID dan STATUS dari parameter
$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(["status" => false, "msg" => "Parameter 'id' dan 'status' wajib diisi."]);
    exit;
}

// Bangun URL ke VirtuSIM
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) .
       "&action=set_status&id=" . urlencode($id) .
       "&status=" . urlencode($status);

// CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(["status" => false, "msg" => "Gagal ambil status order"]);
    exit;
}

$data = json_decode($response, true);
if (!$data) {
    echo json_encode(["status" => false, "msg" => "Respon tidak valid", "raw" => $response]);
} else {
    if (!isset($data['msg'])) {
        $data['msg'] = $data['status'] ? "Berhasil" : "Gagal";
    }
    echo json_encode($data);
}
