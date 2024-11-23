<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new Usuarios;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			
			if($accion == "get_user"){
                
				echo json_encode($cl->get_user_s($_SESSION["usuario_rotario"]));
			}
			
			else{
				echo json_encode(["resultado" => "error","mensaje" => "Acción no programada"]);
			}

			$cl->set_con(null);
			exit;
		}

		$resp = $cl->get_user_s($_SESSION["usuario_rotario"]);
		if($resp["resultado"] == "get_user"){
			$datos_user = $resp["mensaje"];
			$datos_user["status-get"] = true;
		}
		else{
			$datos_user["mensaje"] = $resp["mensaje"];
			$datos_user["status-get"] = false;
		}


		Bitacora::ingreso_modulo("Perfil");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>