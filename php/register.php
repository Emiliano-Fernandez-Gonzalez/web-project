<?php
require "db.php";

$usuario = $_POST['usuario'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($usuario) || empty($email) || empty($password)) {
    die("Faltan datos");
}


$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("El correo ya está registrado");
}

$stmt->close();

$hashed_pass = password_hash($password, PASSWORD_DEFAULT);


$stmt = $conn->prepare(
    "INSERT INTO usuarios (usuario, email, password_hash, temario_json, progreso_json)
     VALUES (?, ?, ?, NULL, NULL)"
);
$stmt->bind_param("sss", $usuario, $email, $hashed_pass);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Error al registrar";
}

$stmt->close();
$conn->close();
?>
