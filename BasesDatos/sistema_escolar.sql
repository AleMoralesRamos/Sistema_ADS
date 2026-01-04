-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-01-2026 a las 07:08:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_escolar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `boleta` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(120) NOT NULL,
  `nivel` enum('Kinder','Primaria','Secundaria') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`boleta`, `nombre`, `apellidos`, `nivel`) VALUES
(2023630289, 'Juan', 'Pérez', 'Kinder'),
(2023630290, 'Ana', 'López', 'Primaria'),
(2023630291, 'Pedro', 'García', 'Secundaria'),
(2023630292, 'María', 'Rodríguez', 'Kinder'),
(2023630293, 'Carlos', 'Martínez', 'Primaria'),
(2023630294, 'Laura', 'Hernández', 'Secundaria'),
(2023630295, 'Jorge', 'González', 'Kinder'),
(2023630296, 'Sofía', 'Sánchez', 'Primaria'),
(2023630297, 'Miguel', 'Ramírez', 'Secundaria'),
(2023630298, 'Isabel', 'Torres', 'Kinder');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_grupo`
--

CREATE TABLE `alumno_grupo` (
  `boleta` bigint(20) NOT NULL,
  `grupo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumno_grupo`
--

INSERT INTO `alumno_grupo` (`boleta`, `grupo_id`) VALUES
(2023630289, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario_eventos`
--

CREATE TABLE `calendario_eventos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `evento` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calendario_eventos`
--

INSERT INTO `calendario_eventos` (`id`, `fecha`, `evento`, `tipo`) VALUES
(1, '2025-12-16', 'Día del Trabajo', 'Festivo'),
(2, '2025-12-19', 'Reunión de Padres', 'Reunión'),
(3, '2025-12-23', 'Entrega de Calificaciones', 'Académico'),
(4, '2025-12-30', 'Feria de Ciencias', 'Evento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicacion`
--

CREATE TABLE `comunicacion` (
  `boleta` bigint(20) NOT NULL,
  `remitente_nombre` varchar(100) DEFAULT NULL,
  `destinatario_tipo` varchar(50) DEFAULT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunicacion`
--

INSERT INTO `comunicacion` (`boleta`, `remitente_nombre`, `destinatario_tipo`, `asunto`, `mensaje`, `fecha_envio`) VALUES
(2023630289, 'Juan Pérez', 'Profesor', 'Progreso académico', 'Buen día, quisiera saber cómo va el desempeño de mi hijo en Matemáticas.', '2026-01-04 08:30:00'),
(2023630289, 'Juan Pérez', 'Director', 'Cita', 'Solicito una cita para tratar temas académicos.', '2026-01-04 09:15:00'),
(2023630290, 'Ana López', 'Profesor', 'Justificante', 'Mi hija no asistió ayer por motivos de salud.', '2026-01-04 10:00:00'),
(2023630291, 'Pedro García', 'Director', 'Cambio de grupo', 'Quisiera información sobre el cambio de grupo.', '2026-01-04 11:20:00'),
(2023630289, 'Juan Pérez', 'Profesor', 'Tarea', 'Tengo dudas sobre la tarea de esta semana.', '2026-01-04 12:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos_emergencia`
--

CREATE TABLE `contactos_emergencia` (
  `id` int(11) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `parentesco` varchar(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos_emergencia`
--

INSERT INTO `contactos_emergencia` (`id`, `id_usuario`, `nombre_completo`, `telefono`, `parentesco`, `fecha_registro`) VALUES
(1, 2023630289, 'hola', '256314587256', 'Madre', '2026-01-03 18:33:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nivel` enum('Kinder','Primaria','Secundaria') NOT NULL,
  `grado` tinyint(4) NOT NULL,
  `grupo` varchar(5) NOT NULL,
  `nombre` varchar(30) GENERATED ALWAYS AS (concat(`nivel`,' ',`grado`,`grupo`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `nivel`, `grado`, `grupo`) VALUES
(1, 'Kinder', 2, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `dia` enum('Lunes','Martes','Miércoles','Jueves','Viernes') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `clave_materia` varchar(10) NOT NULL,
  `profesor` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `grupo_id`, `dia`, `hora_inicio`, `hora_fin`, `clave_materia`, `profesor`) VALUES
(1, 1, 'Lunes', '08:00:00', '09:00:00', 'K004', 'Prof. Jirafales'),
(2, 1, 'Lunes', '09:00:00', '10:00:00', 'K002', 'Prof. X'),
(3, 1, 'Martes', '10:00:00', '11:00:00', 'K003', 'Prof. López'),
(4, 1, 'Miércoles', '08:00:00', '09:30:00', 'K001', 'Prof. Curie');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE `kardex` (
  `boleta` bigint(20) NOT NULL,
  `clave` varchar(10) NOT NULL,
  `calificacion` tinyint(4) DEFAULT NULL,
  `periodo` varchar(10) DEFAULT NULL,
  `forma_evaluacion` enum('ORD','REC','EXT') DEFAULT NULL,
  `estado` enum('Aprobada','Reprobada','Sin cursar') DEFAULT 'Sin cursar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`boleta`, `clave`, `calificacion`, `periodo`, `forma_evaluacion`, `estado`) VALUES
(2023630289, 'K001', 9, '24/1', 'ORD', 'Aprobada'),
(2023630289, 'K002', 10, '24/1', 'ORD', 'Aprobada'),
(2023630289, 'K004', 8, '24/1', 'ORD', 'Aprobada'),
(2023630289, 'P101', 7, '24/1', 'ORD', 'Aprobada'),
(2023630289, 'P102', 6, '24/1', 'ORD', 'Aprobada'),
(2023630289, 'S202', 5, '24/1', 'ORD', 'Reprobada'),
(2023630290, 'K001', 10, '24/1', 'ORD', 'Aprobada'),
(2023630290, 'K003', 9, '24/1', 'ORD', 'Aprobada'),
(2023630290, 'P103', 8, '24/1', 'ORD', 'Aprobada'),
(2023630290, 'P104', 7, '24/1', 'ORD', 'Aprobada'),
(2023630290, 'S201', 6, '24/1', 'ORD', 'Aprobada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `clave` varchar(10) NOT NULL,
  `nivel` enum('Kinder','Primaria','Secundaria') NOT NULL,
  `semestre` tinyint(4) NOT NULL,
  `materia` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`clave`, `nivel`, `semestre`, `materia`) VALUES
('K001', 'Kinder', 1, 'Desarrollo Motriz'),
('K002', 'Kinder', 1, 'Lenguaje Inicial'),
('K003', 'Kinder', 1, 'Expresión Artística'),
('K004', 'Kinder', 2, 'Pensamiento Matemático'),
('K005', 'Kinder', 2, 'Convivencia Social'),
('P101', 'Primaria', 1, 'Español I'),
('P102', 'Primaria', 1, 'Matemáticas I'),
('P103', 'Primaria', 2, 'Ciencias Naturales'),
('P104', 'Primaria', 2, 'Historia'),
('P105', 'Primaria', 3, 'Geografía'),
('P106', 'Primaria', 3, 'Formación Cívica y Ética'),
('S201', 'Secundaria', 1, 'Matemáticas Avanzadas'),
('S202', 'Secundaria', 1, 'Física'),
('S203', 'Secundaria', 2, 'Química'),
('S204', 'Secundaria', 2, 'Tecnología'),
('S205', 'Secundaria', 3, 'Formación Cívica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `emisor_boleta` bigint(20) NOT NULL,
  `receptor_boleta` bigint(20) NOT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `emisor_boleta`, `receptor_boleta`, `asunto`, `contenido`, `fecha`) VALUES
(1, 2023630289, 2023630290, 'Aviso escolar', 'Mañana no habrá clases por consejo técnico.', '2026-01-04 03:18:10'),
(2, 2023630290, 2023630289, 'Re: Aviso escolar', 'Gracias por avisar.', '2026-01-04 03:18:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `boleta` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`boleta`, `password`) VALUES
(2023630289, 'pepito1'),
(2023630290, 'pepito2'),
(2023630291, 'pepito3'),
(2023630292, 'pepito4'),
(2023630293, 'pepito5'),
(2023630294, 'pepito6'),
(2023630295, 'pepito7'),
(2023630296, 'pepito8'),
(2023630297, 'pepito9'),
(2023630298, 'pepito10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`boleta`);

--
-- Indices de la tabla `alumno_grupo`
--
ALTER TABLE `alumno_grupo`
  ADD PRIMARY KEY (`boleta`),
  ADD KEY `idx_grupo` (`grupo_id`);

--
-- Indices de la tabla `calendario_eventos`
--
ALTER TABLE `calendario_eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comunicacion`
--
ALTER TABLE `comunicacion`
  ADD KEY `idx_comunicacion_boleta` (`boleta`);

--
-- Indices de la tabla `contactos_emergencia`
--
ALTER TABLE `contactos_emergencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_grupo` (`nivel`,`grado`,`grupo`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_grupo_dia_hora` (`grupo_id`,`dia`,`hora_inicio`),
  ADD KEY `idx_materia` (`clave_materia`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`boleta`,`clave`),
  ADD KEY `clave` (`clave`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_receptor_fecha` (`receptor_boleta`,`fecha`),
  ADD KEY `idx_emisor_fecha` (`emisor_boleta`,`fecha`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`boleta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calendario_eventos`
--
ALTER TABLE `calendario_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `contactos_emergencia`
--
ALTER TABLE `contactos_emergencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`boleta`) REFERENCES `usuarios` (`boleta`);

--
-- Filtros para la tabla `alumno_grupo`
--
ALTER TABLE `alumno_grupo`
  ADD CONSTRAINT `fk_ag_alumno` FOREIGN KEY (`boleta`) REFERENCES `alumnos` (`boleta`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ag_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comunicacion`
--
ALTER TABLE `comunicacion`
  ADD CONSTRAINT `fk_comunicacion_alumno` FOREIGN KEY (`boleta`) REFERENCES `alumnos` (`boleta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contactos_emergencia`
--
ALTER TABLE `contactos_emergencia`
  ADD CONSTRAINT `contactos_emergencia_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`boleta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `fk_hor_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_hor_materia` FOREIGN KEY (`clave_materia`) REFERENCES `materias` (`clave`) ON DELETE CASCADE;

--
-- Filtros para la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD CONSTRAINT `kardex_ibfk_1` FOREIGN KEY (`boleta`) REFERENCES `alumnos` (`boleta`),
  ADD CONSTRAINT `kardex_ibfk_2` FOREIGN KEY (`clave`) REFERENCES `materias` (`clave`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_mensajes_emisor` FOREIGN KEY (`emisor_boleta`) REFERENCES `usuarios` (`boleta`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mensajes_receptor` FOREIGN KEY (`receptor_boleta`) REFERENCES `usuarios` (`boleta`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
