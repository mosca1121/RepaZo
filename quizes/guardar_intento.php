<?php
declare(strict_types=1);

function guardarIntentoQuiz(
    mysqli $conexion,
    int $idUsuario,
    string $rutaArchivoQuiz,
    int $correctas,
    int $totalPreguntas = 15
): array {
    $incorrectas = max(0, $totalPreguntas - $correctas);
    $porcentaje = $totalPreguntas > 0 ? round(($correctas / $totalPreguntas) * 100, 2) : 0.00;

    $archivo = mb_strtolower(basename($rutaArchivoQuiz, ".php"), "UTF-8");
    $idDificultad = match (true) {
        str_contains($archivo, "facil") => 1,
        str_contains($archivo, "medio") || str_contains($archivo, "intermedio") => 2,
        str_contains($archivo, "dificil") => 3,
        default => 1,
    };

    $carpeta = mb_strtolower(basename(dirname($rutaArchivoQuiz)), "UTF-8");
    $grado = str_contains($carpeta, "11") ? 11 : 10;

    $nombreMateria = match (true) {
        str_contains($carpeta, "ingles") || str_contains($carpeta, "inglés") => "Inglés",
        str_contains($carpeta, "mate") => "Matemáticas",
        str_contains($carpeta, "fisica") || str_contains($carpeta, "física") => "Física",
        str_contains($carpeta, "quimica") || str_contains($carpeta, "química") => "Química",
        str_contains($carpeta, "espanol") || str_contains($carpeta, "español") => "Español",
        default => "Matemáticas",
    };

    $stmtMat = $conexion->prepare("SELECT id_materia FROM materias WHERE LOWER(nombre) = LOWER(?) LIMIT 1");
    $stmtMat->bind_param("s", $nombreMateria);
    $stmtMat->execute();
    $rowMat = $stmtMat->get_result()->fetch_assoc();
    $stmtMat->close();

    if ($rowMat) {
        $idMateria = (int)$rowMat["id_materia"];
    } else {
        $desc = "Materiales para estudiar " . mb_strtolower($nombreMateria, "UTF-8") . ".";
        $stmtInsMat = $conexion->prepare("INSERT INTO materias (nombre, descripcion) VALUES (?, ?)");
        $stmtInsMat->bind_param("ss", $nombreMateria, $desc);
        $stmtInsMat->execute();
        $idMateria = (int)$conexion->insert_id;
        $stmtInsMat->close();
    }

    $stmtQuiz = $conexion->prepare(
        "SELECT id_quiz FROM quizzes WHERE id_materia = ? AND id_dificultad = ? AND grado = ? ORDER BY id_quiz ASC LIMIT 1"
    );
    $stmtQuiz->bind_param("iii", $idMateria, $idDificultad, $grado);
    $stmtQuiz->execute();
    $rowQuiz = $stmtQuiz->get_result()->fetch_assoc();
    $stmtQuiz->close();

    if ($rowQuiz) {
        $idQuiz = (int)$rowQuiz["id_quiz"];
    } else {
        $nombresDif = [1 => "Fácil", 2 => "Intermedio", 3 => "Difícil"];
        $titulo = "{$nombreMateria} - {$nombresDif[$idDificultad]} - Grado {$grado}";
        $desc = "Quiz {$nombresDif[$idDificultad]} de {$nombreMateria} para grado {$grado}°";
        $stmtIns = $conexion->prepare(
            "INSERT INTO quizzes (id_materia, id_dificultad, titulo, descripcion, grado) VALUES (?, ?, ?, ?, ?)"
        );
        $stmtIns->bind_param("iissi", $idMateria, $idDificultad, $titulo, $desc, $grado);
        $stmtIns->execute();
        $idQuiz = (int)$conexion->insert_id;
        $stmtIns->close();
    }

    $stmtInt = $conexion->prepare(
        "INSERT INTO intentos_quizzes (id_usuario, id_quiz, fecha_fin, porcentaje, respuestas_correctas, respuestas_incorrectas)
         VALUES (?, ?, NOW(), ?, ?, ?)"
    );
    $stmtInt->bind_param("iidii", $idUsuario, $idQuiz, $porcentaje, $correctas, $incorrectas);
    $stmtInt->execute();
    $stmtInt->close();

    return [
        "porcentaje" => $porcentaje,
        "correctas" => $correctas,
        "incorrectas" => $incorrectas,
    ];
}