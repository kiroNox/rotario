-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2024 a las 00:44:08
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rotario-produccion`
--
CREATE DATABASE IF NOT EXISTS `rotario-produccion` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `rotario-produccion`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `codigo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_trabajador_area` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_trabajador` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_trabajador`, `fecha`, `descripcion`) VALUES
(2, '2024-06-19 22:39:56', 'Inicio de sesión'),
(2, '2024-06-19 22:42:41', 'Inicio de sesión'),
(2, '2024-06-19 22:42:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-19 22:43:06', 'Inicio de sesión'),
(2, '2024-06-19 22:43:06', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-19 22:43:29', 'Inicio de sesión'),
(2, '2024-06-19 22:43:29', 'Ingreso en el modulo (Usuarios)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deducciones`
--

CREATE TABLE `deducciones` (
  `id_deducciones` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `monto` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id_detalle_factura` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `sueldo_base` decimal(13,2) NOT NULL,
  `prima_antiguedad` decimal(13,2) NOT NULL,
  `prima_familiar` decimal(13,2) NOT NULL,
  `escalafon` decimal(13,2) NOT NULL,
  `total_primas` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escalafon`
--

CREATE TABLE `escalafon` (
  `id_escalafon` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `anios_servicio` varchar(45) NOT NULL,
  `escala` varchar(45) NOT NULL,
  `propuesto` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion`
--

CREATE TABLE `liquidacion` (
  `id_liquidacion` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `monto` decimal(13,2) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulos`, `nombre`) VALUES
(1, 'usuarios'),
(2, 'areas'),
(3, 'permisos'),
(4, 'asistencias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_rol` int(11) NOT NULL,
  `id_modulos` int(11) NOT NULL,
  `crear` tinyint(4) NOT NULL DEFAULT '1',
  `modificar` tinyint(4) NOT NULL DEFAULT '1',
  `eliminar` tinyint(4) NOT NULL DEFAULT '1',
  `consultar` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `id_modulos`, `crear`, `modificar`, `eliminar`, `consultar`) VALUES
(1, 1, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(1, 3, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `primas_generales`
--

CREATE TABLE `primas_generales` (
  `id_primas_generales` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `monto` decimal(13,2) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_antiguedad`
--

CREATE TABLE `prima_antiguedad` (
  `id_prima_antiguedad` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `anios_antiguedad` int(11) NOT NULL,
  `monto` decimal(13,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_familiar`
--

CREATE TABLE `prima_familiar` (
  `id_prima_familiar` int(11) NOT NULL,
  `monto` decimal(13,2) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_profesionalismo`
--

CREATE TABLE `prima_profesionalismo` (
  `id_prima_profesionalismo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `incremento` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `prima_profesionalismo`
--

INSERT INTO `prima_profesionalismo` (`id_prima_profesionalismo`, `descripcion`, `incremento`) VALUES
(1, 'prueva', '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reposo`
--

CREATE TABLE `reposo` (
  `id_reposo` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_reposo` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `descripcion`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla ` sueldo_base`
--

CREATE TABLE ` sueldo_base` (
  `id sueldo_base` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  ` sueldo_base` decimal(13,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `id_prima_profesionalismo` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `numero_cuenta` varchar(45) NOT NULL,
  `creado` date NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `estado_actividad` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`id_trabajador`, `id_prima_profesionalismo`, `id_rol`, `cedula`, `numero_cuenta`, `creado`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `token`, `estado_actividad`) VALUES
(2, 1, 1, 'V-27250544', '00000000000000000000', '2024-06-19', 'Xavier David', 'Suarez Sanchez', '0414-5555555', 'uptaebxavier@gmail.com', '$2y$10$T2pA0Ie3aXtjmUoecSo1C.R6A94Y74A3NX9oe0lEaX8WWJjSTQ6/a', '$2y$10$qBADS1LGMZcpS4AkbPC1FuM3WWXvf3KITv/ALVSbKBwBdXFCFKck2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores_prima_familiar`
--

CREATE TABLE `trabajadores_prima_familiar` (
  `id_trabajadores_prima_familiar` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `id_prima_familiar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_area`
--

CREATE TABLE `trabajador_area` (
  `id_trabajador_area` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_deducciones`
--

CREATE TABLE `trabajador_deducciones` (
  `id_trabajador_deducciones` int(11) NOT NULL,
  `id_deducciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_prima_general`
--

CREATE TABLE `trabajador_prima_general` (
  `id_trabajador_prima_general` int(11) NOT NULL,
  `iid_primas_generales` int(11) NOT NULL,
  `iid_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id_vacaciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `dias_totales` int(99) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `fk_Asistencias_Trabajador_Area1_idx` (`id_trabajador_area`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD KEY `id_trabajador` (`id_trabajador`),
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  ADD PRIMARY KEY (`id_deducciones`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id_detalle_factura`),
  ADD KEY `fk_detalle_factura_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `escalafon`
--
ALTER TABLE `escalafon`
  ADD PRIMARY KEY (`id_escalafon`),
  ADD KEY `fk_escalafon_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD PRIMARY KEY (`id_liquidacion`),
  ADD KEY `fk_Liquidacion_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulos`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_rol`,`id_modulos`),
  ADD KEY `fk_Permisos_Rol1_idx` (`id_rol`),
  ADD KEY `fk_Permisos_modulos1_idx` (`id_modulos`);

--
-- Indices de la tabla `primas_generales`
--
ALTER TABLE `primas_generales`
  ADD PRIMARY KEY (`id_primas_generales`);

--
-- Indices de la tabla `prima_antiguedad`
--
ALTER TABLE `prima_antiguedad`
  ADD PRIMARY KEY (`id_prima_antiguedad`),
  ADD KEY `fk_prima_antiguedad_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `prima_familiar`
--
ALTER TABLE `prima_familiar`
  ADD PRIMARY KEY (`id_prima_familiar`);

--
-- Indices de la tabla `prima_profesionalismo`
--
ALTER TABLE `prima_profesionalismo`
  ADD PRIMARY KEY (`id_prima_profesionalismo`);

--
-- Indices de la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD PRIMARY KEY (`id_reposo`),
  ADD KEY `fk_Reposo_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla ` sueldo_base`
--
ALTER TABLE ` sueldo_base`
  ADD PRIMARY KEY (`id sueldo_base`),
  ADD KEY `fk_ sueldo_base_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id_trabajador`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `fk_Trabajadores_prima_profesionalismo1_idx` (`id_prima_profesionalismo`),
  ADD KEY `fk_Trabajadores_Rol1_idx` (`id_rol`);

--
-- Indices de la tabla `trabajadores_prima_familiar`
--
ALTER TABLE `trabajadores_prima_familiar`
  ADD PRIMARY KEY (`id_trabajadores_prima_familiar`),
  ADD KEY `fk_trabajadores_prima_beneficios_familiares_Trabajadores1_idx` (`id_trabajador`),
  ADD KEY `fk_trabajadores_prima_beneficios_familiares_prima_beneficio_idx` (`id_prima_familiar`);

--
-- Indices de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  ADD PRIMARY KEY (`id_trabajador_area`),
  ADD KEY `fk_Trabajador_has_Area_Areas1_idx` (`id_area`),
  ADD KEY `fk_Trabajador_Area_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `trabajador_deducciones`
--
ALTER TABLE `trabajador_deducciones`
  ADD PRIMARY KEY (`id_trabajador_deducciones`),
  ADD KEY `fk_trabajador_deducciones_deducciones1_idx` (`id_deducciones`),
  ADD KEY `fk_trabajador_deducciones_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  ADD PRIMARY KEY (`id_trabajador_prima_general`),
  ADD KEY `fk_trabajador_prima_general_primas_generales1_idx` (`iid_primas_generales`),
  ADD KEY `fk_trabajador_prima_general_Trabajadores1_idx` (`iid_trabajador`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`id_vacaciones`),
  ADD KEY `fk_Vacaciones_Trabajadores1_idx` (`id_trabajador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  MODIFY `id_deducciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id_detalle_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `escalafon`
--
ALTER TABLE `escalafon`
  MODIFY `id_escalafon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  MODIFY `id_liquidacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `primas_generales`
--
ALTER TABLE `primas_generales`
  MODIFY `id_primas_generales` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prima_antiguedad`
--
ALTER TABLE `prima_antiguedad`
  MODIFY `id_prima_antiguedad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prima_familiar`
--
ALTER TABLE `prima_familiar`
  MODIFY `id_prima_familiar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prima_profesionalismo`
--
ALTER TABLE `prima_profesionalismo`
  MODIFY `id_prima_profesionalismo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reposo`
--
ALTER TABLE `reposo`
  MODIFY `id_reposo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla ` sueldo_base`
--
ALTER TABLE ` sueldo_base`
  MODIFY `id sueldo_base` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trabajadores_prima_familiar`
--
ALTER TABLE `trabajadores_prima_familiar`
  MODIFY `id_trabajadores_prima_familiar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  MODIFY `id_trabajador_area` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajador_deducciones`
--
ALTER TABLE `trabajador_deducciones`
  MODIFY `id_trabajador_deducciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  MODIFY `id_trabajador_prima_general` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `fk_Asistencias_Trabajador_Area1` FOREIGN KEY (`id_trabajador_area`) REFERENCES `trabajador_area` (`id_trabajador_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `fk_detalle_factura_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `escalafon`
--
ALTER TABLE `escalafon`
  ADD CONSTRAINT `fk_escalafon_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD CONSTRAINT `fk_Liquidacion_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_Permisos_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Permisos_modulos1` FOREIGN KEY (`id_modulos`) REFERENCES `modulos` (`id_modulos`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prima_antiguedad`
--
ALTER TABLE `prima_antiguedad`
  ADD CONSTRAINT `fk_prima_antiguedad_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD CONSTRAINT `fk_Reposo_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla ` sueldo_base`
--
ALTER TABLE ` sueldo_base`
  ADD CONSTRAINT `fk_ sueldo_base_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD CONSTRAINT `fk_Trabajadores_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trabajadores_prima_profesionalismo1` FOREIGN KEY (`id_prima_profesionalismo`) REFERENCES `prima_profesionalismo` (`id_prima_profesionalismo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajadores_prima_familiar`
--
ALTER TABLE `trabajadores_prima_familiar`
  ADD CONSTRAINT `fk_trabajadores_prima_beneficios_familiares_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajadores_prima_beneficios_familiares_prima_beneficios_1` FOREIGN KEY (`id_prima_familiar`) REFERENCES `prima_familiar` (`id_prima_familiar`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  ADD CONSTRAINT `fk_Trabajador_Area_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trabajador_has_Area_Areas1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_deducciones`
--
ALTER TABLE `trabajador_deducciones`
  ADD CONSTRAINT `fk_trabajador_deducciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_deducciones_deducciones1` FOREIGN KEY (`id_deducciones`) REFERENCES `deducciones` (`id_deducciones`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  ADD CONSTRAINT `fk_trabajador_prima_general_Trabajadores1` FOREIGN KEY (`iid_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_prima_general_primas_generales1` FOREIGN KEY (`iid_primas_generales`) REFERENCES `primas_generales` (`id_primas_generales`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `fk_Vacaciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
