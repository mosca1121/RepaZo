<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$gradoUsuario = $_SESSION["grado"] ?? "10";
$resultado_guardado = false;
$puntajeFinal = 0;
$totalPreguntas = 15;
$correctas = 0;
$incorrectas = 0;
$respuestasUsuario = [];

$preguntasQuiz = [
    1 => ['p' => 'En muchas ciudades se construyen más carreteras... ¿Cuál es la idea principal?', 'r' => 'B', 'opciones' => ['A' => 'Todas las carreteras son innecesarias.', 'B' => 'Construir más vías no siempre soluciona el problema del tráfico.', 'C' => 'Los vehículos dejan de circular.', 'D' => 'Prohibir transporte público.']],
    2 => ['p' => '¿Cuál opción fortalece mejor: "La IA puede mejorar el aprendizaje si se usa de manera responsable"?', 'r' => 'C', 'opciones' => ['A' => 'Muchas personas tienen celulares.', 'B' => 'Algunos prefieren no estudiar.', 'C' => 'Estudios muestran que la IA ofrece retroalimentación inmediata.', 'D' => 'Todos los computadores son iguales.']],
    3 => ['p' => 'En un artículo de opinión, datos y estadísticas sirven para:', 'r' => 'B', 'opciones' => ['A' => 'Decorar el texto.', 'B' => 'Dar credibilidad y respaldar la tesis.', 'C' => 'Aumentar páginas.', 'D' => 'Cambiar el tema.']],
    4 => ['p' => 'En "Aunque el reciclaje requiere esfuerzo...", la relación del conector "aunque" es:', 'r' => 'C', 'opciones' => ['A' => 'Consecuencia.', 'B' => 'Causa.', 'C' => 'Contraste.', 'D' => 'Comparación.']],
    5 => ['p' => 'El objetivo principal de un periodista al consultar múltiples fuentes es:', 'r' => 'C', 'opciones' => ['A' => 'Escribir texto largo.', 'B' => 'Convencer sin pruebas.', 'C' => 'Verificar la información y garantizar confiabilidad.', 'D' => 'Publicar rápido.']],
    6 => ['p' => 'Lee: "El acceso a internet ha permitido... sin embargo, también facilita la difusión de noticias falsas." Intención:', 'r' => 'C', 'opciones' => ['A' => 'Criticar el internet.', 'B' => 'Demostrar que no tiene ventajas.', 'C' => 'Mostrar beneficios y riesgos.', 'D' => 'Dejar redes.']],
    7 => ['p' => 'Argumento que respalda: "Reducir plásticos desechables ayuda a disminuir contaminación"', 'r' => 'C', 'opciones' => ['A' => 'Tienen colores.', 'B' => 'Todos usan plástico.', 'C' => 'Gran parte de residuos en océanos son plásticos de un solo uso.', 'D' => 'Es económico.']],
    8 => ['p' => 'Hecho verificable:', 'r' => 'C', 'opciones' => ['A' => 'El fútbol es el mejor.', 'B' => 'Leer novelas es más divertido.', 'C' => 'La Tierra realiza un movimiento de traslación alrededor del Sol.', 'D' => 'Música clásica es hermosa.']],
    9 => ['p' => 'En un ensayo, la función de la conclusión es:', 'r' => 'C', 'opciones' => ['A' => 'Presentar tema nuevo.', 'B' => 'Contradecir tesis.', 'C' => 'Sintetizar ideas principales y cerrar texto.', 'D' => 'Agregar ejemplos sin relación.']],
    10 => ['p' => 'Verificar autor, fecha y fuentes demuestra:', 'r' => 'C', 'opciones' => ['A' => 'Memorización.', 'B' => 'Escritura creativa.', 'C' => 'Evaluación crítica de fuentes.', 'D' => 'Copia textual.']],
    11 => ['p' => 'Conclusión del texto sobre la lectura como hábito diario:', 'r' => 'C', 'opciones' => ['A' => 'Solo sirve para palabras.', 'B' => 'Todos los libros son iguales.', 'C' => 'La lectura debe fomentarse como hábito desde la infancia.', 'D' => 'Los niños leen en el colegio.']],
    12 => ['p' => 'Evidencia que fortalece: "El uso de la bicicleta reduce la contaminación..."', 'r' => 'C', 'opciones' => ['A' => 'Bicicletas son negras.', 'B' => 'Algunos prefieren caminar.', 'C' => 'Estudios muestran que disminuye emisiones y favorece actividad física.', 'D' => 'Tienen ciclovías.']],
    13 => ['p' => 'Efecto de presentar información falsa en un texto argumentativo:', 'r' => 'C', 'opciones' => ['A' => 'Fortalece credibilidad.', 'B' => 'Mejora argumentos.', 'C' => 'Reduce confiabilidad y solidez.', 'D' => 'Convierte en noticia.']],
    14 => ['p' => 'Inferencia de: "Aunque la tecnología facilita tareas, exige uso responsable..."', 'r' => 'C', 'opciones' => ['A' => 'Debe prohibirse.', 'B' => 'Solo genera problemas.', 'C' => 'Ofrece beneficios pero requiere uso responsable.', 'D' => 'Desinformación desaparecerá.']],
    15 => ['p' => 'Comparar fuentes, identificar sesgos y formular conclusiones demuestra:', 'r' => 'C', 'opciones' => ['A' => 'Escritura mecánica.', 'B' => 'Memorización.', 'C' => 'Pensamiento crítico y análisis de información.', 'D' => 'Lectura rápida.']]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuestasUsuario = $_POST['respuestas'] ?? [];

    foreach ($preguntasQuiz as $id => $pregunta) {
        if (isset($respuestasUsuario[$id]) && $respuestasUsuario[$id] === $pregunta['r']) {
            $correctas++;
        }
    }

    $incorrectas = $totalPreguntas - $correctas;
    $porcentaje = ($correctas / $totalPreguntas) * 100;
    $id_quiz = 103; // Quiz Español 10° difícil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (105, 'Español', 'Lengua castellana')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (3, 'Difícil', 'Nivel avanzado')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (103, 105, 3, 'Quiz de Español - Difícil', 'Español Avanzado', 10)");

        $stmt = $conexion->prepare("INSERT INTO intentos_quizzes (id_usuario, id_quiz, porcentaje, respuestas_correctas, respuestas_incorrectas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidii", $id_usuario, $id_quiz, $porcentaje, $correctas, $incorrectas);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }
    $resultado_guardado = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Repazo | Quiz Español - Difícil</title>
    <link href="../../CSS/codigo.css" rel="stylesheet" />
    <link href="../../CSS/quizes.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
    <style>
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); display: flex; justify-content: center;
            align-items: center; z-index: 9999;
        }
        .modal-content {
            background: #fff; padding: 40px; border-radius: 20px; width: 90%; max-width: 450px;
            text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-content h2 { color: #1565C0; font-size: 26px; margin-bottom: 15px; }
        .modal-stats { font-size: 18px; margin: 15px 0; display: flex; justify-content: space-around; }
        .stat-bien { color: #2e7d32; font-weight: bold; }
        .stat-mal { color: #c62828; font-weight: bold; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img alt="logo de Repazo" decoding="async" loading="lazy" src="../../IMAGENES/LOGOREPAZO.jpg" />
            <h2>Repazo</h2>
        </div>
        <nav>
            <ul>
                <li><a href="../../panel.php">Módulos</a></li>
                <li><a class="../../active" href="quizes.html">Quizzes</a></li>
                <li><a href="../../curiosidades.php">Datos Curiosos</a></li>
                <li><a href="../../perfil.php">Perfil</a></li>
                <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Quiz de Español - Nivel Difícil</h1>
            <p>Pon a prueba tu máxima comprensión y pensamiento crítico.</p>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <h2 class="tituloMainT">Preguntas del Quiz (Difícil)</h2>

        <form action="" method="POST">
            <?php foreach ($preguntasQuiz as $id => $pregunta): 
                $respondida = $respuestasUsuario[$id] ?? '';
                $esCorrecta = ($respondida === $pregunta['r']);
            ?>
                <section class="tema" style="<?php if($_SERVER['REQUEST_METHOD'] === 'POST') { echo $esCorrecta ? 'border: 2px solid #43A047;' : 'border: 2px solid #e53935;'; } ?>">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta <?php echo $id; ?></h2>
                    <p><?php echo $pregunta['p']; ?></p>

                    <?php foreach ($pregunta['opciones'] as $letra => $textoOpcion): 
                        $marcado = ($respondida === $letra) ? 'checked' : '';
                        $estiloLabel = "";
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if ($letra === $pregunta['r']) {
                                $estiloLabel = "background: #c8e6c9; padding: 5px 10px; border-radius: 6px; font-weight: bold; display: block; margin-bottom: 5px;";
                            } elseif ($letra === $respondida && !$esCorrecta) {
                                $estiloLabel = "background: #ffcdd2; padding: 5px 10px; border-radius: 6px; font-weight: bold; display: block; margin-bottom: 5px;";
                            }
                        }
                    ?>
                        <div style="<?php echo $estiloLabel; ?>">
                            <label style="cursor: pointer; display: block; padding: 8px; border-radius: 8px; background: #f8f9fc; margin-bottom: 6px; border: 1px solid #e2e8f0;">
                                <input type="radio" name="respuestas[<?php echo $id; ?>]" value="<?php echo $letra; ?>" required <?php echo $marcado; ?> <?php echo ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'disabled' : ''; ?> />
                                <?php echo $textoOpcion; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <div style="margin-top: 10px; font-size: 14px; font-weight: bold; color: <?php echo $esCorrecta ? '#2e7d32' : '#c62828'; ?>;">
                            <?php if ($esCorrecta): ?>
                                <i class="fa-solid fa-check"></i> ¡Correcto!
                            <?php else: ?>
                                <i class="fa-solid fa-xmark"></i> Incorrecto. La respuesta correcta era: <strong><?php echo $pregunta['r']; ?></strong>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>

            <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
                <div style="text-align: center; margin: 40px 0;">
                    <button type="submit" class="btn" style="background: #1565C0; color: white; padding: 15px 40px; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer;">Finalizar y Enviar Quiz</button>
                </div>
            <?php endif; ?>
        </form>
    </main>

    <?php if ($resultado_guardado): ?>
    <div class="modal-overlay">
        <div class="modal-content">
            <h2><i class="fa-solid fa-circle-check" style="color: #43A047;"></i> ¡Quiz Finalizado!</h2>
            <p>Tus resultados se han registrado con éxito en tu perfil.</p>
            
            <div class="modal-stats">
                <span class="stat-bien">✔ Correctas: <?php echo $correctas; ?></span>
                <span class="stat-mal">✘ Incorrectas: <?php echo $incorrectas; ?></span>
            </div>

            <p style="font-size: 20px; font-weight: bold; margin: 15px 0; color: #1565C0;">
                Porcentaje Total: <?php echo round($porcentaje, 1); ?>%
            </p>
            
            <br>
            <a href="../../quizes.php" class="btn" style="text-decoration: none; display: inline-block; padding: 10px 25px;">Volver a Quizzes</a>
        </div>
    </div>
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