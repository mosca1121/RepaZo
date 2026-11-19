<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario =$_SESSION["id_usuario"];

// Conexión a la base de datos 'repazo'
$servidor = "localhost";
$usuario_db = "root";
$password_db = "";
$nombre_bd = "repazo";

$conexion = new mysqli($servidor,$usuario_db, $password_db,$nombre_bd);
if ($conexion->connect_error) {
    die("Fallo la conexión: " . $conexion->connect_error);
}

// Asegurar que exista la columna foto_perfil en la tabla usuarios
$conexion->query("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS foto_perfil VARCHAR(255) DEFAULT NULL");

// Procesar la subida de la foto de perfil
$mensajeErrorFoto = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $archivo =$_FILES['foto'];
    
    if ($archivo['error'] === UPLOAD_ERR_OK) {$tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        
        if (in_array($archivo['type'], $tiposPermitidos)) {$carpetaDestino = "uploads/";
            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }
            
            $nombreArchivo = "perfil_" . $id_usuario . "_" . time() . "." . pathinfo($archivo['name'], PATHINFO_EXTENSION);$rutaFinal = $carpetaDestino .$nombreArchivo;
            
            if (move_uploaded_file($archivo['tmp_name'],$rutaFinal)) {
                $updateSql =$conexion->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
                $updateSql->bind_param("si", $rutaFinal,$id_usuario);
                $updateSql->execute();$updateSql->close();
                
                header("Location: perfil.php");
                exit;
            } else {
                $mensajeErrorFoto = "Error al mover la imagen subida.";
            }
        } else {
            $mensajeErrorFoto = "Formato no válido (Solo JPG, PNG, WEBP).";
        }
    }
}

// 1. Obtener los datos del usuario desde la tabla 'usuarios'
$sql =$conexion->prepare("SELECT nombre, apellido, correo, foto_perfil FROM usuarios WHERE id_usuario = ?");
$sql->bind_param("i", $id_usuario);
$sql->execute();$resultado = $sql->get_result();$usuario = $resultado->fetch_assoc();$sql->close();

// 2. Consulta precisa usando la estructura real de tus tablas:
// Relaciona 'intentos_quizzes' con 'quizzes' mediante 'id_quiz' para sacar el promedio por cada id_materia
$queryProgreso =$conexion->prepare("
    SELECT m.nombre AS materia, COALESCE(AVG(i.porcentaje), 0) AS promedio_materia
    FROM materias m
    LEFT JOIN quizzes q ON q.id_materia = m.id_materia
    LEFT JOIN intentos_quizzes i ON q.id_quiz = i.id_quiz AND i.id_usuario = ?
    WHERE m.nombre IN ('Matemáticas', 'Español', 'Física', 'Química', 'Inglés')
    GROUP BY m.nombre
");
$queryProgreso->bind_param("i", $id_usuario);$queryProgreso->execute();
$resultadoProgreso =$queryProgreso->get_result();

$progresosPorMateria = [];
while ($row =$resultadoProgreso->fetch_assoc()) {
    $progresosPorMateria[$row['materia']] = round($row['promedio_materia']);
}
$queryProgreso->close();$conexion->close();

// Foto por defecto (Avatar SVG) o la foto subida
$fotoPerfilSrc = (!empty($usuario['foto_perfil']) && file_exists($usuario['foto_perfil'])) 
    ? $usuario['foto_perfil'] 
    : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23e2e8f0'/><circle cx='50' cy='38' r='18' fill='%2394a3b8'/><path d='M14 92c4-24 20-34 36-34s32 10 36 34z' fill='%2394a3b8'/></svg>";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Repazo | Perfil</title>
    <link href="CSS/codigo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img alt="logo de Repazo" decoding="async" loading="lazy" src="IMAGENES/LOGOREPAZO.jpg">
            <h2>Repazo</h2>
        </div>
        <nav>
            <ul>
                <li><a href="panel.php">Módulos</a></li>
                <li><a href="quizes.php">Quizzes</a></li>
                <li><a href="curiosidades.php">Datos Curiosos</a></li>
                <li><a href="perfil.php" class="active">Perfil</a></li>
                <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <main class="perfusuario">
        <div class="columna-izquierda">
            <section class="imgperfil">
                <figure>
                    <img alt="Foto de perfil" decoding="async" height="150" id="foto-perfil" loading="lazy" src="<?php echo htmlspecialchars($fotoPerfilSrc); ?>" width="150" style="object-fit: cover; border-radius: 50%;">
                    
                    <?php if (!empty($mensajeErrorFoto)): ?>
                        <p style="color: red; font-size: 0.8rem;"><?php echo $mensajeErrorFoto; ?></p>
                    <?php endif; ?>

                    <figcaption>
                        <form action="perfil.php" enctype="multipart/form-data" method="post">
                            <label>
                                Subir foto de perfil
                                <input accept="image/png, image/jpeg, image/webp" name="foto" required type="file">
                            </label>
                            <button type="submit">Guardar foto</button>
                        </form>
                    </figcaption>
                </figure>
            </section>

            <section class="dtperfil">
                <dl>
                    <dt>Nombre y Apellido</dt>
                    <dd><?php echo htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? '')); ?></dd>
                    
                    <dt>Correo</dt>
                    <dd><?php echo htmlspecialchars($usuario['correo'] ?? 'Sin correo'); ?></dd>
                </dl>
            </section>
        </div>

        <section class="progperfil">
            <h2>Progreso por Materia</h2>
            <ul>
                <?php 
                $materiasMapa = [
                    'Matemáticas',
                    'Español',
                    'Física',
                    'Química',
                    'Inglés'
                ];

                foreach ($materiasMapa as $nombreMateria) {
                    $porc = $progresosPorMateria[$nombreMateria] ?? 0;
                    echo "<li>";
                    echo "<label>{$nombreMateria} <progress max='100' value='{$porc}'>{$porc}\%</progress> ({$porc}%)</label>";
                    echo "</li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <footer>
        <section class="footer1">
            <h2>Repazo</h2>
            <p>Aprender nunca fue tan divertido.</p>
        </section>
        <section class="footer3">
            <h3>Síguenos</h3>
            <div class="redes-sociales">
                <a href="https://www.facebook.com/profile.php?id=61594301955666" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/_repazo?stkn=MWRlamV2MnNlNm1vcw==" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="