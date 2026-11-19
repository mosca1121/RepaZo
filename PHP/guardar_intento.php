<?php
declare(strict_types=1);
session_start();
header("Content-Type: application/json; charset=utf-8");

function responder(int $estado, array $datos): never {
    http_response_code($estado);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION["id_usuario"], $_SESSION["grado"])) {
    responder(401, ["ok" => false, "mensaje" => "Debes iniciar sesión para guardar el resultado."]);
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["ok" => false, "mensaje" => "Método no permitido."]);
}

require_once __DIR__ . "/conexiones.php";

$idUsuario = (int)$_SESSION["id_usuario"];
$gradoUsuario = (int)$_SESSION["grado"];
$idQuiz = filter_var($_POST["id_quiz"] ?? null, FILTER_VALIDATE_INT);
$correctas = filter_var($_POST["respuestas_correctas"] ?? null, FILTER_VALIDATE_INT);
$incorrectas = filter_var($_POST["respuestas_incorrectas"] ?? null, FILTER_VALIDATE_INT);
$tiempo = filter_var($_POST["tiempo_segundos"] ?? 0, FILTER_VALIDATE_INT);

if (!$idQuiz || $correctas === false || $incorrectas === false || $tiempo === false ||
    $correctas < 0 || $incorrectas < 0 || $tiempo < 0 || ($correctas + $incorrectas) < 1) {
    responder(400, ["ok" => false, "mensaje" => "Los datos del resultado no son válidos."]);
}

try {
    $consulta = $conexion->prepare("SELECT grado FROM quizzes WHERE id_quiz = ? LIMIT 1");
    $consulta->bind_param("i", $idQuiz);
    $consulta->execute();
    $consulta->bind_result($gradoQuiz);

    if (!$consulta->fetch()) {
        $consulta->close();
        responder(404, ["ok" => false, "mensaje" => "No se encontró ese quiz en la base de datos."]);
    }
    $consulta->close();

    if ((int)$gradoQuiz !== $gradoUsuario) {
        responder(403, ["ok" => false, "mensaje" => "Este quiz no corresponde a tu grado."]);
    }

    $total = $correctas + $incorrectas;
    $porcentaje = round(($correctas / $total) * 100, 2);

    $insertar = $conexion->prepare(
        "INSERT INTO intentos_quizzes
         (id_usuario, id_quiz, fecha_fin, porcentaje, respuestas_correctas, respuestas_incorrectas, tiempo_segundos)
         VALUES (?, ?, NOW(), ?, ?, ?, ?)"
    );
    $insertar->bind_param("iidiii", $idUsuario, $idQuiz, $porcentaje, $correctas, $incorrectas, $tiempo);
    $insertar->execute();
    $idIntento = $insertar->insert_id;
    $insertar->close();

    responder(200, [
        "ok" => true,
        "mensaje" => "Resultado guardado correctamente.",
        "id_intento" => $idIntento,
        "porcentaje" => $porcentaje
    ]);
} catch (mysqli_sql_exception $e) {
    error_log("Error guardando intento RepaZo: " . $e->getMessage());
    responder(500, ["ok" => false, "mensaje" => "No se pudo guardar el resultado. Verifica la estructura de intentos_quizzes."]);
}
