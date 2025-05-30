-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 13:04:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parroquia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acto_sacramental`
--

CREATE TABLE `acto_sacramental` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `sacramento_id` int(11) NOT NULL,
  `parroquia_id` int(11) DEFAULT NULL,
  `parroco_id` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `libro` varchar(50) DEFAULT NULL,
  `folio` varchar(50) DEFAULT NULL,
  `partida` varchar(50) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `certificado_emitido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acto_sacramental`
--

INSERT INTO `acto_sacramental` (`id`, `persona_id`, `sacramento_id`, `parroquia_id`, `parroco_id`, `fecha`, `libro`, `folio`, `partida`, `observaciones`, `certificado_emitido`) VALUES
(1, 3, 2, NULL, NULL, '2025-05-06', 'log', '12', 'fd', 'dfd', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catequesis`
--

CREATE TABLE `catequesis` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sacramento_id` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `catequista_id` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catequesis`
--

INSERT INTO `catequesis` (`id`, `nombre`, `sacramento_id`, `fecha_inicio`, `fecha_fin`, `catequista_id`, `observaciones`) VALUES
(3, 'Primer año', 1, '2025-04-28', '2025-06-08', 6, 'un buen');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catequistas`
--

CREATE TABLE `catequistas` (
  `persona_id` int(11) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catequistas`
--

INSERT INTO `catequistas` (`persona_id`, `especialidad`) VALUES
(4, 'recursos humanos'),
(5, 'musico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquia`
--

CREATE TABLE `parroquia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante_catequesis`
--

CREATE TABLE `participante_catequesis` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `catequesis_id` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participante_catequesis`
--

INSERT INTO `participante_catequesis` (`id`, `persona_id`, `catequesis_id`, `fecha_inscripcion`) VALUES
(2, 3, 3, '2025-05-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `genero` enum('masculino','femenino','otro') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id`, `nombres`, `apellidos`, `fecha_nacimiento`, `direccion`, `telefono`, `correo`, `genero`) VALUES
(2, 'mercedes', 'malango', '2017-02-27', 'los angeles', '55521475', 'mercedes@gmail.com', 'femenino'),
(3, 'Carlos', 'malale', '2017-02-27', 'los angeles', '55521475', 'mj20@gmail.com', 'masculino'),
(4, 'lucas', 'Mba', '2008-06-20', 'barrio chino', '55521450', 'lucas@gmail.com', 'masculino'),
(5, 'salvador', 'batapa', '1998-06-10', 'calle bata', '222503559', 'batapa@gmail.com', 'masculino'),
(6, 'Amador', 'Batapa', '1996-10-16', 'malabo 2 de semu', '55521470', 'amador@gmail.com', 'masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relaciones_persona`
--

CREATE TABLE `relaciones_persona` (
  `id` int(11) NOT NULL,
  `acto_sacramental_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `rol` enum('padre','madre','padrino','madrina') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sacramento`
--

CREATE TABLE `sacramento` (
  `id` int(11) NOT NULL,
  `nombre` enum('bautismo','comunion','confirmacion','matrimonio') NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sacramento`
--

INSERT INTO `sacramento` (`id`, `nombre`, `descripcion`) VALUES
(1, 'bautismo', 'Sacramento de iniciación cristiana'),
(2, 'comunion', 'Primera Comunión del cuerpo de Cristo'),
(3, 'confirmacion', 'Confirmación de la fe católica'),
(4, 'matrimonio', 'Unión sacramental entre dos personas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','secretario','archivista','parroco') NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `persona_id`, `usuario`, `contrasena`, `rol`, `estado`) VALUES
(1, 4, 'secretario', '$2y$10$ILwC3Tgm3Hm75MAavjIe1uRPyUpieRU8iBfZTpZ1l5AOs9PPN1aCi', 'secretario', 1),
(2, 6, 'admin', '$2y$10$brNS7JWDMkXv9CzY6/QhQO6IfZ/qqkBfWHvE30nIvdjqQQAWj4fXy', 'admin', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acto_sacramental`
--
ALTER TABLE `acto_sacramental`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `sacramento_id` (`sacramento_id`),
  ADD KEY `parroquia_id` (`parroquia_id`),
  ADD KEY `parroco_id` (`parroco_id`);

--
-- Indices de la tabla `catequesis`
--
ALTER TABLE `catequesis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sacramento_id` (`sacramento_id`),
  ADD KEY `catequista_id` (`catequista_id`);

--
-- Indices de la tabla `catequistas`
--
ALTER TABLE `catequistas`
  ADD PRIMARY KEY (`persona_id`);

--
-- Indices de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participante_catequesis`
--
ALTER TABLE `participante_catequesis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `catequesis_id` (`catequesis_id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `relaciones_persona`
--
ALTER TABLE `relaciones_persona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acto_sacramental_id` (`acto_sacramental_id`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `sacramento`
--
ALTER TABLE `sacramento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `persona_id` (`persona_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acto_sacramental`
--
ALTER TABLE `acto_sacramental`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `catequesis`
--
ALTER TABLE `catequesis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participante_catequesis`
--
ALTER TABLE `participante_catequesis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `relaciones_persona`
--
ALTER TABLE `relaciones_persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sacramento`
--
ALTER TABLE `sacramento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acto_sacramental`
--
ALTER TABLE `acto_sacramental`
  ADD CONSTRAINT `acto_sacramental_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `acto_sacramental_ibfk_2` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `acto_sacramental_ibfk_4` FOREIGN KEY (`parroco_id`) REFERENCES `persona` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `catequesis`
--
ALTER TABLE `catequesis`
  ADD CONSTRAINT `catequesis_ibfk_1` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catequesis_ibfk_2` FOREIGN KEY (`catequista_id`) REFERENCES `persona` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `catequistas`
--
ALTER TABLE `catequistas`
  ADD CONSTRAINT `catequistas_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `participante_catequesis`
--
ALTER TABLE `participante_catequesis`
  ADD CONSTRAINT `participante_catequesis_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `participante_catequesis_ibfk_2` FOREIGN KEY (`catequesis_id`) REFERENCES `catequesis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `relaciones_persona`
--
ALTER TABLE `relaciones_persona`
  ADD CONSTRAINT `relaciones_persona_ibfk_1` FOREIGN KEY (`acto_sacramental_id`) REFERENCES `acto_sacramental` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relaciones_persona_ibfk_2` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
