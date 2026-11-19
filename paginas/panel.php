<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirSesion("login.php");

$grado = (string)($_SESSION["grado"] ?? "");
$esAdmin = (($_SESSION["rol"] ?? "") === "admin");

$catalogoModulos = [
    "10" => [
        "numero" => "10°",
        "titulo" => "Grado Décimo",
        "subtitulo" => "Explora los temas fundamentales de décimo con explicaciones, audios y ejemplos.",
        "materias" => [
            ["slug" => "matematicas", "icono" => "fa-square-root-variable", "nombre" => "Matemáticas", "desc" => "Funciones, trigonometría, ecuaciones y estadística."],
            ["slug" => "espanol",     "icono" => "fa-book",                 "nombre" => "Español",     "desc" => "Literatura, comprensión lectora, gramática y comunicación."],
            ["slug" => "fisica",      "icono" => "fa-calculator",           "nombre" => "Física",      "desc" => "Movimiento, fuerzas, energía, trabajo y leyes físicas."],
            ["slug" => "quimica",     "icono" => "fa-atom",                 "nombre" => "Química",     "desc" => "Átomos, tabla periódica, enlaces y reacciones químicas."],
            ["slug" => "ingles",      "icono" => "fa-language",             "nombre" => "Inglés",      "desc" => "Gramática, vocabulario, lectura y comunicación."],
        ]
    ],
    "11" => [
        "numero" => "11°",
        "titulo" => "Grado Undécimo",
        "subtitulo" => "Prepárate para los temas clave de último grado y las pruebas de estado.",
        "materias" => [
            ["slug" => "matematicas", "icono" => "fa-square-root-variable", "nombre" => "Matemáticas", "desc" => "Límites, derivadas, probabilidad, funciones y geometría."],
            ["slug" => "espanol",     "icono" => "fa-book",                 "nombre" => "Español",     "desc" => "Literatura contemporánea, argumentación y análisis textual."],
            ["slug" => "fisica",      "icono" => "fa-calculator",           "nombre" => "Física",      "desc" => "Electricidad, magnetismo, ondas y física moderna."],
            ["slug" => "quimica",     "icono" => "fa-atom",                 "nombre" => "Química",     "desc" => "Química orgánica, soluciones, equilibrio y electroquímica."],
            ["slug" => "ingles",      "icono" => "fa-language",             "nombre" => "Inglés",      "desc" => "Tiempos verbales, conversación, lectura y escritura."],
        ]
    ]
];

$gradosMostrar = $esAdmin ? ["10", "11"] : (isset($catalogoModulos[$grado]) ? [$grado] : []);
$paginaActiva = "modulos";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Módulos</title>
    <link href="../assets/css/codigo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <header class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Módulos Educativos</h1>
            <p>Selecciona una materia para repasar sus temas con audios explicativos, ejemplos paso a paso y videos de apoyo.</p>
        </div>
    </header>

    <main>
        <?php if (!empty($gradosMostrar)): ?>
            <?php foreach ($gradosMostrar as $gKey):
                $info = $catalogoModulos[$gKey];
            ?>
                <section class="gradoM" aria-labelledby="titulo-grado-<?= e($gKey) ?>">
                    <header class="titulo-gradoM">
                        <span class="numeroModulos" aria-hidden="true"><?= e($info["numero"]) ?></span>
                        <div>
                            <h2 id="titulo-grado-<?= e($gKey) ?>"><?= e($info["titulo"]) ?></h2>
                            <p><?= e($info["subtitulo"]) ?></p>
                        </div>
                    </header>

                    <section class="areasM">
                        <?php foreach ($info["materias"] as $mat): ?>
                            <article class="areaM">
                                <header class="contenidoM">
                                    <h3><i class="fa-solid <?= e($mat['icono']) ?>" aria-hidden="true"></i> <?= e($mat["nombre"]) ?></h3>
                                    <p><?= e($mat["desc"]) ?></p>
                                </header>
                                <footer>
                                    <a href="modulo.php?materia=<?= e($mat['slug']) ?>&grado=<?= e($gKey) ?>">
                                        Estudiar <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </footer>
                            </article>
                        <?php endforeach; ?>
                    </section>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <section class="gradoM" style="text-align: center; padding: 40px;">
                <h2>No se ha detectado tu grado escolar.</h2>
                <p>Por favor, verifica tu cuenta o contacta con soporte.</p>
            </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
