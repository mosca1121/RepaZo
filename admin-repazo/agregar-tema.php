<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirAdmin("../paginas/login.php");

$materiasValidas = [
    "matematicas" => "Matemáticas",
    "espanol"     => "Español",
    "fisica"      => "Física",
    "quimica"     => "Química",
    "ingles"      => "Inglés",
];

$slugMateria = (string)($_GET["materia"] ?? "");
$grado = filter_var($_GET["grado"] ?? null, FILTER_VALIDATE_INT);

if (!isset($materiasValidas[$slugMateria]) || !in_array($grado, [10, 11], true)) {
    header("Location: admin.php");
    exit;
}

$nombreMateria = $materiasValidas[$slugMateria];
$rutaBase = "../";
$paginaActiva = "admin";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Agregar tema de <?= e($nombreMateria) ?> <?= $grado ?>°</title>
    <link rel="stylesheet" href="../assets/css/codigo.css">
    <link rel="stylesheet" href="CSS/admin-tema.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="adt-principal">
        <article class="adt-tarjeta <?= $grado === 11 ? 'adt-tarjeta--once' : '' ?>">
            <a class="adt-volver" href="admin.php"><i class="fa-solid fa-arrow-left"></i> Volver al panel de administrador</a>

            <header class="adt-encabezado">
                <h1 class="adt-titulo">Agregar tema de <?= e($nombreMateria) ?></h1>
                <span class="adt-etiqueta-grado">Grado <?= $grado === 10 ? 'décimo (10°)' : 'undécimo (11°)' ?></span>
                <p class="adt-subtitulo">Completa el formulario para publicar un nuevo tema en este módulo.</p>
            </header>

            <form action="../acciones/guardar_tema.php" class="adt-formulario" enctype="multipart/form-data" method="post">
                <input name="grado" type="hidden" value="<?= $grado ?>">
                <input name="materia" type="hidden" value="<?= e($slugMateria) ?>">

                <fieldset class="adt-grupo" style="border:none; padding:0; display:flex; flex-direction:column; gap:18px;">
                    <p class="adt-campo">
                        <label class="adt-etiqueta" for="titulo">Título del tema</label>
                        <input class="adt-entrada" id="titulo" maxlength="150" name="titulo" placeholder="Ej: Funciones trigonométricas" required type="text">
                    </p>

                    <p class="adt-campo">
                        <label class="adt-etiqueta" for="explicacion">Explicación</label>
                        <textarea class="adt-area" id="explicacion" name="explicacion" placeholder="Explica el tema de forma clara y sencilla" required></textarea>
                    </p>

                    <p class="adt-campo">
                        <label class="adt-etiqueta" for="ejemplo">Ejemplo escrito</label>
                        <textarea class="adt-area" id="ejemplo" name="ejemplo" placeholder="Escribe un ejemplo resuelto o ilustrativo" required></textarea>
                    </p>

                    <p class="adt-campo">
                        <label class="adt-etiqueta" for="audio">Audio del tema <span class="adt-opcional">(opcional)</span></label>
                        <input accept="audio/*" class="adt-archivo" id="audio" name="audio" type="file">
                    </p>

                    <p class="adt-campo">
                        <label class="adt-etiqueta" for="video">Video de apoyo <span class="adt-opcional">(opcional)</span></label>
                        <input accept="video/*" class="adt-archivo" id="video" name="video" type="file">
                    </p>

                    <p class="adt-campo">
                        <label class="adt-etiqueta" for="link_video">Link del video de YouTube <span class="adt-opcional">(si aplica)</span></label>
                        <input class="adt-entrada" id="link_video" name="link_video" placeholder="https://www.youtube.com/watch?v=..." type="url">
                    </p>
                </fieldset>

                <footer class="adt-acciones">
                    <button class="adt-boton-guardar" type="submit">Guardar tema</button>
                    <a class="adt-boton-cancelar" href="admin.php">Cancelar</a>
                </footer>
            </form>
        </article>
    </main>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
