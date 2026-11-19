<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}

$id_usuario =$_SESSION["id_usuario"];
$grado = "11";
$resultado_guardado = false;
$puntaje_obtenido = 0;
$total_preguntas = 15;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Repazo | Quiz Español 11° - Medio</title>
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
        .resultado-final { background: #fff; padding: 30px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.15); margin-bottom: 40px; }
        .resultado-final h2 { color: #1565C0; font-size: 28px; margin-bottom: 15px; }
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
            <h1>Quiz de Español - Nivel Medio (Grado 11°)</h1>
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
    $id_quiz = 118; // Quiz Español 11° difícil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (105, 'Español', 'Materiales para estudiar lengua castellana.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (3, 'Difícil', 'Preguntas de nivel avanzado.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (118, 105, 3, 'Quiz de Español - Nivel Difícil Grado 11', 'Quiz avanzado de español', 11)");

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
    <title>Repazo | Quiz Español 11° - Difícil</title>
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
            <h1>Quiz de Español - Nivel Difícil (Grado 11°)</h1>
            <p>Pon a prueba tu máxima comprensión y pensamiento crítico.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz avanzado</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>En muchas ciudades se construyen m&aacute;s carreteras para reducir el tr&aacute;fico; sin embargo, al poco tiempo las nuevas v&iacute;as se congestionan nuevamente.<br>&iquest;Cu&aacute;l es la idea principal que cuestiona el texto?</p>
                <input type="radio" id="d1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="d1_a" class="opcion-label">A. Todas las carreteras construidas son totalmente innecesarias.</label>
                <input type="radio" id="d1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="d1_b" class="opcion-label">B. Construir m&aacute;s v&iacute;as no siempre soluciona el problema de la congesti&oacute;n vehicular a largo plazo.</label>
                <input type="radio" id="d1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="d1_c" class="opcion-label">C. Los autom&oacute;viles particulares deben dejar de circular por completo.</label>
                <input type="radio" id="d1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="d1_d" class="opcion-label">D. Las ciudades modernas deben prohibir el transporte p&uacute;blico.</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>&iquest;Cu&aacute;l de las siguientes opciones fortalece de mejor manera la tesis: <em>"La inteligencia artificial puede mejorar el aprendizaje si se usa con responsabilidad"</em>?</p>
                <input type="radio" id="d2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="d2_a" class="opcion-label">A. La mayor&iacute;a de los j&oacute;venes actuales poseen un tel&eacute;fono inteligente.</label>
                <input type="radio" id="d2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="d2_b" class="opcion-label">B. Algunos estudiantes prefieren no utilizar herramientas digitales en clase.</label>
                <input type="radio" id="d2_c" name="respuestas[2]" value="correcta" class="opcion-input"><label for="d2_c" class="opcion-label">C. Diversos estudios pedag&oacute;gicos muestran que los tutores inteligentes ofrecen retroalimentaci&oacute;n inmediata adaptada al nivel del alumno.</label>
                <input type="radio" id="d2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="d2_d" class="opcion-label">D. Los computadores actuales son mucho m&aacute;s r&aacute;pidos que los de la d&eacute;cada pasada.</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>En un artículo de opinión, la inclusión de datos estadísticos y cifras oficiales tiene como propósito principal:</p>
                <input type="radio" id="d3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="d3_a" class="opcion-label">A. Rellenar espacio y hacer el texto visualmente m&aacute;s extenso.</label>
                <input type="radio" id="d3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="d3_b" class="opcion-label">B. Aportar rigor, credibilidad y respaldar de manera objetiva la tesis planteada.</label>
                <input type="radio" id="d3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="d3_c" class="opcion-label">C. Modificar radicalmente el tema central de la discusi&oacute;n.</label>
                <input type="radio" id="d3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="d3_d" class="opcion-label">D. Confundir al lector con informaci&oacute;n excesivamente compleja.</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Lee el siguiente enunciado:<br><br><em>"Aunque el reciclaje requiere un esfuerzo log&ccedil;stico importante por parte de los ciudadanos, sus beneficios ambientales superan con creces los inconvenientes."</em><br><br>El conector <strong>"aunque"</strong> introduce una relaci&oacute;n de:</p>
                <input type="radio" id="d4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="d4_a" class="opcion-label">A. Consecuencia.</label>
                <input type="radio" id="d4_b" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="d4_b" class="opcion-label">B. Causa.</label>
                <input type="radio" id="d4_c" name="respuestas[4]" value="correcta" class="opcion-input"><label for="d4_c" class="opcion-label">C. Concesi&oacute;n o contraste.</label>
                <input type="radio" id="d4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="d4_d" class="opcion-label">D. Condici&oacute;n temporal.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>&iquest;Por qu&eacute; es fundamental que un investigador consulte m&uacute;ltiples fuentes de informaci&oacute;n al redactar un texto anal&iacute;tico?</p>
                <input type="radio" id="d5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="d5_a" class="opcion-label">A. Para redactar P&aacute;rrafos m&aacute;s extensos sin esfuerzo.</label>
                <input type="radio" id="d5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="d5_b" class="opcion-label">B. Para evitar por completo el uso de conclusiones propias.</label>
                <input type="radio" id="d5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="d5_c" class="opcion-label">C. Para contrastar puntos de vista, detectar posibles sesgos y garantizar la veracidad de los hechos.</label>
                <input type="radio" id="d5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="d5_d" class="opcion-label">D. Para utilizar exactamente las mismas palabras en todo el art&iacute;culo.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Lee el fragmento:<br><br><em>"El acceso masivo a internet ha democratizado el conocimiento; sin embargo, tambi&eacute;n ha facilitado la viralizaci&oacute;n de noticias falsas que desorientan a la opini&oacute;n p&uacute;blica."</em><br><br>&iquest;Cu&aacute;l es la intenci&oacute;n discursiva del autor?</p>
                <input type="radio" id="d6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="d6_a" class="opcion-label">A. Demostrar que el internet no ofrece ninguna ventaja real.</label>
                <input type="radio" id="d6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="d6_b" class="opcion-label">B. Convencer al lector de abandonar las plataformas digitales.</label>
                <input type="radio" id="d6_c" name="respuestas[6]" value="correcta" class="opcion-input"><label for="d6_c" class="opcion-label">C. Exponer de forma equilibrada los beneficios y los riesgos de la era digital.</label>
                <input type="radio" id="d6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="d6_d" class="opcion-label">D. Promover el uso exclusivo de medios impresos tradicionales.</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>&iquest;Cu&aacute;l de los siguientes argumentos resulta m&aacute;s s&oacute;lido para respaldar la afirmaci&oacute;n: <em>"Reducir el uso de plásticos de un solo uso protege los ecosistemas marinos"</em>?</p>
                <input type="radio" id="d7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="d7_a" class="opcion-label">A. Los envases pl&aacute;sticos se fabrican en muchos colores llamativos.</label>
                <input type="radio" id="d7_b" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="d7_b" class="opcion-label">B. Pr&aacute;cticamente todas las personas del mundo utilizan bolsas de pl&aacute;stico a diario.</label>
                <input type="radio" id="d7_c" name="respuestas[7]" value="correcta" class="opcion-input"><label for="d7_c" class="opcion-label">C. Toneladas de residuos pl&aacute;sticos llegan a los oc&eacute;anos cada a&ntilde;o, causando la muerte de numerosas especies acu&aacute;ticas al ingerirlos.</label>
                <input type="radio" id="d7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="d7_d" class="opcion-label">D. Comprar productos sin pl&aacute;stico suele ser mucho m&aacute;s econ&oacute;mico en el supermercado.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>&iquest;Cu&aacute;l de las siguientes opciones corresponde a un hecho verificable y no a una apreciaci&oacute;n subjetiva?</p>
                <input type="radio" id="d8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="d8_a" class="opcion-label">A. La obra literaria Cien a&ntilde;os de soledad es la m&aacute;s aburrida de la historia.</label>
                <input type="radio" id="d8_b" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="d8_b" class="opcion-label">B. Las pel&iacute;culas de ciencia ficci&oacute;n superan en calidad a los dramas.</label>
                <input type="radio" id="d8_c" name="respuestas[8]" value="correcta" class="opcion-input"><label for="d8_c" class="opcion-label">C. Gabriel Garc&iacute;a M&aacute;rquez recibi&oacute; el Premio Nobel de Literatura en 1982.</label>
                <input type="radio" id="d8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="d8_d" class="opcion-label">D. Ning&uacute;n autor contempor&aacute;neo escribe tan bien como los cl&aacute;sicos.</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>En la estructura de un ensayo acad&eacute;mico argumentativo, la funci&oacute;n principal de la conclusi&oacute;n es:</p>
                <input type="radio" id="d9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="d9_a" class="opcion-label">A. Introducir nuevos temas que no se abordaron en el cuerpo del texto.</label>
                <input type="radio" id="d9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="d9_b" class="opcion-label">B. Contradecir la tesis principal para generar debate en el lector.</label>
                <input type="radio" id="d9_c" name="respuestas[9]" value="correcta" class="opcion-input"><label for="d9_c" class="opcion-label">C. Sintetizar los argumentos clave y reafirmar la tesis a la luz de lo analizado.</label>
                <input type="radio" id="d9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="d9_d" class="opcion-label">D. Incluir todas las citas bibliogr&aacute;ficas utilizadas durante la investigaci&oacute;n.</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Cuando un lector eval&uacute;a rigurosamente la autoridad del autor, la fecha de publicaci&oacute;n y la objetividad de un texto digital, est&aacute; aplicando una estrategia de:</p>
                <input type="radio" id="d10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="d10_a" class="opcion-label">A. Memorizaci&oacute;n mec&aacute;nica.</label>
                <input type="radio" id="d10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="d10_b" class="opcion-label">B. Escritura creativa.</label>
                <input type="radio" id="d10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="d10_c" class="opcion-label">C. Valoraci&oacute;n y evaluaci&oacute;n cr&iacute;tica de fuentes.</label>
                <input type="radio" id="d10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="d10_d" class="opcion-label">D. Resumen literal de contenidos.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Lee el siguiente p&aacute;rrafo:<br><br><em>"Los museos interactivos de ciencia fomentan la curiosidad infantil mediante la experimentaci&oacute;n directa. Por consiguiente, deber&iacute;an ser de entrada libre para todas las instituciones educativas p&uacute;blicas."</em><br><br>&iquest;Qu&eacute; relaci&oacute;n l&oacute;gica establece la expresi&oacute;n <strong>"por consiguiente"</strong>?</p>
                <input type="radio" id="d11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="d11_a" class="opcion-label">A. Oposici&oacute;n.</label>
                <input type="radio" id="d11_b" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="d11_b" class="opcion-label">B. Condici&oacute;n.</label>
                <input type="radio" id="d11_c" name="respuestas[11]" value="correcta" class="opcion-input"><label for="d11_c" class="opcion-label">C. Consecuencia o deducci&oacute;n.</label>
                <input type="radio" id="d11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="d11_d" class="opcion-label">D. Temporalidad.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>&iquest;Cu&aacute;l de las siguientes opciones describe un vicio de argumentaci&oacute;n o falacia en un debate?</p>
                <input type="radio" id="d12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="d12_a" class="opcion-label">A. Presentar cifras estadísticas verificables.</label>
                <input type="radio" id="d12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="d12_b" class="opcion-label">B. Citar a un experto reconocido en la materia.</label>
                <input type="radio" id="d12_c" name="respuestas[12]" value="correcta" class="opcion-input"><label for="d12_c" class="opcion-label">C. Atacar a la persona que emite el argumento en lugar de rebatir sus ideas.</label>
                <input type="radio" id="d12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="d12_d" class="opcion-label">D. Exponer una premisa respaldada por evidencia emp&iacute;rica.</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>En el proceso de redacci&oacute;n avanzada, la etapa de <strong>revisi&oacute;n estilstica y de cohesi&oacute;n</strong> busca principalmente:</p>
                <input type="radio" id="d13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="d13_a" class="opcion-label">A. Cambiar por completo el t&iacute;tulo del trabajo.</label>
                <input type="radio" id="d13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="d13_b" class="opcion-label">B. Aumentar el margen de las p&aacute;ginas.</label>
                <input type="radio" id="d13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="d13_c" class="opcion-label">C. Pulir la fluidez del texto, evitar redundancias y asegurar una transici&oacute;n arm&oacute;nica entre par&aacute;grafos.</label>
                <input type="radio" id="d13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="d13_d" class="opcion-label">D. Traducir autom&aacute;ticamente el documento a otro idioma.</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Lee el fragmento:<br><br><em>"Los defensores del patrimonio hist&oacute;rico argumentan que la remodelaci&oacute;n destruir&iacute;a la identidad arquitect&oacute;nica del barrio; los promotores inmobiliarios, en cambio, sostienen que modernizar la zona dinamizar&iacute;a la econom&iacute;a local."</em><br><br>&iquest;Qu&eacute; estructura argumentativa se evidencia?</p>
                <input type="radio" id="d14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="d14_a" class="opcion-label">A. Una secuencia cronol&oacute;gica de eventos hist&oacute;ricos.</label>
                <input type="radio" id="d14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="d14_b" class="opcion-label">B. Un desglose de causas y efectos lineales.</label>
                <input type="radio" id="d14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="d14_c" class="opcion-label">C. La confrontaci&oacute;n de dos posturas opuestas frente a un mismo dilema.</label>
                <input type="radio" id="d14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="d14_d" class="opcion-label">D. Una definici&oacute;n conceptual abstracta.</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Un estudiante analiza un discurso pol&iacute;tico detectando qu&eacute; promesas carecen de sustento real y cu&aacute;les apelaciones buscan manipular las emociones de la audiencia.<br>&iquest;Qu&eacute; competencia lectora de alto nivel est&aacute; aplicando?</p>
                <input type="radio" id="d15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="d15_a" class="opcion-label">A. Lectura literal y memorizaci&oacute;n de datos.</label>
                <input type="radio" id="d15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="d15_b" class="opcion-label">B. Decodificaci&oacute;n fon&eacute;tica de palabras.</label>
                <input type="radio" id="d15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="d15_c" class="opcion-label">C. Lectura cr&iacute;tica e interpretaci&oacute;n ret&oacute;rica profunda.</label>
                <input type="radio" id="d15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="d15_d" class="opcion-label">D. Resumen ejecutivo de contenido.</label>
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