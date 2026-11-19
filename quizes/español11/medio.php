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
    $id_quiz = 117; // Quiz Español 11° medio

    $conexion = new mysqli("localhost", "root", "", "repazo");
    if (!$conexion->connect_error) {
        // Asegurar registros previos para evitar errores de llave foránea
        $conexion->query("INSERT IGNORE INTO materias (id_materia, nombre, descripcion) VALUES (105, 'Español', 'Materiales para estudiar lengua castellana.')");
        $conexion->query("INSERT IGNORE INTO dificultad_quizzes (id_dificultad, nombre, descripcion) VALUES (2, 'Medio', 'Preguntas de nivel intermedio.')");
        $conexion->query("INSERT IGNORE INTO quizzes (id_quiz, id_materia, id_dificultad, titulo, descripcion, grado) VALUES (117, 105, 2, 'Quiz de Español - Nivel Medio Grado 11', 'Quiz intermedio de español', 11)");

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
    <title>Repazo | Quiz Español 11° - Medio</title>
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
            <h1>Quiz de Español - Nivel Medio (Grado 11°)</h1>
            <p>Responde cada pregunta y evalúa tus conocimientos.</p><a href="#quiz">Comenzar</a>
        </div>
    </section>

    <main class="maintemas" id="quiz">
        <form action="" method="POST">
            <h2 class="tituloMainT">Preguntas del quiz</h2>

            <!-- PREGUNTA 1 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 1</h2>
                <p>Lee el siguiente fragmento:<br><br><em>"El acceso a la educaci&oacute;n permite que las personas desarrollen habilidades, mejoren sus oportunidades laborales y participen de manera m&aacute;s activa en la sociedad."</em><br><br>&iquest;Cu&aacute;l es la idea principal del texto?</p>
                <input type="radio" id="m1_a" name="respuestas[1]" value="incorrecta" class="opcion-input" required><label for="m1_a" class="opcion-label">A. Todas las personas deben conseguir un empleo.</label>
                <input type="radio" id="m1_b" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="m1_b" class="opcion-label">B. La educaci&oacute;n solo beneficia a los estudiantes universitarios.</label>
                <input type="radio" id="m1_c" name="respuestas[1]" value="correcta" class="opcion-input"><label for="m1_c" class="opcion-label">C. La educaci&oacute;n favorece el desarrollo personal y social.</label>
                <input type="radio" id="m1_d" name="respuestas[1]" value="incorrecta" class="opcion-input"><label for="m1_d" class="opcion-label">D. La sociedad depende &uacute;nicamente de la tecnolog&iacute;a.</label>
            </section>

            <!-- PREGUNTA 2 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 2</h2>
                <p>&iquest;Cu&aacute;l de las siguientes opciones representa una tesis en un texto argumentativo?</p>
                <input type="radio" id="m2_a" name="respuestas[2]" value="incorrecta" class="opcion-input" required><label for="m2_a" class="opcion-label">A. El agua hierve aproximadamente a 100 &deg;C.</label>
                <input type="radio" id="m2_b" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="m2_b" class="opcion-label">B. En Colombia existen diversas regiones naturales.</label>
                <input type="radio" id="m2_c" name="respuestas[2]" value="correcta" class="opcion-input"><label for="m2_c" class="opcion-label">C. El uso responsable de las redes sociales mejora la convivencia digital.</label>
                <input type="radio" id="m2_d" name="respuestas[2]" value="incorrecta" class="opcion-input"><label for="m2_d" class="opcion-label">D. Los &aacute;rboles producen ox&iacute;geno.</label>
            </section>

            <!-- PREGUNTA 3 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 3</h2>
                <p>&iquest;Cu&aacute;l es la funci&oacute;n principal de una cita textual dentro de un ensayo?</p>
                <input type="radio" id="m3_a" name="respuestas[3]" value="incorrecta" class="opcion-input" required><label for="m3_a" class="opcion-label">A. Hacer el texto m&aacute;s largo.</label>
                <input type="radio" id="m3_b" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="m3_b" class="opcion-label">B. Decorar el contenido.</label>
                <input type="radio" id="m3_c" name="respuestas[3]" value="correcta" class="opcion-input"><label for="m3_c" class="opcion-label">C. Respaldar una idea con informaci&oacute;n de una fuente confiable.</label>
                <input type="radio" id="m3_d" name="respuestas[3]" value="incorrecta" class="opcion-input"><label for="m3_d" class="opcion-label">D. Reemplazar la opini&oacute;n del autor.</label>
            </section>

            <!-- PREGUNTA 4 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 4</h2>
                <p>Lee el siguiente enunciado:<br><br><em>"Muchos estudiantes prepararon el examen con anticipaci&oacute;n; sin embargo, algunos no obtuvieron los resultados esperados."</em><br><br>El conector <strong>"sin embargo"</strong> expresa una relaci&oacute;n de:</p>
                <input type="radio" id="m4_a" name="respuestas[4]" value="incorrecta" class="opcion-input" required><label for="m4_a" class="opcion-label">A. Adici&oacute;n.</label>
                <input type="radio" id="m4_b" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="m4_b" class="opcion-label">B. Consecuencia.</label>
                <input type="radio" id="m4_c" name="respuestas[4]" value="correcta" class="opcion-input"><label for="m4_c" class="opcion-label">C. Contraste.</label>
                <input type="radio" id="m4_d" name="respuestas[4]" value="incorrecta" class="opcion-input"><label for="m4_d" class="opcion-label">D. Causa.</label>
            </section>

            <!-- PREGUNTA 5 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 5</h2>
                <p>Un estudiante compara informaci&oacute;n de varios art&iacute;culos antes de escribir un ensayo.<br>&iquest;Qu&eacute; habilidad est&aacute; desarrollando principalmente?</p>
                <input type="radio" id="m5_a" name="respuestas[5]" value="incorrecta" class="opcion-input" required><label for="m5_a" class="opcion-label">A. Memorizaci&oacute;n.</label>
                <input type="radio" id="m5_b" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="m5_b" class="opcion-label">B. Copia textual.</label>
                <input type="radio" id="m5_c" name="respuestas[5]" value="correcta" class="opcion-input"><label for="m5_c" class="opcion-label">C. An&aacute;lisis y selecci&oacute;n de informaci&oacute;n.</label>
                <input type="radio" id="m5_d" name="respuestas[5]" value="incorrecta" class="opcion-input"><label for="m5_d" class="opcion-label">D. Escritura autom&aacute;tica.</label>
            </section>

            <!-- PREGUNTA 6 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 6</h2>
                <p>Lee el siguiente fragmento:<br><br><em>"Cada vez m&aacute;s empresas implementan el teletrabajo porque reduce costos y permite una mayor flexibilidad para los empleados."</em><br><br>&iquest;Cu&aacute;l es la intenci&oacute;n principal del texto?</p>
                <input type="radio" id="m6_a" name="respuestas[6]" value="incorrecta" class="opcion-input" required><label for="m6_a" class="opcion-label">A. Narrar una historia.</label>
                <input type="radio" id="m6_b" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="m6_b" class="opcion-label">B. Expresar sentimientos.</label>
                <input type="radio" id="m6_c" name="respuestas[6]" value="correcta" class="opcion-input"><label for="m6_c" class="opcion-label">C. Informar sobre una tendencia y sus beneficios.</label>
                <input type="radio" id="m6_d" name="respuestas[6]" value="incorrecta" class="opcion-input"><label for="m6_d" class="opcion-label">D. Describir un paisaje.</label>
            </section>

            <!-- PREGUNTA 7 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 7</h2>
                <p>&iquest;Cu&aacute;l de las siguientes afirmaciones corresponde a un hecho verificable?</p>
                <input type="radio" id="m7_a" name="respuestas[7]" value="incorrecta" class="opcion-input" required><label for="m7_a" class="opcion-label">A. El caf&eacute; colombiano es el mejor del mundo.</label>
                <input type="radio" id="m7_b" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="m7_b" class="opcion-label">B. Leer novelas es m&aacute;s divertido que ver pel&iacute;culas.</label>
                <input type="radio" id="m7_c" name="respuestas[7]" value="correcta" class="opcion-input"><label for="m7_c" class="opcion-label">C. Colombia tiene 32 departamentos.</label>
                <input type="radio" id="m7_d" name="respuestas[7]" value="incorrecta" class="opcion-input"><label for="m7_d" class="opcion-label">D. La m&uacute;sica cl&aacute;sica es la m&aacute;s interesante.</label>
            </section>

            <!-- PREGUNTA 8 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 8</h2>
                <p>En un ensayo, &iquest;qu&eacute; funci&oacute;n cumple un argumento?</p>
                <input type="radio" id="m8_a" name="respuestas[8]" value="incorrecta" class="opcion-input" required><label for="m8_a" class="opcion-label">A. Cambiar el tema del texto.</label>
                <input type="radio" id="m8_b" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="m8_b" class="opcion-label">B. Presentar informaci&oacute;n sin relaci&oacute;n.</label>
                <input type="radio" id="m8_c" name="respuestas[8]" value="correcta" class="opcion-input"><label for="m8_c" class="opcion-label">C. Justificar o respaldar la tesis.</label>
                <input type="radio" id="m8_d" name="respuestas[8]" value="incorrecta" class="opcion-input"><label for="m8_d" class="opcion-label">D. Reemplazar la conclusi&oacute;n.</label>
            </section>

            <!-- PREGUNTA 9 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 9</h2>
                <p>&iquest;Cu&aacute;l de los siguientes conectores expresa una consecuencia?</p>
                <input type="radio" id="m9_a" name="respuestas[9]" value="incorrecta" class="opcion-input" required><label for="m9_a" class="opcion-label">A. Aunque.</label>
                <input type="radio" id="m9_b" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="m9_b" class="opcion-label">B. Sin embargo.</label>
                <input type="radio" id="m9_c" name="respuestas[9]" value="correcta" class="opcion-input"><label for="m9_c" class="opcion-label">C. En consecuencia.</label>
                <input type="radio" id="m9_d" name="respuestas[9]" value="incorrecta" class="opcion-input"><label for="m9_d" class="opcion-label">D. Mientras.</label>
            </section>

            <!-- PREGUNTA 10 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 10</h2>
                <p>Un estudiante consulta un libro, una revista cient&iacute;fica y una p&aacute;gina web oficial para realizar una investigaci&oacute;n.<br>&iquest;Cu&aacute;l es el principal beneficio de utilizar varias fuentes?</p>
                <input type="radio" id="m10_a" name="respuestas[10]" value="incorrecta" class="opcion-input" required><label for="m10_a" class="opcion-label">A. Hacer el trabajo m&aacute;s largo.</label>
                <input type="radio" id="m10_b" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="m10_b" class="opcion-label">B. Evitar escribir conclusiones.</label>
                <input type="radio" id="m10_c" name="respuestas[10]" value="correcta" class="opcion-input"><label for="m10_c" class="opcion-label">C. Comparar informaci&oacute;n y aumentar la confiabilidad de la investigaci&oacute;n.</label>
                <input type="radio" id="m10_d" name="respuestas[10]" value="incorrecta" class="opcion-input"><label for="m10_d" class="opcion-label">D. Copiar informaci&oacute;n de diferentes lugares.</label>
            </section>

            <!-- PREGUNTA 11 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 11</h2>
                <p>Lee el siguiente fragmento:<br><br><em>"Las ciudades que invierten en transporte p&uacute;blico reducen la congesti&oacute;n vehicular y mejoran la calidad del aire. Por ello, cada vez m&aacute;s gobiernos destinan recursos a este tipo de proyectos."</em><br><br>&iquest;Qu&eacute; conclusi&oacute;n puede inferirse del texto?</p>
                <input type="radio" id="m11_a" name="respuestas[11]" value="incorrecta" class="opcion-input" required><label for="m11_a" class="opcion-label">A. El transporte p&uacute;blico aumenta la contaminaci&oacute;n.</label>
                <input type="radio" id="m11_b" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="m11_b" class="opcion-label">B. Todas las personas prefieren usar autom&oacute;vil.</label>
                <input type="radio" id="m11_c" name="respuestas[11]" value="correcta" class="opcion-input"><label for="m11_c" class="opcion-label">C. Invertir en transporte p&uacute;blico puede beneficiar a las ciudades.</label>
                <input type="radio" id="m11_d" name="respuestas[11]" value="incorrecta" class="opcion-input"><label for="m11_d" class="opcion-label">D. Los gobiernos dejar&aacute;n de construir carreteras.</label>
            </section>

            <!-- PREGUNTA 12 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 12</h2>
                <p>&iquest;Cu&aacute;l de las siguientes opciones fortalece mejor un argumento sobre la importancia del reciclaje?</p>
                <input type="radio" id="m12_a" name="respuestas[12]" value="incorrecta" class="opcion-input" required><label for="m12_a" class="opcion-label">A. Muchas personas prefieren comprar productos nuevos.</label>
                <input type="radio" id="m12_b" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="m12_b" class="opcion-label">B. El reciclaje es una actividad muy conocida.</label>
                <input type="radio" id="m12_c" name="respuestas[12]" value="correcta" class="opcion-input"><label for="m12_c" class="opcion-label">C. Estudios ambientales demuestran que reciclar reduce la cantidad de residuos enviados a los rellenos sanitarios.</label>
                <input type="radio" id="m12_d" name="respuestas[12]" value="incorrecta" class="opcion-input"><label for="m12_d" class="opcion-label">D. Algunos recipientes tienen diferentes colores.</label>
            </section>

            <!-- PREGUNTA 13 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 13</h2>
                <p>&iquest;Cu&aacute;l de las siguientes caracter&iacute;sticas corresponde a un ensayo?</p>
                <input type="radio" id="m13_a" name="respuestas[13]" value="incorrecta" class="opcion-input" required><label for="m13_a" class="opcion-label">A. Presenta &uacute;nicamente di&aacute;logos entre personajes.</label>
                <input type="radio" id="m13_b" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="m13_b" class="opcion-label">B. Narra hechos imaginarios sin expresar opiniones.</label>
                <input type="radio" id="m13_c" name="respuestas[13]" value="correcta" class="opcion-input"><label for="m13_c" class="opcion-label">C. Expone una tesis y la desarrolla mediante argumentos.</label>
                <input type="radio" id="m13_d" name="respuestas[13]" value="incorrecta" class="opcion-input"><label for="m13_d" class="opcion-label">D. Describe exclusivamente experimentos cient&iacute;ficos.</label>
            </section>

            <!-- PREGUNTA 14 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 14</h2>
                <p>Lee el siguiente enunciado:<br><br><em>"El cambio clim&aacute;tico representa un desaf&iacute;o mundial; por esta raz&oacute;n, es necesario promover acciones para reducir las emisiones contaminantes."</em><br><br>La expresi&oacute;n <strong>"por esta raz&oacute;n"</strong> introduce una:</p>
                <input type="radio" id="m14_a" name="respuestas[14]" value="incorrecta" class="opcion-input" required><label for="m14_a" class="opcion-label">A. Comparaci&oacute;n.</label>
                <input type="radio" id="m14_b" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="m14_b" class="opcion-label">B. Oposici&oacute;n.</label>
                <input type="radio" id="m14_c" name="respuestas[14]" value="correcta" class="opcion-input"><label for="m14_c" class="opcion-label">C. Consecuencia.</label>
                <input type="radio" id="m14_d" name="respuestas[14]" value="incorrecta" class="opcion-input"><label for="m14_d" class="opcion-label">D. Enumeraci&oacute;n.</label>
            </section>

            <!-- PREGUNTA 15 -->
            <section class="tema">
                <h2><i class="fa-solid fa-circle-question"></i> Pregunta 15</h2>
                <p>Un investigador revisa diferentes art&iacute;culos, identifica posibles sesgos, compara la informaci&oacute;n y redacta sus propias conclusiones.<br>&iquest;Qu&eacute; competencia demuestra principalmente?</p>
                <input type="radio" id="m15_a" name="respuestas[15]" value="incorrecta" class="opcion-input" required><label for="m15_a" class="opcion-label">A. Copia de informaci&oacute;n.</label>
                <input type="radio" id="m15_b" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="m15_b" class="opcion-label">B. Memorizaci&oacute;n.</label>
                <input type="radio" id="m15_c" name="respuestas[15]" value="correcta" class="opcion-input"><label for="m15_c" class="opcion-label">C. Pensamiento cr&iacute;tico y an&aacute;lisis de fuentes.</label>
                <input type="radio" id="m15_d" name="respuestas[15]" value="incorrecta" class="opcion-input"><label for="m15_d" class="opcion-label">D. Escritura mec&aacute;nica.</label>
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