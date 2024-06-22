CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `asignacion` (
  `id_asignacion` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL,
  `formula` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_asignacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `asignacion_pago` (
  `id_asignacion_pago` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona_asig_tiempo` int(11) NOT NULL,
  `id_pagos_nomina` int(11) NOT NULL,
  PRIMARY KEY (`id_asignacion_pago`),
  KEY `fk_asignacion_pago_personas_asignacion1_idx` (`id_persona_asig_tiempo`),
  KEY `fk_asignacion_pago_pagos_nomina1_idx` (`id_pagos_nomina`),
  CONSTRAINT `fk_asignacion_pago_pagos_nomina1` FOREIGN KEY (`id_pagos_nomina`) REFERENCES `pagos_nomina` (`id_pagos_nomina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_asignacion_pago_personas_asignacion1` FOREIGN KEY (`id_persona_asig_tiempo`) REFERENCES `personas_asignacion` (`id_persona_asig_tiempo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador_area` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('asistente','inasistente') DEFAULT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `fk_Asistencias_Trabajador_Area1_idx` (`id_trabajador_area`),
  CONSTRAINT `fk_Asistencias_Trabajador_Area1` FOREIGN KEY (`id_trabajador_area`) REFERENCES `trabajador_area` (`id_trabajador_area`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `bitacora` (
  `id_usuario` int(11) NOT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  `descripcion` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  KEY `fk_Bitacora_Usuarios1_idx` (`id_usuario`),
  KEY `id_modulo` (`id_modulo`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulos`),
  CONSTRAINT `bitacora_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `bitacora` VALUES('1', '', '2024-06-12 17:15:35', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:15:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:17:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:40:51', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:49:19', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:54:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:54:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:54:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:54:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:54:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:55:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:57:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:58:33', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 17:58:35', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:00:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:01:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:02:42', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:04:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:06:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:06:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:06:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:08:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:08:46', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:10:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:12:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:13:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:24:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:25:48', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:33:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:34:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:41:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:41:32', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:43:20', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:52:54', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:54:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:54:33', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:54:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 18:55:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:25:27', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:26:06', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:26:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:40:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:40:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:40:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:40:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:52:42', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:56:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 19:59:46', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:00:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:00:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:00:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:00:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:00:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:03:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:05:01', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:05:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:06:10', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:07:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:08:35', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:11:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:12:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:13:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:13:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:14:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:14:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:15:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:26:51', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:27:18', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:37:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:38:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:38:54', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:41:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 20:46:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 21:00:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 21:02:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 21:48:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 21:53:33', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:01:24', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:03:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:04:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:11:32', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:11:37', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:14:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:20:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:22:12', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:22:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:29:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:44:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:45:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:45:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:46:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:48:05', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:49:32', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:50:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:50:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:50:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:53:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:55:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 22:57:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:00:15', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:01:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:11:10', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:21:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:21:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:21:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:24:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:24:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:25:10', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:27:15', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:36:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:37:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:40:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:44:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:48:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:50:39', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:53:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:57:01', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-12 23:59:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:03:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:04:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:04:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:05:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:07:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:08:01', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:10:24', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:16:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:33:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:34:48', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:41:20', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:43:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:45:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:51:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:52:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:54:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 00:58:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:09:52', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:10:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:10:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:10:20', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:10:27', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:10:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:10:52', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:16:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:17:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:28:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:29:05', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:30:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:31:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:32:18', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:32:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:33:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:33:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:33:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:34:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:35:01', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:36:36', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:36:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:38:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:40:15', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:40:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:43:35', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:48:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:53:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 01:54:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:00:34', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:01:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:05:37', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:10:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:11:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:11:39', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:13:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:20:19', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:31:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:31:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:31:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:33:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:34:20', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:35:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:37:10', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:37:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:37:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:42:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:42:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:43:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:44:35', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:44:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:50:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:51:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:51:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:52:06', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:52:37', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:52:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:55:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:55:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:56:39', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 02:57:19', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:00:46', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:00:48', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:06:54', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:07:18', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:08:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:11:48', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:15:36', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:15:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:22:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:22:46', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:23:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:31:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:32:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:33:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:34:33', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:35:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:35:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:36:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:36:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:37:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:38:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:38:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:39:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:40:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:40:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:41:18', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:41:36', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:41:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:42:08', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:42:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:43:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:43:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:45:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:45:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:45:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:46:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:47:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:48:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:48:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:49:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:49:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:50:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 03:52:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:13:04', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:13:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:14:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:15:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:16:36', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:18:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:26:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:39:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:41:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:41:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:49:08', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:50:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:52:19', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:53:32', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:54:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:55:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:56:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:58:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 04:58:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:07:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:09:48', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:09:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:10:36', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:15:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:21:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:21:34', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:26:15', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:27:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:28:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-13 05:30:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '', '2024-06-15 23:18:23', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-15 23:18:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-15 23:20:51', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-15 23:21:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-15 23:21:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-15 23:22:32', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-16 01:00:05', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-16 01:01:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-16 01:08:05', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '', '2024-06-16 22:54:39', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-16 22:54:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '', '2024-06-19 14:55:41', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 14:55:42', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 15:04:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 15:04:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 15:05:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:02:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:13:04', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:13:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:14:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:14:24', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:25:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 16:28:01', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:07:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:14:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:14:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:16:51', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:17:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:48:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:48:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 17:49:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:20:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:27:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:34:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:34:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:35:12', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:36:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:36:24', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:41:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:41:32', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:41:48', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:42:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:46:34', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:47:06', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:48:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:48:46', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 18:49:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:10:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:14:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:15:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:15:39', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:16:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:17:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:19:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:23:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:23:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:24:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:28:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:28:33', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:29:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 19:30:37', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:31:51', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:34:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:34:19', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:34:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:34:39', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:35:20', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:39:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:40:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:40:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:43:18', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:47:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:48:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-19 23:55:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:06:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:07:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:10:46', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:11:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:11:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:12:11', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:12:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:13:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:13:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:16:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:16:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:17:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:18:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:18:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:19:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:20:05', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:21:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:23:34', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:24:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:25:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:31:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:32:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:32:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:33:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:34:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:34:27', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:34:27', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:41:30', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:42:54', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:43:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:44:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:45:04', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:45:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:46:10', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:46:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:46:54', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 00:48:08', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:00:12', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:01:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:01:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:19:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:32:12', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:32:45', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:40:22', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:41:39', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:46:52', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:51:43', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:52:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:52:34', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:53:01', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:53:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:53:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:57:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:58:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:58:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 01:59:06', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '', '2024-06-20 02:00:53', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 02:00:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 02:01:04', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '', '2024-06-20 17:21:52', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 17:21:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:10:51', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:11:02', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:16:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:16:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:17:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:17:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:17:34', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:18:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:19:21', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:20:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:24:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:28:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:30:12', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:40:52', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:41:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 18:56:56', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 19:01:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 19:05:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 19:07:09', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:37:16', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:41:40', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:43:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:43:59', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:46:12', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:46:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:51:23', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:51:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:53:10', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:53:36', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:54:00', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:59:05', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 21:59:57', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:20:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:29:54', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:37:03', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:39:13', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:43:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:52:14', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 22:59:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:00:06', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:02:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:15:44', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:19:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:19:28', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:19:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:20:26', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-20 23:58:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:00:07', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:00:47', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:00:49', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:01:08', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:01:29', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:01:53', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:02:25', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 00:02:58', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '', '2024-06-21 10:58:17', 'Inicio de sesión');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 10:58:17', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-21 10:58:24', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 13:33:55', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 13:56:31', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 14:28:37', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 14:30:38', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 14:56:41', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 14:56:50', 'Ingreso en el modulo');
INSERT INTO `bitacora` VALUES('1', '2', '2024-06-22 15:14:32', 'Ingreso en el modulo');


CREATE TABLE `calendario` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `fecha` date NOT NULL,
  `recurrente` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `calendario` VALUES('1', 'Hola wapo1', '2026-09-19', '0');
INSERT INTO `calendario` VALUES('2', 'Hola wwww', '0000-09-04', '0');
INSERT INTO `calendario` VALUES('3', 'Un dia como hoy mi cassas', '2024-05-09', '0');
INSERT INTO `calendario` VALUES('18', '9rywe89rhwe98hrwe', '2024-06-11', '0');
INSERT INTO `calendario` VALUES('19', 'Dia de la raza', '2024-06-07', '1');
INSERT INTO `calendario` VALUES('22', 'Dia de la raza', '2024-06-07', '1');
INSERT INTO `calendario` VALUES('25', '31312312', '2024-06-10', '0');
INSERT INTO `calendario` VALUES('26', 'Dia de la madre', '2024-05-10', '0');
INSERT INTO `calendario` VALUES('27', 'aass', '2025-06-09', '0');


CREATE TABLE `deduccion_pago` (
  `id_deduccion_pago` int(11) NOT NULL AUTO_INCREMENT,
  `id_personas_deducciones` int(11) NOT NULL,
  `id_pagos_nomina` int(11) NOT NULL,
  PRIMARY KEY (`id_deduccion_pago`),
  KEY `fk_deduccion_pago_personas_deducciones_idx` (`id_personas_deducciones`),
  KEY `fk_deduccion_pago_pagos_nomina1_idx` (`id_pagos_nomina`),
  CONSTRAINT `fk_deduccion_pago_pagos_nomina1` FOREIGN KEY (`id_pagos_nomina`) REFERENCES `pagos_nomina` (`id_pagos_nomina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_deduccion_pago_personas_deducciones` FOREIGN KEY (`id_personas_deducciones`) REFERENCES `personas_deducciones` (`id_personas_dedudcciones`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `deducciones` (
  `id_deducciones` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_deducciones`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `liquidacion` (
  `id_liquidacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador` int(11) NOT NULL,
  `monto` decimal(11,3) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id_liquidacion`),
  KEY `fk_Liquidacion_Trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_Liquidacion_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id_modulos`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `modulos` VALUES('1', 'inicio');
INSERT INTO `modulos` VALUES('2', 'usuarios');


CREATE TABLE `nivel_educativo` (
  `id_nivel_educativo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `monto` decimal(11,3) NOT NULL,
  `porcentaje` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_nivel_educativo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `nivel_educativo` VALUES('1', 'Nivel 1', '1212.000', '21');
INSERT INTO `nivel_educativo` VALUES('2', 'Nivel 1', '1212.000', '21');


CREATE TABLE `pagos_nomina` (
  `id_pagos_nomina` int(11) NOT NULL AUTO_INCREMENT,
  `iid_trabajador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `sueldo_integral` decimal(12,2) NOT NULL,
  `sueldo_total` decimal(12,2) NOT NULL,
  `deducciones_total` decimal(12,2) NOT NULL,
  `sueldo_base` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id_pagos_nomina`),
  KEY `fk_pagos_nomina_trabajadores1_idx` (`iid_trabajador`),
  CONSTRAINT `fk_pagos_nomina_trabajadores1` FOREIGN KEY (`iid_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `permisos` (
  `id_rol` int(11) NOT NULL,
  `id_modulos` int(11) NOT NULL,
  `crear` tinyint(1) NOT NULL,
  `modificar` tinyint(1) NOT NULL,
  `eliminar` tinyint(1) NOT NULL,
  `consultar` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_rol`,`id_modulos`),
  KEY `fk_Permisos_Rol1_idx` (`id_rol`),
  KEY `fk_Permisos_modulos1_idx` (`id_modulos`),
  CONSTRAINT `fk_Permisos_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Permisos_modulos1` FOREIGN KEY (`id_modulos`) REFERENCES `modulos` (`id_modulos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `permisos` VALUES('1', '1', '1', '1', '1', '1');
INSERT INTO `permisos` VALUES('1', '2', '1', '1', '1', '1');


CREATE TABLE `permisos_trabajador` (
  `id_permisos` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador` int(11) NOT NULL,
  `tipo_de_permiso` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  PRIMARY KEY (`id_permisos`),
  KEY `fk_Permisos_trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_Permisos_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `permisos_trabajador` VALUES('1', '2', '1212-12-12', '0121-12-12', '2012-12-11', '0000-00-00');
INSERT INTO `permisos_trabajador` VALUES('2', '2', '275760-06-07', '275760-09-08', '0000-00-00', '0000-00-00');
INSERT INTO `permisos_trabajador` VALUES('3', '2', '0333-03-31', '0444-04-04', '0000-00-00', '0000-00-00');
INSERT INTO `permisos_trabajador` VALUES('5', '2', '43434-03-31', '56566-06-05', '0000-00-00', '0000-00-00');
INSERT INTO `permisos_trabajador` VALUES('8', '2', 'fgfgfg', 'fgfgvccvcb', '6678-05-31', '8776-07-08');
INSERT INTO `permisos_trabajador` VALUES('9', '2', '434234', '435345', '3453-05-31', '0032-04-02');


CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(12) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `liquidacion` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `personas` VALUES('1', 'V-27250544', 'Xavier David', 'Suarez Sanchez', '0414-5555555', 'uptaebxavier@gmail.com', '0');
INSERT INTO `personas` VALUES('8', 'V-2725054', 'jose pere', '312312', '04161557313', 'asdas@fkamfas.cpa', '0');
INSERT INTO `personas` VALUES('9', 'V-2725345', 'Jaimito Comunica', 'Akis', '04160376905', 'uptaebxavier@gmail.com', '0');


CREATE TABLE `personas_asignacion` (
  `id_persona_asig_tiempo` int(11) NOT NULL AUTO_INCREMENT,
  `id_asignacion_tiempo` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  PRIMARY KEY (`id_persona_asig_tiempo`),
  KEY `fk_personas_has_asignacion_tiempo_asignacion_tiempo1_idx` (`id_asignacion_tiempo`),
  KEY `fk_personas_asignacion_tiempo_trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_personas_asignacion_tiempo_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_personas_has_asignacion_tiempo_asignacion_tiempo1` FOREIGN KEY (`id_asignacion_tiempo`) REFERENCES `asignacion` (`id_asignacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `personas_deducciones` (
  `id_personas_dedudcciones` int(11) NOT NULL AUTO_INCREMENT,
  `id_deducciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  PRIMARY KEY (`id_personas_dedudcciones`),
  KEY `fk_personas_deducciones_deducciones1_idx` (`id_deducciones`),
  KEY `fk_personas_deducciones_trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_personas_deducciones_deducciones1` FOREIGN KEY (`id_deducciones`) REFERENCES `deducciones` (`id_deducciones`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_personas_deducciones_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `reposo` (
  `id_reposo` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador` int(11) NOT NULL,
  `tipo_reposo` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  PRIMARY KEY (`id_reposo`),
  KEY `fk_Reposo_Trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_Reposo_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `reposo` VALUES('1', '2', '0012-12-12', '0012-12-12', '0000-00-00', '0000-00-00');
INSERT INTO `reposo` VALUES('3', '2', '6567-05-04', '6767-07-08', '0000-00-00', '0000-00-00');
INSERT INTO `reposo` VALUES('4', '2', '23www', '434rrrr', '0342-02-02', '0043-04-22');
INSERT INTO `reposo` VALUES('5', '2', 'Reposo por parto', 'Tuvo un hijo o algo asi', '2024-06-15', '2024-06-26');


CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `roles` VALUES('1', 'Administrador');


CREATE TABLE `sueldos` (
  `id_sueldos` int(11) NOT NULL AUTO_INCREMENT,
  `monto` decimal(11,3) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `nombre_sueldo` varchar(45) NOT NULL,
  PRIMARY KEY (`id_sueldos`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `sueldos` VALUES('1', '12123.000', 'Nomina', 'Sueldo1');
INSERT INTO `sueldos` VALUES('2', '12123.000', 'Nomina', 'Sueldo1');


CREATE TABLE `trabajador_area` (
  `id_trabajador_area` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  PRIMARY KEY (`id_trabajador_area`),
  KEY `fk_Trabajador_has_Area_Areas1_idx` (`id_area`),
  KEY `fk_Trabajador_Area_Trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_Trabajador_Area_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Trabajador_has_Area_Areas1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_nivel_educativo` int(11) NOT NULL,
  `numero_cuenta` varchar(45) NOT NULL,
  `sexo` text NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `creado` date NOT NULL,
  `id_sueldos` int(11) NOT NULL,
  PRIMARY KEY (`id_trabajador`),
  KEY `fk_Trabajadores_Personas1_idx` (`id_persona`),
  KEY `fk_trabajadores_nivel_educativo1_idx` (`id_nivel_educativo`),
  KEY `fk_trabajadores_sueldos1_idx` (`id_sueldos`),
  CONSTRAINT `fk_Trabajadores_Personas1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_trabajadores_nivel_educativo1` FOREIGN KEY (`id_nivel_educativo`) REFERENCES `nivel_educativo` (`id_nivel_educativo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_trabajadores_sueldos1` FOREIGN KEY (`id_sueldos`) REFERENCES `sueldos` (`id_sueldos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `trabajadores` VALUES('2', '1', '1', '38972196312683123', '', '', '2024-06-10', '1');
INSERT INTO `trabajadores` VALUES('5', '8', '1', '2132132121212', 'M', '2024-06-05', '0000-00-00', '1');
INSERT INTO `trabajadores` VALUES('6', '9', '1', '2132132121212232', 'M', '2024-06-11', '0000-00-00', '2');


CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_Usuarios_Personas1_idx` (`id_persona`),
  KEY `fk_Usuarios_Rol1_idx` (`id_rol`),
  CONSTRAINT `fk_Usuarios_Personas1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Usuarios_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES('2', '1', '1', '$2y$10$T2pA0Ie3aXtjmUoecSo1C.R6A94Y74A3NX9oe0lEaX8WWJjSTQ6/a', '$2y$10$sCcog2TZfpXImaCsNRUd8OS0uBfof7QIWO37f9fPzwO2DxGe6TyOK');


CREATE TABLE `vacaciones` (
  `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `dias_totales` int(99) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date DEFAULT NULL,
  PRIMARY KEY (`id_vacaciones`),
  KEY `fk_Vacaciones_Trabajadores1_idx` (`id_trabajador`),
  CONSTRAINT `fk_Vacaciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `vacaciones` VALUES('2', '2', '1212', '1212', '0012-12-12', '0222-12-12');
INSERT INTO `vacaciones` VALUES('3', '2', '21212', '1212', '0323-12-12', '0000-00-00');
INSERT INTO `vacaciones` VALUES('4', '2', '12312', '3213', '0000-00-00', '0312-12-23');
INSERT INTO `vacaciones` VALUES('13', '2', '12121', '2212', '0012-12-12', '0032-02-23');
INSERT INTO `vacaciones` VALUES('14', '2', '324', '1223', '0444-04-02', '0344-04-05');
INSERT INTO `vacaciones` VALUES('15', '2', 'Vacaciones de fin de año que se yo', '18', '2024-06-14', '2024-06-28');
INSERT INTO `vacaciones` VALUES('16', '2', 'Hola1212', '0', '0423-03-23', '0543-04-02');


