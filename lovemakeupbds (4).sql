-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-07-2025 a las 01:52:32
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lovemakeupbds`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `accion` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8mb4_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_modulo`, `nombre`) VALUES
(1, 'Reporte'),
(2, 'compra'),
(3, 'producto'),
(4, 'venta'),
(5, 'reserva'),
(6, 'proveedor'),
(7, 'categoria'),
(8, 'cliente'),
(9, 'pedidoweb'),
(10, 'metodopago'),
(11, 'metodoentrega'),
(12, 'bitacora'),
(13, 'usuario'),
(14, 'tipousuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `accion` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `id_modulo`, `id_persona`, `accion`, `estado`) VALUES
(1, 1, 1, 'ver', 1),
(2, 2, 1, 'ver', 1),
(3, 2, 1, 'registrar', 1),
(4, 2, 1, 'editar', 1),
(5, 3, 1, 'ver', 1),
(6, 3, 1, 'registrar', 1),
(7, 3, 1, 'editar', 1),
(8, 3, 1, 'eliminar', 1),
(9, 3, 1, 'especial', 1),
(10, 4, 1, 'ver', 1),
(11, 4, 1, 'registrar', 1),
(12, 4, 1, 'especial', 1),
(13, 5, 1, 'ver', 1),
(14, 5, 1, 'registrar', 1),
(15, 5, 1, 'editar', 1),
(16, 6, 1, 'ver', 1),
(17, 6, 1, 'registrar', 1),
(18, 6, 1, 'editar', 1),
(19, 6, 1, 'eliminar', 1),
(20, 7, 1, 'ver', 1),
(21, 7, 1, 'registrar', 1),
(22, 7, 1, 'editar', 1),
(23, 7, 1, 'eliminar', 1),
(24, 8, 1, 'ver', 1),
(25, 8, 1, 'editar', 1),
(26, 9, 1, 'ver', 1),
(27, 9, 1, 'especial', 1),
(28, 10, 1, 'ver', 1),
(29, 10, 1, 'registrar', 1),
(30, 10, 1, 'editar', 1),
(31, 10, 1, 'eliminar', 1),
(32, 11, 1, 'ver', 1),
(33, 11, 1, 'registrar', 1),
(34, 11, 1, 'editar', 1),
(35, 11, 1, 'eliminar', 1),
(36, 12, 1, 'ver', 1),
(37, 13, 1, 'ver', 1),
(38, 13, 1, 'registrar', 1),
(39, 13, 1, 'editar', 1),
(40, 13, 1, 'eliminar', 1),
(41, 13, 1, 'especial', 1),
(42, 14, 1, 'ver', 1),
(43, 14, 1, 'registrar', 1),
(44, 14, 1, 'editar', 1),
(45, 14, 1, 'eliminar', 1),
(46, 1, 2, 'ver', 1),
(47, 2, 2, 'ver', 1),
(48, 2, 2, 'registrar', 1),
(49, 2, 2, 'editar', 1),
(50, 3, 2, 'ver', 1),
(51, 3, 2, 'registrar', 1),
(52, 3, 2, 'editar', 1),
(53, 3, 2, 'eliminar', 1),
(54, 3, 2, 'especial', 1),
(55, 4, 2, 'ver', 1),
(56, 4, 2, 'registrar', 1),
(57, 4, 2, 'especial', 1),
(58, 5, 2, 'ver', 1),
(59, 5, 2, 'registrar', 1),
(60, 5, 2, 'editar', 1),
(61, 6, 2, 'ver', 1),
(62, 6, 2, 'registrar', 1),
(63, 6, 2, 'editar', 1),
(64, 6, 2, 'eliminar', 1),
(65, 7, 2, 'ver', 1),
(66, 7, 2, 'registrar', 1),
(67, 7, 2, 'editar', 1),
(68, 7, 2, 'eliminar', 1),
(69, 8, 2, 'ver', 1),
(70, 8, 2, 'editar', 1),
(71, 9, 2, 'ver', 1),
(72, 9, 2, 'especial', 1),
(73, 10, 2, 'ver', 1),
(74, 10, 2, 'registrar', 1),
(75, 10, 2, 'editar', 1),
(76, 10, 2, 'eliminar', 1),
(77, 11, 2, 'ver', 1),
(78, 11, 2, 'registrar', 1),
(79, 11, 2, 'editar', 1),
(80, 11, 2, 'eliminar', 1),
(81, 12, 2, 'ver', 1),
(82, 13, 2, 'ver', 1),
(83, 13, 2, 'registrar', 1),
(84, 13, 2, 'editar', 1),
(85, 13, 2, 'eliminar', 1),
(86, 13, 2, 'especial', 1),
(87, 14, 2, 'ver', 1),
(88, 14, 2, 'registrar', 1),
(89, 14, 2, 'editar', 1),
(90, 14, 2, 'eliminar', 1),
(181, 1, 3, 'ver', 1),
(182, 2, 3, 'ver', 0),
(183, 2, 3, 'registrar', 0),
(184, 2, 3, 'editar', 0),
(185, 3, 3, 'ver', 1),
(186, 3, 3, 'registrar', 0),
(187, 3, 3, 'editar', 0),
(188, 3, 3, 'eliminar', 0),
(189, 3, 3, 'especial', 0),
(190, 4, 3, 'ver', 1),
(191, 4, 3, 'registrar', 1),
(192, 4, 3, 'especial', 0),
(193, 5, 3, 'ver', 1),
(194, 5, 3, 'registrar', 1),
(195, 5, 3, 'editar', 0),
(196, 6, 3, 'ver', 0),
(197, 6, 3, 'registrar', 0),
(198, 6, 3, 'editar', 0),
(199, 6, 3, 'eliminar', 0),
(200, 7, 3, 'ver', 0),
(201, 7, 3, 'registrar', 0),
(202, 7, 3, 'editar', 0),
(203, 7, 3, 'eliminar', 0),
(204, 8, 3, 'ver', 0),
(205, 8, 3, 'editar', 0),
(206, 9, 3, 'ver', 1),
(207, 9, 3, 'especial', 0),
(208, 10, 3, 'ver', 0),
(209, 10, 3, 'registrar', 0),
(210, 10, 3, 'editar', 0),
(211, 10, 3, 'eliminar', 0),
(212, 11, 3, 'ver', 0),
(213, 11, 3, 'registrar', 0),
(214, 11, 3, 'editar', 0),
(215, 11, 3, 'eliminar', 0),
(216, 12, 3, 'ver', 0),
(217, 13, 3, 'ver', 0),
(218, 13, 3, 'registrar', 0),
(219, 13, 3, 'editar', 0),
(220, 13, 3, 'eliminar', 0),
(221, 13, 3, 'especial', 0),
(222, 14, 3, 'ver', 0),
(223, 14, 3, 'registrar', 0),
(224, 14, 3, 'editar', 0),
(225, 14, 3, 'eliminar', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_usuario`
--

CREATE TABLE `rol_usuario` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `rol_usuario`
--

INSERT INTO `rol_usuario` (`id_rol`, `nombre`, `nivel`, `estatus`) VALUES
(1, 'Desarrollador', 3, 1),
(2, 'Administrador', 3, 1),
(3, 'Asesora Venta', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_persona` int(11) NOT NULL,
  `cedula` varchar(15) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `nombre` varchar(40) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `apellido` varchar(40) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `correo` varchar(250) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `clave` varchar(512) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_persona`, `cedula`, `nombre`, `apellido`, `correo`, `telefono`, `clave`, `estatus`, `id_rol`) VALUES
(1, '00010030', 'Soporte', 'Desarrollador', 'love@gmail.com', '0422-0000000', '5pP5aZhP9nBSPthy4aZzOzBUaEtKVUhVcDVnVGJmNGVVNnlrTWc9PQ==', 1, 1),
(2, '10200300', 'Jefe', 'Lovemakeup', 'jefe@gmail.com', '0422-0000000', 'NowpEGV4GTFVPaQuEw/GYjVrZUhLL2lDT3RQeWptZUVUbTQ2WlE9PQ==', 1, 2),
(3, '20152522', 'Yarilux', 'Figuero', 'yari@gmail.com', '0422-0000000', 'nmhheEa9puJ/7VecS63r5m5oeExMQ0h2YURuSWM0N1BnalViSHc9PQ==', 1, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_bitacora`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_modulo` (`id_modulo`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `usuario` (`id_persona`);

--
-- Filtros para la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD CONSTRAINT `permiso_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`),
  ADD CONSTRAINT `permiso_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `usuario` (`id_persona`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol_usuario` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
