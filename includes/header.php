<?php
$rutaBase = $rutaBase ?? "../";
$paginaActiva = $paginaActiva ?? "";
$sesionIniciada = isset($_SESSION["id_usuario"]);
$rolSesion = (string)($_SESSION["rol"] ?? "");
$esAdmin = ($rolSesion === "admin" || $rolSesion === "administrador");
?>
<header class="navbar">
    <a class="logo" href="<?= e($rutaBase . 'paginas/' . ($sesionIniciada ? 'panel.php' : 'codigo.php')) ?>" style="text-decoration:none;">
        <img alt="Logo de RepaZo" decoding="async" src="<?= e($rutaBase) ?>assets/img/LOGOREPAZO.jpg">
        <h2>RepaZo</h2>
    </a>
    <nav aria-label="Navegación principal">
        <ul>
            <?php if (!$sesionIniciada): ?>
                <li><a class="<?= $paginaActiva === 'inicio' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/codigo.php">Inicio</a></li>
                <li><a class="<?= $paginaActiva === 'quienes' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/quienes-somos.php">Quiénes somos</a></li>
                <li><a class="<?= $paginaActiva === 'login' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/login.php">Login</a></li>
                <li><a class="registro <?= $paginaActiva === 'registro' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/registro.php">Registrarse</a></li>
            <?php else: ?>
                <li><a class="<?= $paginaActiva === 'modulos' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/panel.php">Módulos</a></li>
                <li><a class="<?= $paginaActiva === 'quizes' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/quizes.php">Quizzes</a></li>
                <li><a class="<?= $paginaActiva === 'curiosidades' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/curiosidades.php">Datos Curiosos</a></li>
                <?php if ($esAdmin): ?>
                    <li><a class="<?= $paginaActiva === 'admin' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>admin-repazo/admin.php">Panel Admin</a></li>
                <?php else: ?>
                    <li><a class="<?= $paginaActiva === 'perfil' ? 'active' : '' ?>" href="<?= e($rutaBase) ?>paginas/perfil.php">Perfil</a></li>
                <?php endif; ?>
                <li><a href="<?= e($rutaBase) ?>acciones/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>