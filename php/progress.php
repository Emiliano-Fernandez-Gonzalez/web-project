<?php
session_start();
require "db.php";


if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$user_id = $_SESSION['id'];


$stmt = $conn->prepare("SELECT temario_json, progreso_json FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($temario_json, $progreso_json);
$stmt->fetch();
$stmt->close();

$conn->close();


echo json_encode([
    "temario" => json_decode($temario_json, true),
    "progreso" => json_decode($progreso_json, true)
]);
?>
