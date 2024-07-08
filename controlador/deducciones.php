<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Deducciones;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_deducciones"){
				if(isset($permisos["deducciones"]["consultar"]) and $permisos["deducciones"]["consultar"] == "1"){
					echo json_encode( $cl->load_deducciones() );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			exit;
		}



		Bitacora::ingreso_modulo("Deducciones");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>