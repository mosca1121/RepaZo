<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "repazo";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli($host, $usuario, $password, $baseDatos);
    $conexion->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Error de conexión RepaZo: " . $e->getMessage());
    http_response_code(500);
    exit("Error temporal de conexión con la base de datos. Asegúrate de importar database/repazo.sql en phpMyAdmin.");
}

function e(?string $valor): string {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function requerirSesion(string $rutaLogin = "login.php"): void {
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: " . $rutaLogin);
        exit;
    }
}

function requerirAdmin(string $rutaInicio = "../paginas/login.php"): void {
    $rol = $_SESSION["rol"] ?? "";
    if (!isset($_SESSION["id_usuario"]) || ($rol !== "admin" && $rol !== "administrador")) {
        header("Location: " . $rutaInicio);
        exit;
    }
}
