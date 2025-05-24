-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2025 a las 05:12:40
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
-- Base de datos: `lovemakeupca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(200) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `estatus` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `estatus`) VALUES
(1, 'Polvo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `fecha_entrada` date DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_detalles`
--

CREATE TABLE `compra_detalles` (
  `id_detalle_compra` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `precio_unitario` float DEFAULT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_deseo`
--

CREATE TABLE `lista_deseo` (
  `id_lista` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `detalle` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_entrega`
--

CREATE TABLE `metodo_entrega` (
  `id_entrega` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `estatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `metodo_entrega`
--

INSERT INTO `metodo_entrega` (`id_entrega`, `nombre`, `descripcion`, `estatus`) VALUES
(1, 'Delivery', 'Barquisimeto', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `id_metodopago` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `estatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`id_metodopago`, `nombre`, `descripcion`, `estatus`) VALUES
(1, 'Pago Movil', 'Pago en Moneda Nacional BsD', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificaciones` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `mensaje` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(100) DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `referencia_bancaria` int(11) DEFAULT NULL,
  `telefono_emisor` varchar(20) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `id_entrega` int(11) DEFAULT NULL,
  `id_metodopago` int(11) DEFAULT NULL,
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id_detalle` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` float DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `cedula` varchar(15) DEFAULT NULL,
  `nombre` varchar(40) DEFAULT NULL,
  `apellido` varchar(40) DEFAULT NULL,
  `correo` varchar(250) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `clave` varchar(512) DEFAULT NULL,
  `estatus` int(2) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `cedula`, `nombre`, `apellido`, `correo`, `telefono`, `clave`, `estatus`, `id_tipo`) VALUES
(1, '10200300', 'Jefe', 'LoveMakeup', 'lovemakeupca@gmail.com', '04241002030', 'KBrKmaUtzcJkZ1FYynYZbW5yVExwaVFPNkF1RWM1KzJxbUR0QXc9PQ==', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preliminar`
--

CREATE TABLE `preliminar` (
  `id_preliminar` int(11) NOT NULL,
  `condicion` varchar(100) DEFAULT NULL,
  `id_detalle` int(11) DEFAULT NULL,
  `id_detalle_reserva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `cantidad_mayor` int(10) DEFAULT NULL,
  `precio_mayor` float DEFAULT NULL,
  `precio_detal` float DEFAULT NULL,
  `stock_disponible` int(25) DEFAULT NULL,
  `stock_maximo` int(10) DEFAULT NULL,
  `stock_minimo` int(10) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estatus` int(1) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `numero_documento` varchar(15) DEFAULT NULL,
  `tipo_documento` varchar(10) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(250) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `estatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `numero_documento`, `tipo_documento`, `nombre`, `correo`, `telefono`, `direccion`, `estatus`) VALUES
(1, '900800700', 'J', 'Inveriones Casa de Maquijalle', 'inversionescasa@hotmail.com', '02518862233', 'av lara', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `fecha_apartado` date DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_detalles`
--

CREATE TABLE `reserva_detalles` (
  `id_detalle_reserva` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_usuario`
--

CREATE TABLE `rol_usuario` (
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `nivel` int(1) DEFAULT NULL,
  `estatus` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol_usuario`
--

INSERT INTO `rol_usuario` (`id_tipo`, `nombre`, `nivel`, `estatus`) VALUES
(1, 'Administrador', 3, 1),
(2, 'Cliente', 1, 1),
(3, 'Asesora de Venta', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasa`
--

CREATE TABLE `tasa` (
  `id_tasa` int(1) NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tasa`
--

INSERT INTO `tasa` (`id_tasa`, `precio`) VALUES
(1, 10.25);

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
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `compra_detalles`
--
ALTER TABLE `compra_detalles`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `lista_deseo`
--
ALTER TABLE `lista_deseo`
  ADD PRIMARY KEY (`id_lista`),
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD UNIQUE KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `metodo_entrega`
--
ALTER TABLE `metodo_entrega`
  ADD PRIMARY KEY (`id_entrega`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`id_metodopago`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificaciones`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_entrega` (`id_entrega`),
  ADD KEY `id_metodopago` (`id_metodopago`),
  ADD KEY `id_persona` (`id_persona`) USING BTREE;

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indices de la tabla `preliminar`
--
ALTER TABLE `preliminar`
  ADD PRIMARY KEY (`id_preliminar`),
  ADD KEY `id_detalle` (`id_detalle`),
  ADD KEY `id_detalle_reserva` (`id_detalle_reserva`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `reserva_detalles`
--
ALTER TABLE `reserva_detalles`
  ADD PRIMARY KEY (`id_detalle_reserva`),
  ADD KEY `id_reserva` (`id_reserva`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tasa`
--
ALTER TABLE `tasa`
  ADD PRIMARY KEY (`id_tasa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra_detalles`
--
ALTER TABLE `compra_detalles`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodo_entrega`
--
ALTER TABLE `metodo_entrega`
  MODIFY `id_entrega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_metodopago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `preliminar`
--
ALTER TABLE `preliminar`
  MODIFY `id_preliminar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva_detalles`
--
ALTER TABLE `reserva_detalles`
  MODIFY `id_detalle_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `compra_detalles`
--
ALTER TABLE `compra_detalles`
  ADD CONSTRAINT `compra_detalles_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_detalles_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `lista_deseo`
--
ALTER TABLE `lista_deseo`
  ADD CONSTRAINT `lista_deseo_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lista_deseo_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `id_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_entrega`) REFERENCES `metodo_entrega` (`id_entrega`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_metodopago`) REFERENCES `metodo_pago` (`id_metodopago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_detalles_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `rol_usuario` (`id_tipo`);

--
-- Filtros para la tabla `preliminar`
--
ALTER TABLE `preliminar`
  ADD CONSTRAINT `preliminar_ibfk_1` FOREIGN KEY (`id_detalle`) REFERENCES `pedido_detalles` (`id_detalle`),
  ADD CONSTRAINT `preliminar_ibfk_2` FOREIGN KEY (`id_detalle_reserva`) REFERENCES `reserva_detalles` (`id_detalle_reserva`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `reserva_detalles`
--
ALTER TABLE `reserva_detalles`
  ADD CONSTRAINT `reserva_detalles_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`),
  ADD CONSTRAINT `reserva_detalles_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
