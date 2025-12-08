<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['id'])) {
    echo json_encode(["error" => "No session"]);
    exit;
}

$user_id = $_SESSION["id"];
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data["day"])) {
    echo json_encode(["error" => "No day"]);
    exit;
}
// day viene de question-page (guardado en la sesión)
$dayIndex = intval($data["day"]);

$stmt = $conn->prepare("SELECT progreso_json FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($progreso_json);
$stmt->fetch();
$stmt->close();


if (!$progreso_json) {
    $progreso = [
        "0" => false,
        "1" => false,
        "2" => false,
        "3" => false,
        "4" => false,
        "5" => false,
        "6" => false
    ];
} else {
    $progreso = json_decode($progreso_json, true);
}


$progreso[(string)$dayIndex] = true;


$newJson = json_encode($progreso, JSON_UNESCAPED_UNICODE);

$stmt2 = $conn->prepare("UPDATE usuarios SET progreso_json = ? WHERE id = ?");
$stmt2->bind_param("si", $newJson, $user_id);
$stmt2->execute();
$stmt2->close();

echo json_encode(["ok" => true]);
