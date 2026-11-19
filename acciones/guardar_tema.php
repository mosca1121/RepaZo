<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/conexiones.php";
requerirAdmin("../paginas/login.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin-repazo/admin.php");
    exit;
}

$slugMateria = trim((string)($_POST["materia"] ?? ""));
$grado = filter_var($_POST["grado"] ?? null, FILTER_VALIDATE_INT);
$titulo = trim((string)($_POST["titulo"] ?? ""));
$explicacion = trim((string)($_POST["explicacion"] ?? ""));
$ejemplo = trim((string)($_POST["ejemplo"] ?? ""));
$linkVideo = trim((string)($_POST["link_video"] ?? ""));

if ($titulo === "" || $explicacion === "" || $ejemplo === "" || !in_array($grado, [10, 11], true)) {
    header("Location: ../admin-repazo/admin.php");
    exit;
}

if ($linkVideo !== "" && preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{11})~', $linkVideo, $m)) {
    $linkVideo = "https://www.youtube.com/embed/" . $m[1];
}

$stmtMat = $conexion->prepare("SELECT id_materia FROM materias WHERE slug = ? LIMIT 1");
$stmtMat->bind_param("s", $slugMateria);
$stmtMat->execute();
$stmtMat->bind_result($idMateria);
if (!$stmtMat->fetch()) {
    $stmtMat->close();
    header("Location: ../admin-repazo/admin.php");
    exit;
}
$stmtMat->close();

$rutaAudio = null;
if (!empty($_FILES["audio"]["name"]) && $_FILES["audio"]["error"] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES["audio"]["name"], PATHINFO_EXTENSION));
    if (in_array($ext, ["mp3", "wav", "ogg", "m4a"], true)) {
        $dirAudio = __DIR__ . "/../AUDIOS/subidos/";
        if (!is_dir($dirAudio)) mkdir($dirAudio, 0755, true);
        $nombreAudio = $slugMateria . $grado . "_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES["audio"]["tmp_name"], $dirAudio . $nombreAudio)) {
            $rutaAudio = "AUDIOS/subidos/" . $nombreAudio;
        }
    }
}

$rutaVideo = null;
if (!empty($_FILES["video"]["name"]) && $_FILES["video"]["error"] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION));
    if (in_array($ext, ["mp4", "webm", "ogg"], true)) {
        $dirVideo = __DIR__ . "/../VIDEOS/subidos/";
        if (!is_dir($dirVideo)) mkdir($dirVideo, 0755, true);
        $nombreVideo = $slugMateria . $grado . "_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $dirVideo . $nombreVideo)) {
            $rutaVideo = "VIDEOS/subidos/" . $nombreVideo;
        }
    }
}

$idAdmin = (int)$_SESSION["id_usuario"];
$stmt = $conexion->prepare("
    INSERT INTO temas_modulos (id_materia, grado, titulo, explicacion, ejemplo, ruta_audio, ruta_video, link_video, creado_por)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "iissssssi",
    $idMateria,
    $grado,
    $titulo,
    $explicacion,
    $ejemplo,
    $rutaAudio,
    $rutaVideo,
    $linkVideo,
    $idAdmin
);
$stmt->execute();
$stmt->close();

header("Location: ../admin-repazo/admin.php?ok=tema");
exit;
