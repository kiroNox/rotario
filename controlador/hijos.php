<?php
	if(is_file("vista/".$pagina.".php")){

		if(!empty($_POST)){// si hay alguna consulta tipo POST

			$cl = new Hijos;

			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar

			if($accion == "valid_cedula_parent"){// iniciar sesion
				if(isset($permisos["hijos"]["consultar"]) and $permisos["hijos"]["consultar"] == "1"){
					echo json_encode($cl->valid_parent_s($_POST["cedula"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "registrar_hijo"){
				if(isset($permisos["hijos"]["crear"]) and $permisos["hijos"]["crear"] == "1"){

					echo json_encode($cl->registrar_hijo_s(
						$_POST["madre_cedula"],
						$_POST["padre_cedula"],
						$_POST["nombre"],
						$_POST["fecha_nacimiento"],
						$_POST["genero"],
						(isset($_POST["discapacitado"])?true:false),
						$_POST["observacion"],
					));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar_hijos"){
				if(isset($permisos["hijos"]["consultar"]) and $permisos["hijos"]["consultar"] == "1"){

					echo json_encode($cl->listar_hijos());
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_hijo"){
				if(isset($permisos["hijos"]["consultar"]) and $permisos["hijos"]["consultar"] == "1"){

					echo json_encode($cl->get_hijo_s($_POST["id"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_hijo"){
				if(isset($permisos["hijos"]["eliminar"]) and $permisos["hijos"]["eliminar"] == "1"){
					echo json_encode($cl->eliminar_hijo_s($_POST["id"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "modificar_hijo"){
				if(isset($permisos["hijos"]["modificar"]) and $permisos["hijos"]["modificar"] == "1"){
					echo json_encode($cl->modificar_hijo_s(
						$_POST["id"],
						$_POST["madre_cedula"],
						$_POST["padre_cedula"],
						$_POST["nombre"],
						$_POST["fecha_nacimiento"],
						$_POST["genero"],
						(isset($_POST["discapacitado"])?true:false),
						$_POST["observacion"]
					));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			$cl->set_con(null);// cierro la conexión
			exit;
		}


		Bitacora::ingreso_modulo("Hijos");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>