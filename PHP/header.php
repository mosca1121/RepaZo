<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sesionIniciada = isset($_SESSION["id_usuario"]);
$gradoUsuario = $_SESSION["grado"] ?? null;
$nombreUsuario = $_SESSION["nombre"] ?? "";
?>

<header class="navbar">
    <div class="logo">
        <img src="IMAGENES/LOGOREPAZO.jpg" alt="logo de Repazo" />
        <h2>Repazo</h2>
    </div>
    <nav>
        <ul>
            <?php if (!$sesionIniciada): ?>
                <!-- MENÚ PÚBLICO -->
                <li><a href="index.php">Inicio</a></li>
                <li><a href="quienes_somos.php">Quiénes somos</a></li>
                <li><a href="login.html">Iniciar sesión</a></li>
                <li><a href="registro.html" class="registro">Registrarse</a></li>
            <?php else: ?>
                <!-- MENÚ PRIVADO (Filtrado por grado: Grado <?= $gradoUsuario ?>°) -->
                <li><a href="panel.php">Módulos</a></li>
                <li><a href="quizes.html">Quizzes</a></li>
                <li><a href="curiosidades.html">Datos Curiosos</a></li>
                <li><a href="perfil.html">Perfil (<?= htmlspecialchars($nombreUsuario) ?>)</a></li>
                <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>