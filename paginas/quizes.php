<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirSesion("login.php");

$idUsuario = (int)$_SESSION["id_usuario"];
$grado = (string)($_SESSION["grado"] ?? "");
$esAdmin = (($_SESSION["rol"] ?? "") === "admin");

$mejoresPuntajes = [];
$stmtBest = $conexion->prepare("
    SELECT q.id_materia, q.grado, MAX(i.porcentaje) AS mejor
    FROM intentos_quizzes i
    INNER JOIN quizzes q ON q.id_quiz = i.id_quiz
    WHERE i.id_usuario = ?
    GROUP BY q.id_materia, q.grado
");
$stmtBest->bind_param("i", $idUsuario);
$stmtBest->execute();
$resBest = $stmtBest->get_result();
while ($r = $resBest->fetch_assoc()) {
    $mejoresPuntajes[(string)$r["grado"]][(int)$r["id_materia"]] = round((float)$r["mejor"]);
}
$stmtBest->close();

$catalogo = [
    "10" => [
        "numero" => "10°",
        "titulo" => "Quizzes · Grado Décimo",
        "subtitulo" => "Selecciona una materia y elige tu nivel de dificultad.",
        "materias" => [
            ["id" => 102, "icono" => "fa-square-root-variable", "nombre" => "Matemáticas", "desc" => "Funciones, trigonometría, ecuaciones y estadística.", "carpeta" => "mate10"],
            ["id" => 105, "icono" => "fa-book",                 "nombre" => "Español",     "desc" => "Comprensión lectora, figuras literarias y gramática.", "carpeta" => "español10"],
            ["id" => 103, "icono" => "fa-calculator",           "nombre" => "Física",      "desc" => "Cinemática, leyes de Newton, trabajo y energía.", "carpeta" => "fisica10"],
            ["id" => 104, "icono" => "fa-atom",                 "nombre" => "Química",     "desc" => "Tabla periódica, enlaces y estequiometría.", "carpeta" => "quimica10"],
            ["id" => 101, "icono" => "fa-language",             "nombre" => "Inglés",      "desc" => "Tiempos perfectos, condicionales y lectura.", "carpeta" => "ingles10"],
        ]
    ],
    "11" => [
        "numero" => "11°",
        "titulo" => "Quizzes · Grado Undécimo",
        "subtitulo" => "Enfrenta los retos y simulacros tipo ICFES de último grado.",
        "materias" => [
            ["id" => 102, "icono" => "fa-square-root-variable", "nombre" => "Matemáticas", "desc" => "Límites, derivadas, cónicas y probabilidad.", "carpeta" => "mate11"],
            ["id" => 105, "icono" => "fa-book",                 "nombre" => "Español",     "desc" => "Argumentación crítica, ensayo y literatura.", "carpeta" => "español11"],
            ["id" => 103, "icono" => "fa-calculator",           "nombre" => "Física",      "desc" => "Ondas, gravitación, electricidad y magnetismo.", "carpeta" => "fisica11"],
            ["id" => 104, "icono" => "fa-atom",                 "nombre" => "Química",     "desc" => "Química orgánica, equilibrio y electroquímica.", "carpeta" => "quimica11"],
            ["id" => 101, "icono" => "fa-language",             "nombre" => "Inglés",      "desc" => "Vocabulario ICFES, modales y comprensión.", "carpeta" => "ingles11"],
        ]
    ]
];

$gradosMostrar = $esAdmin ? ["10", "11"] : (isset($catalogo[$grado]) ? [$grado] : []);
$paginaActiva = "quizes";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepaZo | Quizzes</title>
    <link rel="stylesheet" href="../assets/css/codigo.css">
    <link rel="stylesheet" href="../assets/css/quizes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <header class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Quizzes de Repaso</h1>
            <p>Pon a prueba tus conocimientos con retroalimentación inmediata y guarda tu progreso por materia.</p>
        </div>
    </header>

    <main>
        <?php if (!empty($gradosMostrar)): ?>
            <?php foreach ($gradosMostrar as $gKey):
                $datosGrado = $catalogo[$gKey];
            ?>
                <section class="gradoM" aria-labelledby="titulo-quizzes-grado-<?= e($gKey) ?>">
                    <header class="titulo-gradoM">
                        <span class="numeroModulos" aria-hidden="true"><?= e($datosGrado["numero"]) ?></span>
                        <div>
                            <h2 id="titulo-quizzes-grado-<?= e($gKey) ?>"><?= e($datosGrado["titulo"]) ?></h2>
                            <p><?= e($datosGrado["subtitulo"]) ?></p>
                        </div>
                    </header>

                    <section class="areasM">
                        <?php foreach ($datosGrado["materias"] as $mat):
                            $mejor = $mejoresPuntajes[$gKey][$mat["id"]] ?? null;
                        ?>
                            <article class="areaM">
                                <header class="contenidoM">
                                    <span class="badge-puntaje">
                                        <?= $mejor !== null ? "Mejor nota: {$mejor}%" : "Sin intentos aún" ?>
                                    </span>
                                    <h3><i class="fa-solid <?= e($mat['icono']) ?>" aria-hidden="true"></i> <?= e($mat["nombre"]) ?></h3>
                                    <p><?= e($mat["desc"]) ?></p>
                                </header>
                                <footer>
                                    <button type="button"
                                            class="btn-practicar"
                                            data-materia="<?= e($mat['nombre']) ?>"
                                            data-carpeta="<?= e($mat['carpeta']) ?>">
                                        Practicar ahora
                                    </button>
                                </footer>
                            </article>
                        <?php endforeach; ?>
                    </section>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <section class="gradoM" style="text-align: center; padding: 40px;">
                <h2>No se ha detectado tu grado escolar.</h2>
                <p>Por favor, asegúrate de haber iniciado sesión correctamente.</p>
            </section>
        <?php endif; ?>
    </main>

    <dialog class="modal-niveles" id="dialogNiveles">
        <article class="modal-caja">
            <button type="button" class="modal-cerrar" id="btnCerrarDialog" aria-label="Cerrar">&times;</button>
            <h3 id="modalTituloMateria">Materia</h3>
            <p>Elige el nivel de dificultad:</p>
            <nav class="modal-opciones" aria-label="Niveles de dificultad">
                <a id="linkFacil" href="#" class="nivel-facil"><i class="fa-solid fa-star"></i> Fácil (15 preguntas)</a>
                <a id="linkMedio" href="#" class="nivel-medio"><i class="fa-solid fa-star-half-stroke"></i> Medio (15 preguntas)</a>
                <a id="linkDificil" href="#" class="nivel-dificil"><i class="fa-solid fa-fire"></i> Difícil (15 preguntas)</a>
            </nav>
        </article>
    </dialog>

    <script>
        const dialog = document.getElementById("dialogNiveles");
        document.querySelectorAll(".btn-practicar").forEach(btn => {
            btn.addEventListener("click", () => {
                const materia = btn.dataset.materia;
                const carpeta = btn.dataset.carpeta;
                document.getElementById("modalTituloMateria").textContent = materia;
                document.getElementById("linkFacil").href = `../quizes/${carpeta}/facil.php`;
                document.getElementById("linkMedio").href = `../quizes/${carpeta}/medio.php`;
                document.getElementById("linkDificil").href = `../quizes/${carpeta}/dificil.php`;
                dialog.showModal();
            });
        });
        document.getElementById("btnCerrarDialog").addEventListener("click", () => dialog.close());
        dialog.addEventListener("click", (e) => { if (e.target === dialog) dialog.close(); });
    </script>

    <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
