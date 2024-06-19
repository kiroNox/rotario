<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Autorizaciones;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "listar_roles"){
				if(isset($permisos["roles"]["consultar"]) and $permisos["roles"]["consultar"] == "1"){
					echo json_encode( $cl->listar_roles() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "registrar_roles"){
				if(isset($permisos["roles"]["crear"]) and $permisos["roles"]["crear"] == "1"){
					echo json_encode($cl->registrar_roles_s($_POST["Rol"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "modificar_roles"){
				if(isset($permisos["roles"]["modificar"]) and $permisos["roles"]["modificar"] == "1"){
					echo json_encode($cl->modificar_roles_s($_POST["Rol"],$_POST["id"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_roles"){
				if(isset($permisos["roles"]["modificar"]) and $permisos["roles"]["modificar"] == "1"){
					echo json_encode($cl->eliminar_roles_s($_POST["id"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}

			$cl->set_con(null);
			exit;
		}



		$cl->set_con(null);
		Bitacora::ingreso_modulo("Roles");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>