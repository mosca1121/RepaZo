<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../login.html");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$grado = "11";
$resultado_guardado = false;
$porcentaje = 0.00;
$total_preguntas = 15;
$correctas = 0;
$incorrectas = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuestas = $_POST['respuestas'] ?? [];
    foreach ($respuestas as $val) {
        if ($val === "correcta") { 
            $correctas++; 
        }
    }
    $incorrectas = $total_preguntas - $correctas;
    $porcentaje = ($correctas / $total_preguntas) * 100;
    $id_quiz = 116; // Quiz Español 11° fácil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (105, 'Español', 'Materiales para estudiar lengua castellana.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (1, 'Fácil', 'Preguntas de nivel básico.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (116, 105, 1, 'Quiz de Español - Nivel Fácil Grado 11', 'Quiz básico de español', 11)");

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
    <title>Repazo | Quiz Español 11° - Fácil</title>
    <link href="../../CSS/codigo.css" rel="stylesheet" />
    <link href="../../CSS/quizes.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
    <style>
        .opcion-input { display: none; }
        .opcion-label {
            display: block; width: 100%; text-align: left; background: #f8f9fc; color: #2c3e50;
            border: 1px solid #e2e8f0; padding: 0.9rem 1.2rem; margin-bottom: 0.75rem;
            border-radius: 12px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease;
        }
        .opcion-input:checked + .opcion-label { background: #e3f2fd; border-color: #1565C0; color: #1565C0; font-weight: bold; }
        
        /* Estilos de la Ventana Emergente (Modal) */
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
                <li><a href="../quizes.html">Quizzes</a></li>
                <li><a href="../../curiosidades.php">Datos Curiosos</a></li>
                <li><a href="../../perfil.php">Perfil</a></li>
                <li><a href="../../php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Quiz de Español - Nivel Fácil (Grado 11°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>&iquest;Cu&aacute;l es el prop&oacute;sito principal de un texto argumentativo?</p>
                <input type="radio" id="p1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="p1_a" class="opcion-label">A. Narrar una historia ficticia.</label>
                <input type="radio" id="p1_b" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="p1_b" class="opcion-label">B. Describir un paisaje.</label>
                <input type="radio" id="p1_c" name="respuestas[1]" value="correcta" class="opcion-input"><label for="p1_c" class="opcion-label">C. Defender una idea mediante argumentos.</label>
                <input type="radio" id="p1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="p1_d" class="opcion-label">D. Explicar una receta.</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>&iquest;Qu&eacute; significa que un texto tenga coherencia?</p>
                <input type="radio" id="p2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="p2_a" class="opcion-label">A. Que tenga muchas im&aacute;genes.</label>
                <input type="radio" id="p2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="p2_b" class="opcion-label">B. Que las ideas est&eacute;n organizadas y tengan sentido entre s&iacute;.</label>
                <input type="radio" id="p2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="p2_c" class="opcion-label">C. Que todas las palabras sean largas.</label>
                <input type="radio" id="p2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="p2_d" class="opcion-label">D. Que tenga m&aacute;s de cinco p&aacute;rrafos.</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>&iquest;Cu&aacute;l de los siguientes autores es reconocido como uno de los m&aacute;ximos representantes de la literatura colombiana?</p>
                <input type="radio" id="p3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="p3_a" class="opcion-label">A. Miguel de Cervantes.</label>
                <input type="radio" id="p3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="p3_b" class="opcion-label">B. Julio Verne.</label>
                <input type="radio" id="p3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="p3_c" class="opcion-label">C. Gabriel Garc&iacute;a M&aacute;rquez.</label>
                <input type="radio" id="p3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="p3_d" class="opcion-label">D. William Shakespeare.</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>En una noticia, la informaci&oacute;n debe presentarse principalmente de forma:</p>
                <input type="radio" id="p4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="p4_a" class="opcion-label">A. Subjetiva.</label>
                <input type="radio" id="p4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="p4_b" class="opcion-label">B. Objetiva.</label>
                <input type="radio" id="p4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="p4_c" class="opcion-label">C. Po&eacute;tica.</label>
                <input type="radio" id="p4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="p4_d" class="opcion-label">D. Humor&iacute;stica.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>&iquest;Cu&aacute;l de los siguientes conectores expresa una causa?</p>
                <input type="radio" id="p5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="p5_a" class="opcion-label">A. Sin embargo.</label>
                <input type="radio" id="p5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="p5_b" class="opcion-label">B. Adem&aacute;s.</label>
                <input type="radio" id="p5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="p5_c" class="opcion-label">C. Porque.</label>
                <input type="radio" id="p5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="p5_d" class="opcion-label">D. Finalmente.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>&iquest;Cu&aacute;l es la funci&oacute;n principal de la introducci&oacute;n en un ensayo?</p>
                <input type="radio" id="p6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="p6_a" class="opcion-label">A. Presentar &uacute;nicamente la conclusi&oacute;n.</label>
                <input type="radio" id="p6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="p6_b" class="opcion-label">B. Contar una historia sin relaci&oacute;n con el tema.</label>
                <input type="radio" id="p6_c" name="respuestas[6]" value="correcta" class="opcion-input"><label for="p6_c" class="opcion-label">C. Presentar el tema y la tesis que se desarrollar&aacute;.</label>
                <input type="radio" id="p6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="p6_d" class="opcion-label">D. Incluir todas las referencias bibliogr&aacute;ficas.</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Lee el siguiente enunciado:<br><br><em>"Las energ&iacute;as renovables reducen la contaminaci&oacute;n; adem&aacute;s, disminuyen la dependencia de los combustibles f&oacute;siles."</em><br><br>El conector <strong>"adem&aacute;s"</strong> expresa una relaci&oacute;n de:</p>
                <input type="radio" id="p7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="p7_a" class="opcion-label">A. Contraste.</label>
                <input type="radio" id="p7_b" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="p7_b" class="opcion-label">B. Causa.</label>
                <input type="radio" id="p7_c" name="respuestas[7]" value="correcta" class="opcion-input"><label for="p7_c" class="opcion-label">C. Adici&oacute;n.</label>
                <input type="radio" id="p7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="p7_d" class="opcion-label">D. Consecuencia.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>&iquest;Cu&aacute;l de las siguientes opciones corresponde a una fuente de informaci&oacute;n confiable para una investigaci&oacute;n escolar?</p>
                <input type="radio" id="p8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="p8_a" class="opcion-label">A. Un comentario an&oacute;nimo en redes sociales.</label>
                <input type="radio" id="p8_b" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="p8_b" class="opcion-label">B. Un rumor compartido por un amigo.</label>
                <input type="radio" id="p8_c" name="respuestas[8]" value="correcta" class="opcion-input"><label for="p8_c" class="opcion-label">C. Un art&iacute;culo cient&iacute;fico publicado en una revista acad&eacute;mica.</label>
                <input type="radio" id="p8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="p8_d" class="opcion-label">D. Un mensaje sin autor en internet.</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>&iquest;Cu&aacute;l de las siguientes expresiones corresponde a una opini&oacute;n?</p>
                <input type="radio" id="p9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="p9_a" class="opcion-label">A. Colombia tiene costas sobre el oc&eacute;ano Pac&iacute;fico y el mar Caribe.</label>
                <input type="radio" id="p9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="p9_b" class="opcion-label">B. El agua est&aacute; formada por hidr&oacute;geno y ox&iacute;geno.</label>
                <input type="radio" id="p9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="p9_c" class="opcion-label">C. Un a&ntilde;o tiene doce meses.</label>
                <input type="radio" id="p9_d" name="respuestas[9]" value="correcta" class="opcion-input"><label for="p9_d" class="opcion-label">D. Leer diariamente es el mejor h&aacute;bito para el desarrollo personal.</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>En un texto argumentativo, la conclusi&oacute;n tiene como prop&oacute;sito:</p>
                <input type="radio" id="p10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="p10_a" class="opcion-label">A. Presentar un tema completamente diferente.</label>
                <input type="radio" id="p10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="p10_b" class="opcion-label">B. Repetir el t&iacute;tulo del texto.</label>
                <input type="radio" id="p10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="p10_c" class="opcion-label">C. Resumir los argumentos y reafirmar la tesis.</label>
                <input type="radio" id="p10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="p10_d" class="opcion-label">D. Incluir &uacute;nicamente ejemplos.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>&iquest;Cu&aacute;l de los siguientes textos tiene como prop&oacute;sito principal informar sobre un hecho de actualidad?</p>
                <input type="radio" id="p11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="p11_a" class="opcion-label">A. Un poema.</label>
                <input type="radio" id="p11_b" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="p11_b" class="opcion-label">B. Un cuento de ficci&oacute;n.</label>
                <input type="radio" id="p11_c" name="respuestas[11]" value="correcta" class="opcion-input"><label for="p11_c" class="opcion-label">C. Una noticia.</label>
                <input type="radio" id="p11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="p11_d" class="opcion-label">D. Una obra de teatro.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>&iquest;Qu&eacute; significa realizar una inferencia durante la lectura de un texto?</p>
                <input type="radio" id="p12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="p12_a" class="opcion-label">A. Copiar exactamente lo que dice el texto.</label>
                <input type="radio" id="p12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="p12_b" class="opcion-label">B. Buscar palabras en el diccionario.</label>
                <input type="radio" id="p12_c" name="respuestas[12]" value="correcta" class="opcion-input"><label for="p12_c" class="opcion-label">C. Deducir informaci&oacute;n que no est&aacute; expresada de forma expl&iacute;cita.</label>
                <input type="radio" id="p12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="p12_d" class="opcion-label">D. Memorizar todas las oraciones.</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>&iquest;Cu&aacute;l de las siguientes obras fue escrita por Gabriel Garc&iacute;a M&aacute;rquez?</p>
                <input type="radio" id="p13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="p13_a" class="opcion-label">A. Don Quijote de la Mancha.</label>
                <input type="radio" id="p13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="p13_b" class="opcion-label">B. La Odisea.</label>
                <input type="radio" id="p13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="p13_c" class="opcion-label">C. Cien a&ntilde;os de soledad.</label>
                <input type="radio" id="p13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="p13_d" class="opcion-label">D. La Il&iacute;ada.</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>&iquest;Cu&aacute;l de los siguientes conectores expresa una consecuencia?</p>
                <input type="radio" id="p14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="p14_a" class="opcion-label">A. Aunque.</label>
                <input type="radio" id="p14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="p14_b" class="opcion-label">B. Mientras.</label>
                <input type="radio" id="p14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="p14_c" class="opcion-label">C. Por lo tanto.</label>
                <input type="radio" id="p14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="p14_d" class="opcion-label">D. En cambio.</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Un estudiante revisa la ortograf&iacute;a, la puntuaci&oacute;n y la organizaci&oacute;n de sus ideas antes de entregar un ensayo.<br>&iquest;Qu&eacute; etapa del proceso de escritura est&aacute; realizando?</p>
                <input type="radio" id="p15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="p15_a" class="opcion-label">A. Planeaci&oacute;n.</label>
                <input type="radio" id="p15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="p15_b" class="opcion-label">B. B&uacute;squeda de informaci&oacute;n.</label>
                <input type="radio" id="p15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="p15_c" class="opcion-label">C. Revisi&oacute;n y correcci&oacute;n.</label>
                <input type="radio" id="p15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="p15_d" class="opcion-label">D. Publicaci&oacute;n.</label>
            </section>

            <div style="text-align: center; margin-top: 40px;">
                <button type="submit" class="btn" style="border:none; cursor:pointer; font-size: 18px; padding: 15px 40px;">Enviar Respuestas</button>
            </div>
        </form>
    </main>

    <!-- VENTANA EMERGENTE (MODAL) AL ENVIAR -->
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
        <section class="footer1"><h2>Repazo</h2><p>Aprender nunca fue tan divertido.</p></section>
        <section class="footer3"><h3>Síguenos</h3></section>
    </footer>
</body>
</html>