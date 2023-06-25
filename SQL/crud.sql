-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-06-2023 a las 18:10:39
-- Versión del servidor: 8.0.33-0ubuntu0.22.04.2
-- Versión de PHP: 8.1.2-1ubuntu2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `100371029565636625`
--

CREATE TABLE `100371029565636625` (
  `id` int NOT NULL,
  `name` blob NOT NULL,
  `phone` blob NOT NULL,
  `uuid` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `100371029565636625`
--

INSERT INTO `100371029565636625` (`id`, `name`, `phone`, `uuid`) VALUES
(2, 0x2f4c653722768dddaa22c298b0261cd0, 0xcd87bcf51ba5714ac031980e62e6551a, '100371197757227009'),
(3, 0x9374f3a5c712368edfa994b75f7cddb4, 0x928790062e3f49d9e8942edd3f913146, '100371197757227012');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `100371197757227010`
--

CREATE TABLE `100371197757227010` (
  `id` int NOT NULL,
  `name` blob NOT NULL,
  `phone` blob NOT NULL,
  `uuid` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `100371197757227010`
--

INSERT INTO `100371197757227010` (`id`, `name`, `phone`, `uuid`) VALUES
(1, 0xf1bae56a8b602c168dda917d6bd37c8c, 0xa321c2422745148a72335dd59e0af563, '100371197757227011');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `email` blob NOT NULL,
  `password` text NOT NULL,
  `id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `key_pub` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`email`, `password`, `id`, `key_pub`) VALUES
(0x613ed0da1643670227d53f1953d1adde22584aed2b2dff3bfe130deffca38f18, '$2y$10$0eEJu/tU.anMTNYy0x9miO4A.F3Tm0biqAms2eBmBZQDQUkR1Moti', '100371029565636625', '456610609e50d5d72cf4a39be40611628fd8797362ba2342f1beec4953e0becd'),
(0xd543a0a48bb10af8897483020428593122584aed2b2dff3bfe130deffca38f18, '$2y$10$ut1MUNk90AEkm708aHNeruhAdrlFnma1tIODwZG259Vr0.OeaYGhy', '100371197757227010', '691a3642b2847878c3fdb9ba9ae62bd80e9e2d24022fb179114802473862d2f8');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `100371029565636625`
--
ALTER TABLE `100371029565636625`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `100371197757227010`
--
ALTER TABLE `100371197757227010`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `key_pub` (`key_pub`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `100371029565636625`
--
ALTER TABLE `100371029565636625`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `100371197757227010`
--
ALTER TABLE `100371197757227010`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
