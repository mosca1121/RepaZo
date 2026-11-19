<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sesionIniciada = isset($_SESSION["id_usuario"]);
$nombreUsuario = $_SESSION["nombre"] ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repazo | Inicio</title>
    <link href="CSS/codigo.css" rel="stylesheet">
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
                    <!-- Menú cuando NO hay sesión iniciada -->
                    <li><a class="active" href="codigo.php">Inicio</a></li>
                    <li><a href="quienes-somos.php">Quiénes somos</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a class="registro" href="registro.php">Registrarse</a></li>
                <?php else: ?>
                    <!-- Menú cuando SÍ hay sesión iniciada (SIN opción de Inicio) -->
                    <li><a class="active" href="panel.html">Módulos</a></li>
                    <li><a href="quizes.php">Quizzes</a></li>
                    <li><a href="curiosidades.php">Datos Curiosos</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="heroinicio">
        <div class="contenidoinicio">
            <h1>Aprende de forma divertida</h1>
            <p>Una plataforma diseñada para estudiantes de 10° y 11° donde encontrarás módulos, quizzes interactivos y cientos de datos curiosos.</p>
            <div class="botonesinicio">
                <a href="registro.php">Comenzar</a>
               
            </div>
        </div>
        <div class="imageninicio">
            <img alt="Estudiantes" decoding="async" loading="lazy" src="IMAGENES/ImgInicio.png">
        </div>
    </section>

    <section class="cardsinicio">
        <h2>¿Qué encontrarás?</h2>
        <section class="contenedorCardsI">
            <article class="cardI">
                <i class="fa-solid fa-book"></i>
                <h3>Módulos</h3>
                <p>Aprende Matemáticas, Español, Física, Química e Inglés.</p>
                
            </article>
            <article class="cardI">
                <i class="fa-solid fa-circle-question"></i>
                <h3>Quizzes</h3>
                <p>Más de 150 preguntas con corrección automática.</p>
                
            </article>
            <article class="cardI">
                <i class="fa-solid fa-earth-americas"></i>
                <h3>Datos Curiosos</h3>
                <p>Descubre cientos de curiosidades sobre ciencia, historia, idiomas y mucho más.</p>
               
            </article>
        </section>
    </section>

    <section class="materiasinicio">
        <h2>Áreas Disponibles</h2>
        <section class="gridIM">
            <article class="materiaI">
                <i class="fa-solid fa-book"></i>
                <h3>Español</h3>
            
            </article>
            <article class="materiaI">
                <i class="fa-solid fa-calculator"></i>
                <h3>Fisica</h3>
              
            </article>
            <article class="materiaI">
                <i class="fa-solid fa-atom"></i>
                <h3>Quimica</h3>
              
            </article>
            <article class="materiaI">
                <i class="fa-solid fa-square-root-variable"></i>
                <h3>Matemáticas</h3>
              
            </article>
            <article class="materiaI">
                <i class="fa-solid fa-language"></i>
                <h3>Inglés</h3>
              
            </article>
        </section>
    </section>

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