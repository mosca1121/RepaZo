<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirAdmin("../paginas/login.php");

$idAdmin = (int)$_SESSION["id_usuario"];
$mensaje = "";
$tipoMensaje = "exito";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["foto"])) {
    $archivo = $_FILES["foto"];
    if ($archivo["error"] === UPLOAD_ERR_OK) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($archivo["tmp_name"]);
        $permitidos = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];

        if (isset($permitidos[$mime])) {
            $carpeta = __DIR__ . "/../uploads/";
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0755, true);
            }
            $nombreArchivo = "admin_" . $idAdmin . "_" . time() . "." . $permitidos[$mime];
            if (move_uploaded_file($archivo["tmp_name"], $carpeta . $nombreArchivo)) {
                $rutaRelativa = "uploads/" . $nombreArchivo;
                $upd = $conexion->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
                $upd->bind_param("si", $rutaRelativa, $idAdmin);
                $upd->execute();
                $upd->close();
                header("Location: admin.php?ok=foto");
                exit;
            }
        } else {
            $mensaje = "Formato no permitido. Usa JPG, PNG o WEBP.";
            $tipoMensaje = "error";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_tema"])) {
    $idTemaEliminar = (int)$_POST["eliminar_tema"];
    $del = $conexion->prepare("DELETE FROM temas_modulos WHERE id_tema = ?");
    $del->bind_param("i", $idTemaEliminar);
    $del->execute();
    $del->close();
    header("Location: admin.php?ok=eliminado");
    exit;
}

if (isset($_GET["ok"])) {
    $mensajesOk = [
        "foto" => "Foto de perfil actualizada correctamente.",
        "tema" => "Tema publicado en el módulo correspondiente.",
        "eliminado" => "Tema eliminado correctamente."
    ];
    $mensaje = $mensajesOk[$_GET["ok"]] ?? "";
}

$stmtAdmin = $conexion->prepare("SELECT nombre, apellido, correo, foto_perfil FROM usuarios WHERE id_usuario = ?");
$stmtAdmin->bind_param("i", $idAdmin);
$stmtAdmin->execute();
$admin = $stmtAdmin->get_result()->fetch_assoc();
$stmtAdmin->close();

$fotoSrc = (!empty($admin["foto_perfil"]) && file_exists(__DIR__ . "/../" . $admin["foto_perfil"]))
    ? "../" . $admin["foto_perfil"]
    : "../assets/img/LOGOREPAZO.jpg";

$sqlResultados = "
    SELECT i.id_intento, u.nombre, u.apellido, u.grado, m.nombre AS materia,
           d.nombre AS dificultad, i.respuestas_correctas, i.respuestas_incorrectas, i.porcentaje, i.fecha_fin
    FROM intentos_quizzes i
    INNER JOIN usuarios u ON u.id_usuario = i.id_usuario
    INNER JOIN quizzes q ON q.id_quiz = i.id_quiz
    INNER JOIN materias m ON m.id_materia = q.id_materia
    INNER JOIN dificultad_quizzes d ON d.id_dificultad = q.id_dificultad
    ORDER BY i.id_intento DESC
    LIMIT 100
";
$resultados = $conexion->query($sqlResultados);

$temasPublicados = $conexion->query("
    SELECT t.id_tema, t.titulo, t.grado, m.nombre AS materia, t.fecha_creacion
    FROM temas_modulos t
    INNER JOIN materias m ON m.id_materia = t.id_materia
    ORDER BY t.id_tema DESC
");

$materiasAdmin = [
    ["slug" => "matematicas", "nombre" => "Matemáticas", "icono" => "fa-square-root-variable"],
    ["slug" => "espanol",     "nombre" => "Español",     "icono" => "fa-book"],
    ["slug" => "fisica",      "nombre" => "Física",      "icono" => "fa-calculator"],
    ["slug" => "quimica",     "nombre" => "Química",     "icono" => "fa-atom"],
    ["slug" => "ingles",      "nombre" => "Inglés",      "icono" => "fa-language"],
];

$rutaBase = "../";
$paginaActiva = "admin";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Panel de Administrador</title>
    <link rel="stylesheet" href="../assets/css/codigo.css">
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <?php if ($mensaje !== ""): ?>
        <aside class="adm-alerta adm-alerta--<?= e($tipoMensaje) ?>" role="status">
            <?= e($mensaje) ?>
        </aside>
    <?php endif; ?>

    <main class="adm-principal">
        <section class="adm-panel-izq" aria-label="Gestión de contenidos">
            <?php foreach ([10 => "décimo", 11 => "undécimo"] as $gradoNum => $gradoTexto): ?>
                <section class="adm-fila <?= $gradoNum === 11 ? 'adm-fila--once' : 'adm-fila--decimo' ?>">
                    <h2 class="adm-fila-titulo">Agrega temas para grado <?= $gradoTexto ?> (<?= $gradoNum ?>°)</h2>
                    <ul class="adm-fila-grid" style="list-style:none;">
                        <?php foreach ($materiasAdmin as $mat): ?>
                            <li>
                                <article class="adm-materia">
                                    <span class="adm-materia-icono" aria-hidden="true">
                                        <i class="fa-solid <?= e($mat['icono']) ?>"></i>
                                    </span>
                                    <h3 class="adm-materia-nombre"><?= e($mat['nombre']) ?></h3>
                                    <a class="adm-materia-boton" href="agregar-tema.php?materia=<?= e($mat['slug']) ?>&grado=<?= $gradoNum ?>">
                                        Agregar tema
                                    </a>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        </section>

        <aside class="adm-panel-der" aria-label="Información del administrador">
            <h2 class="adm-panel-der-titulo">Perfil del administrador</h2>

            <figure class="adm-foto">
                <img alt="Foto de perfil del administrador" class="adm-foto-img" height="150" src="<?= e($fotoSrc) ?>" width="150">
                <figcaption>
                    <form action="admin.php" class="adm-foto-form" enctype="multipart/form-data" method="post">
                        <label class="adm-foto-label">
                            Cambiar foto de perfil
                            <input accept="image/png, image/jpeg, image/webp" class="adm-foto-input" name="foto" required type="file">
                        </label>
                        <button class="adm-foto-boton" type="submit">Guardar foto</button>
                    </form>
                </figcaption>
            </figure>

            <dl class="adm-datos">
                <dt class="adm-datos-termino">Nombre y Apellido</dt>
                <dd class="adm-datos-valor adm-datos-valor--nombre">
                    <?= e(($admin["nombre"] ?? "") . " " . ($admin["apellido"] ?? "")) ?>
                </dd>

                <dt class="adm-datos-termino">Correo</dt>
                <dd class="adm-datos-valor"><?= e($admin["correo"] ?? "") ?></dd>

                <dt class="adm-datos-termino">Rol</dt>
                <dd class="adm-datos-valor"><span class="adm-etiqueta-rol">Administrador</span></dd>
            </dl>
        </aside>
    </main>

    <section class="adm-resultados" aria-labelledby="titulo-temas-pub">
        <h2 class="adm-resultados-titulo" id="titulo-temas-pub">Temas publicados dinámicamente</h2>
        <div class="adm-tabla-contenedor">
            <table class="adm-tabla">
                <thead>
                    <tr>
                        <th class="adm-tabla-encabezado" scope="col">ID</th>
                        <th class="adm-tabla-encabezado" scope="col">Grado</th>
                        <th class="adm-tabla-encabezado" scope="col">Materia</th>
                        <th class="adm-tabla-encabezado" scope="col">Título del tema</th>
                        <th class="adm-tabla-encabezado" scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($temasPublicados->num_rows > 0): ?>
                        <?php while ($t = $temasPublicados->fetch_assoc()): ?>
                            <tr class="adm-tabla-fila">
                                <td class="adm-tabla-celda"><?= (int)$t["id_tema"] ?></td>
                                <td class="adm-tabla-celda"><?= (int)$t["grado"] ?>°</td>
                                <td class="adm-tabla-celda"><?= e($t["materia"]) ?></td>
                                <td class="adm-tabla-celda"><?= e($t["titulo"]) ?></td>
                                <td class="adm-tabla-celda">
                                    <form method="post" action="admin.php" onsubmit="return confirm('¿Eliminar este tema?');">
                                        <input type="hidden" name="eliminar_tema" value="<?= (int)$t["id_tema"] ?>">
                                        <button type="submit" class="adm-btn-eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td class="adm-tabla-vacio" colspan="5">Aún no se han agregado temas nuevos desde el panel.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="adm-resultados" aria-labelledby="titulo-resultados">
        <h2 class="adm-resultados-titulo" id="titulo-resultados">Resultados de quizzes de estudiantes</h2>
        <div class="adm-tabla-contenedor">
            <table class="adm-tabla">
                <thead>
                    <tr>
                        <th class="adm-tabla-encabezado" scope="col">#</th>
                        <th class="adm-tabla-encabezado" scope="col">Estudiante</th>
                        <th class="adm-tabla-encabezado" scope="col">Grado</th>
                        <th class="adm-tabla-encabezado" scope="col">Materia</th>
                        <th class="adm-tabla-encabezado" scope="col">Nivel</th>
                        <th class="adm-tabla-encabezado" scope="col">Correctas</th>
                        <th class="adm-tabla-encabezado" scope="col">Incorrectas</th>
                        <th class="adm-tabla-encabezado" scope="col">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultados->num_rows > 0): ?>
                        <?php while ($fila = $resultados->fetch_assoc()):
                            $porc = (float)$fila["porcentaje"];
                            $clasePorc = $porc >= 70 ? "alto" : ($porc >= 40 ? "medio" : "bajo");
                        ?>
                            <tr class="adm-tabla-fila">
                                <td class="adm-tabla-celda"><?= (int)$fila["id_intento"] ?></td>
                                <td class="adm-tabla-celda"><?= e($fila["nombre"] . " " . $fila["apellido"]) ?></td>
                                <td class="adm-tabla-celda adm-tabla-celda--centro"><?= (int)$fila["grado"] ?>°</td>
                                <td class="adm-tabla-celda"><?= e($fila["materia"]) ?></td>
                                <td class="adm-tabla-celda"><?= e($fila["dificultad"]) ?></td>
                                <td class="adm-tabla-celda adm-tabla-celda--centro"><?= (int)$fila["respuestas_correctas"] ?></td>
                                <td class="adm-tabla-celda adm-tabla-celda--centro"><?= (int)$fila["respuestas_incorrectas"] ?></td>
                                <td class="adm-tabla-celda">
                                    <span class="adm-porcentaje adm-porcentaje--<?= $clasePorc ?>">
                                        <?= round($porc, 1) ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td class="adm-tabla-vacio" colspan="8">Aún no hay resultados de quizzes registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
