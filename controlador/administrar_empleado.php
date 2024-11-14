<?php

require_once 'vendor/autoload.php';
use Dompdf\Dompdf;

	if (is_file("vista/" . $pagina . ".php")) {

		$cl = new administrar_empleados;

		function generar_reporte_vacaciones_anual($cl,$year) {
			
			
			
			$datos = $cl->obtener_vacaciones_anuales($year);
			
			// Calcular el total de empleados en el año
			$totalEmpleados = array_sum(array_column($datos, 'total_empleados'));
			
			// Generar HTML para el reporte
			$html = '<h1>Reporte Anual de Vacaciones - ' . $year . '</h1>';
			$html .= '<table border="1" cellspacing="0" cellpadding="5">
						<thead>
							<tr>
								<th>Mes</th>
								<th>Total Empleados</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>';
		
			foreach ($datos as $dato) {
				$mes = DateTime::createFromFormat('!m', $dato['mes'])->format('F');
				$totalEmpleadosMes = $dato['total_empleados'];
				$porcentaje = ($totalEmpleadosMes / $totalEmpleados) * 100;
		
				$html .= '<tr>
							<td>' . $mes . '</td>
							<td>' . $totalEmpleadosMes . '</td>
							<td>' . number_format($porcentaje, 2) . '%</td>
						  </tr>';
			}
		
			$html .= '  </tbody>
					  </table>';
			
					  $dompdf = new Dompdf\Dompdf();
                
					  // Definimos el tamaño y orientación del papel que queremos.
					  $dompdf->set_paper("A4", "portrait");
					  
					  // Cargamos el contenido HTML.
					  $dompdf->load_html($html);
			$dompdf->render();
			$pdfOutput = $dompdf->output();
                $base64Data = base64_encode($pdfOutput);

                // Imprimir la cadena base64 para que JavaScript pueda capturarla
                echo $base64Data;
		}
	
		if (!empty($_POST)) { // Si hay alguna consulta tipo POST
			$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar
	
			
			if ($accion == "registrar_vacaciones") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $cl->registrar_vacaciones(
						$_POST["desde"],
						$_POST["hasta"],
						$_POST["dias_totales"],
						descripcion: $_POST["descripcion"],
						id: $_POST["id"]
						
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
			} elseif ($_POST['accion'] === 'generar_reporte_vacaciones_anual') {
				$year = intval("2024");
				generar_reporte_vacaciones_anual($cl, $year);
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