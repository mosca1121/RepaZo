<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirSesion("login.php");

$id_usuario = (int)$_SESSION["id_usuario"];
$mensajeErrorFoto = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["foto"])) {
    $archivo = $_FILES["foto"];
    if ($archivo["error"] === UPLOAD_ERR_OK) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($archivo["tmp_name"]);
        $permitidos = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];

        if (isset($permitidos[$mime])) {
            $carpetaDestino = __DIR__ . "/../uploads/";
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0755, true);
            }
            $nombreArchivo = "perfil_" . $id_usuario . "_" . time() . "." . $permitidos[$mime];
            if (move_uploaded_file($archivo["tmp_name"], $carpetaDestino . $nombreArchivo)) {
                $rutaRelativa = "uploads/" . $nombreArchivo;
                $updateSql = $conexion->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
                $updateSql->bind_param("si", $rutaRelativa, $id_usuario);
                $updateSql->execute();
                $updateSql->close();

                header("Location: perfil.php");
                exit;
            } else {
                $mensajeErrorFoto = "Error al guardar la imagen subida.";
            }
        } else {
            $mensajeErrorFoto = "Formato no válido (Solo JPG, PNG o WEBP).";
        }
    }
}

$sql = $conexion->prepare("SELECT nombre, apellido, correo, grado, foto_perfil FROM usuarios WHERE id_usuario = ?");
$sql->bind_param("i", $id_usuario);
$sql->execute();
$usuario = $sql->get_result()->fetch_assoc();
$sql->close();

$queryProgreso = $conexion->prepare("
    SELECT m.nombre AS materia, COALESCE(AVG(i.porcentaje), 0) AS promedio_materia
    FROM materias m
    LEFT JOIN quizzes q ON q.id_materia = m.id_materia
    LEFT JOIN intentos_quizzes i ON q.id_quiz = i.id_quiz AND i.id_usuario = ?
    WHERE m.nombre IN ('Matemáticas', 'Español', 'Física', 'Química', 'Inglés')
    GROUP BY m.nombre
");
$queryProgreso->bind_param("i", $id_usuario);
$queryProgreso->execute();
$resultadoProgreso = $queryProgreso->get_result();

$progresosPorMateria = [];
while ($row = $resultadoProgreso->fetch_assoc()) {
    $progresosPorMateria[$row["materia"]] = round((float)$row["promedio_materia"]);
}
$queryProgreso->close();

$fotoPerfilSrc = (!empty($usuario["foto_perfil"]) && file_exists(__DIR__ . "/../" . $usuario["foto_perfil"]))
    ? "../" . $usuario["foto_perfil"]
    : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23e2e8f0'/><circle cx='50' cy='38' r='18' fill='%2394a3b8'/><path d='M14 92c4-24 20-34 36-34s32 10 36 34z' fill='%2394a3b8'/></svg>";

$paginaActiva = "perfil";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Perfil</title>
    <link href="../assets/css/codigo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="perfusuario">
        <aside class="columna-izquierda" aria-label="Datos personales">
            <section class="imgperfil">
                <figure>
                    <img alt="Foto de perfil" decoding="async" height="150" id="foto-perfil" loading="lazy" src="<?= e($fotoPerfilSrc) ?>" width="150">

                    <?php if ($mensajeErrorFoto !== ""): ?>
                        <p role="alert" style="color: #dc2626; font-size: 0.85rem;"><?= e($mensajeErrorFoto) ?></p>
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
                    <dd><?= e(($usuario["nombre"] ?? "") . " " . ($usuario["apellido"] ?? "")) ?></dd>

                    <dt>Correo</dt>
                    <dd><?= e($usuario["correo"] ?? "Sin correo") ?></dd>

                    <dt>Grado</dt>
                    <dd><?= e((string)($usuario["grado"] ?? "No asignado")) ?>°</dd>
                </dl>
            </section>
        </aside>

        <section class="progperfil" aria-labelledby="titulo-progreso">
            <h2 id="titulo-progreso">Progreso por Materia</h2>
            <ul>
                <?php
                $materiasMapa = ["Matemáticas", "Español", "Física", "Química", "Inglés"];
                foreach ($materiasMapa as $nombreMateria):
                    $porc = $progresosPorMateria[$nombreMateria] ?? 0;
                ?>
                    <li>
                        <label>
                            <?= e($nombreMateria) ?>
                            <progress max="100" value="<?= $porc ?>"><?= $porc ?>%</progress>
                            <span>(<?= $porc ?>%)</span>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
