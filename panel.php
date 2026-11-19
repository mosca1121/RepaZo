<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}
$grado = $_SESSION["grado"] ?? ""; 
$sesionIniciada = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Repazo | Módulos</title>
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
                    <!-- Menú público (Sin sesión) -->
                    <li><a href="codigo.php">Inicio</a></li>
                    <li><a href="quienes-somos.php">Quiénes somos</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a class="registro" href="registro.php">Registrarse</a></li>
                <?php else: ?>
                    <!-- Menú privado (Con sesión activa) -->
                    <li><a class="active" href="panel.php">Módulos</a></li>
                    <li><a href="quizes.php">Quizzes</a></li>
                    <li><a href="curiosidades.php">Datos Curiosos</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Módulos Educativos</h1>
            <p>En esta página encontrarás las materias a repasar correspondientes a tu grado registrado. ¡A estudiar se dijo!</p>
        </div>
    </section>

    <?php if ($grado == "10"): ?>
        <!-- GRADO 10 -->
        <section class="gradoM">
            <div class="titulo-gradoM">
                <div class="numeroModulos">10°</div>
                <div>
                    <h2>Grado Décimo</h2>
                    <p>Explora los temas fundamentales de décimo.</p>
                </div>
            </div>
            <section class="areasM">
                <!-- MATEMÁTICAS -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Matemáticas</h3>
                        <p>Funciones, trigonometría, ecuaciones y estadística.</p>
                        <a href="modulosmatematicas10.html">Estudiar</a>
                    </div>
                </article>
                <!-- ESPAÑOL -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Español</h3>
                        <p>Literatura, comprensión lectora, gramática y comunicación.</p>
                        <a href="español10.html">Estudiar</a>
                    </div>
                </article>
                <!-- FÍSICA -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Física</h3>
                        <p>Movimiento, fuerzas, energía, trabajo y leyes físicas.</p>
                        <a href="fisica10.html">Estudiar</a>
                    </div>
                </article>
                <!-- QUÍMICA -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Química</h3>
                        <p>Átomos, tabla periódica, enlaces y reacciones químicas.</p>
                        <a href="quimica10.html">Estudiar</a>
                    </div>
                </article>
                <!-- INGLÉS -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Inglés</h3>
                        <p>Gramática, vocabulario, lectura y comunicación.</p>
                        <a href="ingles10.html">Estudiar</a>
                    </div>
                </article>
            </section>
        </section>

    <?php elseif ($grado == "11"): ?>
        <!-- GRADO 11 -->
        <section class="gradoM">
            <div class="titulo-gradoM">
                <div class="numeroModulos">11°</div>
                <div>
                    <h2>Grado Undécimo</h2>
                    <p>Prepárate para los temas de último grado.</p>
                </div>
            </div>
            <section class="areasM">
                <!-- MATEMÁTICAS -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Matemáticas</h3>
                        <p>Límites, derivadas, probabilidad, funciones y geometría.</p>
                        <a href="matematicas11.html">Estudiar</a>
                    </div>
                </article>
                <!-- ESPAÑOL -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Español</h3>
                        <p>Literatura contemporánea, argumentación y análisis textual.</p>
                        <a href="español11.html">Estudiar</a>
                    </div>
                </article>
                <!-- FÍSICA -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Física</h3>
                        <p>Electricidad, magnetismo, ondas y física moderna.</p>
                        <a href="fisica11.html">Estudiar</a>
                    </div>
                </article>
                <!-- QUÍMICA -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Química</h3>
                        <p>Química orgánica, soluciones, equilibrio y electroquímica.</p>
                        <a href="quimica11.html">Estudiar</a>
                    </div>
                </article>
                <!-- INGLÉS -->
                <article class="areaM">
                    <div class="contenidoM">
                        <h3>Inglés</h3>
                        <p>Tiempos verbales, conversación, lectura y escritura.</p>
                        <a href="ingles11.html">Estudiar</a>
                    </div>
                </article>
            </section>
        </section>
    <?php else: ?>
        <!-- En caso de que el grado no esté definido o haya algún error -->
        <section class="gradoM" style="text-align: center; padding: 40px;">
            <h2>No se ha detectado tu grado escolar.</h2>
            <p>Por favor, actualiza tu información en tu perfil o contacta con soporte.</p>
        </section>
    <?php endif; ?>

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