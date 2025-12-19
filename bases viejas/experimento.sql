-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-12-2025 a las 06:24:16
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
-- Base de datos: `experimento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `emisor_id`, `receptor_id`, `asunto`, `contenido`, `fecha`, `leido`) VALUES
(1, 1, 2, 'Aviso escolar', 'Mañana no habrá clases por consejo técnico.', '2025-12-17 03:04:38', 0),
(4, 1, 3, 'Fwd: Aviso escolar', 'Mañana no habrá clases por consejo técnico.', '2025-12-17 03:15:12', 0),
(5, 1, 3, 'Fwd: Aviso escolar', 'Mañana no habrá clases por consejo técnico.', '2025-12-17 03:15:15', 0),
(6, 2, 1, 'Re: Aviso escolar', 'Gracias por la información.', '2025-12-17 03:15:18', 0),
(7, 2, 1, 'Re: Aviso escolar', 'Gracias por la información.', '2025-12-17 03:15:20', 0),
(8, 2, 1, 'Re: Aviso escolar', 'Gracias por la información.', '2025-12-17 03:15:22', 0),
(9, 2, 1, 'Re: Aviso escolar', 'Gracias por la información.', '2025-12-17 03:18:13', 0),
(10, 2, 1, 'Re: Aviso escolar', 'okey muchas gracias, enterado, abajo mosso.', '2025-12-17 03:18:35', 0),
(11, 2, 1, 'Re: Aviso escolar', 'puta administración\r\n', '2025-12-17 03:19:16', 0),
(12, 1, 2, 'Suspensión de clases', 'Se informa que el día viernes no habrá clases debido a la realización del Consejo Técnico Escolar.', '2025-12-17 03:22:17', 0),
(13, 1, 2, 'Reunión de padres', 'Se convoca a los padres de familia a la reunión general el próximo lunes a las 8:00 a.m. en el auditorio.', '2025-12-17 03:22:17', 0),
(14, 1, 2, 'Entrega de boletas', 'La entrega de boletas se realizará el día miércoles en un horario de 9:00 a.m. a 12:00 p.m.', '2025-12-17 03:22:17', 0),
(15, 1, 2, 'Pago de colegiatura', 'Se recuerda que la fecha límite para el pago de la colegiatura correspondiente al mes en curso es el día 15.', '2025-12-17 03:22:17', 0),
(16, 1, 2, 'Evento cultural', 'El próximo jueves se llevará a cabo el evento cultural anual. Se invita a los padres a asistir.', '2025-12-17 03:22:17', 0),
(17, 1, 3, 'Cambio de horario', 'A partir de la próxima semana el horario de entrada será a las 7:30 a.m.', '2025-12-17 03:22:17', 0),
(18, 1, 3, 'Vacunación escolar', 'El día martes se realizará una jornada de vacunación. Favor de enviar la cartilla de vacunación.', '2025-12-17 03:22:17', 0),
(19, 1, 3, 'Salida anticipada', 'El día de mañana la salida será a las 11:00 a.m. por actividades internas del personal docente.', '2025-12-17 03:22:17', 0),
(20, 2, 1, 'Re: Evento cultural', 'puta admistracion me tienen hasta la pinche verga', '2025-12-17 03:22:56', 0),
(21, 2, 1, 'Re: Suspensión de clases', 'puto mosso', '2025-12-18 19:07:53', 0),
(22, 2, 1, 'Re: Suspensión de clases', 'hHAHA', '2025-12-18 21:30:28', 0),
(23, 2, 1, 'Re: Suspensión de clases', 'lalalalal', '2025-12-18 22:41:17', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`) VALUES
(1, 'Escuela', 'escuela@correo.com'),
(2, 'Padre Juan', 'juan@correo.com'),
(3, 'Madre Ana', 'ana@correo.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emisor` (`emisor_id`),
  ADD KEY `fk_receptor` (`receptor_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_emisor` FOREIGN KEY (`emisor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_receptor` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
