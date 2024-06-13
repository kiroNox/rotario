-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2024 a las 12:15:52
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
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `id_asignacion` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL,
  `formula` varchar(45) DEFAULT NULL
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
  `estado` enum('asistente','inasistente') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_usuario` int(11) NOT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  `descripcion` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_usuario`, `id_modulo`, `fecha`, `descripcion`) VALUES
(1, NULL, '2024-06-12 21:15:35', 'Inicio de sesión'),
(1, 2, '2024-06-12 21:15:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:17:55', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:40:51', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:49:19', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:54:28', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:54:40', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:54:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:54:56', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:54:57', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:55:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:57:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:58:33', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 21:58:35', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:00:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:01:25', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:02:42', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:04:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:06:09', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:06:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:06:28', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:08:41', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:08:46', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:10:26', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:12:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:13:07', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:24:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:25:48', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:33:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:34:03', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:41:26', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:41:32', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:43:20', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:52:54', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:54:23', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:54:33', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:54:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 22:55:07', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:25:27', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:26:06', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:26:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:40:41', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:40:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:40:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:40:57', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:52:42', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:56:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-12 23:59:46', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:00:00', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:00:26', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:00:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:00:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:00:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:03:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:05:01', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:05:57', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:06:10', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:07:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:08:35', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:11:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:12:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:13:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:13:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:14:23', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:14:56', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:15:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:26:51', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:27:18', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:37:45', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:38:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:38:54', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:41:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 00:46:03', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 01:00:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 01:02:40', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 01:48:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 01:53:33', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:01:24', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:03:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:04:40', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:11:32', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:11:37', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:14:02', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:20:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:22:12', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:22:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:29:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:44:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:45:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:45:56', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:46:25', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:48:05', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:49:32', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:50:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:50:28', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:50:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:53:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:55:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 02:57:29', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:00:15', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:01:55', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:11:10', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:21:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:21:25', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:21:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:24:44', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:24:56', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:25:10', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:27:15', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:36:43', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:37:23', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:40:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:44:45', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:48:23', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:50:39', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:53:11', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:57:01', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 03:59:11', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:03:00', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:04:02', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:04:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:05:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:07:23', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:08:01', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:10:24', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:16:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:33:09', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:34:48', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:41:20', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:43:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:45:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:51:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:52:31', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:54:44', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 04:58:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:09:52', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:10:00', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:10:13', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:10:20', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:10:27', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:10:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:10:52', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:16:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:17:07', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:28:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:29:05', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:30:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:31:29', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:32:18', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:32:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:33:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:33:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:33:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:34:00', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:35:01', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:36:36', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:36:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:38:22', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:40:15', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:40:56', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:43:35', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:48:55', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:53:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 05:54:25', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:00:34', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:01:44', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:05:37', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:10:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:11:09', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:11:39', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:13:07', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:20:19', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:31:29', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:31:43', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:31:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:33:26', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:34:20', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:35:09', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:37:10', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:37:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:37:26', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:42:03', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:42:41', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:43:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:44:35', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:44:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:50:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:51:03', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:51:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:52:06', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:52:37', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:52:57', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:55:40', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:55:57', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:56:39', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 06:57:19', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:00:46', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:00:48', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:06:54', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:07:18', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:08:43', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:11:48', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:15:36', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:15:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:22:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:22:46', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:23:13', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:31:43', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:32:03', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:33:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:34:33', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:35:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:35:31', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:36:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:36:55', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:37:53', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:38:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:38:44', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:39:09', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:40:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:40:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:41:18', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:41:36', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:41:59', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:42:08', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:42:26', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:43:30', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:43:49', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:45:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:45:31', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:45:59', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:46:43', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:47:09', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:48:16', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:48:59', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:49:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:49:57', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:50:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 07:52:31', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:13:04', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:13:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:14:59', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:15:41', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:16:36', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:18:28', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:26:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:39:47', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:41:11', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:41:38', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:49:08', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:50:21', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:52:19', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:53:32', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:54:45', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:55:29', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:56:41', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:58:03', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 08:58:31', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:07:50', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:09:48', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:09:58', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:10:36', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:15:17', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:21:07', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:21:34', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:26:15', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:27:11', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:28:14', 'Ingreso en el modulo'),
(1, 2, '2024-06-13 09:30:43', 'Ingreso en el modulo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deducciones`
--

CREATE TABLE `deducciones` (
  `id_deducciones` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
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
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulos`, `nombre`) VALUES
(1, 'inicio'),
(2, 'usuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_educativo`
--

CREATE TABLE `nivel_educativo` (
  `id_nivel_educativo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `monto` decimal(11,3) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `nivel_educativo`
--

INSERT INTO `nivel_educativo` (`id_nivel_educativo`, `descripcion`, `monto`, `porcentaje`) VALUES
(1, 'Nivel 1', 1212.000, 21),
(2, 'Nivel 1', 1212.000, 21);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `id_modulos`, `crear`, `modificar`, `eliminar`, `consultar`) VALUES
(1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_trabajador`
--

CREATE TABLE `permisos_trabajador` (
  `id_permisos` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_de_permiso` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `permisos_trabajador`
--

INSERT INTO `permisos_trabajador` (`id_permisos`, `id_trabajador`, `tipo_de_permiso`, `descripcion`, `desde`, `hasta`) VALUES
(1, 2, '1212-12-12', '0121-12-12', '2012-12-11', '0000-00-00'),
(2, 2, '275760-06-07', '275760-09-08', '0000-00-00', '0000-00-00'),
(3, 2, '0333-03-31', '0444-04-04', '0000-00-00', '0000-00-00'),
(5, 2, '43434-03-31', '56566-06-05', '0000-00-00', '0000-00-00'),
(8, 2, 'fgfgfg', 'fgfgvccvcb', '6678-05-31', '8776-07-08'),
(9, 2, '434234', '435345', '3453-05-31', '0032-04-02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `liquidacion` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `liquidacion`) VALUES
(1, 'V-27250544', 'Xavier David', 'Suarez Sanchez', '0414-5555555', 'uptaebxavier@gmail.com', 0),
(8, 'V-2725054', 'jose pere', '312312', '04161557313', 'asdas@fkamfas.cpa', 0),
(9, 'V-2725345', 'Jaimito Comunica', 'Akis', '04160376905', 'uptaebxavier@gmail.com', 0);

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
  `tipo_reposo` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `reposo`
--

INSERT INTO `reposo` (`id_reposo`, `id_trabajador`, `tipo_reposo`, `descripcion`, `desde`, `hasta`) VALUES
(1, 2, '0012-12-12', '0012-12-12', '0000-00-00', '0000-00-00'),
(3, 2, '6567-05-04', '6767-07-08', '0000-00-00', '0000-00-00'),
(4, 2, '23www', '434rrrr', '0342-02-02', '0043-04-22'),
(5, 2, 'Reposo por parto', 'Tuvo un hijo o algo asi', '2024-06-15', '2024-06-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `descripcion`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sueldos`
--

CREATE TABLE `sueldos` (
  `id_sueldos` int(11) NOT NULL,
  `monto` decimal(11,3) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `nombre_sueldo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sueldos`
--

INSERT INTO `sueldos` (`id_sueldos`, `monto`, `descripcion`, `nombre_sueldo`) VALUES
(1, 12123.000, 'Nomina', 'Sueldo1'),
(2, 12123.000, 'Nomina', 'Sueldo1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_nivel_educativo` int(11) NOT NULL,
  `numero_cuenta` varchar(45) NOT NULL,
  `sexo` text NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `creado` date NOT NULL,
  `id_sueldos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`id_trabajador`, `id_persona`, `id_nivel_educativo`, `numero_cuenta`, `sexo`, `fecha_nacimiento`, `creado`, `id_sueldos`) VALUES
(2, 1, 1, '38972196312683123', '', NULL, '2024-06-10', 1),
(5, 8, 1, '2132132121212', 'M', '2024-06-05', '0000-00-00', 1),
(6, 9, 1, '2132132121212232', 'M', '2024-06-11', '0000-00-00', 2);

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
  `clave` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_persona`, `id_rol`, `clave`, `token`) VALUES
(2, 1, 1, '$2y$10$T2pA0Ie3aXtjmUoecSo1C.R6A94Y74A3NX9oe0lEaX8WWJjSTQ6/a', '$2y$10$9YnQYQjoSHI4fxj1XHNMtOf45q9f4WVvtkH1EKtOX3EhteRs3.wL2');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vacaciones`
--

INSERT INTO `vacaciones` (`id_vacaciones`, `id_trabajador`, `descripcion`, `dias_totales`, `desde`, `hasta`) VALUES
(2, 2, '1212', 1212, '0012-12-12', '0222-12-12'),
(3, 2, '21212', 1212, '0323-12-12', '0000-00-00'),
(4, 2, '12312', 3213, '0000-00-00', '0312-12-23'),
(13, 2, '12121', 2212, '0012-12-12', '0032-02-23'),
(14, 2, '324', 1223, '0444-04-02', '0344-04-05'),
(15, 2, 'Vacaciones de fin de año que se yo', 18, '2024-06-14', '2024-06-28'),
(16, 2, 'Hola1212', 0, '0423-03-23', '0543-04-02');

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
-- Indices de la tabla `permisos_trabajador`
--
ALTER TABLE `permisos_trabajador`
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
  MODIFY `id_modulos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `nivel_educativo`
--
ALTER TABLE `nivel_educativo`
  MODIFY `id_nivel_educativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  MODIFY `id_pagos_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos_trabajador`
--
ALTER TABLE `permisos_trabajador`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id_reposo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sueldos`
--
ALTER TABLE `sueldos`
  MODIFY `id_sueldos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  MODIFY `id_trabajador_area` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
-- Filtros para la tabla `permisos_trabajador`
--
ALTER TABLE `permisos_trabajador`
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
