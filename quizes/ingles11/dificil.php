<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../paginas/login.php");
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
    $id_quiz = 130; // Quiz Inglés 11° difícil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (101, 'Inglés', 'Materiales para aprender y practicar inglés.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (3, 'Difícil', 'Preguntas de nivel avanzado.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (130, 101, 3, 'Quiz de Inglés - Nivel Avanzado Grado 11', 'Quiz avanzado de inglés', 11)");

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
    <title>Repazo | Quiz Inglés 11° - Avanzado</title>
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
            <h1>Quiz de Inglés - Nivel Avanzado (Grado 11°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 1</h2>
                <p>Read the situation: <strong>Mark arrived late to the interview because he had forgotten the appointment time.</strong> Why did Mark arrive late?</p>
                <input type="radio" id="ia11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="ia11_1_a" class="opcion-label">A. Because he forgets the interview.</label>
                <input type="radio" id="ia11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="ia11_1_b" class="opcion-label">B. Because he had forgotten the appointment time.</label>
                <input type="radio" id="ia11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="ia11_1_c" class="opcion-label">C. Because he will forget the appointment.</label>
                <input type="radio" id="ia11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="ia11_1_d" class="opcion-label">D. Because he was forgetting the interview.</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 2</h2>
                <p>Choose the correct mixed conditional: <strong>If she had studied medicine, she ___ a doctor now.</strong></p>
                <input type="radio" id="ia11_2_a" name="respuestas[2]" value="correcta" class="opcion-input" required><label for="ia11_2_a" class="opcion-label">A. would be</label>
                <input type="radio" id="ia11_2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ia11_2_b" class="opcion-label">B. would have been</label>
                <input type="radio" id="ia11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ia11_2_c" class="opcion-label">C. will be</label>
                <input type="radio" id="ia11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ia11_2_d" class="opcion-label">D. had been</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 3</h2>
                <p>Choose the correct indirect question: <strong>"Where did he buy that laptop?"</strong></p>
                <input type="radio" id="ia11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="ia11_3_a" class="opcion-label">A. Do you know where did he buy that laptop?</label>
                <input type="radio" id="ia11_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="ia11_3_b" class="opcion-label">B. Do you know where he bought that laptop?</label>
                <input type="radio" id="ia11_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="ia11_3_c" class="opcion-label">C. Do you know where bought he that laptop?</label>
                <input type="radio" id="ia11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="ia11_3_d" class="opcion-label">D. Do you know where he did bought that laptop?</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 4</h2>
                <p>Choose the correct adjective order: <strong>She owns a ___ necklace.</strong></p>
                <input type="radio" id="ia11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="ia11_4_a" class="opcion-label">A. gold beautiful old</label>
                <input type="radio" id="ia11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="ia11_4_b" class="opcion-label">B. beautiful old gold</label>
                <input type="radio" id="ia11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="ia11_4_c" class="opcion-label">C. old gold beautiful</label>
                <input type="radio" id="ia11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="ia11_4_d" class="opcion-label">D. gold old beautiful</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 5</h2>
                <p>Choose the correct question tag: <strong>Nobody called you, ___?</strong></p>
                <input type="radio" id="ia11_5_a" name="respuestas[5]" value="correcta" class="opcion-input" required><label for="ia11_5_a" class="opcion-label">A. did they?</label>
                <input type="radio" id="ia11_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ia11_5_b" class="opcion-label">B. didn't they?</label>
                <input type="radio" id="ia11_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ia11_5_c" class="opcion-label">C. didn't nobody?</label>
                <input type="radio" id="ia11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ia11_5_d" class="opcion-label">D. doesn't they?</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 6</h2>
                <p>Read the situation: <strong>Sarah couldn't enter the university because she hadn't completed the required documents.</strong> What happened first?</p>
                <input type="radio" id="ia11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="ia11_6_a" class="opcion-label">A. Sarah entered the university.</label>
                <input type="radio" id="ia11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="ia11_6_b" class="opcion-label">B. Sarah had not completed the required documents.</label>
                <input type="radio" id="ia11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="ia11_6_c" class="opcion-label">C. Sarah completed the university course.</label>
                <input type="radio" id="ia11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="ia11_6_d" class="opcion-label">D. Sarah will send the documents.</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 7</h2>
                <p>Choose the correct mixed conditional: <strong>If I hadn't wasted so much time, I ___ more successful now.</strong></p>
                <input type="radio" id="ia11_7_a" name="respuestas[7]" value="correcta" class="opcion-input" required><label for="ia11_7_a" class="opcion-label">A. would be</label>
                <input type="radio" id="ia11_7_b" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ia11_7_b" class="opcion-label">B. will be</label>
                <input type="radio" id="ia11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ia11_7_c" class="opcion-label">C. would have been</label>
                <input type="radio" id="ia11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ia11_7_d" class="opcion-label">D. had been</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 8</h2>
                <p>Choose the correct indirect question: <strong>"Can you help me with this problem?"</strong></p>
                <input type="radio" id="ia11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="ia11_8_a" class="opcion-label">A. Could you tell me can you help me?</label>
                <input type="radio" id="ia11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="ia11_8_b" class="opcion-label">B. Could you tell me if you can help me with this problem?</label>
                <input type="radio" id="ia11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="ia11_8_c" class="opcion-label">C. Could you tell me do you help me?</label>
                <input type="radio" id="ia11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="ia11_8_d" class="opcion-label">D. Could you tell me you can help?</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 9</h2>
                <p>Choose the correct comparative sentence: <strong>This exam was ___ than the previous one.</strong></p>
                <input type="radio" id="ia11_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="ia11_9_a" class="opcion-label">A. difficulter</label>
                <input type="radio" id="ia11_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="ia11_9_b" class="opcion-label">B. more difficult</label>
                <input type="radio" id="ia11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="ia11_9_c" class="opcion-label">C. the most difficult</label>
                <input type="radio" id="ia11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="ia11_9_d" class="opcion-label">D. difficultest</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 10</h2>
                <p>Choose the correct question tag: <strong>Let's study for the exam, ___?</strong></p>
                <input type="radio" id="ia11_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="ia11_10_a" class="opcion-label">A. will we?</label>
                <input type="radio" id="ia11_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="ia11_10_b" class="opcion-label">B. shall we?</label>
                <input type="radio" id="ia11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="ia11_10_c" class="opcion-label">C. don't we?</label>
                <input type="radio" id="ia11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="ia11_10_d" class="opcion-label">D. aren't we?</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 11</h2>
                <p>Read the situation: <strong>When the scientist presented the invention, she had already tested it many times.</strong> What happened first?</p>
                <input type="radio" id="ia11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="ia11_11_a" class="opcion-label">A. She presented the invention.</label>
                <input type="radio" id="ia11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="ia11_11_b" class="opcion-label">B. She had tested the invention many times.</label>
                <input type="radio" id="ia11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="ia11_11_c" class="opcion-label">C. She will test the invention.</label>
                <input type="radio" id="ia11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="ia11_11_d" class="opcion-label">D. She is testing the invention.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 12</h2>
                <p>Choose the correct mixed conditional: <strong>If he had learned English earlier, he ___ better opportunities now.</strong></p>
                <input type="radio" id="ia11_12_a" name="respuestas[12]" value="correcta" class="opcion-input" required><label for="ia11_12_a" class="opcion-label">A. would have</label>
                <input type="radio" id="ia11_12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ia11_12_b" class="opcion-label">B. will have</label>
                <input type="radio" id="ia11_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ia11_12_c" class="opcion-label">C. had</label>
                <input type="radio" id="ia11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ia11_12_d" class="opcion-label">D. would had</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 13</h2>
                <p>Choose the correct indirect question: <strong>"Why did the company change its strategy?"</strong></p>
                <input type="radio" id="ia11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="ia11_13_a" class="opcion-label">A. Do you know why did the company change its strategy?</label>
                <input type="radio" id="ia11_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="ia11_13_b" class="opcion-label">B. Do you know why the company changed its strategy?</label>
                <input type="radio" id="ia11_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="ia11_13_c" class="opcion-label">C. Do you know why changed the company its strategy?</label>
                <input type="radio" id="ia11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="ia11_13_d" class="opcion-label">D. Do you know why does the company changed its strategy?</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 14</h2>
                <p>Choose the correct option: <strong>This is ___ most important decision of my career.</strong></p>
                <input type="radio" id="ia11_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="ia11_14_a" class="opcion-label">A. a</label>
                <input type="radio" id="ia11_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="ia11_14_b" class="opcion-label">B. an</label>
                <input type="radio" id="ia11_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="ia11_14_c" class="opcion-label">C. the</label>
                <input type="radio" id="ia11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="ia11_14_d" class="opcion-label">D. no article</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 15</h2>
                <p>Choose the correct question tag: <strong>You haven't finished the assignment, ___?</strong></p>
                <input type="radio" id="ia11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="ia11_15_a" class="opcion-label">A. haven't you?</label>
                <input type="radio" id="ia11_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="ia11_15_b" class="opcion-label">B. have you?</label>
                <input type="radio" id="ia11_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="ia11_15_c" class="opcion-label">C. did you?</label>
                <input type="radio" id="ia11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="ia11_15_d" class="opcion-label">D. do you?</label>
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