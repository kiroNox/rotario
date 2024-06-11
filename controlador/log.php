<?php
	if(is_file("vista/".$pagina.".php")){

		if(!empty($_POST)){// si hay alguna consulta tipo POST

			$cl = new Loging;

			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar

			if($accion == "singing"){// iniciar sesion
				echo json_encode($cl->singing_c(
					$_POST["user"],
					$_POST["pass"]
				));

				// en el caso tipico debe enviar un json diciendo que la sesion es correcta
				// o no con el error, incluye errores de conexion a la bd 
			}
			$cl->set_con(null);// cierro la conexión
			exit;
		}



		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>