<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Autorizaciones;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "listar_roles"){
				if(isset($permisos["permisos"]["consultar"]) and $permisos["permisos"]["consultar"] == "1"){
					echo json_encode( $cl->listar_roles() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar_modulos"){
				if(isset($permisos["permisos"]["consultar"]) and $permisos["permisos"]["consultar"] == "1"){
					echo json_encode( $cl->listar_modulos_s($_POST["rol"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "cambiar_permiso"){
				if(isset($permisos["permisos"]["modificar"]) and $permisos["permisos"]["modificar"] == "1"){
					echo json_encode( $cl->cambiar_permiso_s($_POST["datos"],$_POST["rol"], $_POST["modulo"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			

			$cl->set_con(null);
			exit;
		}



		$cl->set_con(null);
		Bitacora::ingreso_modulo("Permisos");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>