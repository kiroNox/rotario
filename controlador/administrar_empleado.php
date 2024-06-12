<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new Usuarios;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "valid_cedula"){
				echo json_encode( $cl->valid_cedula($_POST["cedula"]) );
			}
			else if($accion == "registrar"){
				if(isset($permisos["usuarios"]["crear"]) and $permisos["usuarios"]["crear"] == "1"){
					$resp = $cl->registrar_s(
						$_POST["cedula"],
						$_POST["nombre"],
						$_POST["apellido"],
						$_POST["telefono"],
						$_POST["correo"],
						$_POST["rol"],
						$_POST["pass"]
					);
					echo json_encode($resp);
				}
				else{
					$cl->no_permision_msg();
				}

			}
			else if($accion == "get_roles"){

				if(isset($permisos["usuarios"]["consultar"]) and $permisos["usuarios"]["consultar"] == "1"){
					echo json_encode( $cl->get_roles() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar"){
				if($permisos["usuarios"]["consultar"])
				echo json_encode( $cl->listar_usuarios() );
			}

			$cl->set_con(null);
			exit;
		}



		$cl->set_con(null);
		Bitacora::ingreso_modulo(2);
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>