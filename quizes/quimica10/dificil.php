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
    $id_dificultad = 3; // Avanzado
    $id_quiz = 112; // Quiz Química 10° difícil

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES ($id_materia, 'Química', 'Materiales para estudiar química.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES ($id_dificultad, 'Avanzado', 'Preguntas de nivel avanzado.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES ($id_quiz, $id_materia, $id_dificultad, 'Quiz de Química 10° - Nivel Avanzado', 'Quiz avanzado de química para grado 10', 10)");

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
    <title>Repazo | Quiz Química 10° - Nivel Avanzado</title>
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
            <p>Nivel Avanzado. Responde cada pregunta y demuestra lo que has aprendido.</p>
            <a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Química 10° - Nivel Avanzado</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>En una fábrica se produce agua mediante la reacción: <strong>2H₂ + O₂ → 2H₂O</strong>. Si reaccionan 4 moles de hidrógeno con oxígeno suficiente, ¿cuántos moles de agua se producen?</p>
                <input type="radio" id="qa10_1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="qa10_1_a" class="opcion-label">A. 2 moles</label>
                <input type="radio" id="qa10_1_b" name="respuestas[1]" value="correcta" class="opcion-input"><label for="qa10_1_b" class="opcion-label">B. 4 moles</label>
                <input type="radio" id="qa10_1_c" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qa10_1_c" class="opcion-label">C. 6 moles</label>
                <input type="radio" id="qa10_1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="qa10_1_d" class="opcion-label">D. 8 moles</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>Un químico analiza una muestra de dióxido de carbono (CO₂). Si tiene 44 gramos de CO₂ y su masa molar es 44 g/mol, ¿cuántos moles tiene la muestra?</p>
                <input type="radio" id="qa10_2_a" name="respuestas[2]" value="correcta" class="opcion-input" required><label for="qa10_2_a" class="opcion-label">A. 1 mol</label>
                <input type="radio" id="qa10_2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qa10_2_b" class="opcion-label">B. 2 moles</label>
                <input type="radio" id="qa10_2_c" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qa10_2_c" class="opcion-label">C. 22 moles</label>
                <input type="radio" id="qa10_2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="qa10_2_d" class="opcion-label">D. 44 moles</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>Un agricultor utiliza un fertilizante que contiene nitrógeno. El elemento pertenece al grupo 15 de la tabla periódica. ¿Cuántos electrones de valencia tiene el nitrógeno?</p>
                <input type="radio" id="qa10_3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="qa10_3_a" class="opcion-label">A. 2</label>
                <input type="radio" id="qa10_3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qa10_3_b" class="opcion-label">B. 3</label>
                <input type="radio" id="qa10_3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="qa10_3_c" class="opcion-label">C. 5</label>
                <input type="radio" id="qa10_3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="qa10_3_d" class="opcion-label">D. 7</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>En un laboratorio se mezcla ácido clorhídrico (HCl) con hidróxido de sodio (NaOH): <strong>HCl + NaOH → NaCl + H₂O</strong>. Este tipo de reacción se clasifica como:</p>
                <input type="radio" id="qa10_4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="qa10_4_a" class="opcion-label">A. Combustión</label>
                <input type="radio" id="qa10_4_b" name="respuestas[4]" value="correcta" class="opcion-input"><label for="qa10_4_b" class="opcion-label">B. Neutralización</label>
                <input type="radio" id="qa10_4_c" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qa10_4_c" class="opcion-label">C. Descomposición</label>
                <input type="radio" id="qa10_4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="qa10_4_d" class="opcion-label">D. Oxidación</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Una empresa diseña un material resistente usando enlaces entre átomos de carbono. Estos enlaces donde los átomos comparten electrones son:</p>
                <input type="radio" id="qa10_5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="qa10_5_a" class="opcion-label">A. Iónicos</label>
                <input type="radio" id="qa10_5_b" name="respuestas[5]" value="correcta" class="opcion-input"><label for="qa10_5_b" class="opcion-label">B. Covalentes</label>
                <input type="radio" id="qa10_5_c" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qa10_5_c" class="opcion-label">C. Metálicos</label>
                <input type="radio" id="qa10_5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="qa10_5_d" class="opcion-label">D. Nucleares</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Un laboratorio necesita preparar una solución con una concentración específica. Si aumenta la cantidad de soluto manteniendo el mismo volumen de solvente, la concentración de la solución:</p>
                <input type="radio" id="qa10_6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="qa10_6_a" class="opcion-label">A. Disminuye</label>
                <input type="radio" id="qa10_6_b" name="respuestas[6]" value="correcta" class="opcion-input"><label for="qa10_6_b" class="opcion-label">B. Aumenta</label>
                <input type="radio" id="qa10_6_c" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qa10_6_c" class="opcion-label">C. No cambia</label>
                <input type="radio" id="qa10_6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="qa10_6_d" class="opcion-label">D. Desaparece</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>En una industria se realiza la combustión del metano: <strong>CH₄ + 2O₂ → CO₂ + 2H₂O</strong>. ¿Qué elemento del metano se combina con oxígeno para formar dióxido de carbono?</p>
                <input type="radio" id="qa10_7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="qa10_7_a" class="opcion-label">A. Hidrógeno</label>
                <input type="radio" id="qa10_7_b" name="respuestas[7]" value="correcta" class="opcion-input"><label for="qa10_7_b" class="opcion-label">B. Carbono</label>
                <input type="radio" id="qa10_7_c" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qa10_7_c" class="opcion-label">C. Oxígeno</label>
                <input type="radio" id="qa10_7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="qa10_7_d" class="opcion-label">D. Nitrógeno</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>Un científico analiza un átomo con 20 protones. Según la tabla periódica, ¿qué elemento está estudiando?</p>
                <input type="radio" id="qa10_8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="qa10_8_a" class="opcion-label">A. Sodio (Na)</label>
                <input type="radio" id="qa10_8_b" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qa10_8_b" class="opcion-label">B. Magnesio (Mg)</label>
                <input type="radio" id="qa10_8_c" name="respuestas[8]" value="correcta" class="opcion-input"><label for="qa10_8_c" class="opcion-label">C. Calcio (Ca)</label>
                <input type="radio" id="qa10_8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="qa10_8_d" class="opcion-label">D. Potasio (K)</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>Una empresa farmacéutica estudia un compuesto formado por carbono, hidrógeno y oxígeno. Este tipo de compuesto pertenece principalmente a la química:</p>
                <input type="radio" id="qa10_9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="qa10_9_a" class="opcion-label">A. Inorgánica</label>
                <input type="radio" id="qa10_9_b" name="respuestas[9]" value="correcta" class="opcion-input"><label for="qa10_9_b" class="opcion-label">B. Orgánica</label>
                <input type="radio" id="qa10_9_c" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qa10_9_c" class="opcion-label">C. Nuclear</label>
                <input type="radio" id="qa10_9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="qa10_9_d" class="opcion-label">D. Analítica solamente</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Un investigador observa que una reacción ocurre más rápido al aumentar la temperatura. Esto sucede porque las partículas:</p>
                <input type="radio" id="qa10_10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="qa10_10_a" class="opcion-label">A. Pierden masa</label>
                <input type="radio" id="qa10_10_b" name="respuestas[10]" value="correcta" class="opcion-input"><label for="qa10_10_b" class="opcion-label">B. Tienen mayor energía cinética y chocan más frecuentemente</label>
                <input type="radio" id="qa10_10_c" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qa10_10_c" class="opcion-label">C. Dejan de moverse</label>
                <input type="radio" id="qa10_10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="qa10_10_d" class="opcion-label">D. Cambian de elemento químico</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Un químico necesita producir una cantidad determinada de sal mediante la reacción: <strong>2Na + Cl₂ → 2NaCl</strong>. Si utiliza 2 moles de sodio y tiene cloro suficiente, ¿cuántos moles de NaCl puede producir?</p>
                <input type="radio" id="qa10_11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="qa10_11_a" class="opcion-label">A. 1 mol</label>
                <input type="radio" id="qa10_11_b" name="respuestas[11]" value="correcta" class="opcion-input"><label for="qa10_11_b" class="opcion-label">B. 2 moles</label>
                <input type="radio" id="qa10_11_c" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qa10_11_c" class="opcion-label">C. 3 moles</label>
                <input type="radio" id="qa10_11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="qa10_11_d" class="opcion-label">D. 4 moles</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>En una planta de tratamiento se mide el pH de una sustancia y se obtiene un valor de 12. ¿Qué característica tiene esta sustancia?</p>
                <input type="radio" id="qa10_12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="qa10_12_a" class="opcion-label">A. Es altamente ácida</label>
                <input type="radio" id="qa10_12_b" name="respuestas[12]" value="correcta" class="opcion-input"><label for="qa10_12_b" class="opcion-label">B. Es básica o alcalina</label>
                <input type="radio" id="qa10_12_c" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qa10_12_c" class="opcion-label">C. Es completamente neutra</label>
                <input type="radio" id="qa10_12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="qa10_12_d" class="opcion-label">D. No contiene agua</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>Un investigador compara dos elementos: el sodio (Na) y el cloro (Cl). ¿Por qué el sodio pierde electrones fácilmente?</p>
                <input type="radio" id="qa10_13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="qa10_13_a" class="opcion-label">A. Porque tiene muchos electrones de valencia</label>
                <input type="radio" id="qa10_13_b" name="respuestas[13]" value="correcta" class="opcion-input"><label for="qa10_13_b" class="opcion-label">B. Porque busca alcanzar una configuración más estable</label>
                <input type="radio" id="qa10_13_c" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qa10_13_c" class="opcion-label">C. Porque no tiene protones</label>
                <input type="radio" id="qa10_13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="qa10_13_d" class="opcion-label">D. Porque pertenece los gases nobles</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Un laboratorio analiza una muestra de agua contaminada y encuentra una concentración alta de iones metálicos. ¿Qué propiedad química está estudiando principalmente?</p>
                <input type="radio" id="qa10_14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="qa10_14_a" class="opcion-label">A. El color de la muestra</label>
                <input type="radio" id="qa10_14_b" name="respuestas[14]" value="correcta" class="opcion-input"><label for="qa10_14_b" class="opcion-label">B. Su composición química</label>
                <input type="radio" id="qa10_14_c" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qa10_14_c" class="opcion-label">C. Su temperatura solamente</label>
                <input type="radio" id="qa10_14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="qa10_14_d" class="opcion-label">D. Su volumen únicamente</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Una industria quiere disminuir la cantidad de residuos químicos producidos. ¿Qué principio de la química sostenible busca reducir los desechos antes de generarlos?</p>
                <input type="radio" id="qa10_15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="qa10_15_a" class="opcion-label">A. Aumentar el consumo de sustancias</label>
                <input type="radio" id="qa10_15_b" name="respuestas[15]" value="correcta" class="opcion-input"><label for="qa10_15_b" class="opcion-label">B. Prevención de residuos</label>
                <input type="radio" id="qa10_15_c" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qa10_15_c" class="opcion-label">C. Usar más reactivos peligrosos</label>
                <input type="radio" id="qa10_15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="qa10_15_d" class="opcion-label">D. Eliminar todos los procesos químicos</label>
            </section>

            <div style="text-align: center; margin-top: 40px;">
                <button type="submit" class="btn" style="border:none; cursor:pointer; font-size: 18px; padding: 15px 40px;">Enviar Respuestas</button>
            </div>
        </form>
    </main>

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