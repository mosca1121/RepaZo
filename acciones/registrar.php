<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";

function volverRegistro(string $mensaje): never {
    header("Location: ../paginas/registro.php?mensaje=" . urlencode($mensaje));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../paginas/registro.php");
    exit;
}

$nombre = trim((string)($_POST["nombre"] ?? ""));
$apellido = trim((string)($_POST["apellido"] ?? ""));
$correo = strtolower(trim((string)($_POST["correo"] ?? "")));
$password = (string)($_POST["password"] ?? "");
$confirmar = (string)($_POST["confirmar"] ?? "");
$grado = filter_var($_POST["grado"] ?? null, FILTER_VALIDATE_INT);

if ($nombre === "" || $apellido === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    volverRegistro("datos");
}
if (strlen($password) < 4) {
    volverRegistro("contrasena");
}
if ($password !== $confirmar) {
    volverRegistro("confirmar");
}
if (!in_array($grado, [10, 11], true)) {
    volverRegistro("grado");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $consulta = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? LIMIT 1");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows > 0) {
        $consulta->close();
        volverRegistro("existe");
    }
    $consulta->close();

    $insertar = $conexion->prepare(
        "INSERT INTO usuarios (nombre, apellido, correo, contrasena_hash, grado, rol) VALUES (?, ?, ?, ?, ?, 'estudiante')"
    );
    $insertar->bind_param("ssssi", $nombre, $apellido, $correo, $hash, $grado);
    $insertar->execute();
    $idUsuario = (int)$insertar->insert_id;
    $insertar->close();

    session_regenerate_id(true);
    $_SESSION["id_usuario"] = $idUsuario;
    $_SESSION["nombre"] = $nombre . " " . $apellido;
    $_SESSION["grado"] = $grado;
    $_SESSION["rol"] = "estudiante";

    header("Location: ../paginas/panel.php");
    exit;
} catch (mysqli_sql_exception $e) {
    error_log("Error registro RepaZo: " . $e->getMessage());
    volverRegistro("servidor");
}
