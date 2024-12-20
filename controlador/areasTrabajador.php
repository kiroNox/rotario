<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new areasTrabajador();
		$claseUsuarios= new Usuarios();
		$claseAreas = new Areas;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "valid_cedula"){echo json_encode( $claseUsuarios->valid_cedula_s($_POST["cedula"]) );}

			else if($accion == "registrar"){
				if(isset($permisos["usuarios"]["crear"]) and $permisos["usuarios"]["crear"] == "1"){
					$resultado = $cl->registrar_area_trabajador(
						$_POST["id_trabajador"],
						$_POST["id_area"]
					);
					echo json_encode($resultado);
				}
				else{
					$cl->no_permision_msg();
				}

			}
			else if($accion == "get_roles"){

				if(isset($permisos["usuarios"]["consultar"]) and $permisos["usuarios"]["consultar"] == "1"){
					echo json_encode( $claseUsuarios->get_roles() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "nivel_profesional"){
				if(isset($permisos["usuarios"]["consultar"]) and $permisos["usuarios"]["consultar"] == "1"){
					echo json_encode( $claseUsuarios->get_niveles_educativos() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar_areasTrabajador"){
				if($permisos["usuarios"]["consultar"]){
					echo json_encode( $cl->listar_areasTrabajador() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar_usuarios"){
				if($permisos["usuarios"]["consultar"]){
					echo json_encode( $claseUsuarios->listar_usuarios() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar_areas"){
				if($permisos["usuarios"]["consultar"]){
					echo json_encode( $claseAreas->listar_areas() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "modificar_usuario"){
				if($permisos["usuarios"]["modificar"]){
					if (isset($_POST["discapacidad"])) {
						$_POST["discapacidad"] = true;
					}
					else{
						$_POST["discapacidad"] = false;
						$_POST["discapacidad_info"] = null;
					}
					echo json_encode( $claseUsuarios->modificar_usuario_s(
						$_POST["id"],
						$_POST["cedula"],
						$_POST["nombre"],
						$_POST["apellido"],
						$_POST["telefono"],
						$_POST["correo"],
						$_POST["rol"],
						$_POST["pass"],
						$_POST["numero_cuenta"],
						$_POST["nivel_educativo"],
						$_POST["fecha_ingreso"],
						$_POST["comision_servicios"],
						$_POST["discapacidad"],
						$_POST["discapacidad_info"],
						$_POST["genero"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_trabajadorarea"){
				if(isset($permisos["usuarios"]["eliminar"]) and $permisos["usuarios"]["eliminar"] == "1"){
					echo json_encode( $cl->eliminar_trabajadorArea($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "get_user"){
				echo json_encode( $claseUsuarios->get_user_s($_POST["id"]));
			}

			$cl->set_con(null);
			exit;
		}



		$cl->set_con(null);
		Bitacora::ingreso_modulo("areasTrabajador");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>