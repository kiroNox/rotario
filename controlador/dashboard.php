<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new dashboard;

		if (!empty($_POST)) { // Si hay alguna consulta tipo POST
			$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar
	
			
			if ($accion == "obtenerDatosDashboard") {
				if ($permisos["usuarios"]["consultar"]) {

					$totales = [
					$cl->totalTrabajadores(),
					$cl->totalVacacionesActivas(),
					$cl->totalAreas(),
					$cl->totalFacturas(),
					$cl->totalPermisos(),
					$cl->totalReposos(),
					$cl->totalHijos(),
					];
					echo json_encode($totales);
				
				}
				
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