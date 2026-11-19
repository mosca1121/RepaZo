<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../login.html");
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
    $id_quiz = 114; // Quiz Inglés 10° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (101, 'Inglés', 'Materiales para aprender y practicar inglés.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (2, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (114, 101, 2, 'Quiz de Inglés - Nivel Medio Grado 10', 'Quiz intermedio de inglés', 10)");

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
    <title>Repazo | Quiz Inglés 10° - Medio</title>
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
            <h1>Quiz de Inglés - Nivel Medio (Grado 10°)</h1>
            <p>Responde cada pregunta y demuestra lo que has aprendido.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 1</h2>
                <p>Choose the correct option: <strong>She ___ already finished her project.</strong></p>
                <input type="radio" id="im1_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="im1_1_a" class="opcion-label">A. have</label>
                <input type="radio" id="im1_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="im1_1_b" class="opcion-label">B. has</label>
                <input type="radio" id="im1_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="im1_1_c" class="opcion-label">C. did</label>
                <input type="radio" id="im1_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="im1_1_d" class="opcion-label">D. is</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 2</h2>
                <p>A student says: <strong>"I have lived in Medellín for five years."</strong> What tense is used?</p>
                <input type="radio" id="im1_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="im1_2_a" class="opcion-label">A. Past simple</label>
                <input type="radio" id="im1_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="im1_2_b" class="opcion-label">B. Present perfect</label>
                <input type="radio" id="im1_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="im1_2_c" class="opcion-label">C. Future simple</label>
                <input type="radio" id="im1_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="im1_2_d" class="opcion-label">D. Present continuous</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 3</h2>
                <p>Choose the correct option: <strong>They ___ visit their grandparents next weekend.</strong></p>
                <input type="radio" id="im1_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="im1_3_a" class="opcion-label">A. visited</label>
                <input type="radio" id="im1_3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="im1_3_b" class="opcion-label">B. visits</label>
                <input type="radio" id="im1_3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="im1_3_c" class="opcion-label">C. are going to</label>
                <input type="radio" id="im1_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="im1_3_d" class="opcion-label">D. were</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 4</h2>
                <p>Read the sentence: <strong>"Tom studies every day because he wants to pass his exams."</strong> Why does Tom study every day?</p>
                <input type="radio" id="im1_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="im1_4_a" class="opcion-label">A. Because he likes games.</label>
                <input type="radio" id="im1_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="im1_4_b" class="opcion-label">B. Because he wants to pass his exams.</label>
                <input type="radio" id="im1_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="im1_4_c" class="opcion-label">C. Because he dislikes school.</label>
                <input type="radio" id="im1_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="im1_4_d" class="opcion-label">D. Because he is on vacation.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 5</h2>
                <p>Choose the correct sentence:</p>
                <input type="radio" id="im1_5_a" name="respuestas[5]" value="correcta" class="opcion-input" required><label for="im1_5_a" class="opcion-label">A. I have never traveled by plane.</label>
                <input type="radio" id="im1_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="im1_5_b" class="opcion-label">B. I never have traveled by plane.</label>
                <input type="radio" id="im1_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="im1_5_c" class="opcion-label">C. I has never traveled by plane.</label>
                <input type="radio" id="im1_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="im1_5_d" class="opcion-label">D. I never traveled by plane since.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 6</h2>
                <p>Choose the correct option: <strong>If it rains tomorrow, I ___ stay at home.</strong></p>
                <input type="radio" id="im1_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="im1_6_a" class="opcion-label">A. stayed</label>
                <input type="radio" id="im1_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="im1_6_b" class="opcion-label">B. will</label>
                <input type="radio" id="im1_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="im1_6_c" class="opcion-label">C. am</label>
                <input type="radio" id="im1_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="im1_6_d" class="opcion-label">D. have</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 7</h2>
                <p>Choose the correct sentence:</p>
                <input type="radio" id="im1_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="im1_7_a" class="opcion-label">A. She don't like mathematics.</label>
                <input type="radio" id="im1_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="im1_7_b" class="opcion-label">B. She doesn't like mathematics.</label>
                <input type="radio" id="im1_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="im1_7_c" class="opcion-label">C. She doesn't likes mathematics.</label>
                <input type="radio" id="im1_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="im1_7_d" class="opcion-label">D. She not like mathematics.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 8</h2>
                <p>A person says: <strong>"I was studying when my friend called me."</strong> What happened first?</p>
                <input type="radio" id="im1_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="im1_8_a" class="opcion-label">A. The friend called.</label>
                <input type="radio" id="im1_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="im1_8_b" class="opcion-label">B. The person was studying.</label>
                <input type="radio" id="im1_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="im1_8_c" class="opcion-label">C. The person went home.</label>
                <input type="radio" id="im1_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="im1_8_d" class="opcion-label">D. The person finished studying.</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 9</h2>
                <p>Choose the correct word: <strong>My father is ___ than my brother.</strong></p>
                <input type="radio" id="im1_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="im1_9_a" class="opcion-label">A. tall</label>
                <input type="radio" id="im1_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="im1_9_b" class="opcion-label">B. taller</label>
                <input type="radio" id="im1_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="im1_9_c" class="opcion-label">C. tallest</label>
                <input type="radio" id="im1_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="im1_9_d" class="opcion-label">D. more tall</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 10</h2>
                <p>Read: <strong>"Maria has studied English since 2022."</strong> The word <strong>"since"</strong> indicates:</p>
                <input type="radio" id="im1_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="im1_10_a" class="opcion-label">A. A future action</label>
                <input type="radio" id="im1_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="im1_10_b" class="opcion-label">B. The beginning of an action in time</label>
                <input type="radio" id="im1_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="im1_10_c" class="opcion-label">C. A place</label>
                <input type="radio" id="im1_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="im1_10_d" class="opcion-label">D. A person</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 11</h2>
                <p>Choose the correct option: <strong>My friends ___ to the cinema yesterday.</strong></p>
                <input type="radio" id="im1_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="im1_11_a" class="opcion-label">A. go</label>
                <input type="radio" id="im1_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="im1_11_b" class="opcion-label">B. went</label>
                <input type="radio" id="im1_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="im1_11_c" class="opcion-label">C. gone</label>
                <input type="radio" id="im1_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="im1_11_d" class="opcion-label">D. going</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 12</h2>
                <p>Choose the correct option: <strong>I have lived here ___ 2020.</strong></p>
                <input type="radio" id="im1_12_a" name="respuestas[12]" value="correcta" class="opcion-input" required><label for="im1_12_a" class="opcion-label">A. since</label>
                <input type="radio" id="im1_12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="im1_12_b" class="opcion-label">B. for</label>
                <input type="radio" id="im1_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="im1_12_c" class="opcion-label">C. during</label>
                <input type="radio" id="im1_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="im1_12_d" class="opcion-label">D. from</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 13</h2>
                <p>Choose the correct option: <strong>There isn't ___ milk in the fridge.</strong></p>
                <input type="radio" id="im1_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="im1_13_a" class="opcion-label">A. many</label>
                <input type="radio" id="im1_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="im1_13_b" class="opcion-label">B. much</label>
                <input type="radio" id="im1_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="im1_13_c" class="opcion-label">C. a few</label>
                <input type="radio" id="im1_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="im1_13_d" class="opcion-label">D. several</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 14</h2>
                <p>Read the sentence: <strong>"John can speak three languages."</strong> The word <strong>"can"</strong> expresses:</p>
                <input type="radio" id="im1_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="im1_14_a" class="opcion-label">A. Obligation</label>
                <input type="radio" id="im1_14_b" name="respuestas[14]" value="correcta" class="opcion-input"><label for="im1_14_b" class="opcion-label">B. Ability</label>
                <input type="radio" id="im1_14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="im1_14_c" class="opcion-label">C. Past action</label>
                <input type="radio" id="im1_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="im1_14_d" class="opcion-label">D. Possession</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Question 15</h2>
                <p>Choose the correct option: <strong>Students must wear uniforms at school.</strong> The word <strong>"must"</strong> means:</p>
                <input type="radio" id="im1_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="im1_15_a" class="opcion-label">A. Permission</label>
                <input type="radio" id="im1_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="im1_15_b" class="opcion-label">B. Obligation</label>
                <input type="radio" id="im1_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="im1_15_c" class="opcion-label">C. Ability</label>
                <input type="radio" id="im1_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="im1_15_d" class="opcion-label">D. Possibility</label>
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