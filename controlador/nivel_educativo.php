<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Profesionalismo;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_niveles"){
				if(isset($permisos["educacion"]["consultar"]) and $permisos["educacion"]["consultar"] == "1"){
					echo json_encode( $cl->load_niveles() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "registrar_nivel_educativo"){
				if(isset($permisos["educacion"]["crear"]) and $permisos["educacion"]["crear"] == "1"){
					echo json_encode( $cl->registrar_nivel_educativo_s(
						$_POST["nivel_descripcion"]
						// ,$_POST["nivel_monto"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "modificar_nivel_educativo"){
				if(isset($permisos["educacion"]["modificar"]) and $permisos["educacion"]["modificar"] == "1"){
					echo json_encode( $cl->modificar_nivel_educativo_s(
						$_POST["id"]
						,$_POST["nivel_descripcion"]
						// ,$_POST["nivel_monto"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_nivel_educativo"){
				if(isset($permisos["educacion"]["modificar"]) and $permisos["educacion"]["modificar"] == "1"){
					echo json_encode( $cl->get_nivel_educativo_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_nivel_educativo"){
				if(isset($permisos["educacion"]["eliminar"]) and $permisos["educacion"]["eliminar"] == "1"){
					echo json_encode( $cl->eliminar_nivel_educativo_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			

			else{
				echo json_encode(["resultado" => "error","mensaje" => "Acción no programada"]);
			}


			$cl->set_con(null);
			exit;
		}

		$cl->set_con(null);



		Bitacora::ingreso_modulo("Nivel Educativo");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>