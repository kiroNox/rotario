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
			}else if ($accion == "modificar_vacaciones") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->modificar_vacaciones(
						$_POST["desde"],
						$_POST["hasta"],
						$_POST["dias_totales"],
						$_POST["descripcion"],
						$_POST["id_tabla"]
						
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			} else if($accion == "registrar_trabajador"){
				if(isset($permisos["usuarios"]["crear"]) and $permisos["usuarios"]["crear"] == "1"){
					$resp = $cl->registrar_trabajador(
						$_POST["cedula"],
						$_POST["nombre"],
						$_POST["apellido"],
						$_POST["telefono"],
						$_POST["correo"],
						$_POST["numero_cuenta"],
						$_POST["fecha_nacimiento"],
						$_POST["sexo"],
						$_POST["instruccion"],
						$_POST["salario"]
					);
					echo json_encode($resp);
				}
				else{
					$cl->no_permision_msg();
				}

			}elseif ($accion == "registrar_reposo") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_reposo(
						$_POST["id"],
						$_POST["tipo_reposo"],
						$_POST["descripcion_reposo"],
						$_POST["fecha_inicio_reposo"],
						$_POST["fecha_reincorporacion_reposo"]
						
						
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			} elseif ($accion == "registrar_permiso") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_permiso(
						$_POST["id"],
						$_POST["tipo_de_permiso"],
						$_POST["descripcion_permiso"],
						$_POST["fecha_inicio_permiso"],
						$_POST["fecha_reincorporacion_permiso"]
						
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
			elseif ($accion == "listar_vacaciones") {
				if ($permisos["usuarios"]["consultar"]) {
					echo json_encode($cl->listar_vacaciones());
				}
			}
			elseif ($accion == "listar_reposos") {
				if ($permisos["usuarios"]["consultar"]) {
					echo json_encode($cl->listar_reposos());
				}
			}
			elseif ($accion == "listar_permisos") {
				if ($permisos["usuarios"]["consultar"]) {
					echo json_encode($cl->listar_permisos());
				}
			}
			elseif ($accion == "detalles_vacaciones") {
				$id_trabajador = $_POST['id'];
				echo json_encode($cl->obtener_detalles_vacaciones($id_trabajador));
			}
	
			$cl->set_con(null);
			exit;
		}
	
		$cl->set_con(null);
		Bitacora::ingreso_modulo("Areas");
		require_once("vista/" . $pagina . ".php");
	} else {
		require_once("vista/404.php");
	}
	
?>