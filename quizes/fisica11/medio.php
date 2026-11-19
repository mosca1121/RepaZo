<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$grado = "11";
$resultado_guardado = false;
$puntaje_obtenido = 0;
$total_preguntas = 15;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuestas = $_POST['respuestas'] ?? [];
    $correctas = 0;
    foreach ($respuestas as $val) {
        if ($val === "correcta") { $correctas++; }
    }
    $incorrectas = $total_preguntas - $correctas;
    $puntaje_obtenido = ($correctas / $total_preguntas) * 100;

    $id_quiz = 123;
    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (2, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (103, 'Física', 'Materiales para estudiar ciencias físicas.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (123, 103, 2, 'Quiz de Física - Nivel Medio Grado 11', 'Quiz intermedio de física', 11)");
        $stmt = $conexion->prepare("INSERT INTO intentos_quizzes (id_usuario, id_quiz, porcentaje, respuestas_correctas, respuestas_incorrectas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidii", $id_usuario, $id_quiz, $puntaje_obtenido, $correctas, $incorrectas);
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
    <title>Repazo | Quiz Física 11° - Medio</title>
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
            <h1>Quiz de Física - Nivel Medio (Grado 11°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <?php if ($resultado_guardado): ?>
            <div class="resultado-final">
                <h2>¡Quiz Finalizado!</h2>
                <p>Tus resultados se han registrado con éxito en tu perfil.</p>
                <p style="font-size: 22px; font-weight: bold; margin-top: 15px; color: #43A047;">Puntaje: <?php echo round($puntaje_obtenido, 1); ?>%</p>
                <br>
                <a href="../../quizes.php" class="btn">Volver a Quizzes</a>
            </div>
        <?php else: ?>
            <form action="quiz_fisica_11_medio.php" method="POST">
                <h2 class="tituloMainT">Preguntas del quiz</h2>

                <!-- PREGUNTA 1 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                    <p>Una rueda de bicicleta gira a velocidad constante. La fuerza que mantiene la rueda siguiendo una trayectoria circular es:</p>
                    <input type="radio" id="m11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="m11_1_a" class="opcion-label">A. Fuerza el&eacute;ctrica.</label>
                    <input type="radio" id="m11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="m11_1_b" class="opcion-label">B. Fuerza centr&iacute;peta[cite: 16].</label>
                    <input type="radio" id="m11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="m11_1_c" class="opcion-label">C. Fuerza t&eacute;rmica.</label>
                    <input type="radio" id="m11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="m11_1_d" class="opcion-label">D. Fuerza nuclear.</label>
                </section>

                <!-- PREGUNTA 2 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                    <p>Un sat&eacute;lite aumenta su distancia respecto a la Tierra. &iquest;Qu&eacute; ocurre con la fuerza gravitacional que recibe?</p>
                    <input type="radio" id="m11_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="m11_2_a" class="opcion-label">A. Aumenta.</label>
                    <input type="radio" id="m11_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="m11_2_b" class="opcion-label">B. Disminuye[cite: 16].</label>
                    <input type="radio" id="m11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="m11_2_c" class="opcion-label">C. Permanece siempre igual.</label>
                    <input type="radio" id="m11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="m11_2_d" class="opcion-label">D. Desaparece completamente.</label>
                </section>

                <!-- PREGUNTA 3 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                    <p>Un circuito tiene un voltaje de 12 V y una resistencia de 4 &Omega;. &iquest;Cu&aacute;l es la corriente el&eacute;ctrica?</p>
                    <input type="radio" id="m11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="m11_3_a" class="opcion-label">A. 2 A</label>
                    <input type="radio" id="m11_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="m11_3_b" class="opcion-label">B. 3 A[cite: 16]</label>
                    <input type="radio" id="m11_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="m11_3_c" class="opcion-label">C. 4 A</label>
                    <input type="radio" id="m11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="m11_3_d" class="opcion-label">D. 48 A</label>
                </section>

                <!-- PREGUNTA 4 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                    <p>Un conductor el&eacute;ctrico con corriente genera alrededor de &eacute;l:</p>
                    <input type="radio" id="m11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="m11_4_a" class="opcion-label">A. Un campo gravitacional.</label>
                    <input type="radio" id="m11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="m11_4_b" class="opcion-label">B. Un campo magn&eacute;tico[cite: 16].</label>
                    <input type="radio" id="m11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="m11_4_c" class="opcion-label">C. Una disminuci&oacute;n de masa.</label>
                    <input type="radio" id="m11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="m11_4_d" class="opcion-label">D. Energ&iacute;a qu&iacute;mica.</label>
                </section>

                <!-- PREGUNTA 5 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                    <p>Un objeto de 10 kg se mueve con una velocidad de 4 m/s. Su energ&iacute;a cin&eacute;tica es:</p>
                    <input type="radio" id="m11_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="m11_5_a" class="opcion-label">A. 20 J</label>
                    <input type="radio" id="m11_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="m11_5_b" class="opcion-label">B. 40 J</label>
                    <input type="radio" id="m11_5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="m11_5_c" class="opcion-label">C. 80 J[cite: 16]</label>
                    <input type="radio" id="m11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="m11_5_d" class="opcion-label">D. 160 J</label>
                </section>

                <!-- PREGUNTA 6 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                    <p>Un autom&oacute;vil toma una curva a alta velocidad. Para evitar salir de la trayectoria, la fuerza centr&iacute;peta depende principalmente de:</p>
                    <input type="radio" id="m11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="m11_6_a" class="opcion-label">A. El color del autom&oacute;vil.</label>
                    <input type="radio" id="m11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="m11_6_b" class="opcion-label">B. La masa, velocidad y radio de la curva[cite: 16].</label>
                    <input type="radio" id="m11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="m11_6_c" class="opcion-label">C. La temperatura del motor.</label>
                    <input type="radio" id="m11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="m11_6_d" class="opcion-label">D. La cantidad de combustible.</label>
                </section>

                <!-- PREGUNTA 7 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                    <p>Un planeta tiene mayor masa que otro planeta. Seg&uacute;n la ley de gravitaci&oacute;n universal, su fuerza de atracci&oacute;n ser&aacute;:</p>
                    <input type="radio" id="m11_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="m11_7_a" class="opcion-label">A. Menor siempre.</label>
                    <input type="radio" id="m11_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="m11_7_b" class="opcion-label">B. Mayor si la distancia es la misma[cite: 16].</label>
                    <input type="radio" id="m11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="m11_7_c" class="opcion-label">C. Igual en todos los casos.</label>
                    <input type="radio" id="m11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="m11_7_d" class="opcion-label">D. Cero.</label>
                </section>

                <!-- PREGUNTA 8 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                    <p>Un dispositivo el&eacute;ctrico consume 600 J de energ&iacute;a en 30 segundos. &iquest;Cu&aacute;l es su potencia?</p>
                    <input type="radio" id="m11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="m11_8_a" class="opcion-label">A. 10 W</label>
                    <input type="radio" id="m11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="m11_8_b" class="opcion-label">B. 20 W[cite: 16]</label>
                    <input type="radio" id="m11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="m11_8_c" class="opcion-label">C. 30 W</label>
                    <input type="radio" id="m11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="m11_8_d" class="opcion-label">D. 60 W</label>
                </section>

                <!-- PREGUNTA 9 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                    <p>En un experimento, una onda aumenta su frecuencia manteniendo la misma velocidad. &iquest;Qu&eacute; sucede con su longitud de onda?</p>
                    <input type="radio" id="m11_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="m11_9_a" class="opcion-label">A. Aumenta.</label>
                    <input type="radio" id="m11_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="m11_9_b" class="opcion-label">B. Disminuye[cite: 16].</label>
                    <input type="radio" id="m11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="m11_9_c" class="opcion-label">C. No cambia.</label>
                    <input type="radio" id="m11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="m11_9_d" class="opcion-label">D. Desaparece.</label>
                </section>

                <!-- PREGUNTA 10 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                    <p>Un motor el&eacute;ctrico funciona porque transforma energ&iacute;a:</p>
                    <input type="radio" id="m11_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="m11_10_a" class="opcion-label">A. T&eacute;rmica en qu&iacute;mica.</label>
                    <input type="radio" id="m11_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="m11_10_b" class="opcion-label">B. El&eacute;ctrica en mec&aacute;nica[cite: 16].</label>
                    <input type="radio" id="m11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="m11_10_c" class="opcion-label">C. Mec&aacute;nica en nuclear.</label>
                    <input type="radio" id="m11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="m11_10_d" class="opcion-label">D. Qu&iacute;mica en sonora.</label>
                </section>

                <!-- PREGUNTA 11 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                    <p>Un generador el&eacute;ctrico produce corriente gracias al interacci&oacute;n entre:</p>
                    <input type="radio" id="m11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="m11_11_a" class="opcion-label">A. Calor y masa.</label>
                    <input type="radio" id="m11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="m11_11_b" class="opcion-label">B. Campo magn&eacute;tico y movimiento[cite: 16].</label>
                    <input type="radio" id="m11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="m11_11_c" class="opcion-label">C. Presi&oacute;n y volumen.</label>
                    <input type="radio" id="m11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="m11_11_d" class="opcion-label">D. Temperatura y densidad.</label>
                </section>

                <!-- PREGUNTA 12 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                    <p>Una part&iacute;cula con carga el&eacute;ctrica se mueve dentro de un campo magn&eacute;tico. La fuerza que puede afectar su trayectoria se llama:</p>
                    <input type="radio" id="m11_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="m11_12_a" class="opcion-label">A. Fuerza gravitacional.</label>
                    <input type="radio" id="m11_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="m11_12_b" class="opcion-label">B. Fuerza magn&eacute;tica[cite: 16].</label>
                    <input type="radio" id="m11_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="m11_12_c" class="opcion-label">C. Fuerza de fricci&oacute;n.</label>
                    <input type="radio" id="m11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="m11_12_d" class="opcion-label">D. Fuerza t&eacute;rmica.</label>
                </section>

                <!-- PREGUNTA 13 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                    <p>Un objeto aumenta su velocidad al caer desde una altura. La energ&iacute;a potencial gravitatoria se transforma principalmente en:</p>
                    <input type="radio" id="m11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="m11_13_a" class="opcion-label">A. Energ&iacute;a qu&iacute;mica.</label>
                    <input type="radio" id="m11_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="m11_13_b" class="opcion-label">B. Energ&iacute;a cin&eacute;tica[cite: 16].</label>
                    <input type="radio" id="m11_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="m11_13_c" class="opcion-label">C. Energ&iacute;a nuclear.</label>
                    <input type="radio" id="m11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="m11_13_d" class="opcion-label">D. Energ&iacute;a el&eacute;ctrica.</label>
                </section>

                <!-- PREGUNTA 14 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                    <p>Un circuito el&eacute;ctrico tiene una resistencia mayor mientras mantiene el mismo voltaje. Seg&uacute;n la ley de Ohm, la corriente:</p>
                    <input type="radio" id="m11_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="m11_14_a" class="opcion-label">A. Aumenta.</label>
                    <input type="radio" id="m11_14_b" name="respuestas[14]" value="correcta" class="opcion-input"><label for="m11_14_b" class="opcion-label">B. Disminuye[cite: 16].</label>
                    <input type="radio" id="m11_14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="m11_14_c" class="opcion-label">C. Se vuelve infinita.</label>
                    <input type="radio" id="m11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="m11_14_d" class="opcion-label">D. No cambia nunca.</label>
                </section>

                <!-- PREGUNTA 15 -->
                <section class="tema">
                    <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                    <p>Un cient&iacute;fico analiza la luz de una estrella para conocer su composici&oacute;n. Este estudio pertenece al &aacute;rea de:</p>
                    <input type="radio" id="m11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="m11_15_a" class="opcion-label">A. Mec&aacute;nica cl&aacute;sica.</label>
                    <input type="radio" id="m11_15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="m11_15_b" class="opcion-label">B. Termodin&aacute;mica.</label>
                    <input type="radio" id="m11_15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="m11_15_c" class="opcion-label">C. F&iacute;sica moderna y espectroscopia[cite: 16].</label>
                    <input type="radio" id="m11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="m11_15_d" class="opcion-label">D. Hidrost&aacute;tica.</label>
                </section>

                <div style="text-align: center; margin-top: 40px;">
                    <button type="submit" class="btn" style="border:none; cursor:pointer; font-size: 18px; padding: 15px 40px;">Enviar Respuestas</button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <section class="footer1"><h2>Repazo</h2><p>Aprender nunca fue tan divertido.</p></section>
        <section class="footer3"><h3>Síguenos</h3></section>
    </footer>
</body>
</html>