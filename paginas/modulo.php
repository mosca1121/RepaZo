<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirSesion("login.php");

$materiasInfo = [
    "matematicas" => ["id" => 102, "nombre" => "Matemáticas", "icono" => "fa-square-root-variable", "archivo_base" => ["10" => "modulosmatematicas10.html", "11" => "matematicas11.html"]],
    "espanol"     => ["id" => 105, "nombre" => "Español",     "icono" => "fa-book",                 "archivo_base" => ["10" => "español10.html",           "11" => "español11.html"]],
    "fisica"      => ["id" => 103, "nombre" => "Física",      "icono" => "fa-calculator",           "archivo_base" => ["10" => "fisica10.html",            "11" => "fisica11.html"]],
    "quimica"     => ["id" => 104, "nombre" => "Química",     "icono" => "fa-atom",                 "archivo_base" => ["10" => "quimica10.html",           "11" => "quimica11.html"]],
    "ingles"      => ["id" => 101, "nombre" => "Inglés",      "icono" => "fa-language",             "archivo_base" => ["10" => "ingles10.html",            "11" => "ingles11.html"]],
];

$slug = (string)($_GET["materia"] ?? "");
$grado = (int)($_GET["grado"] ?? ($_SESSION["grado"] ?? 10));

if (!isset($materiasInfo[$slug]) || !in_array($grado, [10, 11], true)) {
    header("Location: panel.php");
    exit;
}

$info = $materiasInfo[$slug];
$stmt = $conexion->prepare("SELECT * FROM temas_modulos WHERE id_materia = ? AND grado = ? ORDER BY id_tema ASC");
$stmt->bind_param("ii", $info["id"], $grado);
$stmt->execute();
$temasNuevos = $stmt->get_result();

$paginaActiva = "modulos";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | <?= e($info["nombre"]) ?> <?= $grado ?>°</title>
    <link rel="stylesheet" href="../assets/css/codigo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <header class="heroMQC">
        <div class="heroMQC-texto">
            <h1><?= e($info["nombre"]) ?> · <?= $grado ?>°</h1>
            <p>Aprende los temas clave de grado <?= $grado === 10 ? 'décimo' : 'undécimo' ?> mediante explicaciones claras, audios y ejemplos.</p>
            <a href="#maintemas">Comenzar estudio</a>
        </div>
    </header>

    <main class="maintemas" id="maintemas">
        <nav aria-label="Navegación del módulo" style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:28px;">
            <a class="btn" href="panel.php"><i class="fa-solid fa-arrow-left"></i> Volver a Módulos</a>
            <a class="btn" href="quizes.php"><i class="fa-solid fa-circle-question"></i> Practicar Quizzes</a>
        </nav>

        <h2 class="tituloMainT">Temas de <?= e($info["nombre"]) ?> <?= $grado ?>°</h2>

        <?php
        $archivoBase = __DIR__ . "/../modulos/" . $info["archivo_base"][(string)$grado];
        if (file_exists($archivoBase)) {
            $htmlBase = file_get_contents($archivoBase);
            if (preg_match('/<main[^>]*>(.*?)<\/main>/is', $htmlBase, $matches)) {
                $contenidoMain = preg_replace('/<h2 class="tituloMainT">.*?<\/h2>/is', '', $matches[1]);
                // Semantización: convertir divs internos repetitivos a figure/aside
                $contenidoMain = str_replace('<div class="audio-tema">', '<figure class="audio-tema">', $contenidoMain);
                $contenidoMain = str_replace('</audio></div>', '</audio></figure>', $contenidoMain);
                $contenidoMain = preg_replace('/<\/audio>\s*<\/div>/is', '</audio></figure>', $contenidoMain);
                $contenidoMain = str_replace('<div class="ejemploT">', '<aside class="ejemploT">', $contenidoMain);
                $contenidoMain = preg_replace('/<\/ul>\s*<\/div>/is', '</ul></aside>', $contenidoMain);
                echo $contenidoMain;
            }
        }
        ?>

        <?php while ($tema = $temasNuevos->fetch_assoc()): ?>
            <article class="tema">
                <h2><i class="fa-solid <?= e($info['icono']) ?>" aria-hidden="true"></i> <?= e($tema["titulo"]) ?></h2>
                <p><?= nl2br(e($tema["explicacion"])) ?></p>

                <?php if (!empty($tema["ruta_audio"])): ?>
                    <figure class="audio-tema">
                        <figcaption><h3><i class="fa-solid fa-volume-high"></i> Escuchar explicación</h3></figcaption>
                        <audio controls preload="none">
                            <source src="../<?= e($tema["ruta_audio"]) ?>">
                        </audio>
                    </figure>
                <?php endif; ?>

                <aside class="ejemploT">
                    <h3>Ejemplo práctico</h3>
                    <p><?= nl2br(e($tema["ejemplo"])) ?></p>

                    <?php if (!empty($tema["ruta_video"])): ?>
                        <h3>Video de apoyo</h3>
                        <video controls style="width:100%; max-width:560px; border-radius:12px;">
                            <source src="../<?= e($tema["ruta_video"]) ?>">
                        </video>
                    <?php elseif (!empty($tema["link_video"])): ?>
                        <h3>Video de apoyo</h3>
                        <iframe allowfullscreen height="315" src="<?= e($tema["link_video"]) ?>" title="<?= e($tema['titulo']) ?>" width="560" style="max-width:100%; border:0; border-radius:12px;"></iframe>
                    <?php endif; ?>
                </aside>
            </article>
        <?php endwhile; ?>
    </main>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
