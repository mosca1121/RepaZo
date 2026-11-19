<?php
declare(strict_types=1);
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../includes/conexiones.php";

function responder(int $estado, array $datos): never {
    http_response_code($estado);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION["id_usuario"])) {
    responder(401, ["ok" => false, "mensaje" => "Debes iniciar sesión para guardar el resultado."]);
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["ok" => false, "mensaje" => "Método no permitido."]);
}

$idUsuario = (int)$_SESSION["id_usuario"];
$idQuiz = filter_var($_POST["id_quiz"] ?? null, FILTER_VALIDATE_INT);
$correctas = filter_var($_POST["respuestas_correctas"] ?? null, FILTER_VALIDATE_INT);
$incorrectas = filter_var($_POST["respuestas_incorrectas"] ?? null, FILTER_VALIDATE_INT);
$tiempo = filter_var($_POST["tiempo_segundos"] ?? 0, FILTER_VALIDATE_INT);

if (!$idQuiz || $correctas === false || $incorrectas === false || $tiempo === false ||
    $correctas < 0 || $incorrectas < 0 || $tiempo < 0 || ($correctas + $incorrectas) < 1) {
    responder(400, ["ok" => false, "mensaje" => "Los datos del resultado no son válidos."]);
}

try {
    $total = $correctas + $incorrectas;
    $porcentaje = round(($correctas / $total) * 100, 2);

    $insertar = $conexion->prepare(
        "INSERT INTO intentos_quizzes
         (id_usuario, id_quiz, fecha_fin, porcentaje, respuestas_correctas, respuestas_incorrectas, tiempo_segundos)
         VALUES (?, ?, NOW(), ?, ?, ?, ?)"
    );
    $insertar->bind_param("iidiii", $idUsuario, $idQuiz, $porcentaje, $correctas, $incorrectas, $tiempo);
    $insertar->execute();
    $idIntento = (int)$insertar->insert_id;
    $insertar->close();

    responder(200, [
        "ok" => true,
        "mensaje" => "Resultado guardado correctamente.",
        "id_intento" => $idIntento,
        "porcentaje" => $porcentaje
    ]);
} catch (mysqli_sql_exception $e) {
    error_log("Error guardando intento RepaZo: " . $e->getMessage());
    responder(500, ["ok" => false, "mensaje" => "No se pudo guardar el resultado."]);
}
