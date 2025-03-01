-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2024 a las 02:28:42
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
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartados`
--

CREATE TABLE `apartados` (
  `id` int(11) NOT NULL,
  `fecha_apartado` datetime NOT NULL,
  `cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `apartados`
--

INSERT INTO `apartados` (`id`, `fecha_apartado`, `cliente_id`) VALUES
(1, '2024-03-21 10:00:00', 1),
(2, '2024-03-22 11:00:00', 2),
(3, '2024-03-23 12:00:00', 3),
(4, '2024-03-24 13:00:00', 4),
(5, '2024-03-25 14:00:00', 5),
(6, '2024-03-26 15:00:00', 6),
(7, '2024-03-27 16:00:00', 7),
(8, '2024-03-28 17:00:00', 8),
(9, '2024-03-29 18:00:00', 9),
(10, '2024-03-30 19:00:00', 10),
(11, '2024-04-02 11:00:00', 11),
(12, '2024-04-03 12:00:00', 12),
(13, '2024-04-04 13:00:00', 13),
(14, '2024-04-05 14:00:00', 14),
(15, '2024-04-06 15:00:00', 15),
(16, '2024-04-07 16:00:00', 16),
(17, '2024-04-08 17:00:00', 17),
(18, '2024-04-09 18:00:00', 18),
(19, '2024-04-10 19:00:00', 19),
(20, '2024-11-19 15:07:42', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartados_detalles`
--

CREATE TABLE `apartados_detalles` (
  `apartado_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `apartados_detalles`
--

INSERT INTO `apartados_detalles` (`apartado_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 1, 1, 199.99),
(2, 2, 5, 12.99),
(3, 3, 2, 25.99),
(4, 4, 1, 65.99),
(5, 5, 1, 129.99),
(6, 6, 1, 35.99),
(7, 7, 5, 18.99),
(8, 8, 20, 1.99),
(9, 9, 2, 15.99),
(10, 10, 1, 25.99),
(11, 2, 3, 12.99),
(12, 3, 1, 25.99),
(13, 4, 4, 65.99),
(14, 5, 2, 129.99),
(15, 6, 1, 35.99),
(16, 7, 3, 18.99),
(17, 8, 10, 1.99),
(18, 9, 1, 15.99),
(19, 10, 2, 25.99),
(20, 1, 1, 21.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'img/categorias/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'Herramientas Electricas', 'Herramientas que funcionan con electricidad', 'otros/img/categorias/default.png'),
(2, 'Plomeria', 'Articulos para instalaciones de agua', 'otros/img/categorias/default.png'),
(3, 'Iluminacion', 'Productos de iluminacion y accesorios', 'otros/img/categorias/default.png'),
(4, 'Pinturas', 'Pinturas y materiales de acabado', 'otros/img/categorias/default.png'),
(5, 'Cerrajeria', 'Productos de seguridad y cerraduras', 'otros/img/categorias/default.png'),
(6, 'Jardineria', 'Herramientas y productos para jardin', 'otros/img/categorias/default.png'),
(7, 'Construccion', 'Materiales de construccion basicos', 'otros/img/categorias/default.png'),
(8, 'Electricidad', 'Material electrico y componentes', 'otros/img/categorias/default.png'),
(9, 'Ferreteria General', 'Articulos diversos de ferreteria', 'otros/img/categorias/default.png'),
(10, 'Seguridad Industrial', 'Equipos de proteccion y seguridad', 'otros/img/categorias/default.png'),
(11, 'Herramientas Manuales', 'Herramientas que se utilizan manualmente', 'otros/img/categorias/default.png'),
(12, 'Accesorios', 'Accesorios para herramientas y maquinaria', 'otros/img/categorias/default.png'),
(13, 'Materiales de Construcción', 'Materiales para la construcción', 'otros/img/categorias/default.png'),
(14, 'Seguridad Personal', 'Equipos de protección personal', 'otros/img/categorias/default.png'),
(15, 'Ferretería para Jardín', 'Artículos para el cuidado del jardín', 'otros/img/categorias/default.png'),
(16, 'Suministros de Pintura', 'Todo lo necesario para pintar', 'otros/img/categorias/default.png'),
(17, 'Herramientas de Carpintería', 'Herramientas específicas para carpintería', 'otros/img/categorias/673d217657043_categoria4.jpg'),
(18, 'Equipos de Limpieza', 'Productos y herramientas para limpieza', 'otros/img/categorias/673d216b5b881_categoria3.jpg'),
(19, 'Ferretería Automotriz', 'Artículos para el mantenimiento de vehículos', 'otros/img/categorias/673d216150a14_categoria2.jpg'),
(20, 'Iluminación Exterior', 'Productos de iluminación para exteriores', 'otros/img/categorias/673d39f696e0c_673d2152e640d_categoria1.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `numero_documento` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(25) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `tipo_documento`, `numero_documento`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`) VALUES
(1, 'Cédula', '12345678', 'Juan', 'Perez', 'juan@email.com', '04141234567', 'Calle Principal 123'),
(2, 'Cédula', '98765432', 'Maria', 'Gonzalez', 'maria@email.com', '04242345678', 'Avenida Central 456'),
(3, 'Cédula', '45678912', 'Pedro', 'Ramirez', 'pedro@email.com', '04163456789', 'Carrera 7 #89'),
(4, 'Cédula', '78912345', 'Ana', 'Martinez', 'ana@email.com', '04124567890', 'Calle 15 #45-67'),
(5, 'Cédula', '32165498', 'Luis', 'Garcia', 'luis@email.com', '04145678901', 'Avenida 5 #12-34'),
(6, 'Cédula', '65498732', 'Carmen', 'Lopez', 'carmen@email.com', '04166789012', 'Carrera 23 #56'),
(7, 'Cédula', '95175384', 'Jose', 'Torres', 'jose@email.com', '04127890123', 'Calle 8 #90-12'),
(8, 'Cédula', '75395146', 'Rosa', 'Diaz', 'rosa@email.com', '04148901234', 'Avenida 12 #34-56'),
(9, 'Cédula', '85296374', 'Miguel', 'Sanchez', 'miguel@email.com', '04169012345', 'Carrera 45 #78'),
(10, 'Cédula', '14725836', 'Laura', 'Hernandez', 'laura@email.com', '04123456789', 'Calle 67 #89-12'),
(11, 'Cédula', '23456789', 'Carlos', 'Mendoza', 'carlos@email.com', '04141234568', 'Calle 10 #20-30'),
(12, 'Cédula', '34567890', 'Sofia', 'Martinez', 'sofia@email.com', '04242345679', 'Avenida 20 #40-50'),
(13, 'Cédula', '45678901', 'Andres', 'Gonzalez', 'andres@email.com', '04163456780', 'Carrera 30 #60-70'),
(14, 'Cédula', '56789012', 'Lucia', 'Hernandez', 'lucia@email.com', '04124567891', 'Calle 40 #80-90'),
(15, 'Cédula', '67890123', 'Fernando', 'Lopez', 'fernando@email.com', '04145678902', 'Avenida 50 #100-110'),
(16, 'Cédula', '78901234', 'Patricia', 'Diaz', 'patricia@email.com', '04166789013', 'Carrera 60 #120-130'),
(17, 'Cédula', '89012345', 'Javier', 'Torres', 'javier@email.com', '04127890124', 'Calle 70 #140-150'),
(18, 'Cédula', '90123456', 'Claudia', 'Sanchez', 'claudia@email.com', '04148901235', 'Avenida 80 #160-170'),
(19, 'Cédula', '01234567', 'Diego', 'Ramirez', 'diego@email.com', '04169012346', 'Carrera 90 #180-190'),
(20, 'Cédula', '1234567', 'Valeria', 'García', 'valeria@email.com', '04123456789', 'Calle 100 #200-210');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `nota_entrega` varchar(255) DEFAULT NULL,
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`id`, `fecha_entrada`, `nota_entrega`, `proveedor_id`) VALUES
(1, '2024-03-01 10:00:00', 'NE001', 1),
(2, '2024-03-02 11:00:00', 'NE002', 2),
(3, '2024-03-03 12:00:00', 'NE003', 3),
(4, '2024-03-04 13:00:00', 'NE004', 4),
(5, '2024-03-05 14:00:00', 'NE005', 5),
(6, '2024-03-06 15:00:00', 'NE006', 6),
(7, '2024-03-07 16:00:00', 'NE007', 7),
(8, '2024-03-08 17:00:00', 'NE008', 8),
(9, '2024-03-09 18:00:00', 'NE009', 9),
(10, '2024-03-10 19:00:00', 'NE010', 10),
(11, '2024-04-01 10:00:00', '', 1),
(12, '2024-04-02 11:00:00', '', 2),
(13, '2024-04-03 12:00:00', '', 3),
(14, '2024-04-04 13:00:00', '', 4),
(15, '2024-04-05 14:00:00', '', 5),
(16, '2024-04-06 15:00:00', '', 6),
(17, '2024-04-07 16:00:00', '', 7),
(18, '2024-04-08 17:00:00', '', 8),
(19, '2024-04-09 18:00:00', '', 9),
(20, '2024-04-10 19:00:00', '', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_detalles`
--

CREATE TABLE `entradas_detalles` (
  `entrada_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `entradas_detalles`
--

INSERT INTO `entradas_detalles` (`entrada_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 1, 10, 150.00),
(2, 2, 50, 8.50),
(3, 3, 20, 15.00),
(4, 4, 15, 45.00),
(5, 5, 8, 89.00),
(6, 6, 10, 25.00),
(7, 7, 100, 12.00),
(8, 8, 500, 0.85),
(9, 9, 20, 10.00),
(10, 10, 15, 18.00),
(11, 1, 5, 150.00),
(12, 2, 30, 8.50),
(13, 3, 15, 15.00),
(14, 4, 10, 45.00),
(15, 5, 20, 89.00),
(16, 6, 25, 25.00),
(17, 7, 50, 12.00),
(18, 8, 300, 0.85),
(19, 9, 10, 10.00),
(20, 10, 5, 18.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_compra` decimal(30,2) NOT NULL,
  `precio_venta` decimal(30,2) NOT NULL,
  `stock_total` int(25) NOT NULL,
  `stock_minimo` int(10) DEFAULT NULL,
  `marca` varchar(35) NOT NULL,
  `modelo` varchar(35) DEFAULT NULL,
  `tipo_unidad` varchar(20) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `ubicacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `precio_compra`, `precio_venta`, `stock_total`, `stock_minimo`, `marca`, `modelo`, `tipo_unidad`, `imagen`, `categoria_id`, `ubicacion_id`) VALUES
(1, 'TOOL001', 'Taladro Electrico', 150.00, 150.00, 25, 5, 'DeWalt', 'DW745', 'Unidad', 'otros/img/productos/default.png', 1, 1),
(2, 'PLUM001', 'Tuberia PVC', 8.50, 8.50, 100, 20, 'Pavco', 'T12', 'Unidad', 'otros/img/productos/default.png', 2, 2),
(3, 'LIGHT001', 'Lampara LED', 15.00, 15.00, 50, 10, 'Philips', 'LED60', 'Unidad', 'otros/img/productos/default.png', 3, 3),
(4, 'PAINT001', 'Pintura Blanca', 45.00, 45.00, 30, 5, 'Pintuco', 'P100', 'Unidad', 'otros/img/productos/default.png', 4, 4),
(5, 'LOCK001', 'Cerradura Digital', 89.00, 89.00, 15, 3, 'Yale', 'YDM3109', 'Unidad', 'otros/img/productos/default.png', 5, 5),
(6, 'GARD001', 'Manguera Jardin', 25.00, 25.00, 20, 4, 'Truper', 'MG50', 'Unidad', 'otros/img/productos/default.png', 6, 6),
(7, 'CONS001', 'Cemento Gris', 12.00, 12.00, 200, 50, 'Holcim', 'Portland', 'Saco', 'otros/img/productos/default.png', 7, 7),
(8, 'ELEC001', 'Cable Electrico', 0.85, 0.85, 1000, 100, 'Condumex', 'THW12', 'Unidad', 'otros/img/productos/default.png', 8, 8),
(9, 'TOOL002', 'Martillo', 10.00, 10.00, 40, 8, 'Stanley', 'ST150', 'Unidad', 'otros/img/productos/default.png', 9, 9),
(10, 'SAFE001', 'Casco Seguridad', 18.00, 18.00, 35, 7, '3M', 'H700', 'Unidad', 'otros/img/productos/default.png', 10, 10),
(11, 'TOOL003', 'Sierra Circular', 200.00, 200.00, 15, 3, 'Makita', 'HS7600', 'Unidad', 'otros/img/productos/default.png', 1, 1),
(12, 'PLUM002', 'Codo PVC', 5.00, 5.00, 200, 50, 'Pavco', 'Codo90', 'Unidad', 'otros/img/productos/default.png', 2, 2),
(13, 'LIGHT002', 'Bombillo LED', 10.00, 10.00, 100, 20, 'Philips', 'LED10', 'Unidad', 'otros/img/productos/default.png', 3, 3),
(14, 'PAINT002', 'Pintura Acrilica', 50.00, 50.00, 25, 5, 'Pintuco', 'A100', 'Unidad', 'otros/img/productos/default.png', 4, 4),
(15, '', 'Cerradura Mecánica', 70.00, 70.00, 20, 4, 'Yale', 'YDM3108', 'Unidad', 'otros/img/productos/default.png', 5, 5),
(16, 'GARD002', 'Tijeras de Jardín', 15.00, 15.00, 30, 6, 'Truper', 'TJ20', 'Unidad', 'otros/img/productos/default.png', 6, 6),
(17, 'CONS002', 'Arena Fina', 8.00, 8.00, 150, 30, 'Holcim', 'AF20', 'Saco', 'otros/img/productos/673d2223b66fc_producto4.png', 7, 7),
(18, 'ELEC002', 'Toma Corriente', 1.50, 1.50, 500, 100, 'Condumex', 'TC10', 'Unidad', 'otros/img/productos/673d221877ccb_producto3.jpg', 8, 8),
(19, 'TOOL004', 'Destornillador', 5.00, 5.00, 60, 12, 'Stanley', 'ST200', 'Unidad', 'otros/img/productos/673d220953e74_producto2.jpg', 9, 9),
(20, 'SAFE002', 'Guantes de Seguridad', 12.00, 12.00, 40, 8, '3M', 'G700', 'Unidad', 'otros/img/productos/673d220026c54_producto1.jpg', 11, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `numero_documento` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `correo` varchar(25) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `catalogo` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'img/proveedores/default.png',
  `catalogo_archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `tipo_documento`, `numero_documento`, `nombre`, `direccion`, `correo`, `telefono`, `catalogo`, `imagen`, `catalogo_archivo`) VALUES
(1, 'RIF', 'J-123456789', 'Ferreteria El Constructor', 'Zona Industrial 1', 'constructor@email.com', '02121234567', '', 'otros/img/proveedores/default.png', ''),
(2, 'RIF', 'J-987654321', 'Distribuidora Tools', 'Calle Comercio 234', 'tools@email.com', '02122345678', '', 'otros/img/proveedores/default.png', ''),
(3, 'RIF', 'J-456789123', 'Materiales Express', 'Avenida Principal 567', 'express@email.com', '02123456789', '', 'otros/img/proveedores/default.png', ''),
(4, 'RIF', 'J-789123456', 'Importadora Hardware', 'Calle 45 #12-34', 'hardware@email.com', '02124567890', '', 'otros/img/proveedores/default.png', ''),
(5, 'RIF', 'J-321654987', 'Suministros Pro', 'Carrera 78 #90', 'pro@email.com', '02125678901', '', 'otros/img/proveedores/default.png', ''),
(6, 'Cédula', '65498732', 'Herramientas Plus', 'Avenida 23 #45-67', 'plus@email.com', '02126789012', '', 'otros/img/proveedores/default.png', ''),
(7, 'Cédula', '95175384', 'Distribuidora Industrial', 'Calle 89 #12', 'industrial@email.com', '02127890123', '', 'otros/img/proveedores/default.png', ''),
(8, 'Cédula', '75395146', 'Materiales Unidos', 'Carrera 12 #34-56', 'unidos@email.com', '02128901234', '', 'otros/img/proveedores/default.png', ''),
(9, 'Cédula', '85296374', 'Ferreteria Total', 'Avenida 67 #89', 'total@email.com', '02129012345', '', 'otros/img/proveedores/default.png', ''),
(10, 'Cédula', '14725836', 'Importadora General', 'Calle 34 #56-78', 'general@email.com', '04120123456', '', 'otros/img/proveedores/default.png', ''),
(11, 'RIF', 'J-111111111', 'Ferretería La Nueva', 'Calle 1', 'nueva@email.com', '02121234568', '', 'otros/img/proveedores/default.png', ''),
(12, 'RIF', 'J-222222222', 'Distribuciones Rápidas', 'Calle 2', 'rapidas@email.com', '02122345679', '', 'otros/img/proveedores/default.png', ''),
(13, 'RIF', 'J-333333333', 'Materiales y Suministros', 'Calle 3', 'suministros@email.com', '02123456780', '', 'otros/img/proveedores/default.png', ''),
(14, 'RIF', 'J-444444444', 'Importaciones Globales', 'Calle 4', 'globales@email.com', '02124567891', '', 'otros/img/proveedores/default.png', ''),
(15, 'RIF', 'J-555555555', 'Suministros de Calidad', 'Calle 5', 'calidad@email.com', '02125678902', '', 'otros/img/proveedores/default.png', ''),
(16, 'Cédula', '65432100', 'Herramientas y Más', 'Calle 6', 'mas@email.com', '02126789013', '', 'otros/img/proveedores/default.png', ''),
(17, 'Cédula', '95175300', 'Distribuidora de Ferretería', 'Calle 7', 'distribuidora@email.com', '02127890124', '', 'otros/img/proveedores/673d21e8b784a_proveedor4.jpg', ''),
(18, 'Cédula', '75395100', 'Materiales de Construcción', 'Calle 8', 'materiales@email.com', '02128901235', '', 'otros/img/proveedores/673d21dd98de1_proveedor3.png', ''),
(19, 'Cédula', '85296300', 'Ferretería Total', 'Calle 9', 'total@email.com', '02129012346', '', 'otros/img/proveedores/673d21cec7a6b_proveedor2.jpg', ''),
(20, 'Cédula', '14725800', 'Importadora de Herramientas', 'Calle 10', 'importadora@email.com', '04120123457', '', 'otros/img/proveedores/673d21bfaea3a_proveedor1.png', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `id` int(11) NOT NULL,
  `fecha_salida` datetime NOT NULL,
  `cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `salidas`
--

INSERT INTO `salidas` (`id`, `fecha_salida`, `cliente_id`) VALUES
(1, '2024-03-11 10:00:00', 1),
(2, '2024-03-12 11:00:00', 2),
(3, '2024-03-13 12:00:00', 3),
(4, '2024-03-14 13:00:00', 4),
(5, '2024-03-15 14:00:00', 5),
(6, '2024-03-16 15:00:00', 6),
(7, '2024-03-17 16:00:00', 7),
(8, '2024-03-18 17:00:00', 8),
(9, '2024-03-19 18:00:00', 9),
(10, '2024-03-20 19:00:00', 10),
(11, '2024-04-11 10:00:00', 11),
(12, '2024-04-12 11:00:00', 12),
(13, '2024-04-13 12:00:00', 13),
(14, '2024-04-14 13:00:00', 14),
(15, '2024-04-15 14:00:00', 15),
(16, '2024-04-16 15:00:00', 16),
(17, '2024-04-17 16:00:00', 17),
(18, '2024-04-18 17:00:00', 18),
(19, '2024-04-19 18:00:00', 19),
(20, '2024-04-20 19:00:00', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas_detalles`
--

CREATE TABLE `salidas_detalles` (
  `salida_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `salidas_detalles`
--

INSERT INTO `salidas_detalles` (`salida_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 1, 1, 199.99),
(2, 2, 10, 12.99),
(3, 3, 5, 25.99),
(4, 4, 2, 65.99),
(5, 5, 1, 129.99),
(6, 6, 2, 35.99),
(7, 7, 10, 18.99),
(8, 8, 50, 1.99),
(9, 9, 3, 15.99),
(10, 10, 2, 25.99),
(11, 1, 2, 199.99),
(12, 2, 5, 12.99),
(13, 3, 3, 25.99),
(14, 4, 1, 65.99),
(15, 5, 2, 129.99),
(16, 6, 1, 35.99),
(17, 7, 5, 18.99),
(18, 8, 20, 1.99),
(19, 9, 2, 15.99),
(20, 10, 1, 25.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'Almacen Principal', 'Area principal de almacenamiento', 'otros/img/ubicaciones/default.png'),
(2, 'Estanteria A1', 'Estanteria zona norte', 'otros/img/ubicaciones/default.png'),
(3, 'Estanteria B1', 'Estanteria zona sur', 'otros/img/ubicaciones/default.png'),
(4, 'Bodega Externa', 'Almacen secundario', 'otros/img/ubicaciones/default.png'),
(5, 'Mostrador', 'Area de exhibicion principal', 'otros/img/ubicaciones/default.png'),
(6, 'Vitrina 1', 'Vitrina de productos pequeños', 'otros/img/ubicaciones/default.png'),
(7, 'Deposito 1', 'Deposito de materiales pesados', 'otros/img/ubicaciones/default.png'),
(8, 'Zona Refrigerada', 'Area de productos especiales', 'otros/img/ubicaciones/default.png'),
(9, 'Estanteria C1', 'Estanteria zona este', 'otros/img/ubicaciones/default.png'),
(10, 'Area Seguridad', 'Zona de productos de seguridad', 'otros/img/ubicaciones/default.png'),
(11, 'Almacen Secundario', 'Almacen adicional para productos', 'otros/img/ubicaciones/default.png'),
(12, 'Estanteria D1', 'Estanteria zona oeste', 'otros/img/ubicaciones/default.png'),
(13, 'Estanteria E1', 'Estanteria zona norte', 'otros/img/ubicaciones/default.png'),
(14, 'Bodega Principal', 'Bodega principal de almacenamiento', 'otros/img/ubicaciones/default.png'),
(15, 'Mostrador Secundario', 'Area de exhibicion secundaria', 'otros/img/ubicaciones/default.png'),
(16, 'Vitrina 2', 'Vitrina de productos grandes', 'otros/img/ubicaciones/default.png'),
(17, 'Deposito 2', 'Deposito de productos livianos', 'otros/img/ubicaciones/673d21ac37614_ubicacion4.jpg'),
(18, 'Zona de Herramientas', 'Area dedicada a herramientas', 'otros/img/ubicaciones/673d21a10d4ac_ubicacion3.jpg'),
(19, 'Estanteria F1', 'Estanteria zona sur', 'otros/img/ubicaciones/673d219491cf7_ubicacion2.jpg'),
(20, 'Area de Mantenimiento', 'Zona de productos de mantenimiento', 'otros/img/ubicaciones/673d218b202ab_ubicacion1.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(25) DEFAULT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo_usuario` varchar(50) NOT NULL,
  `imagen` varchar(255) DEFAULT 'img/usuarios/default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `nombre_usuario`, `contraseña`, `tipo_usuario`, `imagen`) VALUES
(1, '', '', '', '', '', 'Administrador', '$2y$10$Fj2EAzVDE82XiQBPiLHZy.iS8VIaRK1jOYQ7RyelpdYPrEVv.OXk2', 'administrador', 'otros/img/usuarios/default.jpg'),
(2, '1234567', 'Gabriel', 'Gabriel', '04128287680', '', 'Gabriel2003', '$2y$10$0A.3LJ/5XbhG.vSTkrzS2.x0RacSDrqhWWi5QL2fNbDTUMw840XAG', 'administrador', 'otros/img/usuarios/673d3a564051a_Gemini_Generated_Image_72holk72holk72ho.jpg'),
(3, '12345678', 'Diego', 'Fonseca', '', '', 'Diego2005', '$2y$10$taxvdP8OPYxLF2dmapi0BeP.FjJWRWJQFhlU7NcMikisDsUE6eT6K', 'usuario', 'otros/img/usuarios/default.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartados`
--
ALTER TABLE `apartados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `apartados_detalles`
--
ALTER TABLE `apartados_detalles`
  ADD KEY `apartado_id` (`apartado_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `entradas_detalles`
--
ALTER TABLE `entradas_detalles`
  ADD KEY `entrada_id` (`entrada_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `ubicacion_id` (`ubicacion_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`) USING BTREE;

--
-- Indices de la tabla `salidas_detalles`
--
ALTER TABLE `salidas_detalles`
  ADD KEY `producto_id` (`producto_id`) USING BTREE,
  ADD KEY `salida_id` (`salida_id`) USING BTREE;

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apartados`
--
ALTER TABLE `apartados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apartados`
--
ALTER TABLE `apartados`
  ADD CONSTRAINT `apartados_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `apartados_detalles`
--
ALTER TABLE `apartados_detalles`
  ADD CONSTRAINT `apartados_productos_ibfk_1` FOREIGN KEY (`apartado_id`) REFERENCES `apartados` (`id`),
  ADD CONSTRAINT `apartados_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `entradas_detalles`
--
ALTER TABLE `entradas_detalles`
  ADD CONSTRAINT `entradas_productos_ibfk_1` FOREIGN KEY (`entrada_id`) REFERENCES `entradas` (`id`),
  ADD CONSTRAINT `entradas_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_fk` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ubicacion_fk` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`);

--
-- Filtros para la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `salidas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `salidas_detalles`
--
ALTER TABLE `salidas_detalles`
  ADD CONSTRAINT `salidas_detalles_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `salidas_detalles_ibfk_2` FOREIGN KEY (`salida_id`) REFERENCES `salidas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
