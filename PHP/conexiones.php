<?php
declare(strict_types=1);

$host = "localhost";
$usuario = "root";       
$password = "";         
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli($host, $usuario, $password, "repazo");
    $conexion->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Error de conexión: " . $e->getMessage());
    exit("Lo sentimos, hay un problema temporal con la base de datos.");
}