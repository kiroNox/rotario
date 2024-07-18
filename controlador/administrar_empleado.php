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
			} elseif ($accion == "calculo_habil") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$valor1 = $_POST["desde"];
					$diasTotales = (int)$_POST["dias_totales"];
			
					// Suponiendo que $cl->dias_habiles() devuelve las fechas no hábiles en el formato mencionado
					$resp = $cl->dias_habiles();
					$noHabiles = array_map(function($dateArray) {
						return $dateArray[0];
					}, $resp['mensaje']);
			
					$fechaInicial = new DateTime($valor1);
					$diasContados = 0;
			
					while ($diasContados < $diasTotales) {
						$fechaInicial->modify('+1 day');
						$diaSemana = $fechaInicial->format('N');
			
						// Excluir sábados (6), domingos (7) y días no hábiles
						if ($diaSemana < 6 && !in_array($fechaInicial->format('Y-m-d'), $noHabiles)) {
							$diasContados++;
						}
					}
			
					$fechaFinal = $fechaInicial->format('Y-m-d');
					echo json_encode([
						"resultado" => "fecha_calculada",
						"titulo" => "Éxito",
						"mensaje" => "La fecha final calculada es: $fechaFinal",
						"fecha_final" => $fechaFinal
					]);
				} else {
					$cl->no_permision_msg();
				}
			} elseif ($accion == "registrar_reposo") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_reposo(
						$_POST["id"],
						$_POST["tipo_reposo"],
						$_POST["descripcion_reposo"],
						$_POST["fecha_inicio_reposo"],
						$_POST["fecha_reincorporacion_reposo"],
						$_POST["dias_totales_repo"]
						
						
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			}else if ($accion == "modificar_reposo") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->modificar_reposo(
						$_POST["fecha_inicio_reposo"],
						$_POST["fecha_reincorporacion_reposo"],
						$_POST["dias_totales_repo"],
						$_POST["tipo_reposo"],
						$_POST["descripcion_reposo"],
						$_POST["id_tabla2"]
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			}  elseif ($accion == "registrar_permiso") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_permiso(
						$_POST["id"],
						$_POST["tipo_de_permiso"],
						$_POST["descripcion_permiso"],
						$_POST["fecha_inicio_permiso"]
						
					);
					echo json_encode($resp);
				} else {
					$cl->no_permision_msg();
				}
			}else if ($accion == "modificar_permiso") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->modificar_permiso(
						$_POST["tipo_de_permiso"],
						$_POST["descripcion_permiso"],
						$_POST["fecha_inicio_permiso"],
						$_POST["id_tabla3"]
						
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
			elseif ($accion == "detalles_reposos") {
				$id_trabajador = $_POST['id'];
				echo json_encode($cl->obtener_detalles_reposo($id_trabajador));
			}
			elseif ($accion == "detalles_permisos") {
				$id_trabajador = $_POST['id'];
				echo json_encode($cl->obtener_detalles_permisos($id_trabajador));
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