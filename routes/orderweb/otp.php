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
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => false, "msg" => "Parameter 'id' wajib diisi"]);
    exit;
}

// Ambil detail status (termasuk OTP)
$url = "https://virtusim.com/api/v2/json.php?api_key=" . urlencode($apikey) .
       "&action=status&id=" . urlencode($id);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(["status" => false, "msg" => "Gagal ambil OTP dari server", "error" => $err]);
    exit;
}

$data = json_decode($response, true);
if (!$data) {
    echo json_encode(["status" => false, "msg" => "Gagal decode OTP", "raw" => $response]);
} else {
    if (!isset($data['msg'])) {
        $data['msg'] = $data['status'] ? "Berhasil ambil OTP" : "Belum ada OTP";
    }
    echo json_encode($data);
}
