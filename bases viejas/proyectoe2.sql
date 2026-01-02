-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-01-2026 a las 05:06:41
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
-- Base de datos: `proyectoe2`
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
(2023630290, 'Ana', 'López', 'Primaria');

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
  `estado` enum('Aprobada','Reprobada','Sin cursar') NOT NULL DEFAULT 'Sin cursar'
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
(2023630289, '55da661f2d0f45a8ba8c8505b4e5bfe53b7d4870d73ab894901375ae125f84b7'),
(2023630290, '991ab88b01736e44794b1b8b446887303b2f9903673137d0ae69d3cde6a44e24'),
(2023630291, '46e6050070dd556fd5d33949767afdc60033a86105b6068118088bc8e94dda10'),
(2023630292, '292b513cb027c300d5ce7f087822d62e5a6ba47327528a6641fc13125503ea9d'),
(2023630293, 'd4733008a4ae4676c10a4a1f004a218ee026ea84aa5ccbcef02cc78772ac2274'),
(2023630294, 'd17cc018cb8d15cff6e52241d848771f3a6072a3bc6d7c3ab9e3d9f7c4c63726'),
(2023630295, '69f8e50949b72e9b8258032233e615991166c63be98ea42c8b91ece962ff8da4'),
(2023630296, 'eab0ee36a0e7f37de368889628f9c1425941815074965f7ff4571861d5f48622'),
(2023630297, '14121100fe06a87351992542fc32c6f8b82115a5cbe0ca4b03a93496fec244f6'),
(2023630298, 'bd826efbf7ebe65ccfc70e9797128e50bd9da8eee87e9efe19869aff9814446c');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`boleta`);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`boleta`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`boleta`) REFERENCES `usuarios` (`boleta`);

--
-- Filtros para la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD CONSTRAINT `kardex_ibfk_1` FOREIGN KEY (`boleta`) REFERENCES `alumnos` (`boleta`),
  ADD CONSTRAINT `kardex_ibfk_2` FOREIGN KEY (`clave`) REFERENCES `materias` (`clave`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
