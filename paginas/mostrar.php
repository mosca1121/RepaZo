<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/../includes/conexiones.php";

if (!isset($_SESSION["id_usuario"], $_SESSION["grado"])) {
    header("Location: login.php?error=sesion");
    exit;
}

$grado = (int)$_SESSION["grado"];
if (!in_array($grado, [10, 11], true)) {
    http_response_code(403);
    exit("El grado de esta cuenta no es válido.");
}

function e(?string $valor): string {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

try {
    $stmt = $conexion->prepare(
        "SELECT r.id_recurso, r.titulo, r.grado, r.tipo_recurso, r.contenido_texto, r.url, m.nombre AS materia
         FROM recursos_materias r
         INNER JOIN materias m ON m.id_materia = r.id_materia
         WHERE r.grado = ?
         ORDER BY m.nombre, r.orden, r.titulo"
    );
    $stmt->bind_param("i", $grado);
    $stmt->execute();
    $recursos = $stmt->get_result();

    $stmtQuizzes = $conexion->prepare(
        "SELECT q.id_quiz, q.titulo, q.descripcion, q.grado, m.nombre AS materia, d.nombre AS dificultad
         FROM quizzes q
         INNER JOIN materias m ON m.id_materia = q.id_materia
         INNER JOIN dificultad_quizzes d ON d.id_dificultad = q.id_dificultad
         WHERE q.grado = ?
         ORDER BY m.nombre, d.id_dificultad, q.titulo"
    );
    $stmtQuizzes->bind_param("i", $grado);
    $stmtQuizzes->execute();
    $quizzes = $stmtQuizzes->get_result();
} catch (mysqli_sql_exception $e) {
    error_log("Error al mostrar contenido de RepaZo: " . $e->getMessage());
    http_response_code(500);
    exit("No se pudo consultar el contenido. Comprueba que las tablas y columnas estén creadas con los nombres esperados.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi contenido | RepaZo</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f2f6fc;margin:0;color:#263238}
        header{background:#1e88e5;color:#fff;padding:18px 6%;display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap}
        header a{color:white;margin-right:12px}
        main{max-width:1050px;margin:28px auto;padding:0 18px}
        .tarjeta{background:#fff;padding:18px;margin:14px 0;border-radius:12px;box-shadow:0 2px 8px #00000012}
        h1,h2{color:#1565c0}.etiqueta{font-size:.9rem;color:#546e7a}
        .boton{display:inline-block;background:#1e88e5;color:white;text-decoration:none;padding:9px 14px;border-radius:7px}
        .vacio{padding:15px;background:#fff8e1;border-radius:8px}
    </style>
</head>
<body>
<header>
    <div><strong>RepaZo</strong> · Contenido para grado <?= $grado ?></div>
    <nav>
        <a href="codigo.php">Inicio</a>
        <a href="mostrar.php">Mi contenido</a>
        <a href="../acciones/cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>
<main>
    <h1>¡Hola, <?= e((string)$_SESSION["nombre"]) ?>!</h1>
    <p>Estás viendo contenido disponible para grado <?= $grado ?>.</p>

    <h2>Recursos de estudio</h2>
    <?php if ($recursos->num_rows > 0): ?>
        <?php while ($r = $recursos->fetch_assoc()): ?>
            <article class="tarjeta">
                <p class="etiqueta"><?= e($r["materia"]) ?> · <?= e($r["tipo_recurso"]) ?></p>
                <h3><?= e($r["titulo"]) ?></h3>
                <?php if (!empty($r["contenido_texto"])): ?><p><?= nl2br(e($r["contenido_texto"])) ?></p><?php endif; ?>
                <?php if (!empty($r["url"])): ?><a class="boton" href="<?= e($r["url"]) ?>" target="_blank" rel="noopener noreferrer">Abrir recurso</a><?php endif; ?>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="vacio">Todavía no hay recursos registrados para tu grado.</p>
    <?php endif; ?>

    <h2>Quizzes disponibles</h2>
    <?php if ($quizzes->num_rows > 0): ?>
        <?php while ($q = $quizzes->fetch_assoc()): ?>
            <article class="tarjeta">
                <p class="etiqueta"><?= e($q["materia"]) ?> · Dificultad: <?= e($q["dificultad"]) ?></p>
                <h3><?= e($q["titulo"]) ?></h3>
                <p><?= e($q["descripcion"]) ?></p>
                <p class="etiqueta">ID del quiz en la base de datos: <?= (int)$q["id_quiz"] ?></p>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="vacio">Todavía no hay quizzes registrados para tu grado.</p>
    <?php endif; ?>
</main>
</body>
</html>
<?php
$stmt->close();
$stmtQuizzes->close();
$conexion->close();
?>
