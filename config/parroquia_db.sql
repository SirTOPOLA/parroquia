-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 16:27:34
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
-- Base de datos: `parroquia_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catequesis`
--

CREATE TABLE `catequesis` (
  `id_catequesis` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catequesis`
--

INSERT INTO `catequesis` (`id_catequesis`, `nombre`, `descripcion`) VALUES
(1, 'preparatoria', 'preparcion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catequesis_catequistas`
--

CREATE TABLE `catequesis_catequistas` (
  `id` int(11) NOT NULL,
  `id_catequesis` int(11) NOT NULL,
  `id_catequista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catequistas`
--

CREATE TABLE `catequistas` (
  `id_catequista` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catequistas`
--

INSERT INTO `catequistas` (`id_catequista`, `nombre`, `apellido`, `telefono`, `correo`) VALUES
(1, 'marta', 'beña', '551718822', 'mar@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `id_catequesis` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `id_catequesis`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 1, 'Pastoral', 'un buen viaje', '2025-06-13', '2025-07-02'),
(2, 1, 'Comunion A', 'un curso de preparacion de la comunion', '2025-06-13', '2025-08-08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_catequistas`
--

CREATE TABLE `curso_catequistas` (
  `id_curso` int(11) NOT NULL,
  `id_catequista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curso_catequistas`
--

INSERT INTO `curso_catequistas` (`id_curso`, `id_catequista`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_feligres`
--

CREATE TABLE `curso_feligres` (
  `id_feligres` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `estado` enum('pendiente','en_proceso','completado') DEFAULT 'pendiente',
  `fecha_inscripcion` date DEFAULT NULL,
  `fecha_finalizacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curso_feligres`
--

INSERT INTO `curso_feligres` (`id_feligres`, `id_curso`, `estado`, `fecha_inscripcion`, `fecha_finalizacion`) VALUES
(9, 2, 'pendiente', '2025-06-12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feligreses`
--

CREATE TABLE `feligreses` (
  `id_feligres` int(11) NOT NULL,
  `id_parroquia` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','Otro') DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado_civil` enum('soltero','casado','viudo','separado') DEFAULT NULL,
  `matrimonio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`matrimonio`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `feligreses`
--

INSERT INTO `feligreses` (`id_feligres`, `id_parroquia`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `direccion`, `telefono`, `estado_civil`, `matrimonio`) VALUES
(1, 1, 'justa', 'carioca', '2018-07-06', 'F', 'begoña 2', '55120456', 'soltero', NULL),
(2, 1, 'salvador', 'mete ', '2023-06-13', '', 'Sumko', NULL, 'soltero', NULL),
(4, 1, 'marta', 'mete ', '2023-06-13', '', 'Sumko', NULL, 'soltero', NULL),
(8, 1, 'sinforosa', 'beña', '2022-02-02', 'F', 'Sumko', NULL, 'soltero', NULL),
(9, 1, 'Melchor', 'BTobileri', '2018-06-12', 'M', 'Los Angeles', NULL, 'soltero', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feligres_catequesis`
--

CREATE TABLE `feligres_catequesis` (
  `id_feligres` int(11) NOT NULL,
  `id_catequesis` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT NULL,
  `estado` enum('pendiente','en_proceso','completado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `feligres_catequesis`
--

INSERT INTO `feligres_catequesis` (`id_feligres`, `id_catequesis`, `fecha_inscripcion`, `estado`) VALUES
(1, 1, '2025-06-12', 'pendiente'),
(4, 1, '2025-06-12', 'pendiente'),
(8, 1, '2025-06-12', 'pendiente'),
(9, 1, '2025-06-12', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feligres_parientes`
--

CREATE TABLE `feligres_parientes` (
  `id_feligres` int(11) NOT NULL,
  `id_pariente` int(11) NOT NULL,
  `tipo_relacion` enum('padre','madre','padrino','padrino','testigo') NOT NULL,
  `id_sacramento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `feligres_parientes`
--

INSERT INTO `feligres_parientes` (`id_feligres`, `id_pariente`, `tipo_relacion`, `id_sacramento`) VALUES
(2, 2, 'padre', NULL),
(2, 3, 'padrino', NULL),
(2, 4, '', NULL),
(8, 5, 'padre', NULL),
(8, 6, 'padrino', NULL),
(8, 7, '', NULL),
(9, 8, 'padre', 3),
(9, 9, 'padrino', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feligres_sacramento`
--

CREATE TABLE `feligres_sacramento` (
  `id_feligres` int(11) NOT NULL,
  `id_sacramento` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('pendiente','en_proceso','completado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `feligres_sacramento`
--

INSERT INTO `feligres_sacramento` (`id_feligres`, `id_sacramento`, `fecha`, `lugar`, `observaciones`, `estado`) VALUES
(9, 3, NULL, NULL, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parientes`
--

CREATE TABLE `parientes` (
  `id_pariente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo_pariente` enum('padre','madre','padrino','padrino','testigo') DEFAULT NULL,
  `datos_adicionales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_adicionales`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parientes`
--

INSERT INTO `parientes` (`id_pariente`, `nombre`, `apellido`, `telefono`, `tipo_pariente`, `datos_adicionales`) VALUES
(1, 'Paulino', 'Alicante', '551454578', 'padre', NULL),
(2, 'luis', 'amado', '551718820', 'padre', NULL),
(3, 'marcos', 'matias', '55147850', 'padrino', NULL),
(4, 'marta', 'beña', '551715820', '', NULL),
(5, 'nestor', 'beña', '551718822', 'padre', '[]'),
(6, 'luis', 'amado', '551715820', 'padrino', '[]'),
(7, 'marta', 'beña', '551718822', '', '[]'),
(8, 'nestor', 'beña', '551718822', 'padre', '[]'),
(9, 'luis', 'amado', '551715820', 'padrino', '[]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pariente_catequesis`
--

CREATE TABLE `pariente_catequesis` (
  `id_pariente` int(11) NOT NULL,
  `id_catequesis` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquias`
--

CREATE TABLE `parroquias` (
  `id_parroquia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parroquias`
--

INSERT INTO `parroquias` (`id_parroquia`, `nombre`, `direccion`, `telefono`) VALUES
(1, 'Dominguito', 'Banapa', '222474851');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sacramentos`
--

CREATE TABLE `sacramentos` (
  `id_sacramento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sacramentos`
--

INSERT INTO `sacramentos` (`id_sacramento`, `nombre`) VALUES
(2, 'bautismo'),
(3, 'confirmacion'),
(4, 'comunion'),
(5, 'matrimonio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','secretario','archivista','parroco') NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `dni`, `usuario`, `contrasena`, `rol`, `estado`, `fecha_registro`) VALUES
(1, 'Amador Batapa', '0001457896', 'admin', '$2y$10$rOEIJPa2eBlWq.ztzpCzweP5KFoHD5V3wXqGtv1o0p8Jg6EEShzwu', 'admin', 1, '2025-06-07 08:37:10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `catequesis`
--
ALTER TABLE `catequesis`
  ADD PRIMARY KEY (`id_catequesis`);

--
-- Indices de la tabla `catequesis_catequistas`
--
ALTER TABLE `catequesis_catequistas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_catequesis` (`id_catequesis`),
  ADD KEY `id_catequista` (`id_catequista`);

--
-- Indices de la tabla `catequistas`
--
ALTER TABLE `catequistas`
  ADD PRIMARY KEY (`id_catequista`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_catequesis` (`id_catequesis`);

--
-- Indices de la tabla `curso_catequistas`
--
ALTER TABLE `curso_catequistas`
  ADD PRIMARY KEY (`id_curso`,`id_catequista`),
  ADD KEY `id_catequista` (`id_catequista`);

--
-- Indices de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  ADD PRIMARY KEY (`id_feligres`),
  ADD KEY `id_parroquia` (`id_parroquia`);

--
-- Indices de la tabla `feligres_catequesis`
--
ALTER TABLE `feligres_catequesis`
  ADD PRIMARY KEY (`id_feligres`,`id_catequesis`),
  ADD KEY `id_catequesis` (`id_catequesis`);

--
-- Indices de la tabla `feligres_parientes`
--
ALTER TABLE `feligres_parientes`
  ADD PRIMARY KEY (`id_feligres`,`id_pariente`,`tipo_relacion`),
  ADD KEY `id_pariente` (`id_pariente`),
  ADD KEY `id_sacramento` (`id_sacramento`);

--
-- Indices de la tabla `feligres_sacramento`
--
ALTER TABLE `feligres_sacramento`
  ADD PRIMARY KEY (`id_feligres`,`id_sacramento`),
  ADD KEY `id_sacramento` (`id_sacramento`);

--
-- Indices de la tabla `parientes`
--
ALTER TABLE `parientes`
  ADD PRIMARY KEY (`id_pariente`);

--
-- Indices de la tabla `pariente_catequesis`
--
ALTER TABLE `pariente_catequesis`
  ADD PRIMARY KEY (`id_pariente`,`id_catequesis`),
  ADD KEY `id_catequesis` (`id_catequesis`);

--
-- Indices de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`);

--
-- Indices de la tabla `sacramentos`
--
ALTER TABLE `sacramentos`
  ADD PRIMARY KEY (`id_sacramento`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `catequesis`
--
ALTER TABLE `catequesis`
  MODIFY `id_catequesis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `catequesis_catequistas`
--
ALTER TABLE `catequesis_catequistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catequistas`
--
ALTER TABLE `catequistas`
  MODIFY `id_catequista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  MODIFY `id_feligres` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `parientes`
--
ALTER TABLE `parientes`
  MODIFY `id_pariente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sacramentos`
--
ALTER TABLE `sacramentos`
  MODIFY `id_sacramento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catequesis_catequistas`
--
ALTER TABLE `catequesis_catequistas`
  ADD CONSTRAINT `catequesis_catequistas_ibfk_1` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`),
  ADD CONSTRAINT `catequesis_catequistas_ibfk_2` FOREIGN KEY (`id_catequista`) REFERENCES `catequistas` (`id_catequista`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`);

--
-- Filtros para la tabla `curso_catequistas`
--
ALTER TABLE `curso_catequistas`
  ADD CONSTRAINT `curso_catequistas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `curso_catequistas_ibfk_2` FOREIGN KEY (`id_catequista`) REFERENCES `catequistas` (`id_catequista`);

--
-- Filtros para la tabla `feligreses`
--
ALTER TABLE `feligreses`
  ADD CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquias` (`id_parroquia`);

--
-- Filtros para la tabla `feligres_catequesis`
--
ALTER TABLE `feligres_catequesis`
  ADD CONSTRAINT `feligres_catequesis_ibfk_1` FOREIGN KEY (`id_feligres`) REFERENCES `feligreses` (`id_feligres`),
  ADD CONSTRAINT `feligres_catequesis_ibfk_2` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`);

--
-- Filtros para la tabla `feligres_parientes`
--
ALTER TABLE `feligres_parientes`
  ADD CONSTRAINT `feligres_parientes_ibfk_1` FOREIGN KEY (`id_feligres`) REFERENCES `feligreses` (`id_feligres`),
  ADD CONSTRAINT `feligres_parientes_ibfk_2` FOREIGN KEY (`id_pariente`) REFERENCES `parientes` (`id_pariente`),
  ADD CONSTRAINT `feligres_parientes_ibfk_3` FOREIGN KEY (`id_sacramento`) REFERENCES `sacramentos` (`id_sacramento`);

--
-- Filtros para la tabla `feligres_sacramento`
--
ALTER TABLE `feligres_sacramento`
  ADD CONSTRAINT `feligres_sacramento_ibfk_1` FOREIGN KEY (`id_feligres`) REFERENCES `feligreses` (`id_feligres`),
  ADD CONSTRAINT `feligres_sacramento_ibfk_2` FOREIGN KEY (`id_sacramento`) REFERENCES `sacramentos` (`id_sacramento`);

--
-- Filtros para la tabla `pariente_catequesis`
--
ALTER TABLE `pariente_catequesis`
  ADD CONSTRAINT `pariente_catequesis_ibfk_1` FOREIGN KEY (`id_pariente`) REFERENCES `parientes` (`id_pariente`),
  ADD CONSTRAINT `pariente_catequesis_ibfk_2` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
