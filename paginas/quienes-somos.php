<?php declare(strict_types=1); require_once __DIR__ . "/../includes/conexiones.php"; $paginaActiva = "quienes"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Quiénes Somos</title>
    <link rel="stylesheet" href="../assets/css/codigo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

   <?php include __DIR__ . "/../includes/header.php"; ?>

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
                <article class="persona">
                    <img src="../assets/img/emiliano.jpg" alt="Emiliano Tobón">
                    <h3>Emiliano Tobón</h3>
                    <p>
                        BACKEND del equipo:encargado de la base de datos
                        
                    </p>
                </article>

                <article class="persona">
                    <img src="../assets/img/caleb.jpg" alt="Caleb">
                    <h3>Caleb</h3>
                    <p>
                        FULLSTACK del equipo:encargado de hacer tareas varias del sitio
                    </p>
                </article>

                <article class="persona">
                    <img src="../assets/img/valentina.jpg" alt="Valentina">
                    <h3>Valentina</h3>
                    <p>
                        FRONTED del equipo:es la encargada de la parte visual y grafica del sitio
                    </p>
                </article>

                <article class="persona">
                    <img src="../assets/img/nataly.jpg" alt="Nataly">
                    <h3>Nataly</h3>
                    <p>
                       FULLSTACK del equipo:es la encargada de 
              hacer tareas varias del sitio
                    </p></article>
            </div>
        </section>


        <section class="botones">
            <a class="btn" href="codigo.php"><i class="fa-solid fa-arrow-left"></i> Volver al Inicio</a>
        </section>
    </main>

    <!-- FOOTER -->
    <?php include __DIR__ . "/../includes/footer.php"; ?>

</body>
</html>