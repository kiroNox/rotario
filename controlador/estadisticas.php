<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new estadisticas;

		if (!empty($_POST)) { // Si hay alguna consulta tipo POST
			$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar

			if ($accion == "obtener_vacaciones_anuales") {
				$datos = $cl->obtenerVacacionesAnuales();
				echo json_encode($datos);
			} elseif ($accion == "obtener_niveles_educativos") {
				$datos = $cl->obtenerNivelesEducativos();
				echo json_encode($datos);
			}
			
					
			$cl->set_con(null);
			Bitacora::ingreso_modulo("Bitacora");
			exit;
		}

		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>