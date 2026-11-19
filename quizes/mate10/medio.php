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
    $id_dificultad = 2;
    $id_quiz = 105; // Quiz Matemáticas 10° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Matemáticas', 'Materiales para estudiar matemáticas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Matemáticas - Grado 10° Medio', 'Quiz intermedio de matemáticas', 10)");

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
    <title>Repazo | Quiz Matemáticas 10° - Nivel Medio</title>
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
            <p>Nivel Medio - Grado 10°. Analiza cada situación antes de responder.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Matemáticas 10° - Nivel Medio</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Una empresa de domicilios cobra una tarifa fija de <strong>$8.000</strong> más <strong>$1.500</strong> por cada kilómetro recorrido. Si un cliente recorrió 10 km, ¿cuál fue el costo total del domicilio?</p>
                <input type="radio" id="mm10_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="mm10_1_a" class="opcion-label">A. $18.000</label>
                <input type="radio" id="mm10_1_b" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="mm10_1_b" class="opcion-label">B. $20.000</label>
                <input type="radio" id="mm10_1_c" name="respuestas[1]" value="correcta" class="opcion-input"><label for="mm10_1_c" class="opcion-label">C. $23.000</label>
                <input type="radio" id="mm10_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="mm10_1_d" class="opcion-label">D. $25.000</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Un estudiante obtuvo las notas 3.8, 4.5, 4.2 y 3.5. ¿Cuál es el promedio de sus calificaciones?</p>
                <input type="radio" id="mm10_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="mm10_2_a" class="opcion-label">A. 3.8</label>
                <input type="radio" id="mm10_2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mm10_2_b" class="opcion-label">B. 3.9</label>
                <input type="radio" id="mm10_2_c" name="respuestas[2]" value="correcta" class="opcion-input"><label for="mm10_2_c" class="opcion-label">C. 4.0</label>
                <input type="radio" id="mm10_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mm10_2_d" class="opcion-label">D. 4.2</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Una escalera mide 10 metros y está apoyada contra una pared. Si la base está a 6 metros de la pared, ¿qué tema matemático permite calcular la altura alcanzada?</p>
                <input type="radio" id="mm10_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="mm10_3_a" class="opcion-label">A. Estadística</label>
                <input type="radio" id="mm10_3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="mm10_3_b" class="opcion-label">B. Probabilidad</label>
                <input type="radio" id="mm10_3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="mm10_3_c" class="opcion-label">C. Trigonometría</label>
                <input type="radio" id="mm10_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="mm10_3_d" class="opcion-label">D. Álgebra</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>En una feria escolar se rifan 60 boletas. Si solo una boleta gana el premio mayor y Laura compró 3 boletas, ¿cuál es la probabilidad de que gane?</p>
                <input type="radio" id="mm10_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="mm10_4_a" class="opcion-label">A. 1/60</label>
                <input type="radio" id="mm10_4_b" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="mm10_4_b" class="opcion-label">B. 1/30</label>
                <input type="radio" id="mm10_4_c" name="respuestas[4]" value="correcta" class="opcion-input"><label for="mm10_4_c" class="opcion-label">C. 3/60</label>
                <input type="radio" id="mm10_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="mm10_4_d" class="opcion-label">D. 6/60</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>La función <strong>y = 5x + 3</strong> representa el costo de un servicio. ¿Cuánto vale <strong>y</strong> cuando <strong>x = 7</strong>?</p>
                <input type="radio" id="mm10_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="mm10_5_a" class="opcion-label">A. 35</label>
                <input type="radio" id="mm10_5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="mm10_5_b" class="opcion-label">B. 36</label>
                <input type="radio" id="mm10_5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="mm10_5_c" class="opcion-label">C. 38</label>
                <input type="radio" id="mm10_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="mm10_5_d" class="opcion-label">D. 40</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un agricultor quiere cercar un terreno rectangular de 25 metros de largo y 15 metros de ancho. ¿Cuál es el perímetro del terreno?</p>
                <input type="radio" id="mm10_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="mm10_6_a" class="opcion-label">A. 60 metros</label>
                <input type="radio" id="mm10_6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="mm10_6_b" class="opcion-label">B. 70 metros</label>
                <input type="radio" id="mm10_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="mm10_6_c" class="opcion-label">C. 75 metros</label>
                <input type="radio" id="mm10_6_d" name="respuestas[6]" value="correcta" class="opcion-input"><label for="mm10_6_d" class="opcion-label">D. 80 metros</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>Un estudiante dibuja la gráfica de una función cuadrática. ¿Cuál de las siguientes figuras obtendrá?</p>
                <input type="radio" id="mm10_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="mm10_7_a" class="opcion-label">A. Una recta.</label>
                <input type="radio" id="mm10_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="mm10_7_b" class="opcion-label">B. Una parábola.</label>
                <input type="radio" id="mm10_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="mm10_7_c" class="opcion-label">C. Un círculo.</label>
                <input type="radio" id="mm10_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="mm10_7_d" class="opcion-label">D. Un triángulo.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>En una tienda venden cuadernos a <strong>$6.500</strong> cada uno. Si un estudiante compra 8 cuadernos, ¿cuánto debe pagar?</p>
                <input type="radio" id="mm10_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="mm10_8_a" class="opcion-label">A. $48.000</label>
                <input type="radio" id="mm10_8_b" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="mm10_8_b" class="opcion-label">B. $50.000</label>
                <input type="radio" id="mm10_8_c" name="respuestas[8]" value="correcta" class="opcion-input"><label for="mm10_8_c" class="opcion-label">C. $52.000</label>
                <input type="radio" id="mm10_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="mm10_8_d" class="opcion-label">D. $54.000</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>Un parque tiene forma rectangular. Si mide 40 metros de largo y 25 metros de ancho, ¿cuál es su área?</p>
                <input type="radio" id="mm10_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="mm10_9_a" class="opcion-label">A. 650 m²</label>
                <input type="radio" id="mm10_9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mm10_9_b" class="opcion-label">B. 850 m²</label>
                <input type="radio" id="mm10_9_c" name="respuestas[9]" value="correcta" class="opcion-input"><label for="mm10_9_c" class="opcion-label">C. 1000 m²</label>
                <input type="radio" id="mm10_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mm10_9_d" class="opcion-label">D. 1200 m²</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Un estudiante lanzó una pelota desde una altura de 2 metros. La trayectoria que sigue la pelota corresponde principalmente a una función:</p>
                <input type="radio" id="mm10_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="mm10_10_a" class="opcion-label">A. Lineal.</label>
                <input type="radio" id="mm10_10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="mm10_10_b" class="opcion-label">B. Exponencial.</label>
                <input type="radio" id="mm10_10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="mm10_10_c" class="opcion-label">C. Cuadrática.</label>
                <input type="radio" id="mm10_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="mm10_10_d" class="opcion-label">D. Constante.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>La profesora registró las edades de 20 estudiantes y desea conocer cuál es la edad que más se repite. ¿Qué medida estadística debe calcular?</p>
                <input type="radio" id="mm10_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="mm10_11_a" class="opcion-label">A. Media.</label>
                <input type="radio" id="mm10_11_b" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="mm10_11_b" class="opcion-label">B. Mediana.</label>
                <input type="radio" id="mm10_11_c" name="respuestas[11]" value="correcta" class="opcion-input"><label for="mm10_11_c" class="opcion-label">C. Moda.</label>
                <input type="radio" id="mm10_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="mm10_11_d" class="opcion-label">D. Rango.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Un estudiante obtiene 18 caras al lanzar una moneda 40 veces. ¿Cuál fue la frecuencia relativa aproximada de obtener cara?</p>
                <input type="radio" id="mm10_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="mm10_12_a" class="opcion-label">A. 0.25</label>
                <input type="radio" id="mm10_12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="mm10_12_b" class="opcion-label">B. 0.35</label>
                <input type="radio" id="mm10_12_c" name="respuestas[12]" value="correcta" class="opcion-input"><label for="mm10_12_c" class="opcion-label">C. 0.45</label>
                <input type="radio" id="mm10_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="mm10_12_d" class="opcion-label">D. 0.60</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Un plano muestra el punto A(3,5). Si se desplaza 2 unidades hacia la derecha, ¿cuáles serán sus nuevas coordenadas?</p>
                <input type="radio" id="mm10_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="mm10_13_a" class="opcion-label">A. (3,7)</label>
                <input type="radio" id="mm10_13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="mm10_13_b" class="opcion-label">B. (1,5)</label>
                <input type="radio" id="mm10_13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="mm10_13_c" class="opcion-label">C. (5,5)</label>
                <input type="radio" id="mm10_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="mm10_13_d" class="opcion-label">D. (5,7)</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>En una promoción, una tienda ofrece un 15% de descuento sobre un producto que cuesta $200.000. ¿Cuánto dinero se descuenta?</p>
                <input type="radio" id="mm10_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="mm10_14_a" class="opcion-label">A. $20.000</label>
                <input type="radio" id="mm10_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="mm10_14_b" class="opcion-label">B. $25.000</label>
                <input type="radio" id="mm10_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="mm10_14_c" class="opcion-label">C. $30.000</label>
                <input type="radio" id="mm10_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="mm10_14_d" class="opcion-label">D. $35.000</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una empresa registra sus ventas durante seis meses y observa que aumentan siempre la misma cantidad cada mes. ¿Qué modelo matemático describe mejor este comportamiento?</p>
                <input type="radio" id="mm10_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="mm10_15_a" class="opcion-label">A. Función cuadrática.</label>
                <input type="radio" id="mm10_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="mm10_15_b" class="opcion-label">B. Función lineal.</label>
                <input type="radio" id="mm10_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mm10_15_c" class="opcion-label">C. Función exponencial.</label>
                <input type="radio" id="mm10_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mm10_15_d" class="opcion-label">D. Función logarítmica.</label>
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