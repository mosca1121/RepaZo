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
    $id_quiz = 108; // Quiz Física 10° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (103, 'Física', 'Materiales para estudiar ciencias físicas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (2, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (108, 103, 2, 'Quiz de Física - Nivel Medio Grado 10', 'Quiz intermedio de física', 10)");

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
    <title>Repazo | Quiz Física 10° - Medio</title>
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
            <h1>Quiz de Física - Nivel Medio (Grado 10°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Un autom&oacute;vil parte del reposo y alcanza una velocidad de <strong>20 m/s</strong> en 5 segundos. &iquest;Cu&aacute;l es su aceleraci&oacute;n promedio?</p>
                <input type="radio" id="fm1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="fm1_a" class="opcion-label">A. 2 m/s&sup2;</label>
                <input type="radio" id="fm1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="fm1_b" class="opcion-label">B. 4 m/s&sup2;</label>
                <input type="radio" id="fm1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="fm1_c" class="opcion-label">C. 5 m/s&sup2;</label>
                <input type="radio" id="fm1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="fm1_d" class="opcion-label">D. 10 m/s&sup2;</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Una caja de 10 kg es empujada con una fuerza de 50 N sobre una superficie sin fricci&oacute;n. &iquest;Cu&aacute;l es la aceleraci&oacute;n de la caja?</p>
                <input type="radio" id="fm2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="fm2_a" class="opcion-label">A. 2 m/s&sup2;</label>
                <input type="radio" id="fm2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="fm2_b" class="opcion-label">B. 5 m/s&sup2;</label>
                <input type="radio" id="fm2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="fm2_c" class="opcion-label">C. 10 m/s&sup2;</label>
                <input type="radio" id="fm2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="fm2_d" class="opcion-label">D. 50 m/s&sup2;</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Un estudiante levanta una mochila de 8 kg hasta una altura de 2 metros. La energ&iacute;a relacionada con la altura de la mochila es:</p>
                <input type="radio" id="fm3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="fm3_a" class="opcion-label">A. Energ&iacute;a cin&eacute;tica</label>
                <input type="radio" id="fm3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="fm3_b" class="opcion-label">B. Energ&iacute;a potencial gravitatoria</label>
                <input type="radio" id="fm3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="fm3_c" class="opcion-label">C. Energ&iacute;a el&eacute;ctrica</label>
                <input type="radio" id="fm3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="fm3_d" class="opcion-label">D. Energ&iacute;a sonora</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Un corredor aumenta su velocidad constantemente durante una competencia. &iquest;Qu&eacute; ocurre con su movimiento?</p>
                <input type="radio" id="fm4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="fm4_a" class="opcion-label">A. Tiene velocidad constante.</label>
                <input type="radio" id="fm4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="fm4_b" class="opcion-label">B. Presenta aceleraci&oacute;n positiva.</label>
                <input type="radio" id="fm4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="fm4_c" class="opcion-label">C. Est&aacute; en reposo.</label>
                <input type="radio" id="fm4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="fm4_d" class="opcion-label">D. No existe fuerza.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Una persona aplica una fuerza de 100 N para mover una caja 3 metros. &iquest;Cu&aacute;l es el trabajo realizado?</p>
                <input type="radio" id="fm5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="fm5_a" class="opcion-label">A. 30 J</label>
                <input type="radio" id="fm5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="fm5_b" class="opcion-label">B. 100 J</label>
                <input type="radio" id="fm5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="fm5_c" class="opcion-label">C. 300 J</label>
                <input type="radio" id="fm5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="fm5_d" class="opcion-label">D. 500 J</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un veh&iacute;culo viaja a una velocidad de <strong>72 km/h</strong>. &iquest;Cu&aacute;l es su velocidad en metros por segundo?</p>
                <input type="radio" id="fm6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="fm6_a" class="opcion-label">A. 10 m/s</label>
                <input type="radio" id="fm6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="fm6_b" class="opcion-label">B. 20 m/s</label>
                <input type="radio" id="fm6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="fm6_c" class="opcion-label">C. 36 m/s</label>
                <input type="radio" id="fm6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="fm6_d" class="opcion-label">D. 72 m/s</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Un objeto de 4 kg acelera a raz&oacute;n de 3 m/s&sup2;. &iquest;Cu&aacute;l es la fuerza aplicada sobre el objeto?</p>
                <input type="radio" id="fm7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="fm7_a" class="opcion-label">A. 7 N</label>
                <input type="radio" id="fm7_b" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="fm7_b" class="opcion-label">B. 10 N</label>
                <input type="radio" id="fm7_c" name="respuestas[7]" value="correcta" class="opcion-input"><label for="fm7_c" class="opcion-label">C. 12 N</label>
                <input type="radio" id="fm7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="fm7_d" class="opcion-label">D. 15 N</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Un astronauta tiene una masa de 70 kg en la Tierra. Al viajar a la Luna, su masa:</p>
                <input type="radio" id="fm8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="fm8_a" class="opcion-label">A. Aumenta mucho.</label>
                <input type="radio" id="fm8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="fm8_b" class="opcion-label">B. Permanece igual.</label>
                <input type="radio" id="fm8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="fm8_c" class="opcion-label">C. Desaparece.</label>
                <input type="radio" id="fm8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="fm8_d" class="opcion-label">D. Se convierte en energ&iacute;a.</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>En una monta&ntilde;a rusa, cuando el carro est&aacute; en la parte m&aacute;s alta de una subida, posee principalmente:</p>
                <input type="radio" id="fm9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="fm9_a" class="opcion-label">A. Energ&iacute;a sonora.</label>
                <input type="radio" id="fm9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="fm9_b" class="opcion-label">B. Energ&iacute;a potencial gravitatoria.</label>
                <input type="radio" id="fm9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="fm9_c" class="opcion-label">C. Energ&iacute;a nuclear.</label>
                <input type="radio" id="fm9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="fm9_d" class="opcion-label">D. Energ&iacute;a el&eacute;ctrica.</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Un circuito tiene una resistencia de 5 &Omega; y una corriente de 2 A. Seg&uacute;n la ley de Ohm, &iquest;cu&aacute;l es el voltaje?</p>
                <input type="radio" id="fm10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="fm10_a" class="opcion-label">A. 2 V</label>
                <input type="radio" id="fm10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="fm10_b" class="opcion-label">B. 5 V</label>
                <input type="radio" id="fm10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="fm10_c" class="opcion-label">C. 10 V</label>
                <input type="radio" id="fm10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="fm10_d" class="opcion-label">D. 20 V</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Un objeto cae desde una altura y aumenta su velocidad mientras baja. &iquest;Cu&aacute;l es la causa principal de este cambio?</p>
                <input type="radio" id="fm11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="fm11_a" class="opcion-label">A. La resistencia del aire.</label>
                <input type="radio" id="fm11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="fm11_b" class="opcion-label">B. La aceleraci&oacute;n producida por la gravedad.</label>
                <input type="radio" id="fm11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="fm11_c" class="opcion-label">C. La disminuci&oacute;n de la masa.</label>
                <input type="radio" id="fm11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="fm11_d" class="opcion-label">D. La ausencia de fuerzas.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Una m&aacute;quina realiza un trabajo de 600 J en 30 segundos. &iquest;Cu&aacute;l es su potencia?</p>
                <input type="radio" id="fm12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="fm12_a" class="opcion-label">A. 10 W</label>
                <input type="radio" id="fm12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="fm12_b" class="opcion-label">B. 20 W</label>
                <input type="radio" id="fm12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="fm12_c" class="opcion-label">C. 30 W</label>
                <input type="radio" id="fm12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="fm12_d" class="opcion-label">D. 60 W</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Dos fuerzas act&uacute;an sobre un objeto en la misma direcci&oacute;n: 20 N y 30 N. &iquest;Cu&aacute;l es la fuerza resultante?</p>
                <input type="radio" id="fm13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="fm13_a" class="opcion-label">A. 10 N</label>
                <input type="radio" id="fm13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="fm13_b" class="opcion-label">B. 20 N</label>
                <input type="radio" id="fm13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="fm13_c" class="opcion-label">C. 50 N</label>
                <input type="radio" id="fm13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="fm13_d" class="opcion-label">D. 60 N</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Un estudiante conecta una bater&iacute;a, cables y un bombillo formando un circuito cerrado. &iquest;Qu&eacute; ocurre?</p>
                <input type="radio" id="fm14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="fm14_a" class="opcion-label">A. No existe corriente el&eacute;ctrica.</label>
                <input type="radio" id="fm14_b" name="respuestas[14]" value="correcta" class="opcion-input"><label for="fm14_b" class="opcion-label">B. La corriente puede circular y encender el bombillo.</label>
                <input type="radio" id="fm14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="fm14_c" class="opcion-label">C. La bater&iacute;a pierde su masa.</label>
                <input type="radio" id="fm14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="fm14_d" class="opcion-label">D. El circuito se vuelve magn&eacute;tico siempre.</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una onda aumenta su altura m&aacute;xima sin cambiar su frecuencia. &iquest;Qu&eacute; propiedad cambi&oacute;?</p>
                <input type="radio" id="fm15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="fm15_a" class="opcion-label">A. Velocidad.</label>
                <input type="radio" id="fm15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="fm15_b" class="opcion-label">B. Amplitud.</label>
                <input type="radio" id="fm15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="fm15_c" class="opcion-label">C. Periodo.</label>
                <input type="radio" id="fm15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="fm15_d" class="opcion-label">D. Masa.</label>
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