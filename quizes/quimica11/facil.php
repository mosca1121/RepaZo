<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../login.php");
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
    $id_dificultad = 1; // Fácil
    $id_quiz = 125; // Quiz Química 11° fácil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Química', 'Materiales para estudiar química.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Fácil', 'Preguntas de nivel básico.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Química 11° - Nivel Fácil', 'Quiz básico de química para grado 11', 11)");

        $stmt = $conexion->prepare("INSERT INTO intentos_quizzes (id_usuario, id_quiz, porcentaje, respuestas_correctas, respuestas_incorrectas) VALUES (?, ?, ?, ?, ?)");
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
    <title>Repazo | Quiz Química 11° - Nivel Fácil</title>
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
            <h1>Quiz de Química 11°</h1>
            <p>Nivel Fácil. Responde cada pregunta y demuestra lo que has aprendido.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Química 11° - Nivel Fácil</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>La química orgánica estudia principalmente los compuestos formados por:</p>
                <input type="radio" id="qf11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="qf11_1_a" class="opcion-label">A. Metales y minerales</label>
                <input type="radio" id="qf11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="qf11_1_b" class="opcion-label">B. Carbono y sus compuestos</label>
                <input type="radio" id="qf11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qf11_1_c" class="opcion-label">C. Solo agua y sales</label>
                <input type="radio" id="qf11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qf11_1_d" class="opcion-label">D. Gases nobles</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>¿Cuál es la fórmula general de los alcanos?</p>
                <input type="radio" id="qf11_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="qf11_2_a" class="opcion-label">A. CnH₂n</label>
                <input type="radio" id="qf11_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="qf11_2_b" class="opcion-label">B. CnH₂n+₂</label>
                <input type="radio" id="qf11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qf11_2_c" class="opcion-label">C. CnHn</label>
                <input type="radio" id="qf11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qf11_2_d" class="opcion-label">D. CnH₂n-₂</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>¿Cuál es el nombre del compuesto <strong>CH₄</strong>?</p>
                <input type="radio" id="qf11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="qf11_3_a" class="opcion-label">A. Etano</label>
                <input type="radio" id="qf11_3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qf11_3_b" class="opcion-label">B. Propano</label>
                <input type="radio" id="qf11_3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="qf11_3_c" class="opcion-label">C. Metano</label>
                <input type="radio" id="qf11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qf11_3_d" class="opcion-label">D. Butano</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Los compuestos que presentan un doble enlace carbono-carbono pertenecen al grupo de:</p>
                <input type="radio" id="qf11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="qf11_4_a" class="opcion-label">A. Alcanos</label>
                <input type="radio" id="qf11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="qf11_4_b" class="opcion-label">B. Alquenos</label>
                <input type="radio" id="qf11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qf11_4_c" class="opcion-label">C. Alquinos</label>
                <input type="radio" id="qf11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qf11_4_d" class="opcion-label">D. Alcoholes</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>¿Qué grupo funcional caracteriza a los alcoholes?</p>
                <input type="radio" id="qf11_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="qf11_5_a" class="opcion-label">A. -COOH</label>
                <input type="radio" id="qf11_5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="qf11_5_b" class="opcion-label">B. -OH</label>
                <input type="radio" id="qf11_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qf11_5_c" class="opcion-label">C. -NH₂</label>
                <input type="radio" id="qf11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qf11_5_d" class="opcion-label">D. -CHO</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>¿Cuál es el nombre del compuesto <strong>C₂H₆</strong>?</p>
                <input type="radio" id="qf11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="qf11_6_a" class="opcion-label">A. Metano</label>
                <input type="radio" id="qf11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="qf11_6_b" class="opcion-label">B. Etano</label>
                <input type="radio" id="qf11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qf11_6_c" class="opcion-label">C. Propano</label>
                <input type="radio" id="qf11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qf11_6_d" class="opcion-label">D. Eteno</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Los hidrocarburos están formados principalmente por los elementos:</p>
                <input type="radio" id="qf11_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="qf11_7_a" class="opcion-label">A. Oxígeno y nitrógeno</label>
                <input type="radio" id="qf11_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="qf11_7_b" class="opcion-label">B. Carbono e hidrógeno</label>
                <input type="radio" id="qf11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qf11_7_c" class="opcion-label">C. Calcio y oxígeno</label>
                <input type="radio" id="qf11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qf11_7_d" class="opcion-label">D. Sodio y cloro</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>¿Cuál es la fórmula química del propano?</p>
                <input type="radio" id="qf11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="qf11_8_a" class="opcion-label">A. C₂H₆</label>
                <input type="radio" id="qf11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="qf11_8_b" class="opcion-label">B. C₃H₈</label>
                <input type="radio" id="qf11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qf11_8_c" class="opcion-label">C. C₄H₁₀</label>
                <input type="radio" id="qf11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qf11_8_d" class="opcion-label">D. CH₄</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>En una reacción de combustión completa de un hidrocarburo se producen:</p>
                <input type="radio" id="qf11_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="qf11_9_a" class="opcion-label">A. Hidrógeno y carbono</label>
                <input type="radio" id="qf11_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="qf11_9_b" class="opcion-label">B. Dióxido de carbono y agua</label>
                <input type="radio" id="qf11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qf11_9_c" class="opcion-label">C. Oxígeno y nitrógeno</label>
                <input type="radio" id="qf11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qf11_9_d" class="opcion-label">D. Sales y metales</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>¿Qué nombre recibe el compuesto <strong>CH₃OH</strong>?</p>
                <input type="radio" id="qf11_10_a" name="respuestas[10]" value="correcta" class="opcion-input" required><label for="qf11_10_a" class="opcion-label">A. Metanol</label>
                <input type="radio" id="qf11_10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qf11_10_b" class="opcion-label">B. Etanol</label>
                <input type="radio" id="qf11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qf11_10_c" class="opcion-label">C. Propanol</label>
                <input type="radio" id="qf11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qf11_10_d" class="opcion-label">D. Ácido metanoico</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>¿Cuál es el grupo funcional característico de los ácidos carboxílicos?</p>
                <input type="radio" id="qf11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="qf11_11_a" class="opcion-label">A. -OH</label>
                <input type="radio" id="qf11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="qf11_11_b" class="opcion-label">B. -COOH</label>
                <input type="radio" id="qf11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qf11_11_c" class="opcion-label">C. -NH₂</label>
                <input type="radio" id="qf11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qf11_11_d" class="opcion-label">D. -CH₃</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Los alquinos se caracterizan por presentar:</p>
                <input type="radio" id="qf11_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="qf11_12_a" class="opcion-label">A. Enlaces simples entre carbonos</label>
                <input type="radio" id="qf11_12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qf11_12_b" class="opcion-label">B. Enlaces dobles entre carbonos</label>
                <input type="radio" id="qf11_12_c" name="respuestas[12]" value="correcta" class="opcion-input"><label for="qf11_12_c" class="opcion-label">C. Enlaces triples entre carbonos</label>
                <input type="radio" id="qf11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qf11_12_d" class="opcion-label">D. Enlaces entre metales</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>¿Qué elemento permite que los compuestos orgánicos formen cadenas largas?</p>
                <input type="radio" id="qf11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="qf11_13_a" class="opcion-label">A. Oxígeno</label>
                <input type="radio" id="qf11_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="qf11_13_b" class="opcion-label">B. Carbono</label>
                <input type="radio" id="qf11_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qf11_13_c" class="opcion-label">C. Hierro</label>
                <input type="radio" id="qf11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qf11_13_d" class="opcion-label">D. Sodio</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>¿Cuál de los siguientes compuestos es un hidrocarburo?</p>
                <input type="radio" id="qf11_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="qf11_14_a" class="opcion-label">A. H₂O</label>
                <input type="radio" id="qf11_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qf11_14_b" class="opcion-label">B. NaCl</label>
                <input type="radio" id="qf11_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="qf11_14_c" class="opcion-label">C. C₄H₁₀</label>
                <input type="radio" id="qf11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qf11_14_d" class="opcion-label">D. CO₂</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>La energía que se libera o absorbe durante una reacción química se estudia en:</p>
                <input type="radio" id="qf11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="qf11_15_a" class="opcion-label">A. Química orgánica</label>
                <input type="radio" id="qf11_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="qf11_15_b" class="opcion-label">B. Termoquímica</label>
                <input type="radio" id="qf11_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qf11_15_c" class="opcion-label">C. Nomenclatura</label>
                <input type="radio" id="qf11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qf11_15_d" class="opcion-label">D. Geometría molecular</label>
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