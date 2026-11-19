<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/conexiones.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.html");
    exit;
}

$correo = strtolower(trim((string)($_POST["correo"] ?? "")));
$password = (string)($_POST["password"] ?? "");

if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === "") {
    header("Location: ../login.html?error=datos");
    exit;
}

try {
    $consulta = $conexion->prepare(
        "SELECT id_usuario, nombre, contrasena_hash, grado FROM usuarios WHERE correo = ? LIMIT 1"
    );
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $consulta->bind_result($idUsuario, $nombre, $hash, $grado);

    if (!$consulta->fetch() || !password_verify($password, $hash)) {
        $consulta->close();
        header("Location: ../login.html?error=credenciales");
        exit;
    }
    $consulta->close();

    session_regenerate_id(true);
    $_SESSION["id_usuario"] = (int)$idUsuario;
    $_SESSION["nombre"] = (string)$nombre;
    $_SESSION["grado"] = (int)$grado;

    // Redirige al panel principal de módulos o contenido
    header("Location: ../panel.php");
    exit;
} catch (mysqli_sql_exception $e) {
    error_log("Error al iniciar sesión en RepaZo: " . $e->getMessage());
    header("Location: ../login.html?error=servidor");
    exit;
}