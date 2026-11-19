-- Base de datos completa para RepaZo
-- Importar directamente en phpMyAdmin

CREATE DATABASE IF NOT EXISTS `repazo`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `repazo`;

-- 1. Tabla de usuarios (estudiantes y administradores)
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `apellido` VARCHAR(80) NOT NULL,
  `correo` VARCHAR(150) NOT NULL,
  `contrasena_hash` VARCHAR(255) NOT NULL,
  `grado` TINYINT UNSIGNED DEFAULT NULL COMMENT '10, 11 o NULL para admin',
  `rol` ENUM('estudiante', 'admin') NOT NULL DEFAULT 'estudiante',
  `foto_perfil` VARCHAR(255) DEFAULT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_usuarios_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Catálogo de materias (IDs compatibles con todos los quizzes existentes)
CREATE TABLE IF NOT EXISTS `materias` (
  `id_materia` INT UNSIGNED NOT NULL,
  `slug` VARCHAR(30) NOT NULL,
  `nombre` VARCHAR(60) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_materia`),
  UNIQUE KEY `uk_materias_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materias` (`id_materia`, `slug`, `nombre`, `descripcion`) VALUES
(101, 'ingles', 'Inglés', 'Gramática, vocabulario, lectura y comunicación.'),
(102, 'matematicas', 'Matemáticas', 'Funciones, trigonometría, cálculo y estadística.'),
(103, 'fisica', 'Física', 'Movimiento, fuerzas, energía, ondas y electromagnetismo.'),
(104, 'quimica', 'Química', 'Átomos, tabla periódica, enlaces, reacciones y química orgánica.'),
(105, 'espanol', 'Español', 'Literatura, comprensión lectora, gramática y argumentación.')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `slug` = VALUES(`slug`);

-- 3. Niveles de dificultad
CREATE TABLE IF NOT EXISTS `dificultad_quizzes` (
  `id_dificultad` TINYINT UNSIGNED NOT NULL,
  `nombre` VARCHAR(40) NOT NULL,
  `descripcion` VARCHAR(150) DEFAULT NULL,
  PRIMARY KEY (`id_dificultad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dificultad_quizzes` (`id_dificultad`, `nombre`, `descripcion`) VALUES
(1, 'Fácil', 'Preguntas de nivel básico.'),
(2, 'Medio', 'Preguntas de nivel intermedio.'),
(3, 'Difícil', 'Preguntas de nivel avanzado.')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- 4. Catálogo de los 30 quizzes
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id_quiz` INT UNSIGNED NOT NULL,
  `id_materia` INT UNSIGNED NOT NULL,
  `id_dificultad` TINYINT UNSIGNED NOT NULL,
  `titulo` VARCHAR(150) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `grado` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_quiz`),
  KEY `idx_quizzes_materia` (`id_materia`),
  KEY `idx_quizzes_dificultad` (`id_dificultad`),
  CONSTRAINT `fk_quizzes_materia` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE,
  CONSTRAINT `fk_quizzes_dificultad` FOREIGN KEY (`id_dificultad`) REFERENCES `dificultad_quizzes` (`id_dificultad`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quizzes` (`id_quiz`, `id_materia`, `id_dificultad`, `titulo`, `descripcion`, `grado`) VALUES
(101, 105, 1, 'Quiz de Español - Grado 10° Fácil', 'Español Básico', 10),
(102, 105, 2, 'Quiz de Español - Grado 10° Medio', 'Español Intermedio', 10),
(103, 105, 3, 'Quiz de Español - Grado 10° Difícil', 'Español Avanzado', 10),
(104, 102, 1, 'Quiz de Matemáticas - Grado 10° Fácil', 'Matemáticas Básico', 10),
(105, 102, 2, 'Quiz de Matemáticas - Grado 10° Medio', 'Matemáticas Intermedio', 10),
(106, 102, 3, 'Quiz de Matemáticas - Grado 10° Difícil', 'Matemáticas Avanzado', 10),
(107, 103, 1, 'Quiz de Física - Grado 10° Fácil', 'Física Básico', 10),
(108, 103, 2, 'Quiz de Física - Grado 10° Medio', 'Física Intermedio', 10),
(109, 103, 3, 'Quiz de Física - Grado 10° Difícil', 'Física Avanzado', 10),
(110, 104, 1, 'Quiz de Química - Grado 10° Fácil', 'Química Básico', 10),
(111, 104, 2, 'Quiz de Química - Grado 10° Medio', 'Química Intermedio', 10),
(112, 104, 3, 'Quiz de Química - Grado 10° Difícil', 'Química Avanzado', 10),
(113, 101, 1, 'Quiz de Inglés - Grado 10° Fácil', 'Inglés Básico', 10),
(114, 101, 2, 'Quiz de Inglés - Grado 10° Medio', 'Inglés Intermedio', 10),
(115, 101, 3, 'Quiz de Inglés - Grado 10° Difícil', 'Inglés Avanzado', 10),
(116, 105, 1, 'Quiz de Español - Grado 11° Fácil', 'Español Básico', 11),
(117, 105, 2, 'Quiz de Español - Grado 11° Medio', 'Español Intermedio', 11),
(118, 105, 3, 'Quiz de Español - Grado 11° Difícil', 'Español Avanzado', 11),
(119, 102, 1, 'Quiz de Matemáticas - Grado 11° Fácil', 'Matemáticas Básico', 11),
(120, 102, 2, 'Quiz de Matemáticas - Grado 11° Medio', 'Matemáticas Intermedio', 11),
(121, 102, 3, 'Quiz de Matemáticas - Grado 11° Difícil', 'Matemáticas Avanzado', 11),
(122, 103, 1, 'Quiz de Física - Grado 11° Fácil', 'Física Básico', 11),
(123, 103, 2, 'Quiz de Física - Grado 11° Medio', 'Física Intermedio', 11),
(124, 103, 3, 'Quiz de Física - Grado 11° Difícil', 'Física Avanzado', 11),
(125, 104, 1, 'Quiz de Química - Grado 11° Fácil', 'Química Básico', 11),
(126, 104, 2, 'Quiz de Química - Grado 11° Medio', 'Química Intermedio', 11),
(127, 104, 3, 'Quiz de Química - Grado 11° Difícil', 'Química Avanzado', 11),
(128, 101, 1, 'Quiz de Inglés - Grado 11° Fácil', 'Inglés Básico', 11),
(129, 101, 2, 'Quiz de Inglés - Grado 11° Medio', 'Inglés Intermedio', 11),
(130, 101, 3, 'Quiz de Inglés - Grado 11° Difícil', 'Inglés Avanzado', 11)
ON DUPLICATE KEY UPDATE `titulo` = VALUES(`titulo`);

-- 5. Intentos de quizzes realizados por estudiantes
CREATE TABLE IF NOT EXISTS `intentos_quizzes` (
  `id_intento` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT UNSIGNED NOT NULL,
  `id_quiz` INT UNSIGNED NOT NULL,
  `fecha_fin` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `porcentaje` DECIMAL(5,2) NOT NULL,
  `respuestas_correctas` TINYINT UNSIGNED NOT NULL,
  `respuestas_incorrectas` TINYINT UNSIGNED NOT NULL,
  `tiempo_segundos` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_intento`),
  KEY `idx_intentos_usuario` (`id_usuario`),
  KEY `idx_intentos_quiz` (`id_quiz`),
  CONSTRAINT `fk_intentos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_intentos_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quizzes` (`id_quiz`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Temas creados desde el Panel de Administrador
CREATE TABLE IF NOT EXISTS `temas_modulos` (
  `id_tema` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_materia` INT UNSIGNED NOT NULL,
  `grado` TINYINT UNSIGNED NOT NULL,
  `titulo` VARCHAR(150) NOT NULL,
  `explicacion` TEXT NOT NULL,
  `ejemplo` TEXT NOT NULL,
  `ruta_audio` VARCHAR(255) DEFAULT NULL,
  `ruta_video` VARCHAR(255) DEFAULT NULL,
  `link_video` VARCHAR(255) DEFAULT NULL,
  `creado_por` INT UNSIGNED DEFAULT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tema`),
  KEY `idx_temas_materia_grado` (`id_materia`, `grado`),
  CONSTRAINT `fk_temas_materia` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE,
  CONSTRAINT `fk_temas_admin` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuario administrador por defecto (Correo: admin@repazo.com | Contraseña: password)
INSERT INTO `usuarios` (`nombre`, `apellido`, `correo`, `contrasena_hash`, `grado`, `rol`)
VALUES (
  'Administrador',
  'RepaZo',
  'admin@repazo.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  11,
  'admin'
) ON DUPLICATE KEY UPDATE `rol` = 'admin';
