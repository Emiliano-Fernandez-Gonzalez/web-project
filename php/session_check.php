<?php
session_start();

if (!isset($_SESSION["id"])) {
    echo "NO_ACTIVA";
    exit;
}

require "db.php";

$id = $_SESSION["id"];


$stmt = $conn->prepare("SELECT temario_json FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($temario_json);
$stmt->fetch();
$stmt->close();

if ($temario_json !== null && $temario_json !== "") {
    echo "EX_PLAN";
} else {
    echo "NO_PLAN";
}
