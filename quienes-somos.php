<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sesionIniciada = isset($_SESSION["id_usuario"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Quiénes Somos</title>
    <link rel="stylesheet" href="CSS/codigo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
                <li><a class="active" href="quienes-somos.php">Quiénes somos</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a class="registro" href="registro.php">Registrarse</a></li>
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

    <!-- HERO / SECCIÓN PRINCIPAL -->
    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>¿Quiénes Somos?</h1>
            <p>Conoce más sobre el equipo y el propósito detrás de RepaZo, tu plataforma de confianza para repasar y triunfar en el colegio.</p>
        </div>
    </section>

    <!-- CONTENIDO QUIÉNES SOMOS -->
    <main class="maintemas">
        <section class="tema">
            <h2><i class="fa-solid fa-bullseye"></i> Nuestro Propósito</h2>
            <p><strong>RepaZo</strong> nació como un proyecto escolar enfocado en brindar una herramienta digital accesible, interactiva y dinámica para los estudiantes de <strong>10° y 11° grado</strong>. Queremos hacer que el repaso de materias complejas como Matemáticas, Física, Química, Español e Inglés sea mucho más fácil y entretenido.</p>
        </section>

       <section class="tema">
            <h2><i class="fa-solid fa-users"></i> El Equipo</h2>
            <p>Somos estudiantes apasionados por la tecnología y la educación, comprometidos en crear soluciones prácticas que ayuden a nuestra comunidad escolar a reforzar sus conocimientos desde cualquier lugar.</p>
            <p>RepaZo nació con el propósito de apoyar a los estudiantes de grados superiores que necesitan recuperar el tiempo perdido o reforzar conceptos clave. Es el aliado ideal para quienes buscan ponerse al día, despejar dudas sobre temas que no lograron comprender en su momento y construir las bases sólidas necesarias para alcanzar y superar todas sus competencias académicas.</p>
        </section>

        <section class="tema">
            <h2><i class="fa-solid fa-user-group"></i> Integrantes del Equipo</h2>
            <div class="integrantes">
                <div class="persona">
                    <img src="IMAGENES/emiliano.jpg" alt="Emiliano Tobón">
                    <h3>Emiliano Tobón</h3>
                    <p>
                        BACKEND del equipo:encargado de la base de datos
                        
                    </p>
                </div>

                <div class="persona">
                    <img src="IMAGENES/caleb.jpg" alt="Caleb">
                    <h3>Caleb</h3>
                    <p>
                        FULLSTACK del equipo:encargado de hacer tareas varias del sitio
                    </p>
                </div>

                <div class="persona">
                    <img src="IMAGENES/valentina.jpg" alt="Valentina">
                    <h3>Valentina</h3>
                    <p>
                        FRONTED del equipo:es la encargada de la parte visual y grafica del sitio
                    </p>
                </div>

                <div class="persona">
                    <img src="IMAGENES/nataly.jpg" alt="Nataly">
                    <h3>Nataly</h3>
                    <p>
                       FULLSTACK del equipo:es la encargada de 
              hacer tareas varias del sitio
                    </p>
                </div>
            </div>
        </section>


        <section class="botones">
            <a class="btn" href="codigo.php"><i class="fa-solid fa-arrow-left"></i> Volver al Inicio</a>
        </section>
    </main>

    <!-- FOOTER -->
    <footer>
        <section class="footer1">
            <h2>Repazo</h2>
            <p>Aprender nunca fue tan divertido.</p>
        </section>
        <section class="footer3">
            <h3>Síguenos</h3>
            <div class="redes-sociales">
                <a href="https://www.facebook.com/profile.php?id=61594301955666" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/_repazo?stkn=MWRlamV2MnNlNm1vcw==" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@repazo80?_r=1&_t=ZS-99uzZN8ve1I" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </section>
    </footer>

</body>
</html>