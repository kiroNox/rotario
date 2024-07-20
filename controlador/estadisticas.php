<?php
	if (is_file("vista/" . $pagina . ".php")) {
		$cl = new estadisticas;
	
		if (!empty($_POST)) {
			$accion = $_POST["accion"];
			if ($accion == "obtener_vacaciones_y_reposos_por_rango_fechas") {
				$fecha_inicio = $_POST['fecha_inicio'];
				$fecha_fin = $_POST['fecha_fin'];
				$datos = $cl->obtenerVacacionesYRepososPorRangoFechas($fecha_inicio, $fecha_fin);
				echo json_encode($datos);
			} elseif ($accion == "obtener_niveles_educativos") {
				$datos = $cl->obtenerNivelesEducativos();
				echo json_encode($datos);
			} elseif ($accion == "obtener_fecha_minima_vacaciones") {
				$fecha_minima = $cl->obtenerFechaMinimaVacaciones();
				echo json_encode(['fecha_minima' => $fecha_minima]);
			} elseif ($accion == "obtener_fecha_maxima_vacaciones") {
				$fecha_maxima = $cl->obtenerFechaMaximaVacaciones();
				echo json_encode(['fecha_maxima' => $fecha_maxima]);
			}
	
			$cl->set_con(null);
			Bitacora::ingreso_modulo("Estadistica");
			exit;
		}
	
		require_once("vista/" . $pagina . ".php");
	} else {
		require_once("vista/404.php");
	}
	
	
	
?>