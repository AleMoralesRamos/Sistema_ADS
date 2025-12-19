-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-12-2025 a las 06:24:27
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
-- Base de datos: `ads_proyecto`
--

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
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `dia` varchar(20) NOT NULL,
  `ini` time NOT NULL,
  `fin` time NOT NULL,
  `materia` varchar(100) NOT NULL,
  `profesor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `dia`, `ini`, `fin`, `materia`, `profesor`) VALUES
(1, 'Lunes', '08:00:00', '09:00:00', 'Matemáticas', 'Prof. Jirafales'),
(2, 'Lunes', '09:00:00', '10:00:00', 'Historia', 'Prof. X'),
(3, 'Martes', '10:00:00', '11:00:00', 'Español', 'Prof. López'),
(4, 'Miércoles', '08:00:00', '09:30:00', 'Ciencias', 'Prof. Curie');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `nivel` enum('Kinder','Primaria','Secundaria') NOT NULL,
  `semestre` int(11) NOT NULL,
  `clave` varchar(10) NOT NULL,
  `materia` varchar(150) NOT NULL,
  `calificacion` int(11) DEFAULT NULL,
  `periodo` varchar(10) DEFAULT NULL,
  `forma_evaluacion` varchar(10) DEFAULT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nivel`, `semestre`, `clave`, `materia`, `calificacion`, `periodo`, `forma_evaluacion`, `estado`) VALUES
(1, 'Kinder', 1, 'K001', 'Desarrollo Motriz', 9, '23/1', 'ORD', 'Aprobada'),
(2, 'Kinder', 1, 'K002', 'Lenguaje Inicial', 10, '23/1', 'ORD', 'Aprobada'),
(3, 'Kinder', 1, 'K003', 'Expresión Artística', 8, '23/1', 'ORD', 'Aprobada'),
(4, 'Kinder', 2, 'K004', 'Pensamiento Matemático', 7, '23/2', 'ORD', 'Aprobada'),
(5, 'Kinder', 2, 'K005', 'Convivencia Social', 8, '26/1', 'ORD', 'Sin cursar'),
(6, 'Primaria', 1, 'P101', 'Español I', 8, '24/1', 'ORD', 'Aprobada'),
(7, 'Primaria', 1, 'P102', 'Matemáticas I', 7, '24/1', 'ORD', 'Aprobada'),
(8, 'Primaria', 2, 'P103', 'Ciencias Naturales', 9, '24/2', 'ORD', 'Aprobada'),
(9, 'Primaria', 2, 'P104', 'Historia', 6, '24/2', 'REC', 'Aprobada'),
(10, 'Primaria', 3, 'P105', 'Geografía', NULL, NULL, NULL, 'Sin cursar'),
(11, 'Secundaria', 1, 'S201', 'Matemáticas Avanzadas', 6, '25/1', 'ORD', 'Aprobada'),
(12, 'Secundaria', 1, 'S202', 'Física', 7, '25/1', 'ORD', 'Aprobada'),
(13, 'Secundaria', 2, 'S203', 'Química', 5, '25/2', 'ORD', 'Reprobada'),
(14, 'Secundaria', 2, 'S204', 'Tecnología', 8, '25/2', 'ORD', 'Aprobada'),
(15, 'Secundaria', 3, 'S205', 'Formación Cívica', 0, '26/1', 'ORD', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `remitente_nombre` varchar(100) DEFAULT NULL,
  `destinatario_tipo` varchar(50) DEFAULT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `remitente_nombre`, `destinatario_tipo`, `asunto`, `mensaje`, `fecha_envio`) VALUES
(1, 'Juan Pérez', 'Profesor', 'Progreso Académico', 'Buen día, quisiera información sobre el progreso de mi hijo.', '2025-12-16 15:40:56'),
(2, 'hola', 'Profesor', 'Justificante', 'saasassasasa', '2025-12-16 15:44:51');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calendario_eventos`
--
ALTER TABLE `calendario_eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calendario_eventos`
--
ALTER TABLE `calendario_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
