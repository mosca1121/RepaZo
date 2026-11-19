<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";

if (isset($_SESSION["id_usuario"])) {
    header("Location: panel.php");
    exit;
}

$mensajesRegistro = [
    "datos"      => "Revisa que tu nombre, apellido y correo sean válidos.",
    "contrasena" => "La contraseña debe tener al menos 4 caracteres.",
    "confirmar"  => "Las contraseñas no coinciden.",
    "grado"      => "Selecciona un grado válido.",
    "existe"     => "Ya existe una cuenta con ese correo electrónico.",
    "servidor"   => "No se pudo crear la cuenta por un error del servidor o de la base de datos.",
];
$codigoMensaje = (string)($_GET["mensaje"] ?? "");
$paginaActiva = "registro";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Crear Cuenta</title>
    <link href="../assets/css/codigo.css" rel="stylesheet">
    <link href="../assets/css/registro.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="registro-container">
        <section class="registro-box" aria-labelledby="titulo-registro">
            <header style="text-align:center;">
                <span class="icono" aria-hidden="true" style="font-size:32px;">📚</span>
                <h1>RepaZo</h1>
                <p style="color:#64748b;">Aprende, practica y mejora cada día</p>
            </header>
            <h2 id="titulo-registro">Crear una cuenta</h2>

            <?php if (isset($mensajesRegistro[$codigoMensaje])): ?>
                <p class="registro-mensaje" role="alert"><?= e($mensajesRegistro[$codigoMensaje]) ?></p>
            <?php endif; ?>

            <form id="formRegistro" action="../acciones/registrar.php" method="POST">
                <fieldset class="fila" style="border:none; padding:0;">
                    <input id="nombre" name="nombre" placeholder="Nombre" required type="text">
                    <input id="apellido" name="apellido" placeholder="Apellido" required type="text">
                </fieldset>
                <input id="correo" name="correo" placeholder="Correo electrónico" required type="email">
                <input id="password" name="password" placeholder="Contraseña" required type="password">
                <input id="confirmar" name="confirmar" placeholder="Confirmar contraseña" required type="password">
                <select id="grado" name="grado" required>
                    <option value="">Selecciona tu grado</option>
                    <option value="10">Décimo (10°)</option>
                    <option value="11">Undécimo (11°)</option>
                </select>
                <button type="submit">Crear cuenta 🚀</button>
            </form>
            <p class="login">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
            </p>
        </section>
    </main>
</body>
</html>
