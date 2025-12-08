<?php
session_start();
require_once "db.php"; 

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['id'])) {
    die("No hay usuario logueado");
}

$user_id = $_SESSION['id'];
$lenguaje = $_POST["lenguaje"] ?? "";
$nivel = $_POST["nivel"] ?? "";
$objetivo = $_POST["objetivo"] ?? "";

if (!$lenguaje || !$nivel || !$objetivo) {
    die("Faltan datos");
}

//Cambiar la apiURL por una personal al usar la aplicación de forma local
$apiUrl = "https://kevin-apocopic-edna.ngrok-free.dev/api/generate";

$prompt = "
Genera un plan de aprendizaje en formato JSON ESTRICTO para 7 dias (7 temas).
NO incluyas texto fuera del JSON.

FORMATO OBLIGATORIO:
{
    \"lenguaje\": \"\",
    \"nivel\": \"\",
    \"objetivo\": \"\",
    \"plan\": [
        { \"clave\": \"T1\", \"tema\": \"\" }
    ]
}

Reglas:
- El tema debe ser conciso y ser un titulo breve, nada de explicaciones ni dos puntos (:), aproximadamente 4-5 palabras
- La clave SIEMPRE debe ser T1, T2, T3… según el orden.
- Solo incluir clave y tema.
- Nada de descripción, duración, subtemas u otros campos.

Genera el plan para:
Lenguaje: \"$lenguaje\"
Nivel: \"$nivel\"
Objetivo: \"$objetivo\"
";

$body = json_encode([
    "model" => "qwen-json",
    "prompt" => $prompt,
    "format" => "json",
    "stream" => false
]);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'ngrok-skip-browser-warning: true'
]);

$response = curl_exec($ch);
curl_close($ch);

//validar
if (!$response) {
    die("Error al generar el plan");
}


$data = json_decode($response, true);
if (!isset($data['response'])) {
    die("Respuesta de API inválida");
}

$plan_json = json_decode($data['response'], true);
if (!isset($plan_json['plan'])) {
    die("El plan no contiene datos");
}


$plan_generado = json_encode($plan_json, JSON_UNESCAPED_UNICODE);
$progreso_inicial = json_encode(array_fill(0, 7, false)); 

$stmt = $conn->prepare("
    UPDATE usuarios 
    SET temario_json = ?, progreso_json = ? 
    WHERE id = ?
");
$stmt->bind_param("ssi", $plan_generado, $progreso_inicial, $user_id);

if ($stmt->execute()) {
    header("Location: ../html/progress-page.html");
    exit;
} else {
    echo "Error al guardar el plan";
}

$stmt->close();
$conn->close();
?>
