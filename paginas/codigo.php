<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
$paginaActiva = "inicio";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Inicio</title>
    <link href="../assets/css/codigo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main>
        <section class="heroinicio" aria-labelledby="titulo-hero">
            <header class="contenidoinicio">
                <h1 id="titulo-hero">Aprende de forma divertida</h1>
                <p>Una plataforma diseñada para estudiantes de 10° y 11° donde encontrarás módulos, quizzes interactivos y cientos de datos curiosos.</p>
                <nav class="botonesinicio" aria-label="Acciones rápidas">
                    <?php if (!isset($_SESSION["id_usuario"])): ?>
                        <a href="registro.php">Comenzar</a>
                        <a href="login.php">Ya tengo cuenta</a>
                    <?php else: ?>
                        <a href="panel.php">Ir a mis Módulos</a>
                        <a href="quizes.php">Practicar Quizzes</a>
                    <?php endif; ?>
                </nav>
            </header>
            <figure class="imageninicio">
                <img alt="Estudiantes aprendiendo en RepaZo" decoding="async" loading="lazy" src="../assets/img/ImgInicio.png">
            </figure>
        </section>

        <section class="cardsinicio" aria-labelledby="titulo-que-encontraras">
            <h2 id="titulo-que-encontraras">¿Qué encontrarás?</h2>
            <section class="contenedorCardsI">
                <article class="cardI">
                    <i class="fa-solid fa-book" aria-hidden="true"></i>
                    <h3>Módulos</h3>
                    <p>Aprende Matemáticas, Español, Física, Química e Inglés.</p>
                </article>
                <article class="cardI">
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    <h3>Quizzes</h3>
                    <p>Más de 450 preguntas organizadas por nivel con corrección automática.</p>
                </article>
                <article class="cardI">
                    <i class="fa-solid fa-earth-americas" aria-hidden="true"></i>
                    <h3>Datos Curiosos</h3>
                    <p>Descubre cientos de curiosidades sobre ciencia, historia, idiomas y mucho más.</p>
                </article>
            </section>
        </section>

        <section class="materiasinicio" aria-labelledby="titulo-areas">
            <h2 id="titulo-areas">Áreas Disponibles</h2>
            <section class="gridIM">
                <article class="materiaI">
                    <i class="fa-solid fa-book" aria-hidden="true"></i>
                    <h3>Español</h3>
                </article>
                <article class="materiaI">
                    <i class="fa-solid fa-calculator" aria-hidden="true"></i>
                    <h3>Física</h3>
                </article>
                <article class="materiaI">
                    <i class="fa-solid fa-atom" aria-hidden="true"></i>
                    <h3>Química</h3>
                </article>
                <article class="materiaI">
                    <i class="fa-solid fa-square-root-variable" aria-hidden="true"></i>
                    <h3>Matemáticas</h3>
                </article>
                <article class="materiaI">
                    <i class="fa-solid fa-language" aria-hidden="true"></i>
                    <h3>Inglés</h3>
                </article>
            </section>
        </section>
    </main>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
