<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../paginas/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$grado = "10";
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

    $id_materia = 102;
    $id_dificultad = 3;
    $id_quiz = 106; // Quiz Matemáticas 10° difícil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Matemáticas', 'Materiales para estudiar matemáticas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Difícil', 'Preguntas de nivel avanzado.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Matemáticas - Grado 10° Avanzado', 'Quiz avanzado de matemáticas', 10)");

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
    <title>Repazo | Quiz Matemáticas 10° - Nivel Muy Difícil</title>
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
            <h1>Quiz de Matemáticas</h1>
            <p>Nivel Muy Difícil - Grado 10°. Analiza cuidadosamente cada situación antes de responder.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Matemáticas 10° - Nivel Muy Difícil</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Una empresa cobra por fabricar camisetas una tarifa fija de <strong>$500.000</strong> más <strong>$12.000</strong> por cada camiseta producida. Si el costo total fue de <strong>$980.000</strong>, ¿cuántas camisetas se fabricaron?</p>
                <input type="radio" id="ma10_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="ma10_1_a" class="opcion-label">A. 35</label>
                <input type="radio" id="ma10_1_b" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="ma10_1_b" class="opcion-label">B. 38</label>
                <input type="radio" id="ma10_1_c" name="respuestas[1]" value="correcta" class="opcion-input"><label for="ma10_1_c" class="opcion-label">C. 40</label>
                <input type="radio" id="ma10_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="ma10_1_d" class="opcion-label">D. 42</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Una función está representada por la expresión <strong>f(x)=3x²−12x+9</strong>. ¿Qué tipo de función es?</p>
                <input type="radio" id="ma10_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="ma10_2_a" class="opcion-label">A. Lineal</label>
                <input type="radio" id="ma10_2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ma10_2_b" class="opcion-label">B. Exponencial</label>
                <input type="radio" id="ma10_2_c" name="respuestas[2]" value="correcta" class="opcion-input"><label for="ma10_2_c" class="opcion-label">C. Cuadrática</label>
                <input type="radio" id="ma10_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="ma10_2_d" class="opcion-label">D. Constante</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Un avión despega formando un ángulo de 30° con respecto al suelo y recorre 600 metros. ¿Qué herramienta matemática permite calcular aproximadamente la altura alcanzada?</p>
                <input type="radio" id="ma10_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="ma10_3_a" class="opcion-label">A. Probabilidad</label>
                <input type="radio" id="ma10_3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="ma10_3_b" class="opcion-label">B. Estadística</label>
                <input type="radio" id="ma10_3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="ma10_3_c" class="opcion-label">C. Trigonometría</label>
                <input type="radio" id="ma10_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="ma10_3_d" class="opcion-label">D. Álgebra</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Una empresa registra la temperatura de un horno cada minuto. Los datos muestran que la temperatura aumenta rápidamente al inicio, alcanza un máximo y luego comienza a disminuir. ¿Qué tipo de función representa mejor este comportamiento?</p>
                <input type="radio" id="ma10_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="ma10_4_a" class="opcion-label">A. Lineal</label>
                <input type="radio" id="ma10_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="ma10_4_b" class="opcion-label">B. Cuadrática</label>
                <input type="radio" id="ma10_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="ma10_4_c" class="opcion-label">C. Constante</label>
                <input type="radio" id="ma10_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="ma10_4_d" class="opcion-label">D. Logarítmica</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Un dron despega y asciende formando un ángulo de 45° con el suelo. Si ha recorrido 120 metros, ¿qué distancia aproximada hay entre el dron y el suelo?</p>
                <input type="radio" id="ma10_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="ma10_5_a" class="opcion-label">A. 60 m</label>
                <input type="radio" id="ma10_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ma10_5_b" class="opcion-label">B. 75 m</label>
                <input type="radio" id="ma10_5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="ma10_5_c" class="opcion-label">C. 85 m</label>
                <input type="radio" id="ma10_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="ma10_5_d" class="opcion-label">D. 120 m</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>En un salón hay 12 mujeres y 18 hombres. Si se escoge un estudiante al azar, ¿cuál es la probabilidad de seleccionar una mujer?</p>
                <input type="radio" id="ma10_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="ma10_6_a" class="opcion-label">A. 1/5</label>
                <input type="radio" id="ma10_6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="ma10_6_b" class="opcion-label">B. 2/5</label>
                <input type="radio" id="ma10_6_c" name="respuestas[6]" value="correcta" class="opcion-input"><label for="ma10_6_c" class="opcion-label">C. 12/30</label>
                <input type="radio" id="ma10_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="ma10_6_d" class="opcion-label">D. 18/30</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Un empresario registra las ventas de los últimos cinco meses: 120, 135, 150, 165 y 180 productos. ¿Cuál es el incremento mensual observado?</p>
                <input type="radio" id="ma10_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="ma10_7_a" class="opcion-label">A. 10 productos</label>
                <input type="radio" id="ma10_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="ma10_7_b" class="opcion-label">B. 15 productos</label>
                <input type="radio" id="ma10_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ma10_7_c" class="opcion-label">C. 20 productos</label>
                <input type="radio" id="ma10_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="ma10_7_d" class="opcion-label">D. 25 productos</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Un arquitecto diseña una plaza cuadrada cuyo lado mide 28 metros. ¿Cuál será el área total de la plaza?</p>
                <input type="radio" id="ma10_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="ma10_8_a" class="opcion-label">A. 112 m²</label>
                <input type="radio" id="ma10_8_b" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="ma10_8_b" class="opcion-label">B. 560 m²</label>
                <input type="radio" id="ma10_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="ma10_8_c" class="opcion-label">C. 728 m²</label>
                <input type="radio" id="ma10_8_d" name="respuestas[8]" value="correcta" class="opcion-input"><label for="ma10_8_d" class="opcion-label">D. 784 m²</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>Una empresa de transporte modela el costo de un viaje con la función <strong>C(x)=2500x+18000</strong>, donde <strong>x</strong> representa los kilómetros recorridos. ¿Cuál será el costo de un recorrido de <strong>12 km</strong>?</p>
                <input type="radio" id="ma10_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="ma10_9_a" class="opcion-label">A. $42.000</label>
                <input type="radio" id="ma10_9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="ma10_9_b" class="opcion-label">B. $45.000</label>
                <input type="radio" id="ma10_9_c" name="respuestas[9]" value="correcta" class="opcion-input"><label for="ma10_9_c" class="opcion-label">C. $48.000</label>
                <input type="radio" id="ma10_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="ma10_9_d" class="opcion-label">D. $50.000</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Una encuesta realizada a 120 estudiantes mostró que 72 prefieren estudiar en la mañana. ¿Qué porcentaje representan esos estudiantes?</p>
                <input type="radio" id="ma10_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="ma10_10_a" class="opcion-label">A. 55%</label>
                <input type="radio" id="ma10_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="ma10_10_b" class="opcion-label">B. 60%</label>
                <input type="radio" id="ma10_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="ma10_10_c" class="opcion-label">C. 65%</label>
                <input type="radio" id="ma10_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="ma10_10_d" class="opcion-label">D. 72%</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Una persona observa la parte superior de una torre formando un ángulo de elevación de 60°. Si se encuentra a 20 metros de la base, ¿qué procedimiento matemático debe utilizar para calcular la altura de la torre?</p>
                <input type="radio" id="ma10_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="ma10_11_a" class="opcion-label">A. Regla de tres.</label>
                <input type="radio" id="ma10_11_b" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="ma10_11_b" class="opcion-label">B. Estadística.</label>
                <input type="radio" id="ma10_11_c" name="respuestas[11]" value="correcta" class="opcion-input"><label for="ma10_11_c" class="opcion-label">C. Razones trigonométricas.</label>
                <input type="radio" id="ma10_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="ma10_11_d" class="opcion-label">D. Probabilidad.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Un grupo de estudiantes obtuvo las siguientes calificaciones: <strong>3.8, 4.2, 4.5, 4.2, 4.7 y 4.2.</strong> ¿Cuál es la moda?</p>
                <input type="radio" id="ma10_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="ma10_12_a" class="opcion-label">A. 4.5</label>
                <input type="radio" id="ma10_12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ma10_12_b" class="opcion-label">B. 4.7</label>
                <input type="radio" id="ma10_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="ma10_12_c" class="opcion-label">C. 3.8</label>
                <input type="radio" id="ma10_12_d" name="respuestas[12]" value="correcta" class="opcion-input"><label for="ma10_12_d" class="opcion-label">D. 4.2</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>En un plano cartesiano se ubican los puntos A(2,3) y B(8,3). ¿Cuál es la distancia horizontal entre ambos puntos?</p>
                <input type="radio" id="ma10_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="ma10_13_a" class="opcion-label">A. 3 unidades.</label>
                <input type="radio" id="ma10_13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="ma10_13_b" class="opcion-label">B. 5 unidades.</label>
                <input type="radio" id="ma10_13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="ma10_13_c" class="opcion-label">C. 6 unidades.</label>
                <input type="radio" id="ma10_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="ma10_13_d" class="opcion-label">D. 8 unidades.</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Un comerciante compra un producto por <strong>$180.000</strong> y desea obtener una ganancia del <strong>25%</strong>. ¿En cuánto debe venderlo?</p>
                <input type="radio" id="ma10_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="ma10_14_a" class="opcion-label">A. $200.000</label>
                <input type="radio" id="ma10_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="ma10_14_b" class="opcion-label">B. $215.000</label>
                <input type="radio" id="ma10_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="ma10_14_c" class="opcion-label">C. $225.000</label>
                <input type="radio" id="ma10_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="ma10_14_d" class="opcion-label">D. $240.000</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una empresa analiza sus ventas y descubre que, al aumentar la publicidad, las ventas primero crecen lentamente, luego muy rápido y finalmente vuelven a estabilizarse. ¿Qué aspecto es más importante analizar para tomar decisiones?</p>
                <input type="radio" id="ma10_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="ma10_15_a" class="opcion-label">A. El color de la gráfica.</label>
                <input type="radio" id="ma10_15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="ma10_15_b" class="opcion-label">B. El nombre de la función.</label>
                <input type="radio" id="ma10_15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="ma10_15_c" class="opcion-label">C. El comportamiento o tendencia de la gráfica.</label>
                <input type="radio" id="ma10_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="ma10_15_d" class="opcion-label">D. La cantidad de ejes del plano.</label>
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