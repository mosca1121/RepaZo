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

    $id_materia = 102; // Matemáticas
    $id_dificultad = 2; // Medio
    $id_quiz = 120; // Quiz Matemáticas 11° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Matemáticas', 'Materiales para estudiar matemáticas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Matemáticas - Grado 11° Medio', 'Quiz intermedio de matemáticas', 11)");

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
    <title>Repazo | Quiz Matemáticas 11° - Nivel Medio</title>
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
            <h1>Quiz de Matemáticas 11°</h1>
            <p>Nivel medio. Responde cada pregunta y demuestra lo que has aprendido.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Matemáticas 11° - Nivel Medio</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Una función está definida como <strong>f(x)=3x²-2x+1</strong>. ¿Cuál es el valor de f(2)?</p>
                <input type="radio" id="mm11_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="mm11_1_a" class="opcion-label">A. 7</label>
                <input type="radio" id="mm11_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="mm11_1_b" class="opcion-label">B. 9</label>
                <input type="radio" id="mm11_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="mm11_1_c" class="opcion-label">C. 13</label>
                <input type="radio" id="mm11_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="mm11_1_d" class="opcion-label">D. 15</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>La ecuación de una recta es <strong>y=5x-7</strong>. ¿Cuál es el valor de la pendiente?</p>
                <input type="radio" id="mm11_2_a" name="respuestas[2]" value="correcta" class="opcion-input" required><label for="mm11_2_a" class="opcion-label">A. 5</label>
                <input type="radio" id="mm11_2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mm11_2_b" class="opcion-label">B. -7</label>
                <input type="radio" id="mm11_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mm11_2_c" class="opcion-label">C. 7</label>
                <input type="radio" id="mm11_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mm11_2_d" class="opcion-label">D. -5</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Si una parábola tiene la ecuación <strong>y=x²-4x+3</strong>, ¿cuál es el valor del vértice en x?</p>
                <input type="radio" id="mm11_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="mm11_3_a" class="opcion-label">A. 1</label>
                <input type="radio" id="mm11_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="mm11_3_b" class="opcion-label">B. 2</label>
                <input type="radio" id="mm11_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="mm11_3_c" class="opcion-label">C. 3</label>
                <input type="radio" id="mm11_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="mm11_3_d" class="opcion-label">D. 4</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Una sucesión tiene los términos: <strong>4, 8, 12, 16...</strong>. ¿Cuál será el término número 10?</p>
                <input type="radio" id="mm11_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="mm11_4_a" class="opcion-label">A. 36</label>
                <input type="radio" id="mm11_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="mm11_4_b" class="opcion-label">B. 40</label>
                <input type="radio" id="mm11_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="mm11_4_c" class="opcion-label">C. 44</label>
                <input type="radio" id="mm11_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="mm11_4_d" class="opcion-label">D. 48</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Un estudiante obtiene las notas: <strong>4.0, 3.5, 4.5 y 5.0</strong>. ¿Cuál es su promedio?</p>
                <input type="radio" id="mm11_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="mm11_5_a" class="opcion-label">A. 4.0</label>
                <input type="radio" id="mm11_5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="mm11_5_b" class="opcion-label">B. 4.25</label>
                <input type="radio" id="mm11_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="mm11_5_c" class="opcion-label">C. 4.5</label>
                <input type="radio" id="mm11_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="mm11_5_d" class="opcion-label">D. 5.0</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>La función de una población está dada por <strong>P(t)=500+50t</strong>, donde t representa el tiempo en años. ¿Cuál será la población después de 6 años?</p>
                <input type="radio" id="mm11_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="mm11_6_a" class="opcion-label">A. 700</label>
                <input type="radio" id="mm11_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="mm11_6_b" class="opcion-label">B. 800</label>
                <input type="radio" id="mm11_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="mm11_6_c" class="opcion-label">C. 850</label>
                <input type="radio" id="mm11_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="mm11_6_d" class="opcion-label">D. 900</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>La solución de la ecuación cuadrática <strong>x²-5x+6=0</strong> es:</p>
                <input type="radio" id="mm11_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="mm11_7_a" class="opcion-label">A. x=1 y x=6</label>
                <input type="radio" id="mm11_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="mm11_7_b" class="opcion-label">B. x=2 y x=3</label>
                <input type="radio" id="mm11_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="mm11_7_c" class="opcion-label">C. x=3 y x=5</label>
                <input type="radio" id="mm11_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="mm11_7_d" class="opcion-label">D. x=0 y x=6</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Un objeto se mueve con una velocidad dada por la función <strong>v(t)=4t+10</strong>. ¿Cuál es su velocidad después de 5 segundos?</p>
                <input type="radio" id="mm11_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="mm11_8_a" class="opcion-label">A. 20 m/s</label>
                <input type="radio" id="mm11_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="mm11_8_b" class="opcion-label">B. 30 m/s</label>
                <input type="radio" id="mm11_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="mm11_8_c" class="opcion-label">C. 40 m/s</label>
                <input type="radio" id="mm11_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="mm11_8_d" class="opcion-label">D. 50 m/s</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>Si dos eventos son independientes, la probabilidad de que ocurran ambos se calcula:</p>
                <input type="radio" id="mm11_9_a" name="respuestas[9]" value="correcta" class="opcion-input" required><label for="mm11_9_a" class="opcion-label">A. Multiplicando sus probabilidades.</label>
                <input type="radio" id="mm11_9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mm11_9_b" class="opcion-label">B. Sumando sus probabilidades.</label>
                <input type="radio" id="mm11_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mm11_9_c" class="opcion-label">C. Restando sus probabilidades.</label>
                <input type="radio" id="mm11_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mm11_9_d" class="opcion-label">D. Dividiendo siempre entre dos.</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Una circunferencia tiene centro en el origen y radio 4. ¿Cuál es su ecuación?</p>
                <input type="radio" id="mm11_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="mm11_10_a" class="opcion-label">A. x+y=4</label>
                <input type="radio" id="mm11_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="mm11_10_b" class="opcion-label">B. x²+y²=16</label>
                <input type="radio" id="mm11_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="mm11_10_c" class="opcion-label">C. x²+y²=8</label>
                <input type="radio" id="mm11_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="mm11_10_d" class="opcion-label">D. x²-y²=16</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>La derivada de la función <strong>f(x)=x²+4x</strong> es:</p>
                <input type="radio" id="mm11_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="mm11_11_a" class="opcion-label">A. x+4</label>
                <input type="radio" id="mm11_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="mm11_11_b" class="opcion-label">B. 2x+4</label>
                <input type="radio" id="mm11_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="mm11_11_c" class="opcion-label">C. x²+4</label>
                <input type="radio" id="mm11_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="mm11_11_d" class="opcion-label">D. 2x²+4</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Si una recta pasa por los puntos A(2,3) y B(6,11), ¿cuál es su pendiente?</p>
                <input type="radio" id="mm11_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="mm11_12_a" class="opcion-label">A. 1</label>
                <input type="radio" id="mm11_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="mm11_12_b" class="opcion-label">B. 2</label>
                <input type="radio" id="mm11_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="mm11_12_c" class="opcion-label">C. 3</label>
                <input type="radio" id="mm11_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="mm11_12_d" class="opcion-label">D. 4</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Un experimento tiene 20 resultados posibles y 5 son favorables. ¿Cuál es la probabilidad de obtener un resultado favorable?</p>
                <input type="radio" id="mm11_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="mm11_13_a" class="opcion-label">A. 1/2</label>
                <input type="radio" id="mm11_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="mm11_13_b" class="opcion-label">B. 1/4</label>
                <input type="radio" id="mm11_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="mm11_13_c" class="opcion-label">C. 1/5</label>
                <input type="radio" id="mm11_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="mm11_13_d" class="opcion-label">D. 5/20</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Una función exponencial tiene la forma:</p>
                <input type="radio" id="mm11_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="mm11_14_a" class="opcion-label">A. f(x)=mx+b</label>
                <input type="radio" id="mm11_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="mm11_14_b" class="opcion-label">B. f(x)=ax²+bx+c</label>
                <input type="radio" id="mm11_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="mm11_14_c" class="opcion-label">C. f(x)=a·bˣ</label>
                <input type="radio" id="mm11_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="mm11_14_d" class="opcion-label">D. f(x)=x+y</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Si una matriz tiene 3 filas y 2 columnas, su orden es:</p>
                <input type="radio" id="mm11_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="mm11_15_a" class="opcion-label">A. 2x3</label>
                <input type="radio" id="mm11_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="mm11_15_b" class="opcion-label">B. 3x2</label>
                <input type="radio" id="mm11_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mm11_15_c" class="opcion-label">C. 5x5</label>
                <input type="radio" id="mm11_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mm11_15_d" class="opcion-label">D. 6x1</label>
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