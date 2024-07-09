<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Primas;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_all_primas"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){
					echo json_encode( $cl->load_all_primas() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "load_primas_generales"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){
					echo json_encode( $cl->load_primas_generales() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "load_primas_hijos"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){
					echo json_encode( $cl->load_primas_hijos() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "load_primas_antiguedad"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){
					echo json_encode( $cl->load_primas_antiguedad() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "load_primas_escalafon"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){
					echo json_encode( $cl->load_primas_escalafon() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "registrar_prima_hijo"){
				if(isset($permisos["primas"]["crear"]) and $permisos["primas"]["crear"] == "1"){

					echo json_encode( $cl->registrar_prima_hijo_s(
						$_POST["hijo_descripcion"]
						,$_POST["hijo_monto"]
						,$_POST["hijo_menor"]
						,$_POST["hijo_discapacidad"]
						,$_POST["hijo_porcentaje"]

					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_prima_hijos"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){

					echo json_encode( $cl->get_prima_hijos_s($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_prima_hijo"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){

					echo json_encode( $cl->eliminar_prima_hijo_s($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			


			exit;
		}



		Bitacora::ingreso_modulo("Primas");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>