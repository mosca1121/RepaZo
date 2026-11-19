<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../paginas/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$grado = "10";
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
    $id_quiz = 107; // Quiz Física 10° fácil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (103, 'Física', 'Materiales para estudiar ciencias físicas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (1, 'Fácil', 'Preguntas de nivel básico.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (107, 103, 1, 'Quiz de Física - Nivel Fácil Grado 10', 'Quiz básico de física', 10)");

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
    <title>Repazo | Quiz Física 10° - Fácil</title>
    <link href="../../assets/css/codigo.css" rel="stylesheet" />
    <link href="../../assets/css/quizes.css" rel="stylesheet" />
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
            <img alt="logo de Repazo" decoding="async" loading="lazy" src="../../assets/img/LOGOREPAZO.jpg" />
            <h2>Repazo</h2>
        </div>
        <nav>
            <ul>
                <li><a href="../../paginas/panel.php">Módulos</a></li>
                <li><a href="../../paginas/quizes.php">Quizzes</a></li>
                <li><a href="../../paginas/curiosidades.php">Datos Curiosos</a></li>
                <li><a href="../../paginas/perfil.php">Perfil</a></li>
                <li><a href="../../acciones/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Quiz de Física - Nivel Fácil (Grado 10°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Un autom&oacute;vil recorre una carretera con velocidad constante de <strong>60 km/h</strong>. &iquest;Qu&eacute; tipo de movimiento realiza?</p>
                <input type="radio" id="ff1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="ff1_a" class="opcion-label">A. Movimiento circular</label>
                <input type="radio" id="ff1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="ff1_b" class="opcion-label">B. Movimiento rectil&iacute;neo uniforme (MRU)</label>
                <input type="radio" id="ff1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="ff1_c" class="opcion-label">C. Movimiento acelerado</label>
                <input type="radio" id="ff1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="ff1_d" class="opcion-label">D. Movimiento oscilatorio</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Un ciclista recorre 100 metros en 20 segundos. &iquest;Cu&aacute;l es su velocidad promedio?</p>
                <input type="radio" id="ff2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="ff2_a" class="opcion-label">A. 2 m/s</label>
                <input type="radio" id="ff2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="ff2_b" class="opcion-label">B. 5 m/s</label>
                <input type="radio" id="ff2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ff2_c" class="opcion-label">C. 10 m/s</label>
                <input type="radio" id="ff2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ff2_d" class="opcion-label">D. 20 m/s</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>La primera ley de Newton establece que un objeto:</p>
                <input type="radio" id="ff3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="ff3_a" class="opcion-label">A. Siempre aumenta su velocidad.</label>
                <input type="radio" id="ff3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="ff3_b" class="opcion-label">B. Mantiene su estado si no act&uacute;a una fuerza externa.</label>
                <input type="radio" id="ff3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="ff3_c" class="opcion-label">C. Tiene energ&iacute;a infinita.</label>
                <input type="radio" id="ff3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="ff3_d" class="opcion-label">D. Nunca puede moverse.</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>&iquest;Cu&aacute;l es la unidad de medida de la fuerza en el Sistema Internacional?</p>
                <input type="radio" id="ff4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="ff4_a" class="opcion-label">A. Joule</label>
                <input type="radio" id="ff4_b" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="ff4_b" class="opcion-label">B. Watt</label>
                <input type="radio" id="ff4_c" name="respuestas[4]" value="correcta" class="opcion-input"><label for="ff4_c" class="opcion-label">C. Newton</label>
                <input type="radio" id="ff4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="ff4_d" class="opcion-label">D. Pascal</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Una persona empuja una caja y logra moverla. La energ&iacute;a utilizada para realizar este movimiento se conoce como:</p>
                <input type="radio" id="ff5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="ff5_a" class="opcion-label">A. Temperatura</label>
                <input type="radio" id="ff5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="ff5_b" class="opcion-label">B. Trabajo mec&aacute;nico</label>
                <input type="radio" id="ff5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ff5_c" class="opcion-label">C. Masa</label>
                <input type="radio" id="ff5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ff5_d" class="opcion-label">D. Densidad</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un objeto tiene una masa de <strong>5 kg</strong> y una aceleraci&oacute;n de <strong>2 m/s&sup2;</strong>. Seg&uacute;n la segunda ley de Newton, la fuerza aplicada es:</p>
                <input type="radio" id="ff6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="ff6_a" class="opcion-label">A. 2 N</label>
                <input type="radio" id="ff6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="ff6_b" class="opcion-label">B. 10 N</label>
                <input type="radio" id="ff6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="ff6_c" class="opcion-label">C. 15 N</label>
                <input type="radio" id="ff6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="ff6_d" class="opcion-label">D. 20 N</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>&iquest;Qu&eacute; instrumento se utiliza para medir la temperatura?</p>
                <input type="radio" id="ff7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="ff7_a" class="opcion-label">A. Balanza</label>
                <input type="radio" id="ff7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="ff7_b" class="opcion-label">B. Term&oacute;metro</label>
                <input type="radio" id="ff7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ff7_c" class="opcion-label">C. Cron&oacute;metro</label>
                <input type="radio" id="ff7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ff7_d" class="opcion-label">D. Regla</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Una pelota cae desde una altura debido a la gravedad. &iquest;Qu&eacute; tipo de movimiento realiza?</p>
                <input type="radio" id="ff8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="ff8_a" class="opcion-label">A. Movimiento uniforme</label>
                <input type="radio" id="ff8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="ff8_b" class="opcion-label">B. Movimiento acelerado</label>
                <input type="radio" id="ff8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="ff8_c" class="opcion-label">C. Movimiento sin velocidad</label>
                <input type="radio" id="ff8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="ff8_d" class="opcion-label">D. Movimiento circular</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>La energ&iacute;a que posee un objeto debido a su movimiento se llama:</p>
                <input type="radio" id="ff9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="ff9_a" class="opcion-label">A. Energ&iacute;a potencial</label>
                <input type="radio" id="ff9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="ff9_b" class="opcion-label">B. Energ&iacute;a cin&eacute;tica</label>
                <input type="radio" id="ff9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="ff9_c" class="opcion-label">C. Energ&iacute;a t&eacute;rmica</label>
                <input type="radio" id="ff9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="ff9_d" class="opcion-label">D. Energ&iacute;a qu&iacute;mica</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>En un circuito el&eacute;ctrico, el dispositivo que permite abrir o cerrar el paso de corriente es:</p>
                <input type="radio" id="ff10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="ff10_a" class="opcion-label">A. Bombillo</label>
                <input type="radio" id="ff10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="ff10_b" class="opcion-label">B. Bater&iacute;a</label>
                <input type="radio" id="ff10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="ff10_c" class="opcion-label">C. Interruptor</label>
                <input type="radio" id="ff10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="ff10_d" class="opcion-label">D. Cable</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Un estudiante observa que una onda se repite varias veces en un segundo. La cantidad de repeticiones por segundo se conoce como:</p>
                <input type="radio" id="ff11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="ff11_a" class="opcion-label">A. Amplitud</label>
                <input type="radio" id="ff11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="ff11_b" class="opcion-label">B. Frecuencia</label>
                <input type="radio" id="ff11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="ff11_c" class="opcion-label">C. Longitud</label>
                <input type="radio" id="ff11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="ff11_d" class="opcion-label">D. Fuerza</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Una persona recorre 200 metros en l&iacute;nea recta. La magnitud que indica cu&aacute;nto espacio recorri&oacute; se llama:</p>
                <input type="radio" id="ff12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="ff12_a" class="opcion-label">A. Tiempo</label>
                <input type="radio" id="ff12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="ff12_b" class="opcion-label">B. Distancia</label>
                <input type="radio" id="ff12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ff12_c" class="opcion-label">C. Masa</label>
                <input type="radio" id="ff12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ff12_d" class="opcion-label">D. Temperatura</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Cuando una fuerza provoca que un objeto cambie su movimiento, se produce:</p>
                <input type="radio" id="ff13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="ff13_a" class="opcion-label">A. Un cambio de color</label>
                <input type="radio" id="ff13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="ff13_b" class="opcion-label">B. Una aceleraci&oacute;n</label>
                <input type="radio" id="ff13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="ff13_c" class="opcion-label">C. Una disminuci&oacute;n de masa</label>
                <input type="radio" id="ff13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="ff13_d" class="opcion-label">D. Una eliminaci&oacute;n de energ&iacute;a</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>&iquest;Cu&aacute;l de los siguientes elementos es un conductor el&eacute;ctrico?</p>
                <input type="radio" id="ff14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="ff14_a" class="opcion-label">A. Pl&aacute;stico</label>
                <input type="radio" id="ff14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="ff14_b" class="opcion-label">B. Madera seca</label>
                <input type="radio" id="ff14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="ff14_c" class="opcion-label">C. Cobre</label>
                <input type="radio" id="ff14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="ff14_d" class="opcion-label">D. Vidrio</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>La energ&iacute;a almacenada en un objeto debido a su posici&oacute;n o altura se llama:</p>
                <input type="radio" id="ff15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="ff15_a" class="opcion-label">A. Energ&iacute;a cin&eacute;tica</label>
                <input type="radio" id="ff15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="ff15_b" class="opcion-label">B. Energ&iacute;a potencial</label>
                <input type="radio" id="ff15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="ff15_c" class="opcion-label">C. Energ&iacute;a el&eacute;ctrica</label>
                <input type="radio" id="ff15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="ff15_d" class="opcion-label">D. Energ&iacute;a sonora</label>
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
            <a href="../../paginas/quizes.php" class="btn" style="text-decoration: none; display: inline-block; padding: 10px 25px;">Volver a Quizzes</a>
        </div>
    </div>
    <?php endif; ?>

    <footer>
        <section class="footer1"><h2>Repazo</h2><p>Aprender nunca fue tan divertido.</p></section>
        <section class="footer3"><h3>Síguenos</h3></section>
    </footer>
</body>
</html>