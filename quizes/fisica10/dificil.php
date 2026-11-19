<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../login.html");
    exit;
}

$id_usuario =$_SESSION["id_usuario"];
$grado = "11";
$resultado_guardado = false;
$porcentaje = 0.00;
$total_preguntas = 15;
$correctas = 0;
$incorrectas = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuestas =$_POST['respuestas'] ?? [];
    foreach ($respuestas as$val) {
        if ($val === "correcta") { 
            $correctas++; 
        }
    }
    $incorrectas = $total_preguntas -$correctas;
    $porcentaje = ($correctas / $total_preguntas) * 100;
    $id_quiz = 109; // Quiz Física 10° difícil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (103, 'Física', 'Materiales para estudiar ciencias físicas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (3, 'Difícil', 'Preguntas de nivel avanzado.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (109, 103, 3, 'Quiz de Física - Nivel Avanzado Grado 10', 'Quiz avanzado de física', 10)");

        $stmt =$conexion->prepare("INSERT INTO intentos_quizzes (id_usuario, id_quiz, porcentaje, respuestas_correctas, respuestas_incorrectas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidii", $id_usuario,$id_quiz, $porcentaje,$correctas, $incorrectas);$stmt->execute();
        $stmt->close();$conexion->close();
    }
    $resultado_guardado = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Repazo | Quiz Física 11° - Avanzado</title>
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
            <h1>Quiz de Física - Nivel Avanzado (Grado 11°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Un sat&eacute;lite artificial gira alrededor de la Tierra con una velocidad constante. Aunque su rapidez no cambia, existe aceleraci&oacute;n porque:</p>
                <input type="radio" id="a11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="a11_1_a" class="opcion-label">A. Su masa aumenta constantemente.</label>
                <input type="radio" id="a11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="a11_1_b" class="opcion-label">B. La direcci&oacute;n de su velocidad cambia continuamente.</label>
                <input type="radio" id="a11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="a11_1_c" class="opcion-label">C. No existe ninguna fuerza sobre &eacute;l.</label>
                <input type="radio" id="a11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="a11_1_d" class="opcion-label">D. Su energ&iacute;a desaparece.</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Un planeta se encuentra al doble de distancia de una estrella. Seg&uacute;n la ley de gravitaci&oacute;n universal, la fuerza de atracci&oacute;n:</p>
                <input type="radio" id="a11_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="a11_2_a" class="opcion-label">A. Se duplica.</label>
                <input type="radio" id="a11_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="a11_2_b" class="opcion-label">B. Disminuye cuatro veces.</label>
                <input type="radio" id="a11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="a11_2_c" class="opcion-label">C. Aumenta cuatro veces.</label>
                <input type="radio" id="a11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="a11_2_d" class="opcion-label">D. No cambia.</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Un motor el&eacute;ctrico consume 1200 J de energ&iacute;a en 20 segundos. &iquest;Cu&aacute;l es su potencia?</p>
                <input type="radio" id="a11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="a11_3_a" class="opcion-label">A. 40 W</label>
                <input type="radio" id="a11_3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="a11_3_b" class="opcion-label">B. 50 W</label>
                <input type="radio" id="a11_3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="a11_3_c" class="opcion-label">C. 60 W</label>
                <input type="radio" id="a11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="a11_3_d" class="opcion-label">D. 80 W</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Una part&iacute;cula con carga el&eacute;ctrica entra en un campo magn&eacute;tico perpendicular a su movimiento. &iquest;Qu&eacute; ocurre?</p>
                <input type="radio" id="a11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="a11_4_a" class="opcion-label">A. Aumenta su masa.</label>
                <input type="radio" id="a11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="a11_4_b" class="opcion-label">B. Cambia la direcci&oacute;n de su trayectoria.</label>
                <input type="radio" id="a11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="a11_4_c" class="opcion-label">C. Pierde toda su energ&iacute;a.</label>
                <input type="radio" id="a11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="a11_4_d" class="opcion-label">D. Se detiene inmediatamente.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Una planta hidroel&eacute;ctrica transforma la energ&iacute;a del agua en electricidad. La secuencia correcta es:</p>
                <input type="radio" id="a11_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="a11_5_a" class="opcion-label">A. El&eacute;ctrica &rarr; qu&iacute;mica &rarr; mec&aacute;nica.</label>
                <input type="radio" id="a11_5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="a11_5_b" class="opcion-label">B. Potencial gravitatoria &rarr; mec&aacute;nica &rarr; el&eacute;ctrica.</label>
                <input type="radio" id="a11_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="a11_5_c" class="opcion-label">C. T&eacute;rmica &rarr; nuclear &rarr; el&eacute;ctrica.</label>
                <input type="radio" id="a11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="a11_5_d" class="opcion-label">D. Qu&iacute;mica &rarr; sonora &rarr; el&eacute;ctrica.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un autom&oacute;vil toma una curva con mayor velocidad manteniendo el mismo radio. &iquest;Qu&eacute; sucede con la fuerza centr&iacute;peta necesaria?</p>
                <input type="radio" id="a11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="a11_6_a" class="opcion-label">A. Disminuye.</label>
                <input type="radio" id="a11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="a11_6_b" class="opcion-label">B. Aumenta.</label>
                <input type="radio" id="a11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="a11_6_c" class="opcion-label">C. Permanece igual.</label>
                <input type="radio" id="a11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="a11_6_d" class="opcion-label">D. Desaparece.</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Dos cargas el&eacute;ctricas del mismo signo se acercan entre s&iacute;. Seg&uacute;n la interacci&oacute;n el&eacute;ctrica:</p>
                <input type="radio" id="a11_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="a11_7_a" class="opcion-label">A. Se atraen.</label>
                <input type="radio" id="a11_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="a11_7_b" class="opcion-label">B. Se repelen.</label>
                <input type="radio" id="a11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="a11_7_c" class="opcion-label">C. Pierden su carga.</label>
                <input type="radio" id="a11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="a11_7_d" class="opcion-label">D. Desaparece el campo el&eacute;ctrico.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Un objeto de 5 kg se mueve con una velocidad de 10 m/s. &iquest;Cu&aacute;l es su energ&iacute;a cin&eacute;tica?</p>
                <input type="radio" id="a11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="a11_8_a" class="opcion-label">A. 50 J</label>
                <input type="radio" id="a11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="a11_8_b" class="opcion-label">B. 250 J</label>
                <input type="radio" id="a11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="a11_8_c" class="opcion-label">C. 500 J</label>
                <input type="radio" id="a11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="a11_8_d" class="opcion-label">D. 1000 J</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>Un panel solar convierte la energ&iacute;a de la radiaci&oacute;n del Sol en:</p>
                <input type="radio" id="a11_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="a11_9_a" class="opcion-label">A. Energ&iacute;a mec&aacute;nica &uacute;nicamente.</label>
                <input type="radio" id="a11_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="a11_9_b" class="opcion-label">B. Energ&iacute;a el&eacute;ctrica.</label>
                <input type="radio" id="a11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="a11_9_c" class="opcion-label">C. Energ&iacute;a nuclear.</label>
                <input type="radio" id="a11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="a11_9_d" class="opcion-label">D. Energ&iacute;a gravitacional.</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Una onda electromagn&eacute;tica aumenta su frecuencia. &iquest;Qu&eacute; ocurre con su energ&iacute;a?</p>
                <input type="radio" id="a11_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="a11_10_a" class="opcion-label">A. Disminuye.</label>
                <input type="radio" id="a11_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="a11_10_b" class="opcion-label">B. Aumenta.</label>
                <input type="radio" id="a11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="a11_10_c" class="opcion-label">C. Se mantiene siempre igual.</label>
                <input type="radio" id="a11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="a11_10_d" class="opcion-label">D. Desaparece.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Un astronauta en el espacio observa que un objeto contin&uacute;a movi&eacute;ndose sin detenerse. Esto se explica principalmente por:</p>
                <input type="radio" id="a11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="a11_11_a" class="opcion-label">A. La existencia de una fuerza constante hacia adelante.</label>
                <input type="radio" id="a11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="a11_11_b" class="opcion-label">B. La ley de inercia de Newton.</label>
                <input type="radio" id="a11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="a11_11_c" class="opcion-label">C. La ausencia total de masa.</label>
                <input type="radio" id="a11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="a11_11_d" class="opcion-label">D. La gravedad del Sol &uacute;nicamente.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Un transformador el&eacute;ctrico aumenta el voltaje de una red el&eacute;ctrica. Este dispositivo funciona gracias al fen&oacute;meno de:</p>
                <input type="radio" id="a11_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="a11_12_a" class="opcion-label">A. Combusti&oacute;n.</label>
                <input type="radio" id="a11_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="a11_12_b" class="opcion-label">B. Inducci&oacute;n electromagn&eacute;tica.</label>
                <input type="radio" id="a11_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="a11_12_c" class="opcion-label">C. Gravedad.</label>
                <input type="radio" id="a11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="a11_12_d" class="opcion-label">D. Fusi&oacute;n nuclear.</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Un cient&iacute;fico analiza la radiaci&oacute;n emitida por una estrella para conocer los elementos que contiene. &iquest;Qu&eacute; t&eacute;cnica utiliza?</p>
                <input type="radio" id="a11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="a11_13_a" class="opcion-label">A. Mec&aacute;nica.</label>
                <input type="radio" id="a11_13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="a11_13_b" class="opcion-label">B. Din&aacute;mica.</label>
                <input type="radio" id="a11_13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="a11_13_c" class="opcion-label">C. Espectroscopia.</label>
                <input type="radio" id="a11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="a11_13_d" class="opcion-label">D. Hidrost&aacute;tica.</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>En un circuito el&eacute;ctrico, si el voltaje aumenta y la resistencia permanece constante, la corriente:</p>
                <input type="radio" id="a11_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="a11_14_a" class="opcion-label">A. Disminuye.</label>
                <input type="radio" id="a11_14_b" name="respuestas[14]" value="correcta" class="opcion-input"><label for="a11_14_b" class="opcion-label">B. Aumenta.</label>
                <input type="radio" id="a11_14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="a11_14_c" class="opcion-label">C. Desaparece.</label>
                <input type="radio" id="a11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="a11_14_d" class="opcion-label">D. No cambia.</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una central nuclear produce energ&iacute;a utilizando principalmente:</p>
                <input type="radio" id="a11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="a11_15_a" class="opcion-label">A. La combusti&oacute;n del carb&oacute;n.</label>
                <input type="radio" id="a11_15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="a11_15_b" class="opcion-label">B. La energ&iacute;a del viento.</label>
                <input type="radio" id="a11_15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="a11_15_c" class="opcion-label">C. La fisi&oacute;n de n&uacute;cleos at&oacute;micos.</label>
                <input type="radio" id="a11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="a11_15_d" class="opcion-label">D. La evaporaci&oacute;n del agua solamente.</label>
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
            <h2><i class="fa-solid fa-circle-check" style="color: #43A047;"></i> ¡Quiz Finalizado con Éxito!</h2>
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
    