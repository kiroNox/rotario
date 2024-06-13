<?php
	if (is_file("vista/" . $pagina . ".php")) {

		$cl = new administrar_empleados;
	
		if (!empty($_POST)) { // Si hay alguna consulta tipo POST
			$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar
	
			
			if ($accion == "registrar_vacaciones") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_vacaciones(
						$_POST["desde"],
						$_POST["hasta"],
						$_POST["dias_totales"],
						$_POST["descripcion"],
						$_POST["id"]
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			} elseif ($accion == "registrar_reposo") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_reposo(
						$_POST["tipo_reposo"],
						$_POST["descripcion_reposo"],
						$_POST["fecha_inicio_reposo"],
						$_POST["fecha_reincorporacion_reposo"],
						$_POST["id"]
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			} elseif ($accion == "registrar_permiso") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_permiso(
						$_POST["tipo_permiso"],
						$_POST["descripcion_permiso"],
						$_POST["fecha_inicio_permiso"],
						$_POST["fecha_reincorporacion_permiso"],
						$_POST["id"]
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			} elseif ($accion == "listar") {
				if ($permisos["usuarios"]["consultar"]) {
					echo json_encode($cl->listar_usuarios());
				}
			}
	
			$cl->set_con(null);
			exit;
		}
	
		$cl->set_con(null);
		Bitacora::ingreso_modulo(2);
		require_once("vista/" . $pagina . ".php");
	} else {
		require_once("vista/404.php");
	}
	
?>