<?php
session_start();
require_once "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    die("No hay usuario logueado");
}

$user_id = $_SESSION["id"];


$json = file_get_contents("php://input");
$data = json_decode($json, true);

$dayIndex = $data["day"] ?? null;
if ($dayIndex === null) {
    die("Falta dayIndex");
}

$stmt = $conn->prepare("SELECT temario_json FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($temario_json);
$stmt->fetch();
$stmt->close();

$temario = json_decode($temario_json, true);


$tema      = $temario["plan"][$dayIndex]["tema"];
$lenguaje  = $temario["lenguaje"] ?? "Idioma desconocido";
$nivel     = $temario["nivel"] ?? "Nivel desconocido";
$objetivo  = $temario["objetivo"] ?? "Objetivo desconocido";


$unique = rand(100000, 999999);


$apiUrl = "https://kevin-apocopic-edna.ngrok-free.dev/api/generate";

$prompt = <<<PROMPT
Eres un generador de ejercicios de aprendizaje del idioma "$lenguaje". 
No eres un generador de conocimiento general. 
PROHIBIDO generar preguntas sobre países, capitales, banderas, historia, geografía o cultura general.

Si intentas generar cultura general → Debes devolver un JSON válido con ejercicios de idioma.

Genera SOLO el siguiente JSON. NO INCLUYAS nada fuera del JSON:

{
  "preguntas": [
    { "pregunta": "", "opciones": ["", "", ""], "correcta": "" },
    { "pregunta": "", "opciones": ["", "", ""], "correcta": "" },
    { "pregunta": "", "opciones": ["", "", ""], "correcta": "" }
  ]
}

RESTRICCIONES ESTRICTAS:
- Todas las preguntas deben ser para APRENDER el idioma "$lenguaje".
- NO puedes generar preguntas sobre capitales, países o cultura.
- NO pueden ser preguntas 
- Tema del día: "$tema".
- Nivel del usuario: "$nivel".
- Objetivo: "$objetivo".

TIPOS DE EJERCICIO PERMITIDOS (elige uno diferente para cada pregunta):
1. Traducción del español al $lenguaje.
2. Traducción del $lenguaje al español.
3. Completar una frase en $lenguaje.
4. Elegir la palabra correcta relacionada al tema "$tema".
5. Seleccionar el sinónimo correcto en $lenguaje.

FORMATO ESTRICTO:
- NO se permiten ejercicios de un tipo diferente a los permitidos.
- Pregunta SIEMPRE en español.
- Al menos 1 opción debe estar en el idioma "$lenguaje".
- "correcta" DEBE coincidir exactamente con una de las opciones.
- Debes generar contenido VARIADO cada vez que ejecutes.
- JSON DEBE ser 100% válido.
- Está TERMINANTEMENTE PROHIBIDO generar preguntas de capitales.

Si no puedes cumplir cualquiera de estas reglas, DEBES regenerar internamente la pregunta hasta cumplirlas.
PROMPT;


$body = json_encode([
    "model" => "qwen-json",
    "prompt" => $prompt,
    "format" => "json",
    "stream" => false,
    "temperature" => 0.9,
    "top_p" => 0.9,
    "repeat_penalty" => 2.2,
    "seed" => null
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

// validar
$data = json_decode($response, true);

if (!isset($data["response"])) {
    die("Error: respuesta vacía del modelo");
}

$preguntas = json_decode($data["response"], true);

if (!isset($preguntas["preguntas"]) || count($preguntas["preguntas"]) !== 3) {
    die("Error: JSON inválido generado");
}

header("Content-Type: application/json");
echo json_encode($preguntas, JSON_UNESCAPED_UNICODE);
?>
