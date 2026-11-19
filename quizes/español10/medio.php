<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../paginas/login.php");
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
    1 => ['p' => 'Lee el fragmento: "Aunque el camino era difícil, los excursionistas continuaron..." ¿Qué idea principal expresa?', 'r' => 'B', 'opciones' => ['A' => 'Los excursionistas regresaron a casa.', 'B' => 'Los excursionistas lograron su objetivo a pesar de las dificultades.', 'C' => 'El camino era muy corto.', 'D' => 'Los excursionistas cambiaron de destino.']],
    2 => ['p' => '¿Cuál de las siguientes oraciones contiene una metáfora?', 'r' => 'B', 'opciones' => ['A' => 'Corre tan rápido como un rayo.', 'B' => 'Tus ojos son dos estrellas.', 'C' => 'El perro ladra muy fuerte.', 'D' => 'La casa es grande.']],
    3 => ['p' => 'En un texto argumentativo, ¿cuál es la función de los argumentos?', 'r' => 'C', 'opciones' => ['A' => 'Narrar una historia.', 'B' => 'Describir un lugar.', 'C' => 'Sustentar o defender una opinión.', 'D' => 'Expresar únicamente sentimientos.']],
    4 => ['p' => '¿Cuál de los siguientes conectores expresa una consecuencia?', 'r' => 'C', 'opciones' => ['A' => 'Sin embargo.', 'B' => 'Mientras.', 'C' => 'Por lo tanto.', 'D' => 'Aunque.']],
    5 => ['p' => 'Un estudiante escribe un artículo para convencer a sus compañeros de reciclar. ¿Qué tipo de texto está elaborando?', 'r' => 'C', 'opciones' => ['A' => 'Narrativo.', 'B' => 'Descriptivo.', 'C' => 'Argumentativo.', 'D' => 'Lírico.']],
    6 => ['p' => 'Lee el enunciado: "El alcalde anunció nuevas estrategias para mejorar la movilidad de la ciudad." ¿Cuál es la intención?', 'r' => 'B', 'opciones' => ['A' => 'Entretener al lector.', 'B' => 'Informar sobre una decisión.', 'C' => 'Convencer al lector.', 'D' => 'Expresar sentimientos.']],
    7 => ['p' => '¿Cuál de las siguientes oraciones contiene una personificación?', 'r' => 'C', 'opciones' => ['A' => 'Pedro corre más rápido que Juan.', 'B' => 'El carro es muy veloz.', 'C' => 'El viento susurraba entre los árboles.', 'D' => 'La montaña mide 3.000 metros.']],
    8 => ['p' => 'En el texto: "El reciclaje reduce la contaminación; por eso, todas las personas deberían...", "por eso" es un conector de:', 'r' => 'B', 'opciones' => ['A' => 'Oposición.', 'B' => 'Consecuencia.', 'C' => 'Comparación.', 'D' => 'Tiempo.']],
    9 => ['p' => '¿Cuál de las siguientes opciones presenta correctamente una opinión?', 'r' => 'C', 'opciones' => ['A' => 'Medellín está ubicada en Colombia.', 'B' => 'El agua hierve aproximadamente a 100 °C.', 'C' => 'Leer todos los días es una de las mejores formas de aprender.', 'D' => 'Un año tiene doce meses.']],
    10 => ['p' => 'Un estudiante lee un artículo científico para un trabajo. ¿Cuál es el propósito principal?', 'r' => 'C', 'opciones' => ['A' => 'Divertirse.', 'B' => 'Memorizar cada palabra.', 'C' => 'Obtener información confiable sobre un tema.', 'D' => 'Aprender un poema de memoria.']],
    11 => ['p' => 'Lee: "El uso excesivo del celular puede afectar el rendimiento académico; por esta razón..." ¿Cuál es la tesis?', 'r' => 'B', 'opciones' => ['A' => 'Los celulares son muy costosos.', 'B' => 'Es importante establecer horarios para utilizar el celular.', 'C' => 'Todos los estudiantes usan celulares.', 'D' => 'Los celulares fueron creados para estudiar.']],
    12 => ['p' => '¿Cuál de las oraciones utiliza correctamente un conector de contraste?', 'r' => 'B', 'opciones' => ['A' => 'Estudié mucho, por lo tanto perdí el examen.', 'B' => 'Estudié mucho; sin embargo, el examen fue muy difícil.', 'C' => 'Estudié mucho porque, además, llovía.', 'D' => 'Estudié mucho, es decir, lleg temprano.']],
    13 => ['p' => 'En la expresión "El tiempo vuela", la figura literaria utilizada es:', 'r' => 'B', 'opciones' => ['A' => 'Símil.', 'B' => 'Metáfora.', 'C' => 'Hipérbole.', 'D' => 'Onomatopeya.']],
    14 => ['p' => 'Un periodista entrevista a un científico para conocer resultados. ¿Qué tipo de fuente utiliza?', 'r' => 'B', 'opciones' => ['A' => 'Fuente secundaria.', 'B' => 'Fuente primaria.', 'C' => 'Fuente ficticia.', 'D' => 'Fuente literaria.']],
    15 => ['p' => 'Después de leer, un estudiante relaciona ideas con sus conocimientos y formula conclusiones. ¿Qué habilidad desarrolla?', 'r' => 'C', 'opciones' => ['A' => 'Copia textual.', 'B' => 'Memorización.', 'C' => 'Comprensión e interpretación crítica.', 'D' => 'Caligrafía.']]
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
    $id_quiz = 102; // Quiz Español 10° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (105, 'Español', 'Lengua castellana')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (2, 'Intermedio', 'Nivel intermedio')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (102, 105, 2, 'Quiz de Español - Medio', 'Español Intermedio', 10)");

        require_once __DIR__ . "/../guardar_intento.php";
        $conexion->set_charset("utf8mb4");
        $res = guardarIntentoQuiz($conexion, (int)$id_usuario, __FILE__, (int)$correctas, (int)$total_preguntas);
        $porcentaje = $res["porcentaje"];
        $incorrectas = $res["incorrectas"];
        $stmt = new class { public function bind_param(...$a){} public function execute(){} public function close(){} };
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
    <title>Repazo | Quiz Español - Medio</title>
    <link href="../../assets/css/codigo.css" rel="stylesheet" />
    <link href="../../assets/css/quizes.css" rel="stylesheet" />
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
            <img alt="logo de Repazo" decoding="async" loading="lazy" src="../../assets/img/LOGOREPAZO.jpg" />
            <h2>Repazo</h2>
        </div>
        <nav>
            <ul>
                <li><a href="../../paginas/panel.php">Módulos</a></li>
                <li><a class="active" href="../../paginas/quizes.php">Quizzes</a></li>
                <li><a href="../../paginas/curiosidades.php">Datos Curiosos</a></li>
                <li><a href="../../paginas/perfil.php">Perfil</a></li>
                <li><a href="../../acciones/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Quiz de Español - Nivel Medio</h1>
            <p>Demuestra tu capacidad analítica con este nivel intermedio.</p>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <h2 class="tituloMainT">Preguntas del Quiz (Medio)</h2>

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
            <a href="../../paginas/quizes.php" class="btn" style="text-decoration: none; display: inline-block; padding: 10px 25px;">Volver a Quizzes</a>
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