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
    $id_dificultad = 2; // Medio
    $id_quiz = 111; // Quiz Química 10° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Química', 'Materiales para estudiar química.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Química 10° - Nivel Medio', 'Quiz intermedio de química para grado 10', 10)");

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
    <title>Repazo | Quiz Química 10° - Nivel Medio</title>
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
            <h1>Quiz de Química 10°</h1>
            <p>Nivel Medio. Responde cada pregunta y demuestra lo que has aprendido.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Química 10° - Nivel Medio</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Un átomo tiene 17 protones y 18 neutrones. ¿Cuál es su número másico?</p>
                <input type="radio" id="qm10_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="qm10_1_a" class="opcion-label">A. 17</label>
                <input type="radio" id="qm10_1_b" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qm10_1_b" class="opcion-label">B. 18</label>
                <input type="radio" id="qm10_1_c" name="respuestas[1]" value="correcta" class="opcion-input"><label for="qm10_1_c" class="opcion-label">C. 35</label>
                <input type="radio" id="qm10_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qm10_1_d" class="opcion-label">D. 36</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Un elemento tiene configuración electrónica: <strong>1s² 2s² 2p⁶ 3s¹</strong>. ¿A qué elemento corresponde?</p>
                <input type="radio" id="qm10_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="qm10_2_a" class="opcion-label">A. Litio (Li)</label>
                <input type="radio" id="qm10_2_b" name="respuestas[2]" value="correcta" class="opcion-input"><label for="qm10_2_b" class="opcion-label">B. Sodio (Na)</label>
                <input type="radio" id="qm10_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qm10_2_c" class="opcion-label">C. Magnesio (Mg)</label>
                <input type="radio" id="qm10_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qm10_2_d" class="opcion-label">D. Cloro (Cl)</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>El compuesto <strong>NaCl</strong> se forma mediante un enlace:</p>
                <input type="radio" id="qm10_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="qm10_3_a" class="opcion-label">A. Covalente no polar</label>
                <input type="radio" id="qm10_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="qm10_3_b" class="opcion-label">B. Iónico</label>
                <input type="radio" id="qm10_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qm10_3_c" class="opcion-label">C. Metálico</label>
                <input type="radio" id="qm10_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qm10_3_d" class="opcion-label">D. Puente de hidrógeno</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Para nombrar correctamente el compuesto <strong>CO₂</strong>, su nombre es:</p>
                <input type="radio" id="qm10_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="qm10_4_a" class="opcion-label">A. Monóxido de carbono</label>
                <input type="radio" id="qm10_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="qm10_4_b" class="opcion-label">B. Dióxido de carbono</label>
                <input type="radio" id="qm10_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qm10_4_c" class="opcion-label">C. Carbonato de oxígeno</label>
                <input type="radio" id="qm10_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qm10_4_d" class="opcion-label">D. Óxido de carbono</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>En la reacción: <strong>2H₂ + O₂ → 2H₂O</strong>, ¿cuál sustancia es el producto?</p>
                <input type="radio" id="qm10_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="qm10_5_a" class="opcion-label">A. Hidrógeno (H₂)</label>
                <input type="radio" id="qm10_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qm10_5_b" class="opcion-label">B. Oxígeno (O₂)</label>
                <input type="radio" id="qm10_5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="qm10_5_c" class="opcion-label">C. Agua (H₂O)</label>
                <input type="radio" id="qm10_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qm10_5_d" class="opcion-label">D. Hidróxido</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un estudiante mezcla 10 gramos de sal con agua hasta obtener una solución. ¿Cuál es el soluto en esta mezcla?</p>
                <input type="radio" id="qm10_6_a" name="respuestas[6]" value="correcta" class="opcion-input" required><label for="qm10_6_a" class="opcion-label">A. La sal</label>
                <input type="radio" id="qm10_6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qm10_6_b" class="opcion-label">B. El agua</label>
                <input type="radio" id="qm10_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qm10_6_c" class="opcion-label">C. La solución completa</label>
                <input type="radio" id="qm10_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qm10_6_d" class="opcion-label">D. El recipiente</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Una reacción química libera energía en forma de calor al ambiente. Este tipo de reacción se conoce como:</p>
                <input type="radio" id="qm10_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="qm10_7_a" class="opcion-label">A. Endotérmica</label>
                <input type="radio" id="qm10_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="qm10_7_b" class="opcion-label">B. Exotérmica</label>
                <input type="radio" id="qm10_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qm10_7_c" class="opcion-label">C. Neutra</label>
                <input type="radio" id="qm10_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qm10_7_d" class="opcion-label">D. Reversible</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Si un átomo tiene 12 electrones, su distribución electrónica por niveles es:</p>
                <input type="radio" id="qm10_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="qm10_8_a" class="opcion-label">A. 2, 6, 4</label>
                <input type="radio" id="qm10_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="qm10_8_b" class="opcion-label">B. 2, 8, 2</label>
                <input type="radio" id="qm10_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qm10_8_c" class="opcion-label">C. 8, 2, 2</label>
                <input type="radio" id="qm10_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qm10_8_d" class="opcion-label">D. 2, 10</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>En una reacción química, ajustar la cantidad de átomos en reactivos y productos se llama:</p>
                <input type="radio" id="qm10_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="qm10_9_a" class="opcion-label">A. Filtración</label>
                <input type="radio" id="qm10_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="qm10_9_b" class="opcion-label">B. Balanceo químico</label>
                <input type="radio" id="qm10_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qm10_9_c" class="opcion-label">C. Evaporación</label>
                <input type="radio" id="qm10_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qm10_9_d" class="opcion-label">D. Destilación</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Una molécula tiene enlaces donde los átomos comparten electrones. Este tipo de enlace es:</p>
                <input type="radio" id="qm10_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="qm10_10_a" class="opcion-label">A. Iónico</label>
                <input type="radio" id="qm10_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="qm10_10_b" class="opcion-label">B. Covalente</label>
                <input type="radio" id="qm10_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qm10_10_c" class="opcion-label">C. Metálico</label>
                <input type="radio" id="qm10_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qm10_10_d" class="opcion-label">D. Nuclear</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Un laboratorio necesita calcular la masa molar del agua (H₂O). Si H=1 g/mol y O=16 g/mol, ¿cuál es su masa molar?</p>
                <input type="radio" id="qm10_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="qm10_11_a" class="opcion-label">A. 16 g/mol</label>
                <input type="radio" id="qm10_11_b" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qm10_11_b" class="opcion-label">B. 17 g/mol</label>
                <input type="radio" id="qm10_11_c" name="respuestas[11]" value="correcta" class="opcion-input"><label for="qm10_11_c" class="opcion-label">C. 18 g/mol</label>
                <input type="radio" id="qm10_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qm10_11_d" class="opcion-label">D. 20 g/mol</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>En una reacción química, aumentar la temperatura generalmente provoca que las partículas:</p>
                <input type="radio" id="qm10_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="qm10_12_a" class="opcion-label">A. Se detengan completamente</label>
                <input type="radio" id="qm10_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="qm10_12_b" class="opcion-label">B. Se muevan con mayor velocidad</label>
                <input type="radio" id="qm10_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qm10_12_c" class="opcion-label">C. Pierdan toda su energía</label>
                <input type="radio" id="qm10_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qm10_12_d" class="opcion-label">D. Desaparezcan</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Un estudiante analiza una sustancia con fórmula <strong>CaO</strong>. ¿Qué tipo de compuesto es?</p>
                <input type="radio" id="qm10_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="qm10_13_a" class="opcion-label">A. Ácido</label>
                <input type="radio" id="qm10_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="qm10_13_b" class="opcion-label">B. Óxido metálico</label>
                <input type="radio" id="qm10_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qm10_13_c" class="opcion-label">C. Hidrocarburo</label>
                <input type="radio" id="qm10_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qm10_13_d" class="opcion-label">D. Sal orgánica</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>La cantidad de partículas que contiene un mol de cualquier sustancia corresponde a:</p>
                <input type="radio" id="qm10_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="qm10_14_a" class="opcion-label">A. La masa atómica</label>
                <input type="radio" id="qm10_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qm10_14_b" class="opcion-label">B. El número atómico</label>
                <input type="radio" id="qm10_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="qm10_14_c" class="opcion-label">C. 6.022 × 10²³ partículas</label>
                <input type="radio" id="qm10_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qm10_14_d" class="opcion-label">D. 100 partículas</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una reacción donde una sustancia se separa en sustancias más simples se llama:</p>
                <input type="radio" id="qm10_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="qm10_15_a" class="opcion-label">A. Síntesis</label>
                <input type="radio" id="qm10_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="qm10_15_b" class="opcion-label">B. Descomposición</label>
                <input type="radio" id="qm10_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qm10_15_c" class="opcion-label">C. Combustión</label>
                <input type="radio" id="qm10_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qm10_15_d" class="opcion-label">D. Neutralización</label>
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