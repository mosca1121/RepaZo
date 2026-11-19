<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";

if (isset($_SESSION["id_usuario"])) {
    header("Location: " . (($_SESSION["rol"] ?? "") === "admin" ? "../admin-repazo/admin.php" : "panel.php"));
    exit;
}

$mensajesError = [
    "datos"        => "Por favor ingresa un correo válido y tu contraseña.",
    "credenciales" => "Correo o contraseña incorrectos.",
    "servidor"     => "Error temporal en el servidor. Verifica tu conexión a la base de datos.",
];
$errorCodigo = (string)($_GET["error"] ?? "");
$paginaActiva = "login";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Iniciar Sesión</title>
    <link href="../assets/css/codigo.css" rel="stylesheet">
    <link href="../assets/css/login.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="login-container">
        <section class="login-box" aria-labelledby="titulo-login">
            <header class="logo" style="justify-content:center; margin-bottom:10px;">
                <h1 style="color:#1e293b; font-size:28px;">📚 RepaZo</h1>
            </header>
            <p style="color:#64748b; margin-bottom:14px;">Tu plataforma de aprendizaje</p>
            <h2 id="titulo-login">Iniciar Sesión</h2>

            <?php if (isset($mensajesError[$errorCodigo])): ?>
                <p role="alert" style="margin:12px 0; padding:10px; border-radius:8px; background:#fef2f2; color:#991b1b; border:1px solid #fca5a5; font-size:14px;">
                    <?= e($mensajesError[$errorCodigo]) ?>
                </p>
            <?php endif; ?>

            <form action="../acciones/iniciar_sesion.php" method="POST">
                <input id="correo" name="correo" placeholder="Correo electrónico" type="email" required>
                <input id="password" name="password" placeholder="Contraseña" type="password" required>
                <button type="submit">Entrar</button>
            </form>

            <p class="separador"><span>o</span></p>
            <p class="registro">
                ¿No tienes cuenta? <a href="registro.php">Crear cuenta</a>
            </p>
        </section>
    </main>
</body>
</html>
