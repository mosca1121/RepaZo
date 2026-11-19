<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../paginas/login.php");
    exit;
}

$correo = strtolower(trim((string)($_POST["correo"] ?? "")));
$password = (string)($_POST["password"] ?? "");

if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === "") {
    header("Location: ../paginas/login.php?error=datos");
    exit;
}

try {
    $stmt = $conexion->prepare(
        "SELECT id_usuario, nombre, apellido, contrasena_hash, grado, rol FROM usuarios WHERE correo = ? LIMIT 1"
    );
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($idUsuario, $nombre, $apellido, $hash, $grado, $rol);

    if (!$stmt->fetch() || !password_verify($password, $hash)) {
        $stmt->close();
        header("Location: ../paginas/login.php?error=credenciales");
        exit;
    }
    $stmt->close();

    session_regenerate_id(true);
    $_SESSION["id_usuario"] = (int)$idUsuario;
    $_SESSION["nombre"] = trim($nombre . " " . $apellido);
    $_SESSION["grado"] = $grado !== null ? (int)$grado : null;
    $_SESSION["rol"] = (string)$rol;

    if ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "administrador") {
        header("Location: ../admin-repazo/admin.php");
    } else {
        header("Location: ../paginas/panel.php");
    }
    exit;
} catch (mysqli_sql_exception $e) {
    error_log("Error login RepaZo: " . $e->getMessage());
    header("Location: ../paginas/login.php?error=servidor");
    exit;
}