<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sesionIniciada = isset($_SESSION["id_usuario"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Repazo | Iniciar Sesión</title>
    <link href="CSS/codigo.css" rel="stylesheet">
    <link href="CSS/login.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
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
                <li><a class="active" href="login.php">Login</a></li>
                <li><a class="registro" href="registro.php">Registrarse</a></li>
            <?php else: ?>
                <!-- Menú privado (Con sesión activa) -->
                <li><a href="moduloscodigo.php">Módulos</a></li>
                <li><a href="quizes.php">Quizzes</a></li>
                <li><a href="curiosidades.php">Datos Curiosos</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <h1>📚 Repazo</h1>
                <p>Tu plataforma de aprendizaje</p>
            </div>
            <h2>Iniciar Sesión</h2>
            <form action="php/iniciar_sesion.php" method="POST">
                <input id="correo" name="correo" placeholder="Correo electrónico" type="email" required>
                <input id="password" name="password" type="password" placeholder="Contraseña" required>
                <button type="submit">Entrar</button>
            </form>
            <div class="separador">
                <span>o</span>
            </div>
            <p class="registro">
                ¿No tienes cuenta? <a href="registro.html">Crear cuenta</a>
            </p>
        </div>
    </div>
</body>
</html>