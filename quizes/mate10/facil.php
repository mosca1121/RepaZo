<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../login.php");
    exit;
}

$id_usuario =$_SESSION["id_usuario"];
$resultado_guardado = false;
$porcentaje_obtenido = 0.00;
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
    $porcentaje_obtenido = ($correctas / $total_preguntas) * 100;
   
    // IDs basados en tu base de datos (Matemáticas = id_materia 2, Fácil = id_dificultad 1, Quiz de Matemáticas 10° Fácil = id_quiz 2)
    $id_materia = 102;
    $id_dificultad = 1;
    $id_quiz = 104;

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar que existan la materia, dificultad y el quiz en la BD para evitar error de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Matemáticas', 'Materiales para estudiar matemáticas.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Fácil', 'Preguntas de nivel básico.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia,$id_dificultad, 'Quiz de Matemáticas - Grado 10° Fácil', 'Quiz básico de matemáticas', 10)");

        // Insertar el intento con tus columnas exactas
        $stmt =$conexion->prepare("INSERT INTO intentos_quizzes (id_usuario, id_quiz, porcentaje, respuestas_correctas, respuestas_incorrectas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidii", $id_usuario,$id_quiz, $porcentaje_obtenido,$correctas, $incorrectas);$stmt->execute();
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
    <title>Repazo | Quiz Matemáticas 10° - Fácil</title>
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
            <h1>Quiz de Matemáticas</h1>
            <p>Nivel Fácil - Grado 10°. Responde las siguientes preguntas y demuestra tus conocimientos.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Matemáticas 10° - Nivel Fácil</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema"><h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Juan ahorra <strong>$20.000</strong> cada semana para comprar un computador. Si continúa ahorrando la misma cantidad todas las semanas, ¿qué tipo de función representa esta situación?</p>
                <input type="radio" id="mf10_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="mf10_1_a" class="opcion-label">A. Función cuadrática</label>
                <input type="radio" id="mf10_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="mf10_1_b" class="opcion-label">B. Función lineal</label>
                <input type="radio" id="mf10_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="mf10_1_c" class="opcion-label">C. Función exponencial</label>
                <input type="radio" id="mf10_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="mf10_1_d" class="opcion-label">D. Función trigonométrica</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Un taxi cobra una tarifa inicial de <strong>$6.000</strong> y luego <strong>$2.500</strong> por cada kilómetro recorrido. Si una persona recorre 4 km, ¿cuánto deberá pagar?</p>
                <input type="radio" id="mf10_2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="mf10_2_a" class="opcion-label">A. $12.000</label>
                <input type="radio" id="mf10_2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mf10_2_b" class="opcion-label">B. $14.000</label>
                <input type="radio" id="mf10_2_c" name="respuestas[2]" value="correcta" class="opcion-input"><label for="mf10_2_c" class="opcion-label">C. $16.000</label>
                <input type="radio" id="mf10_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="mf10_2_d" class="opcion-label">D. $18.000</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Sofía obtuvo las siguientes calificaciones: <strong>4.0, 3.5, 4.5 y 5.0.</strong> ¿Cuál es el promedio de sus notas?</p>
                <input type="radio" id="mf10_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="mf10_3_a" class="opcion-label">A. 4.0</label>
                <input type="radio" id="mf10_3_b" name="respuestas[3]" value="correcta" class="opcion-input"><label for="mf10_3_b" class="opcion-label">B. 4.25</label>
                <input type="radio" id="mf10_3_c" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="mf10_3_c" class="opcion-label">C. 4.5</label>
                <input type="radio" id="mf10_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="mf10_3_d" class="opcion-label">D. 3.75</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>En una bolsa hay 8 balotas azules y 8 balotas rojas. Si se extrae una balota sin mirar, ¿cuál es la probabilidad de sacar una balota azul?</p>
                <input type="radio" id="mf10_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="mf10_4_a" class="opcion-label">A. 1/4</label>
                <input type="radio" id="mf10_4_b" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="mf10_4_b" class="opcion-label">B. 1/3</label>
                <input type="radio" id="mf10_4_c" name="respuestas[4]" value="correcta" class="opcion-input"><label for="mf10_4_c" class="opcion-label">C. 1/2</label>
                <input type="radio" id="mf10_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="mf10_4_d" class="opcion-label">D. 3/4</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Un arquitecto ubica un edificio en el plano cartesiano en el punto (6,4). ¿Qué representa el número 6?</p>
                <input type="radio" id="mf10_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="mf10_5_a" class="opcion-label">A. La coordenada Y.</label>
                <input type="radio" id="mf10_5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="mf10_5_b" class="opcion-label">B. La coordenada X.</label>
                <input type="radio" id="mf10_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="mf10_5_c" class="opcion-label">C. La altura del edificio.</label>
                <input type="radio" id="mf10_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="mf10_5_d" class="opcion-label">D. El cuarto cuadrante.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un trabajador necesita calcular la altura de un poste utilizando la sombra que este proyecta sobre el suelo. ¿Qué tema de matemáticas debe aplicar?</p>
                <input type="radio" id="mf10_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="mf10_6_a" class="opcion-label">A. Estadística.</label>
                <input type="radio" id="mf10_6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="mf10_6_b" class="opcion-label">B. Probabilidad.</label>
                <input type="radio" id="mf10_6_c" name="respuestas[6]" value="correcta" class="opcion-input"><label for="mf10_6_c" class="opcion-label">C. Trigonometría.</label>
                <input type="radio" id="mf10_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="mf10_6_d" class="opcion-label">D. Geometría Analítica.</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>En un partido de fútbol, un jugador patea el balón y este sube, alcanza una altura máxima y luego cae al suelo. ¿Qué tipo de gráfica representa mejor este movimiento?</p>
                <input type="radio" id="mf10_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="mf10_7_a" class="opcion-label">A. Línea recta.</label>
                <input type="radio" id="mf10_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="mf10_7_b" class="opcion-label">B. Parábola.</label>
                <input type="radio" id="mf10_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="mf10_7_c" class="opcion-label">C. Circunferencia.</label>
                <input type="radio" id="mf10_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="mf10_7_d" class="opcion-label">D. Línea horizontal.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>En una cafetería venden almuerzos y jugos. El administrador conoce el dinero total recaudado y la cantidad de productos vendidos. ¿Qué tema matemático le permite encontrar el precio de cada producto utilizando dos ecuaciones?</p>
                <input type="radio" id="mf10_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="mf10_8_a" class="opcion-label">A. Probabilidad.</label>
                <input type="radio" id="mf10_8_b" name="respuestas[8]" value="correcta" class="opcion-input"><label for="mf10_8_b" class="opcion-label">B. Sistemas de ecuaciones.</label>
                <input type="radio" id="mf10_8_c" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="mf10_8_c" class="opcion-label">C. Estadística.</label>
                <input type="radio" id="mf10_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="mf10_8_d" class="opcion-label">D. Trigonometría.</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>Una tienda ofrece un descuento únicamente a las compras superiores a <strong>$150.000</strong>. ¿Qué símbolo representa correctamente esa condición?</p>
                <input type="radio" id="mf10_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="mf10_9_a" class="opcion-label">A. &lt;</label>
                <input type="radio" id="mf10_9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mf10_9_b" class="opcion-label">B. =</label>
                <input type="radio" id="mf10_9_c" name="respuestas[9]" value="correcta" class="opcion-input"><label for="mf10_9_c" class="opcion-label">C. &gt;</label>
                <input type="radio" id="mf10_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="mf10_9_d" class="opcion-label">D. &le;</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Desde el suelo, una estudiante observa la parte más alta de un edificio y desea calcular su altura usando un ángulo de elevación. ¿Qué razón trigonométrica relaciona el cateto opuesto con el cateto adyacente?</p>
                <input type="radio" id="mf10_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="mf10_10_a" class="opcion-label">A. Seno.</label>
                <input type="radio" id="mf10_10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="mf10_10_b" class="opcion-label">B. Coseno.</label>
                <input type="radio" id="mf10_10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="mf10_10_c" class="opcion-label">C. Tangente.</label>
                <input type="radio" id="mf10_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="mf10_10_d" class="opcion-label">D. Secante.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Una plataforma de música cobra la misma mensualidad sin importar cuántas canciones escuches. Ese valor fijo corresponde a una:</p>
                <input type="radio" id="mf10_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="mf10_11_a" class="opcion-label">A. Variable dependiente.</label>
                <input type="radio" id="mf10_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="mf10_11_b" class="opcion-label">B. Constante.</label>
                <input type="radio" id="mf10_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="mf10_11_c" class="opcion-label">C. Función cuadrática.</label>
                <input type="radio" id="mf10_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="mf10_11_d" class="opcion-label">D. Probabilidad.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>Después de realizar una encuesta sobre el deporte favorito de los estudiantes, el profesor desea comparar fácilmente los resultados. ¿Qué representación es la más adecuada?</p>
                <input type="radio" id="mf10_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="mf10_12_a" class="opcion-label">A. Una ecuación.</label>
                <input type="radio" id="mf10_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="mf10_12_b" class="opcion-label">B. Un gráfico de barras.</label>
                <input type="radio" id="mf10_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="mf10_12_c" class="opcion-label">C. Un plano cartesiano.</label>
                <input type="radio" id="mf10_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="mf10_12_d" class="opcion-label">D. Una parábola.</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Al lanzar un dado de seis caras, ¿cuál es la probabilidad de obtener un número par?</p>
                <input type="radio" id="mf10_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="mf10_13_a" class="opcion-label">A. 1/6</label>
                <input type="radio" id="mf10_13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="mf10_13_b" class="opcion-label">B. 2/6</label>
                <input type="radio" id="mf10_13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="mf10_13_c" class="opcion-label">C. 3/6</label>
                <input type="radio" id="mf10_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="mf10_13_d" class="opcion-label">D. 5/6</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Camila guarda <strong>$15.000</strong> cada semana para comprar una bicicleta. Si ahorra durante 8 semanas, ¿cuánto dinero tendrá?</p>
                <input type="radio" id="mf10_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="mf10_14_a" class="opcion-label">A. $90.000</label>
                <input type="radio" id="mf10_14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="mf10_14_b" class="opcion-label">B. $100.000</label>
                <input type="radio" id="mf10_14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="mf10_14_c" class="opcion-label">C. $120.000</label>
                <input type="radio" id="mf10_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="mf10_14_d" class="opcion-label">D. $150.000</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>El punto <strong>(0,7)</strong> está ubicado:</p>
                <input type="radio" id="mf10_15_a" name="respuestas[15]" value="correcta" class="opcion-input" required><label for="mf10_15_a" class="opcion-label">A. Sobre el eje Y.</label>
                <input type="radio" id="mf10_15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mf10_15_b" class="opcion-label">B. Sobre el eje X.</label>
                <input type="radio" id="mf10_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mf10_15_c" class="opcion-label">C. En el segundo cuadrante.</label>
                <input type="radio" id="mf10_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="mf10_15_d" class="opcion-label">D. En el cuarto cuadrante.</label>
            </section>

            <div style="text-align: center; margin-top: 40px;">
                <button type="submit" class="btn" style="border:none; cursor:pointer; font-size: 18px; padding: 15px 40px;">Enviar Respuestas</button>
            </div>
        </form>
    </main>

    <!-- VENTANA EMERGENTE (MODAL) QUE SE MUESTRA AL ENVIAR -->
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
