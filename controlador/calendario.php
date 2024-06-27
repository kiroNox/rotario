<?php
	if (is_file("vista/" . $pagina . ".php")) {

		$calendario_OO = new calendario;
	
		if (!empty($_POST)) { // Si hay alguna consulta tipo POST
			$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar
	
			
			if ($accion == "agregar_dia") {
				if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
					$resp = $calendario_OO->agregar_dia(
						$_POST["descripcion"],
						$_POST["fecha"],
						isset($_POST["recurrente"]) ? $_POST["recurrente"] : 0
						
					);
					echo json_encode($resp);
				} else {
					$calendario_OO->no_permision_msg();
				}
				
			} 
			elseif ($accion == "eliminar_dia") {
                if (isset($permisos["usuarios"]["eliminar"]) && $permisos["usuarios"]["eliminar"] == "1") {
                    $resp = $calendario_OO->eliminar_dia($_POST["fecha"]);
                    echo json_encode($resp);
                } else {
                    $calendario_OO->no_permision_msg();
                }

            }
			elseif ($accion == "modificar_dia") {
				if (isset($permisos["usuarios"]["modificar"]) && $permisos["usuarios"]["modificar"] == "1") {
					$resp = $calendario_OO->modificar_dia(
						$_POST["descripcion"],
						$_POST["fecha"],
						isset($_POST["recurrente"]) ? $_POST["recurrente"] : 0
					);
					echo json_encode($resp);
				} else {
					$calendario_OO->no_permision_msg();
				}
			}
			elseif ($accion == "obtener_dia") {
				echo json_encode($calendario_OO->obtener_dia($_POST["year"], $_POST["month"]));
			}
			elseif ($accion == "listar") {
				if ($permisos["usuarios"]["consultar"]) {
					echo json_encode($calendario_OO->listar_usuarios());
				}
			}
	
			$calendario_OO->set_con(null);
			exit;
		}
	
		$calendario_OO->set_con(null);
		Bitacora::ingreso_modulo(2);
		require_once("vista/" . $pagina . ".php");
	} else {
		require_once("vista/404.php");
	}
	
?>