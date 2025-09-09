<?php
header('Content-Type: application/json');

if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode([
        "status" => false,
        "msg" => "API key tidak ditemukan di URL."
    ]);
    exit;
}

$apikey = $_GET['apikey'];
$url = "https://virtusim.com/api/v2/json.php?api_key=" . $apikey . "&action=recent_activity";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode([
        "status" => false,
        "msg" => "Gagal mengambil aktivitas."
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

$latest = array_slice($data['data'], 0, 15); // batasin 15 aktivitas terakhir

echo json_encode([
    "status" => true,
    "total" => count($latest),
    "aktivitas" => $latest
]);
<?php
header('Content-Type: application/json');

if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    echo json_encode([
        "status" => false,
        "msg" => "API key tidak ditemukan di URL."
    ]);
    exit;
}

$apikey = $_GET['apikey'];
$url = "https://virtusim.com/api/v2/json.php?api_key=" . $apikey . "&action=recent_activity";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode([
        "status" => false,
        "msg" => "Gagal mengambil aktivitas."
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

$latest = array_slice($data['data'], 0, 15); // batasin 15 aktivitas terakhir

echo json_encode([
    "status" => true,
    "total" => count($latest),
    "aktivitas" => $latest
]);
