<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new generar;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "generar_balance_primas"){

				echo json_encode(
					$cl->generar_balance_s(
						$_POST["fecha_desde"],
						$_POST["fecha_hasta"],
						$_POST["tipo"]
					)
				);
				
			}
			else {
				echo json_encode(['resultado' => 'error', 'mensaje' => 'Trabajador no encontrado.']);
			}

			exit;
		}



		Bitacora::ingreso_modulo("Bitácora");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>