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
			else if($accion == "reset_pass_request"){
				echo json_encode($cl->reset_pass_request_s($_POST["correo"]));
			}
			else if($accion == "change_pass"){
				// TODO agregar validacion por token internamente
				echo json_encode($cl->change_pass($_POST["new_pass"],$_POST["id"]));
			}
			if(isset($_GET['APP-REQUEST'])){
				header("probando:queso");
			}
			exit;
		}




		if(isset($_GET["a"])){

			$data = $_GET["a"];
			$iv_size = openssl_cipher_iv_length("aes-256-cbc");  // Obtener el tamaño del IV con OpenSSL


			$iv = substr($data, -$iv_size);

			$o = new Loging;

			$texto_final = openssl_decrypt(substr($data, 0,-$iv_size), "aes-256-cbc", $o->get_keyword(),0,$iv);

			$data_user_reset = json_decode($texto_final);

			$control = $o->valid_token_reset($data_user_reset);
			require_once("vista/reset_pass.php");
		}
		else{
			require_once("vista/".$pagina.".php");
		}

	}
	else{
		require_once("vista/404.php"); 
	}
?>