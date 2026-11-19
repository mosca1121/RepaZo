<?php
session_start();

$grado = $_SESSION["grado"] ?? "";
$sesionIniciada = isset($_SESSION["id_usuario"]);

$quizzes = [
    "10" => [
        "numero"    => "10°",
        "titulo"    => "Quizzes - Grado Décimo",
        "subtitulo" => "Selecciona una materia y un nivel para practicar los temas de décimo.",
        "materias"  => [
            [
                "nombre" => "Matemáticas",
                "desc"   => "Evalúa tus conocimientos en funciones, trigonometría y ecuaciones.",
                "niveles" => [
                    "facil"   => "quizes/mate10/facil.php",
                    "medio"   => "quizes/mate10/medio.php",
                    "dificil" => "quizes/mate10/dificil.php",
                ],
            ],
            [
                "nombre" => "Español",
                "desc"   => "Pon a prueba tu comprensión lectora y análisis literario.",
                "niveles" => [
                    "facil"   => "quizes/español10/facil.php",
                    "medio"   => "quizes/español10/medio.php",
                    "dificil" => "quizes/español10/dificil.php",
                ],
            ],
            [
                "nombre" => "Física",
                "desc"   => "Desafía tus habilidades en movimiento, fuerzas y energía.",
                "niveles" => [
                    "facil"   => "quizes/fisica10/facil.php",
                    "medio"   => "quizes/fisica10/medio.php",
                    "dificil" => "quizes/fisica10/dificil.php",
                ],
            ],
            [
                "nombre" => "Química",
                "desc"   => "Pon a prueba tu dominio sobre la tabla periódica y reacciones.",
                "niveles" => [
                    "facil"   => "quizes/quimica10/facil.php",
                    "medio"   => "quizes/quimica10/medio.php",
                    "dificil" => "quizes/quimica10/dificil.php",
                ],
            ],
            [
                "nombre" => "Inglés",
                "desc"   => "Evalúa tu vocabulario, gramática y nivel de lectura.",
                "niveles" => [
                    "facil"   => "quizes/ingles10/facil.php",
                    "medio"   => "quizes/ingles10/medio.php",
                    "dificil" => "quizes/ingles10/dificil.php",
                ],
            ],
        ],
    ],
    "11" => [
        "numero"    => "11°",
        "titulo"    => "Quizzes - Grado Undécimo",
        "subtitulo" => "Enfrenta los retos y simulacros de último grado.",
        "materias"  => [
            [
                "nombre" => "Matemáticas",
                "desc"   => "Pon a prueba tus conocimientos en cálculo, límites y derivadas.",
                "niveles" => [
                    "facil"   => "quizes/mate11/facil.php",
                    "medio"   => "quizes/mate11/medio.php",
                    "dificil" => "quizes/mate11/dificil.php",
                ],
            ],
            [
                "nombre" => "Español",
                "desc"   => "Desafía tu capacidad analítica y argumentación crítica.",
                "niveles" => [
                    "facil"   => "quizes/español11/facil.php",
                    "medio"   => "quizes/español11/medio.php",
                    "dificil" => "quizes/español11/dificil.php",
                ],
            ],
            [
                "nombre" => "Física",
                "desc"   => "Evalúa tus habilidades en electromagnetismo y ondas.",
                "niveles" => [
                    "facil"   => "quizes/fisica11/facil.php",
                    "medio"   => "quizes/fisica11/medio.php",
                    "dificil" => "quizes/fisica11/dificil.php",
                ],
            ],
            [
                "nombre" => "Química",
                "desc"   => "Pon a prueba tu conocimiento en química orgánica y soluciones.",
                "niveles" => [
                    "facil"   => "quizes/quimica11/facil.php",
                    "medio"   => "quizes/quimica11/medio.php",
                    "dificil" => "quizes/quimica11/dificil.php",
                ],
            ],
            [
                "nombre" => "Inglés",
                "desc"   => "Desafía tu nivel con tiempos verbales avanzados y lectura.",
                "niveles" => [
                    "facil"   => "quizes/ingles11/facil.php",
                    "medio"   => "quizes/ingles11/medio.php",
                    "dificil" => "quizes/ingles11/dificil.php",
                ],
            ],
        ],
    ],
];

$datosGrado = $quizzes[$grado] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Repazo | Quizzes</title>
    <link href="CSS/codigo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Botón "Practicar" dentro de la tarjeta */
        .areaM { cursor: pointer; }
        .areaM .contenidoM .btn-practicar {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 22px;
            border: none;
            border-radius: 8px;
            background: #1e88e5;
            color: #fff;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }
        .areaM:hover .btn-practicar { background: #0d47a1; transform: translateY(-2px); }

        /* Modal */
        .modal-niveles {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(5, 25, 60, .7);
            z-index: 1000;
            padding: 16px;
        }
        .modal-niveles.abierto { display: flex; }

        .modal-caja {
            position: relative;
            width: 100%;
            max-width: 380px;
            background: #fff;
            border-radius: 16px;
            padding: 28px 24px 24px;
            text-align: center;
            border-top: 6px solid #1e88e5;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
            animation: aparecer .2s ease;
        }
        @keyframes aparecer {
            from { opacity: 0; transform: translateY(10px) scale(.97); }
            to   { opacity: 1; transform: none; }
        }

        .modal-caja h3 { margin: 0 0 4px; color: #1565c0; font-size: 1.5rem; }
        .modal-caja p  { margin: 0 0 20px; color: #555; }

        .modal-cerrar {
            position: absolute;
            top: 10px; right: 14px;
            background: none;
            border: none;
            font-size: 1.8rem;
            line-height: 1;
            color: #888;
            cursor: pointer;
        }
        .modal-cerrar:hover { color: #0d47a1; }

        .modal-opciones { display: flex; flex-direction: column; gap: 10px; }
        .modal-opciones a {
            display: block;
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #1e88e5;
            color: #1565c0;
            background: #fff;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, color .2s, transform .15s;
        }
        .modal-opciones a:hover { color: #fff; transform: translateY(-2px); }

        .modal-opciones a.nivel-facil   { border-color: #64b5f6; color: #1976d2; }
        .modal-opciones a.nivel-medio   { border-color: #1e88e5; color: #1565c0; }
        .modal-opciones a.nivel-dificil { border-color: #0d47a1; color: #0d47a1; }
        .modal-opciones a.nivel-facil:hover   { background: #64b5f6; }
        .modal-opciones a.nivel-medio:hover   { background: #1e88e5; }
        .modal-opciones a.nivel-dificil:hover { background: #0d47a1; }
    </style>
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
                    <li><a href="codigo.php">Inicio</a></li>
                    <li><a href="quienes-somos.php">Quiénes somos</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a class="registro" href="registro.php">Registrarse</a></li>
                <?php else: ?>
                    <li><a href="panel.php">Módulos</a></li>
                    <li><a class="active" href="quizes.php">Quizzes</a></li>
                    <li><a href="curiosidades.php">Datos Curiosos</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Quizzes de Repaso</h1>
            <p>Pon a prueba tus conocimientos con los quizzes correspondientes a tu grado registrado.</p>
        </div>
    </section>

    <?php if ($datosGrado): ?>
        <!-- SECCIÓN DINÁMICA SEGÚN EL GRADO REGISTRADO -->
        <section class="gradoM">
            <div class="titulo-gradoM">
                <div class="numeroModulos"><?php echo $datosGrado["numero"]; ?></div>
                <div>
                    <h2><?php echo $datosGrado["titulo"]; ?></h2>
                    <p><?php echo $datosGrado["subtitulo"]; ?></p>
                </div>
            </div>
            <section class="areasM">
                <?php foreach ($datosGrado["materias"] as $materia): ?>
                    <article class="areaM" onclick="abrirModal(
                        '<?php echo htmlspecialchars($materia['nombre'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($materia['niveles']['facil'] ?? '#', ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($materia['niveles']['medio'] ?? '#', ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($materia['niveles']['dificil'] ?? '#', ENT_QUOTES); ?>'
                    )">
                        <div class="contenidoM">
                            <h3><?php echo htmlspecialchars($materia["nombre"]); ?></h3>
                            <p><?php echo htmlspecialchars($materia["desc"]); ?></p>
                            <button type="button" class="btn-practicar">Practicar</button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        </section>
    <?php else: ?>
        <!-- En caso de que el grado no esté definido o haya algún error -->
        <section class="gradoM" style="text-align: center; padding: 40px;">
            <h2>No se ha detectado tu grado escolar.</h2>
            <p>Por favor, actualiza tu información en tu perfil o asegúrate de haber iniciado sesión correctamente.</p>
        </section>
    <?php endif; ?>

    <!-- Modal de Niveles -->
    <div class="modal-niveles" id="modalNiveles">
        <div class="modal-caja">
            <button type="button" class="modal-cerrar" onclick="cerrarModal()">&times;</button>
            <h3 id="modalTituloMateria">Materia</h3>
            <p>Selecciona el nivel de dificultad:</p>
            <div class="modal-opciones">
                <a id="linkFacil" href="#" class="nivel-facil"><i class="fa-solid fa-star"></i> Fácil</a>
                <a id="linkMedio" href="#" class="nivel-medio"><i class="fa-solid fa-star-half-stroke"></i> Medio</a>
                <a id="linkDificil" href="#" class="nivel-dificil"><i class="fa-solid fa-fire"></i> Difícil / Avanzado</a>
            </div>
        </div>
    </div>

    <script>
        function abrirModal(materia, facilUrl, medioUrl, dificilUrl) {
            document.getElementById('modalTituloMateria').innerText = materia;
            document.getElementById('linkFacil').href = facilUrl;
            document.getElementById('linkMedio').href = medioUrl;
            document.getElementById('linkDificil').href = dificilUrl;
            document.getElementById('modalNiveles').classList.add('abierto');
        }

        function cerrarModal() {
            document.getElementById('modalNiveles').classList.remove('abierto');
        }

        // Cerrar modal al hacer clic fuera de la caja blanca
        window.onclick = function(event) {
            var modal = document.getElementById('modalNiveles');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>

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