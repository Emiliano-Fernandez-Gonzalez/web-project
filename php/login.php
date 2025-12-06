<?php
require "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$usuario = $_POST["usuario"] ?? "";
$password = $_POST["password"] ?? "";

if (!$usuario || !$password) {
    die("Faltan datos");
}

$stmt = $conn->prepare("SELECT id, password_hash FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Usuario no encontrado");
}

$stmt->bind_result($id, $pass_hash);
$stmt->fetch();

if (!password_verify($password, $pass_hash)) {
    die("Contraseña incorrecta");
}


session_start();
$_SESSION["id"] = $id;
$_SESSION["usuario"] = $usuario;

echo "OK";

$stmt->close();
$conn->close();
