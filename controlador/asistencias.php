<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new asistencias();

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "valid_cedula"){echo json_encode( $cl->valid_cedula($_POST["cedula"]) );}

			else if($accion == "registrar"){
				if(isset($permisos["usuarios"]["crear"]) and $permisos["usuarios"]["crear"] == "1"){
					$resp = $cl->registrar_usuario_s(
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
						(isset($_POST["discapacidad"])?true:false),
						(isset($_POST["discapacidad_info"])?$_POST["discapacidad_info"]:"")
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
			else if($accion == "nivel_profesional"){
				if(isset($permisos["usuarios"]["consultar"]) and $permisos["usuarios"]["consultar"] == "1"){
					echo json_encode( $cl->get_niveles_educativos() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "listar_areas"){
				if($permisos["usuarios"]["consultar"]){
					echo json_encode( $cl->listar_asistencia() );
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
					echo json_encode( $cl->modificar_usuario_s(
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
						$_POST["discapacidad_info"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_usuario"){
				if(isset($permisos["usuarios"]["eliminar"]) and $permisos["usuarios"]["eliminar"] == "1"){
					echo json_encode( $cl->eliminar_usuario_s($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "get_user"){
				echo json_encode( $cl->get_user($_POST["id"]));
			}

			$cl->set_con(null);
			exit;
		}



		$cl->set_con(null);
		Bitacora::ingreso_modulo("asistencias");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>