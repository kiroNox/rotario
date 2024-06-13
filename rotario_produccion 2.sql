-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2024 a las 11:52:20
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
-- Base de datos: `rotario_produccion`
--
CREATE DATABASE IF NOT EXISTS `rotario_produccion` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `rotario_produccion`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `id_asignacion` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL,
  `formula` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_pago`
--

CREATE TABLE `asignacion_pago` (
  `id_asignacion_pago` int(11) NOT NULL,
  `id_persona_asig_tiempo` int(11) NOT NULL,
  `id_pagos_nomina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_trabajador_area` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('asistente','inasistente') COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_usuario` int(11) NOT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_usuario`, `id_modulo`, `fecha`, `descripcion`) VALUES
(1, NULL, '2024-06-12 21:15:35', 'Inicio de sesión'),
(1, 2, '2024-06-12 21:15:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:17:55', 'Ingreso en el modulo'),
(1, NULL, '2024-06-13 01:52:20', 'Inicio de sesión'),
(1, 2, '2024-06-13 01:52:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:02:20', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:03:24', 'Registro al usuarios (V-2725054)'),
(1, 2, '2024-06-13 02:08:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:12:35', 'Elimino al usuario (Array)'),
(1, 2, '2024-06-13 02:13:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:32:56', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:33:41', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:34:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:47:52', 'Registro al usuarios (V-2725054)'),
(1, 2, '2024-06-13 02:48:04', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:48:41', 'Elimino al usuario (V-2725054)'),
(1, 2, '2024-06-13 02:49:28', 'Registro al usuarios (V-2725054)'),
(1, 2, '2024-06-13 02:49:51', 'Elimino al usuario (V-2725054)'),
(1, 2, '2024-06-13 02:52:33', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:52:53', 'Registro al usuarios (V-2725054)'),
(1, 2, '2024-06-13 02:53:22', 'Modifico al usuario (V-2725054)'),
(1, 2, '2024-06-13 03:22:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:27:30', 'Ingreso en el modulo'),
(1, 3, '2024-06-13 06:26:47', 'Registro de nuevo rol (probando)'),
(1, 3, '2024-06-13 07:06:11', 'Modifico el rol (probandox)'),
(1, 3, '2024-06-13 07:11:38', 'Elimino el rol (probandox)'),
(1, 3, '2024-06-13 07:12:04', 'Registro de nuevo rol (probando)'),
(1, 3, '2024-06-13 07:22:33', 'Modifico el rol (probando)'),
(1, 3, '2024-06-13 07:22:37', 'Elimino el rol (probando)'),
(1, 3, '2024-06-13 07:22:42', 'Registro de nuevo rol ()'),
(1, 3, '2024-06-13 07:22:49', 'Elimino el rol ()'),
(1, 3, '2024-06-13 07:22:56', 'Registro de nuevo rol (probando)'),
(1, 4, '2024-06-13 09:40:14', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:40:15', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:40:16', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:40:35', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:40:38', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:41:19', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:41:22', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:41:23', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:41:24', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:45:04', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:45:07', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:45:08', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:45:09', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:46:22', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:49:15', 'cambio los permiso de un rol'),
(1, 4, '2024-06-13 09:50:16', 'cambio los permiso de un rol');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deducciones`
--

CREATE TABLE `deducciones` (
  `id_deducciones` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deduccion_pago`
--

CREATE TABLE `deduccion_pago` (
  `id_deduccion_pago` int(11) NOT NULL,
  `id_personas_deducciones` int(11) NOT NULL,
  `id_pagos_nomina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion`
--

CREATE TABLE `liquidacion` (
  `id_liquidacion` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `monto` decimal(11,3) DEFAULT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulos`, `nombre`) VALUES
(1, 'inicio'),
(2, 'usuarios'),
(3, 'roles'),
(4, 'permisos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_educativo`
--

CREATE TABLE `nivel_educativo` (
  `id_nivel_educativo` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `monto` decimal(11,3) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_nomina`
--

CREATE TABLE `pagos_nomina` (
  `id_pagos_nomina` int(11) NOT NULL,
  `iid_trabajador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `sueldo_integral` decimal(12,2) NOT NULL,
  `sueldo_total` decimal(12,2) NOT NULL,
  `deducciones_total` decimal(12,2) NOT NULL,
  `sueldo_base` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_rol` int(11) NOT NULL,
  `id_modulos` int(11) NOT NULL,
  `crear` tinyint(1) NOT NULL,
  `modificar` tinyint(1) NOT NULL,
  `eliminar` tinyint(1) NOT NULL,
  `consultar` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `id_modulos`, `crear`, `modificar`, `eliminar`, `consultar`) VALUES
(1, 1, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(1, 3, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 1),
(19, 1, 0, 0, 1, 1),
(19, 2, 1, 1, 0, 0),
(19, 3, 1, 1, 0, 0),
(19, 4, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_trabajadpr`
--

CREATE TABLE `permisos_trabajadpr` (
  `id_permisos` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `ripo_de_permisos` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `cedula` varchar(12) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `liquidacion` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `liquidacion`) VALUES
(1, 'V-27250544', 'Xavier David', 'Suarez Sanchez', '0414-5555555', 'uptaebxavier@gmail.com', 0),
(2, 'V-2725054', 'Xavier David', 'suarez', '0513-5135131', 'uptaebxavier@gmail.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_asignacion`
--

CREATE TABLE `personas_asignacion` (
  `id_persona_asig_tiempo` int(11) NOT NULL,
  `id_asignacion_tiempo` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_deducciones`
--

CREATE TABLE `personas_deducciones` (
  `id_personas_dedudcciones` int(11) NOT NULL,
  `id_deducciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reposo`
--

CREATE TABLE `reposo` (
  `id_reposo` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_reposo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `descripcion`) VALUES
(1, 'Administrador'),
(19, 'probando');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sueldos`
--

CREATE TABLE `sueldos` (
  `id_sueldos` int(11) NOT NULL,
  `monto` decimal(11,3) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_sueldo` varchar(45) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_nivel_educativo` int(11) NOT NULL,
  `numero_cuenta` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `creado` date NOT NULL,
  `id_sueldos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_area`
--

CREATE TABLE `trabajador_area` (
  `id_trabajador_area` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `clave` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_persona`, `id_rol`, `clave`, `token`) VALUES
(2, 1, 1, '$2y$10$T2pA0Ie3aXtjmUoecSo1C.R6A94Y74A3NX9oe0lEaX8WWJjSTQ6/a', '$2y$10$8kfCaGOBxDmGdNm0c0aXseO5jXKtGG/PM.lz5R.zFypNnjoC23ppy'),
(9, 2, 1, '$2y$10$HDZSPZam0iD89Sd5TkY3HO7UfABp4aGCeyjbLIgp0yVkWzgnmrmyG', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id_vacaciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `dias_totales` int(99) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`id_asignacion`);

--
-- Indices de la tabla `asignacion_pago`
--
ALTER TABLE `asignacion_pago`
  ADD PRIMARY KEY (`id_asignacion_pago`),
  ADD KEY `fk_asignacion_pago_personas_asignacion1_idx` (`id_persona_asig_tiempo`),
  ADD KEY `fk_asignacion_pago_pagos_nomina1_idx` (`id_pagos_nomina`);

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
  ADD KEY `fk_Bitacora_Usuarios1_idx` (`id_usuario`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  ADD PRIMARY KEY (`id_deducciones`);

--
-- Indices de la tabla `deduccion_pago`
--
ALTER TABLE `deduccion_pago`
  ADD PRIMARY KEY (`id_deduccion_pago`),
  ADD KEY `fk_deduccion_pago_personas_deducciones_idx` (`id_personas_deducciones`),
  ADD KEY `fk_deduccion_pago_pagos_nomina1_idx` (`id_pagos_nomina`);

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
-- Indices de la tabla `nivel_educativo`
--
ALTER TABLE `nivel_educativo`
  ADD PRIMARY KEY (`id_nivel_educativo`);

--
-- Indices de la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  ADD PRIMARY KEY (`id_pagos_nomina`),
  ADD KEY `fk_pagos_nomina_trabajadores1_idx` (`iid_trabajador`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_rol`,`id_modulos`),
  ADD KEY `fk_Permisos_Rol1_idx` (`id_rol`),
  ADD KEY `fk_Permisos_modulos1_idx` (`id_modulos`);

--
-- Indices de la tabla `permisos_trabajadpr`
--
ALTER TABLE `permisos_trabajadpr`
  ADD PRIMARY KEY (`id_permisos`),
  ADD KEY `fk_Permisos_trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `personas_asignacion`
--
ALTER TABLE `personas_asignacion`
  ADD PRIMARY KEY (`id_persona_asig_tiempo`),
  ADD KEY `fk_personas_has_asignacion_tiempo_asignacion_tiempo1_idx` (`id_asignacion_tiempo`),
  ADD KEY `fk_personas_asignacion_tiempo_trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `personas_deducciones`
--
ALTER TABLE `personas_deducciones`
  ADD PRIMARY KEY (`id_personas_dedudcciones`),
  ADD KEY `fk_personas_deducciones_deducciones1_idx` (`id_deducciones`),
  ADD KEY `fk_personas_deducciones_trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD PRIMARY KEY (`id_reposo`),
  ADD KEY `fk_Reposo_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sueldos`
--
ALTER TABLE `sueldos`
  ADD PRIMARY KEY (`id_sueldos`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id_trabajador`),
  ADD KEY `fk_Trabajadores_Personas1_idx` (`id_persona`),
  ADD KEY `fk_trabajadores_nivel_educativo1_idx` (`id_nivel_educativo`),
  ADD KEY `fk_trabajadores_sueldos1_idx` (`id_sueldos`);

--
-- Indices de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  ADD PRIMARY KEY (`id_trabajador_area`),
  ADD KEY `fk_Trabajador_has_Area_Areas1_idx` (`id_area`),
  ADD KEY `fk_Trabajador_Area_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_Usuarios_Personas1_idx` (`id_persona`),
  ADD KEY `fk_Usuarios_Rol1_idx` (`id_rol`);

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
-- AUTO_INCREMENT de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignacion_pago`
--
ALTER TABLE `asignacion_pago`
  MODIFY `id_asignacion_pago` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `deduccion_pago`
--
ALTER TABLE `deduccion_pago`
  MODIFY `id_deduccion_pago` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `nivel_educativo`
--
ALTER TABLE `nivel_educativo`
  MODIFY `id_nivel_educativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  MODIFY `id_pagos_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos_trabajadpr`
--
ALTER TABLE `permisos_trabajadpr`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personas_asignacion`
--
ALTER TABLE `personas_asignacion`
  MODIFY `id_persona_asig_tiempo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas_deducciones`
--
ALTER TABLE `personas_deducciones`
  MODIFY `id_personas_dedudcciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reposo`
--
ALTER TABLE `reposo`
  MODIFY `id_reposo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `sueldos`
--
ALTER TABLE `sueldos`
  MODIFY `id_sueldos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  MODIFY `id_trabajador_area` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion_pago`
--
ALTER TABLE `asignacion_pago`
  ADD CONSTRAINT `fk_asignacion_pago_pagos_nomina1` FOREIGN KEY (`id_pagos_nomina`) REFERENCES `pagos_nomina` (`id_pagos_nomina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_asignacion_pago_personas_asignacion1` FOREIGN KEY (`id_persona_asig_tiempo`) REFERENCES `personas_asignacion` (`id_persona_asig_tiempo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `fk_Asistencias_Trabajador_Area1` FOREIGN KEY (`id_trabajador_area`) REFERENCES `trabajador_area` (`id_trabajador_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulos`),
  ADD CONSTRAINT `bitacora_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_persona`);

--
-- Filtros para la tabla `deduccion_pago`
--
ALTER TABLE `deduccion_pago`
  ADD CONSTRAINT `fk_deduccion_pago_pagos_nomina1` FOREIGN KEY (`id_pagos_nomina`) REFERENCES `pagos_nomina` (`id_pagos_nomina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deduccion_pago_personas_deducciones` FOREIGN KEY (`id_personas_deducciones`) REFERENCES `personas_deducciones` (`id_personas_dedudcciones`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD CONSTRAINT `fk_Liquidacion_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  ADD CONSTRAINT `fk_pagos_nomina_trabajadores1` FOREIGN KEY (`iid_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_Permisos_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Permisos_modulos1` FOREIGN KEY (`id_modulos`) REFERENCES `modulos` (`id_modulos`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos_trabajadpr`
--
ALTER TABLE `permisos_trabajadpr`
  ADD CONSTRAINT `fk_Permisos_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `personas_asignacion`
--
ALTER TABLE `personas_asignacion`
  ADD CONSTRAINT `fk_personas_asignacion_tiempo_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_personas_has_asignacion_tiempo_asignacion_tiempo1` FOREIGN KEY (`id_asignacion_tiempo`) REFERENCES `asignacion` (`id_asignacion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `personas_deducciones`
--
ALTER TABLE `personas_deducciones`
  ADD CONSTRAINT `fk_personas_deducciones_deducciones1` FOREIGN KEY (`id_deducciones`) REFERENCES `deducciones` (`id_deducciones`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_personas_deducciones_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD CONSTRAINT `fk_Reposo_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD CONSTRAINT `fk_Trabajadores_Personas1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajadores_nivel_educativo1` FOREIGN KEY (`id_nivel_educativo`) REFERENCES `nivel_educativo` (`id_nivel_educativo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajadores_sueldos1` FOREIGN KEY (`id_sueldos`) REFERENCES `sueldos` (`id_sueldos`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  ADD CONSTRAINT `fk_Trabajador_Area_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trabajador_has_Area_Areas1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuarios_Personas1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Usuarios_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `fk_Vacaciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
