<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sesionIniciada = isset($_SESSION["id_usuario"]);
$mensajesRegistro = [
    "datos" => "Revisa que tu nombre, apellido y correo sean válidos.",
    "contrasena" => "La contraseña debe tener al menos 4 caracteres.",
    "confirmar" => "Las contraseñas no coinciden.",
    "grado" => "Selecciona un grado válido.",
    "existe" => "Ya existe una cuenta con ese correo electrónico.",
    "servidor" => "No se pudo crear la cuenta por un error del servidor o de la base de datos. Revisa que MySQL esté iniciado y que exista la base de datos repazo.",
];
$codigoMensaje = (string)($_GET["mensaje"] ?? "");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Repazo | Crear Cuenta</title>
    <link href="CSS/codigo.css" rel="stylesheet">
    <link href="CSS/registro.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="registro-container">
        <header class="navbar">
    <div class="logo">
        <img alt="logo de Repazo" decoding="async" loading="lazy" src="IMAGENES/LOGOREPAZO.jpg">
        <h2>Repazo</h2>
    </div>
    <nav>
        <ul>
            <?php if (!$sesionIniciada): ?>
                <!-- Menú público (Sin sesión) -->
                <li><a href="codigo.php">Inicio</a></li>
                <li><a href="quienes-somos.php">Quiénes somos</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a class="active" href="registro.php">Registrarse</a></li>
            <?php else: ?>
                <!-- Menú privado (Con sesión activa) -->
                <li><a href="panel.php">Módulos</a></li>
                <li><a href="quizes.php">Quizzes</a></li>
                <li><a href="curiosidades.php">Datos Curiosos</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

        <div class="registro-box">
            <div class="logo">
                <div class="icono">📚</div>
                <h1>Repazo</h1>
                <p>Aprende, practica y mejora cada día</p>
            </div>
            <h2>Crear una cuenta</h2>
            <?php if (isset($mensajesRegistro[$codigoMensaje])): ?>
                <p class="registro-mensaje" role="alert"><?php echo htmlspecialchars($mensajesRegistro[$codigoMensaje], ENT_QUOTES, "UTF-8"); ?></p>
            <?php endif; ?>
            <form id="formRegistro" action="PHP/registrar.php" method="POST">
                <div class="fila">
                    <input id="nombre" name="nombre" placeholder="Nombre" required type="text">
                    <input id="apellido" name="apellido" placeholder="Apellido" required type="text">
                </div>
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
                ¿Ya tienes cuenta? <a href="login.html">Inicia sesión</a>
            </p>
        </div>
    </div>
</body>
</html>