<?php
	if (is_file("vista/" . $pagina . ".php")) {

		$claseAreas = new Areas;
	
		if (!empty($_POST)) { // Si hay alguna consulta tipo POST
			$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar
	
			
			if ($accion == "registrar_areas") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$respuesta = $claseAreas->registrar(
						$_POST["descripcion"],
						
					);
					echo json_encode($respuesta);
				} else {
					$claseAreas->no_permision_msg();
				}
			} elseif ($accion == "registrar_reposo") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $claseAreas->registrar_reposo(
						$_POST["tipo_reposo"],
						$_POST["descripcion_reposo"],
						$_POST["fecha_inicio_reposo"],
						$_POST["fecha_reincorporacion_reposo"],
						$_POST["id"]
					);
					echo json_encode($resp);
				} else {
					$claseAreas->no_permision_msg();
				}
			} elseif ($accion == "registrar_permiso") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $claseAreas->registrar_permiso(
						$_POST["tipo_permiso"],
						$_POST["descripcion_permiso"],
						$_POST["fecha_inicio_permiso"],
						$_POST["fecha_reincorporacion_permiso"],
						$_POST["id"]
					);
					echo json_encode($resp);
				} else {
					$claseAreas->no_permision_msg();
				}
			} elseif ($accion == "listar") {
				if ($permisos["usuarios"]["consultar"]) {
					echo json_encode($claseAreas->listar_usuarios());
				}
			}
	
			$claseAreas->set_con(null);
			exit;
		}
	
		$claseAreas->set_con(null);
		Bitacora::ingreso_modulo(2);
		require_once("vista/" . $pagina . ".php");
	} else {
		require_once("vista/404.php");
	}
	
?>