<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../paginas/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$resultado_guardado = false;
$porcentaje_obtenido = 0.00;
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
    $porcentaje_obtenido = ($correctas / $total_preguntas) * 100;

    $id_materia = 104; // Química
    $id_dificultad = 2; // Medio
    $id_quiz = 126; // Quiz Química 11° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Química', 'Materiales para estudiar química.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Química 11° - Nivel Medio', 'Quiz intermedio de química para grado 11', 11)");

        require_once __DIR__ . "/../guardar_intento.php";
        $conexion->set_charset("utf8mb4");
        $res = guardarIntentoQuiz($conexion, (int)$id_usuario, __FILE__, (int)$correctas, (int)$total_preguntas);
        $porcentaje = $res["porcentaje"];
        $incorrectas = $res["incorrectas"];
        $stmt = new class { public function bind_param(...$a){} public function execute(){} public function close(){} };
        $stmt->bind_param("iidii", $id_usuario, $id_quiz, $porcentaje_obtenido, $correctas, $incorrectas);
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
    <title>Repazo | Quiz Química 11° - Nivel Medio</title>
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
            <h1>Quiz de Química 11°</h1>
            <p>Nivel Medio. Responde cada pregunta y demuestra lo que has aprendido.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Química 11° - Nivel Medio</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Un estudiante analiza el compuesto <strong>C₂H₄</strong>. Según su estructura, este compuesto pertenece al grupo de los:</p>
                <input type="radio" id="qm11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="qm11_1_a" class="opcion-label">A. Alcanos</label>
                <input type="radio" id="qm11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="qm11_1_b" class="opcion-label">B. Alquenos</label>
                <input type="radio" id="qm11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qm11_1_c" class="opcion-label">C. Alcoholes</label>
                <input type="radio" id="qm11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qm11_1_d" class="opcion-label">D. Ácidos carboxílicos</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Una industria utiliza etanol (C₂H₅OH) como combustible. La combustión del etanol produce principalmente:</p>
                <input type="radio" id="qm11_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="qm11_2_a" class="opcion-label">A. Hidrógeno y carbono</label>
                <input type="radio" id="qm11_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="qm11_2_b" class="opcion-label">B. Dióxido de carbono y agua</label>
                <input type="radio" id="qm11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qm11_2_c" class="opcion-label">C. Oxígeno y nitrógeno</label>
                <input type="radio" id="qm11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qm11_2_d" class="opcion-label">D. Metales y sales</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Un químico estudia una reacción que absorbe calor del ambiente. Este tipo de reacción es:</p>
                <input type="radio" id="qm11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="qm11_3_a" class="opcion-label">A. Exotérmica</label>
                <input type="radio" id="qm11_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="qm11_3_b" class="opcion-label">B. Endotérmica</label>
                <input type="radio" id="qm11_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qm11_3_c" class="opcion-label">C. De combustión</label>
                <input type="radio" id="qm11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qm11_3_d" class="opcion-label">D. De oxidación siempre</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>En una celda electroquímica, el proceso donde una sustancia pierde electrones se llama:</p>
                <input type="radio" id="qm11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="qm11_4_a" class="opcion-label">A. Reducción</label>
                <input type="radio" id="qm11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="qm11_4_b" class="opcion-label">B. Oxidación</label>
                <input type="radio" id="qm11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qm11_4_c" class="opcion-label">C. Neutralización</label>
                <input type="radio" id="qm11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qm11_4_d" class="opcion-label">D. Evaporación</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Un compuesto orgánico presenta el grupo funcional <strong>-COOH</strong>. Este compuesto pertenece a la familia de:</p>
                <input type="radio" id="qm11_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="qm11_5_a" class="opcion-label">A. Alcoholes</label>
                <input type="radio" id="qm11_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qm11_5_b" class="opcion-label">B. Éteres</label>
                <input type="radio" id="qm11_5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="qm11_5_c" class="opcion-label">C. Ácidos carboxílicos</label>
                <input type="radio" id="qm11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qm11_5_d" class="opcion-label">D. Alquinos</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Una empresa fabrica perfumes utilizando compuestos orgánicos con enlaces entre carbono y oxígeno. Estos compuestos pueden pertenecer al grupo de:</p>
                <input type="radio" id="qm11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="qm11_6_a" class="opcion-label">A. Hidrocarburos únicamente</label>
                <input type="radio" id="qm11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="qm11_6_b" class="opcion-label">B. Alcoholes y éteres</label>
                <input type="radio" id="qm11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qm11_6_c" class="opcion-label">C. Metales alcalinos</label>
                <input type="radio" id="qm11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qm11_6_d" class="opcion-label">D. Gases nobles</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>En una reacción química, un catalizador tiene como función:</p>
                <input type="radio" id="qm11_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="qm11_7_a" class="opcion-label">A. Aumentar la cantidad de productos</label>
                <input type="radio" id="qm11_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="qm11_7_b" class="opcion-label">B. Acelerar la reacción sin consumirse</label>
                <input type="radio" id="qm11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qm11_7_c" class="opcion-label">C. Cambiar los elementos de la reacción</label>
                <input type="radio" id="qm11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qm11_7_d" class="opcion-label">D. Eliminar los reactivos</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Un laboratorio analiza la reacción: <strong>CH₄ + 2O₂ → CO₂ + 2H₂O</strong>. Esta reacción corresponde a:</p>
                <input type="radio" id="qm11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="qm11_8_a" class="opcion-label">A. Fermentación</label>
                <input type="radio" id="qm11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="qm11_8_b" class="opcion-label">B. Combustión</label>
                <input type="radio" id="qm11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qm11_8_c" class="opcion-label">C. Neutralización</label>
                <input type="radio" id="qm11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qm11_8_d" class="opcion-label">D. Electrólisis</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>En una batería, la energía química se transforma principalmente en energía:</p>
                <input type="radio" id="qm11_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="qm11_9_a" class="opcion-label">A. Térmica</label>
                <input type="radio" id="qm11_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="qm11_9_b" class="opcion-label">B. Eléctrica</label>
                <input type="radio" id="qm11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qm11_9_c" class="opcion-label">C. Mecánica</label>
                <input type="radio" id="qm11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qm11_9_d" class="opcion-label">D. Luminosa solamente</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Un compuesto orgánico con fórmula <strong>C₃H₈</strong> recibe el nombre de:</p>
                <input type="radio" id="qm11_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="qm11_10_a" class="opcion-label">A. Eteno</label>
                <input type="radio" id="qm11_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="qm11_10_b" class="opcion-label">B. Propano</label>
                <input type="radio" id="qm11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qm11_10_c" class="opcion-label">C. Propino</label>
                <input type="radio" id="qm11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qm11_10_d" class="opcion-label">D. Metanol</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Un estudiante mezcla un ácido con una base y obtiene sal y agua. Este tipo de reacción se conoce como:</p>
                <input type="radio" id="qm11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="qm11_11_a" class="opcion-label">A. Combustión</label>
                <input type="radio" id="qm11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="qm11_11_b" class="opcion-label">B. Neutralización</label>
                <input type="radio" id="qm11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qm11_11_c" class="opcion-label">C. Descomposición</label>
                <input type="radio" id="qm11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qm11_11_d" class="opcion-label">D. Polimerización</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>En una reacción de oxidación-reducción (redox), la especie que gana electrones se llama:</p>
                <input type="radio" id="qm11_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="qm11_12_a" class="opcion-label">A. Oxidante solamente</label>
                <input type="radio" id="qm11_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="qm11_12_b" class="opcion-label">B. Sustancia reducida</label>
                <input type="radio" id="qm11_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qm11_12_c" class="opcion-label">C. Catalizador</label>
                <input type="radio" id="qm11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qm11_12_d" class="opcion-label">D. Reactivo ácido</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Un científico analiza una molécula con muchos enlaces carbono-carbono repetidos. Este tipo de compuesto puede ser un:</p>
                <input type="radio" id="qm11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="qm11_13_a" class="opcion-label">A. Metal</label>
                <input type="radio" id="qm11_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="qm11_13_b" class="opcion-label">B. Polímero</label>
                <input type="radio" id="qm11_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qm11_13_c" class="opcion-label">C. Gas noble</label>
                <input type="radio" id="qm11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qm11_13_d" class="opcion-label">D. Ácido mineral</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Una reacción química tiene un cambio de energía negativo. Esto significa que la reacción:</p>
                <input type="radio" id="qm11_14_a" name="respuestas[14]" value="correcta" class="opcion-input" required><label for="qm11_14_a" class="opcion-label">A. Libera energía al ambiente</label>
                <input type="radio" id="qm11_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qm11_14_b" class="opcion-label">B. Absorbe energía del ambiente</label>
                <input type="radio" id="qm11_14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qm11_14_c" class="opcion-label">C. No tiene intercambio de energía</label>
                <input type="radio" id="qm11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qm11_14_d" class="opcion-label">D. No puede ocurrir</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una empresa produce biodiésel usando aceites vegetales. Este proceso pertenece principalmente al estudio de:</p>
                <input type="radio" id="qm11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="qm11_15_a" class="opcion-label">A. Química nuclear</label>
                <input type="radio" id="qm11_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="qm11_15_b" class="opcion-label">B. Química orgánica aplicada</label>
                <input type="radio" id="qm11_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qm11_15_c" class="opcion-label">C. Física cuántica</label>
                <input type="radio" id="qm11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qm11_15_d" class="opcion-label">D. Tabla periódica solamente</label>
            </section>

            <div style="text-align: center; margin-top: 40px;">
                <button type="submit" class="btn" style="border:none; cursor:pointer; font-size: 18px; padding: 15px 40px;">Enviar Respuestas</button>
            </div>
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
                Porcentaje Total: <?php echo round($porcentaje_obtenido, 1); ?>%
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