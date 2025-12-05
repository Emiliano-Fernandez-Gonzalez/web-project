-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 05-12-2025 a las 07:26:30
-- Versión del servidor: 8.0.44-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `temario_json` json DEFAULT NULL,
  `progreso_json` json DEFAULT NULL,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password_hash`, `temario_json`, `progreso_json`, `creado`, `usuario`) VALUES
(1, 'emilianofernandez97334@hotmail.com', '$2y$10$BKvBxb/K6QjlX9/x1EAKcOnGYTGpLriiEpCBPh1YJYFixrOUoy/JC', '{\"plan\": [{\"tema\": \"Destinos Populares\", \"clave\": \"T1\"}, {\"tema\": \"Cultura y Tradiciones\", \"clave\": \"T2\"}, {\"tema\": \"Comida y Bebidas\", \"clave\": \"T3\"}, {\"tema\": \"Turismo en la Ciudad\", \"clave\": \"T4\"}, {\"tema\": \"Viajes a Nubes\", \"clave\": \"T5\"}, {\"tema\": \"Viaje de Noche\", \"clave\": \"T6\"}, {\"tema\": \"Viaje al Mar\", \"clave\": \"T7\"}], \"nivel\": \"Básico\", \"lenguaje\": \"Inglés\", \"objetivo\": \"Viajes\"}', '[false, false, false, false, false, false, false]', '2025-12-03 02:48:27', 'xd');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
