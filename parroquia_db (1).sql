-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 09:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parroquia_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `catequesis`
--

CREATE TABLE `catequesis` (
  `id_catequesis` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catequistas`
--

CREATE TABLE `catequistas` (
  `id_catequista` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `id_catequesis` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curso_catequistas`
--

CREATE TABLE `curso_catequistas` (
  `id_curso` int(11) NOT NULL,
  `id_catequista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feligreses`
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
-- Dumping data for table `feligreses`
--

INSERT INTO `feligreses` (`id_feligres`, `id_parroquia`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `direccion`, `telefono`, `estado_civil`, `matrimonio`) VALUES
(1, 1, 'justa', 'carioca', '2018-07-06', 'F', 'begoña 2', '55120456', 'soltero', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feligres_catequesis`
--

CREATE TABLE `feligres_catequesis` (
  `id_feligres` int(11) NOT NULL,
  `id_catequesis` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feligres_parientes`
--

CREATE TABLE `feligres_parientes` (
  `id_feligres` int(11) NOT NULL,
  `id_pariente` int(11) NOT NULL,
  `tipo_relacion` enum('padre','madre','padrino_bautismo','padrino_confirmacion','otro') NOT NULL,
  `id_sacramento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feligres_sacramento`
--

CREATE TABLE `feligres_sacramento` (
  `id_feligres` int(11) NOT NULL,
  `id_sacramento` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parientes`
--

CREATE TABLE `parientes` (
  `id_pariente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo_pariente` enum('padre','madre','padrino','madrina','otro') DEFAULT NULL,
  `datos_adicionales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_adicionales`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parientes`
--

INSERT INTO `parientes` (`id_pariente`, `nombre`, `apellido`, `telefono`, `tipo_pariente`, `datos_adicionales`) VALUES
(1, 'Paulino', 'Alicante', '551454578', 'padre', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pariente_catequesis`
--

CREATE TABLE `pariente_catequesis` (
  `id_pariente` int(11) NOT NULL,
  `id_catequesis` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parroquias`
--

CREATE TABLE `parroquias` (
  `id_parroquia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parroquias`
--

INSERT INTO `parroquias` (`id_parroquia`, `nombre`, `direccion`, `telefono`) VALUES
(1, 'Dominguito', 'Banapa', '222474851');

-- --------------------------------------------------------

--
-- Table structure for table `sacramentos`
--

CREATE TABLE `sacramentos` (
  `id_sacramento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sacramentos`
--

INSERT INTO `sacramentos` (`id_sacramento`, `nombre`) VALUES
(1, 'bautizmo');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Indexes for dumped tables
--

--
-- Indexes for table `catequesis`
--
ALTER TABLE `catequesis`
  ADD PRIMARY KEY (`id_catequesis`);

--
-- Indexes for table `catequistas`
--
ALTER TABLE `catequistas`
  ADD PRIMARY KEY (`id_catequista`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_catequesis` (`id_catequesis`);

--
-- Indexes for table `curso_catequistas`
--
ALTER TABLE `curso_catequistas`
  ADD PRIMARY KEY (`id_curso`,`id_catequista`),
  ADD KEY `id_catequista` (`id_catequista`);

--
-- Indexes for table `feligreses`
--
ALTER TABLE `feligreses`
  ADD PRIMARY KEY (`id_feligres`),
  ADD KEY `id_parroquia` (`id_parroquia`);

--
-- Indexes for table `feligres_catequesis`
--
ALTER TABLE `feligres_catequesis`
  ADD PRIMARY KEY (`id_feligres`,`id_catequesis`),
  ADD KEY `id_catequesis` (`id_catequesis`);

--
-- Indexes for table `feligres_parientes`
--
ALTER TABLE `feligres_parientes`
  ADD PRIMARY KEY (`id_feligres`,`id_pariente`,`tipo_relacion`),
  ADD KEY `id_pariente` (`id_pariente`),
  ADD KEY `id_sacramento` (`id_sacramento`);

--
-- Indexes for table `feligres_sacramento`
--
ALTER TABLE `feligres_sacramento`
  ADD PRIMARY KEY (`id_feligres`,`id_sacramento`),
  ADD KEY `id_sacramento` (`id_sacramento`);

--
-- Indexes for table `parientes`
--
ALTER TABLE `parientes`
  ADD PRIMARY KEY (`id_pariente`);

--
-- Indexes for table `pariente_catequesis`
--
ALTER TABLE `pariente_catequesis`
  ADD PRIMARY KEY (`id_pariente`,`id_catequesis`),
  ADD KEY `id_catequesis` (`id_catequesis`);

--
-- Indexes for table `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`);

--
-- Indexes for table `sacramentos`
--
ALTER TABLE `sacramentos`
  ADD PRIMARY KEY (`id_sacramento`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catequesis`
--
ALTER TABLE `catequesis`
  MODIFY `id_catequesis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catequistas`
--
ALTER TABLE `catequistas`
  MODIFY `id_catequista` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feligreses`
--
ALTER TABLE `feligreses`
  MODIFY `id_feligres` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parientes`
--
ALTER TABLE `parientes`
  MODIFY `id_pariente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sacramentos`
--
ALTER TABLE `sacramentos`
  MODIFY `id_sacramento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`);

--
-- Constraints for table `curso_catequistas`
--
ALTER TABLE `curso_catequistas`
  ADD CONSTRAINT `curso_catequistas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `curso_catequistas_ibfk_2` FOREIGN KEY (`id_catequista`) REFERENCES `catequistas` (`id_catequista`);

--
-- Constraints for table `feligreses`
--
ALTER TABLE `feligreses`
  ADD CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquias` (`id_parroquia`);

--
-- Constraints for table `feligres_catequesis`
--
ALTER TABLE `feligres_catequesis`
  ADD CONSTRAINT `feligres_catequesis_ibfk_1` FOREIGN KEY (`id_feligres`) REFERENCES `feligreses` (`id_feligres`),
  ADD CONSTRAINT `feligres_catequesis_ibfk_2` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`);

--
-- Constraints for table `feligres_parientes`
--
ALTER TABLE `feligres_parientes`
  ADD CONSTRAINT `feligres_parientes_ibfk_1` FOREIGN KEY (`id_feligres`) REFERENCES `feligreses` (`id_feligres`),
  ADD CONSTRAINT `feligres_parientes_ibfk_2` FOREIGN KEY (`id_pariente`) REFERENCES `parientes` (`id_pariente`),
  ADD CONSTRAINT `feligres_parientes_ibfk_3` FOREIGN KEY (`id_sacramento`) REFERENCES `sacramentos` (`id_sacramento`);

--
-- Constraints for table `feligres_sacramento`
--
ALTER TABLE `feligres_sacramento`
  ADD CONSTRAINT `feligres_sacramento_ibfk_1` FOREIGN KEY (`id_feligres`) REFERENCES `feligreses` (`id_feligres`),
  ADD CONSTRAINT `feligres_sacramento_ibfk_2` FOREIGN KEY (`id_sacramento`) REFERENCES `sacramentos` (`id_sacramento`);

--
-- Constraints for table `pariente_catequesis`
--
ALTER TABLE `pariente_catequesis`
  ADD CONSTRAINT `pariente_catequesis_ibfk_1` FOREIGN KEY (`id_pariente`) REFERENCES `parientes` (`id_pariente`),
  ADD CONSTRAINT `pariente_catequesis_ibfk_2` FOREIGN KEY (`id_catequesis`) REFERENCES `catequesis` (`id_catequesis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
