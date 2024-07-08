<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Bitacora;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_bitacora"){
				if(isset($permisos["bitacora"]["consultar"]) and $permisos["bitacora"]["consultar"] == "1"){
					echo json_encode( $cl->load_bitacora() );
				}
				else{
					$cl->no_permision_msg();
				}
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