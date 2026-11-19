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
    $id_quiz = 128; // Quiz Inglés 11° fácil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (101, 'Inglés', 'Materiales para aprender y practicar inglés.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (1, 'Fácil', 'Preguntas de nivel básico.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (128, 101, 1, 'Quiz de Inglés - Nivel Fácil Grado 11', 'Quiz básico de inglés', 11)");

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
    <title>Repazo | Quiz Inglés 11° - Fácil</title>
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
            <h1>Quiz de Inglés - Nivel Fácil (Grado 11°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 1</h2>
                <p>Choose the correct option: <strong>He ___ studying English for two years.</strong></p>
                <input type="radio" id="if11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="if11_1_a" class="opcion-label">A. have been</label>
                <input type="radio" id="if11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="if11_1_b" class="opcion-label">B. has been</label>
                <input type="radio" id="if11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="if11_1_c" class="opcion-label">C. is</label>
                <input type="radio" id="if11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="if11_1_d" class="opcion-label">D. was</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 2</h2>
                <p>Choose the correct option: <strong>They ___ travel to Canada next year.</strong></p>
                <input type="radio" id="if11_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="if11_2_a" class="opcion-label">A. traveled</label>
                <input type="radio" id="if11_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="if11_2_b" class="opcion-label">B. will</label>
                <input type="radio" id="if11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="if11_2_c" class="opcion-label">C. have</label>
                <input type="radio" id="if11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="if11_2_d" class="opcion-label">D. were</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 3</h2>
                <p>What does the modal verb <strong>"should"</strong> express?</p>
                <input type="radio" id="if11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="if11_3_a" class="opcion-label">A. Past action</label>
                <input type="radio" id="if11_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="if11_3_b" class="opcion-label">B. Advice or recommendation</label>
                <input type="radio" id="if11_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="if11_3_c" class="opcion-label">C. Possession</label>
                <input type="radio" id="if11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="if11_3_d" class="opcion-label">D. Location</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 4</h2>
                <p>Choose the correct sentence:</p>
                <input type="radio" id="if11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="if11_4_a" class="opcion-label">A. She have visited Paris.</label>
                <input type="radio" id="if11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="if11_4_b" class="opcion-label">B. She has visited Paris.</label>
                <input type="radio" id="if11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="if11_4_c" class="opcion-label">C. She visited has Paris.</label>
                <input type="radio" id="if11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="if11_4_d" class="opcion-label">D. She have visit Paris.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 5</h2>
                <p>Choose the correct meaning: <strong>"I am interested in science."</strong></p>
                <input type="radio" id="if11_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="if11_5_a" class="opcion-label">A. I hate science.</label>
                <input type="radio" id="if11_5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="if11_5_b" class="opcion-label">B. Science is something I like.</label>
                <input type="radio" id="if11_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="if11_5_c" class="opcion-label">C. I don't understand science.</label>
                <input type="radio" id="if11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="if11_5_d" class="opcion-label">D. I never study science.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 6</h2>
                <p>Choose the correct option: <strong>My parents ___ watching a movie now.</strong></p>
                <input type="radio" id="if11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="if11_6_a" class="opcion-label">A. is</label>
                <input type="radio" id="if11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="if11_6_b" class="opcion-label">B. are</label>
                <input type="radio" id="if11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="if11_6_c" class="opcion-label">C. am</label>
                <input type="radio" id="if11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="if11_6_d" class="opcion-label">D. be</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 7</h2>
                <p>Choose the correct option: <strong>She ___ her homework yesterday.</strong></p>
                <input type="radio" id="if11_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="if11_7_a" class="opcion-label">A. do</label>
                <input type="radio" id="if11_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="if11_7_b" class="opcion-label">B. did</label>
                <input type="radio" id="if11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="if11_7_c" class="opcion-label">C. done</label>
                <input type="radio" id="if11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="if11_7_d" class="opcion-label">D. doing</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 8</h2>
                <p>Choose the correct option: <strong>You ___ study more if you want better grades.</strong></p>
                <input type="radio" id="if11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="if11_8_a" class="opcion-label">A. mustn't</label>
                <input type="radio" id="if11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="if11_8_b" class="opcion-label">B. should</label>
                <input type="radio" id="if11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="if11_8_c" class="opcion-label">C. can't</label>
                <input type="radio" id="if11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="if11_8_d" class="opcion-label">D. won't</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 9</h2>
                <p>What is the opposite of <strong>"expensive"</strong>?</p>
                <input type="radio" id="if11_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="if11_9_a" class="opcion-label">A. Large</label>
                <input type="radio" id="if11_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="if11_9_b" class="opcion-label">B. Cheap</label>
                <input type="radio" id="if11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="if11_9_c" class="opcion-label">C. Heavy</label>
                <input type="radio" id="if11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="if11_9_d" class="opcion-label">D. Difficult</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 10</h2>
                <p>Choose the correct sentence:</p>
                <input type="radio" id="if11_10_a" name="respuestas[10]" value="correcta" class="opcion-input" required><label for="if11_10_a" class="opcion-label">A. I have never seen that movie.</label>
                <input type="radio" id="if11_10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="if11_10_b" class="opcion-label">B. I never have saw that movie.</label>
                <input type="radio" id="if11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="if11_10_c" class="opcion-label">C. I has never seen that movie.</label>
                <input type="radio" id="if11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="if11_10_d" class="opcion-label">D. I never seen that movie.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 11</h2>
                <p>Choose the correct option: <strong>There ___ many students in the classroom.</strong></p>
                <input type="radio" id="if11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="if11_11_a" class="opcion-label">A. is</label>
                <input type="radio" id="if11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="if11_11_b" class="opcion-label">B. are</label>
                <input type="radio" id="if11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="if11_11_c" class="opcion-label">C. am</label>
                <input type="radio" id="if11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="if11_11_d" class="opcion-label">D. be</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 12</h2>
                <p>Choose the correct option: <strong>My sister is ___ than me.</strong></p>
                <input type="radio" id="if11_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="if11_12_a" class="opcion-label">A. tall</label>
                <input type="radio" id="if11_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="if11_12_b" class="opcion-label">B. taller</label>
                <input type="radio" id="if11_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="if11_12_c" class="opcion-label">C. tallest</label>
                <input type="radio" id="if11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="if11_12_d" class="opcion-label">D. more tall</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 13</h2>
                <p>Choose the correct option: <strong>We ___ English since we were children.</strong></p>
                <input type="radio" id="if11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="if11_13_a" class="opcion-label">A. learn</label>
                <input type="radio" id="if11_13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="if11_13_b" class="opcion-label">B. learned</label>
                <input type="radio" id="if11_13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="if11_13_c" class="opcion-label">C. have learned</label>
                <input type="radio" id="if11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="if11_13_d" class="opcion-label">D. learning</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 14</h2>
                <p>What does the word <strong>"environment"</strong> mean?</p>
                <input type="radio" id="if11_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="if11_14_a" class="opcion-label">A. Technology</label>
                <input type="radio" id="if11_14_b" name="respuestas[14]" value="correcta" class="opcion-input"><label for="if11_14_b" class="opcion-label">B. The natural world around us</label>
                <input type="radio" id="if11_14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="if11_14_c" class="opcion-label">C. A computer program</label>
                <input type="radio" id="if11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="if11_14_d" class="opcion-label">D. A type of food</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 15</h2>
                <p>Choose the correct option: <strong>They ___ finish the project tomorrow.</strong></p>
                <input type="radio" id="if11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="if11_15_a" class="opcion-label">A. finished</label>
                <input type="radio" id="if11_15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="if11_15_b" class="opcion-label">B. finishing</label>
                <input type="radio" id="if11_15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="if11_15_c" class="opcion-label">C. will</label>
                <input type="radio" id="if11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="if11_15_d" class="opcion-label">D. have</label>
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