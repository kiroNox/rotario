<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new Liquidaciones;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			
			if($accion == "load_liquidaciones"){
				if(isset($permisos["liquidacion"]["consultar"]) and $permisos["liquidacion"]["consultar"] == "1"){
					echo json_encode( $cl->load_liquidaciones() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "valid_cedula_trabajador"){
				if(isset($permisos["liquidacion"]["consultar"]) and $permisos["liquidacion"]["consultar"] == "1"){

					echo json_encode( $cl->valid_cedula_trabajador_s(
						$_POST["cedula"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "nueva_liquidacion"){
				if(isset($permisos["liquidacion"]["crear"]) and $permisos["liquidacion"]["crear"] == "1"){

					echo json_encode( $cl->nueva_liquidacion_s(
						$_POST["cedula"],
					) );

				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "registrar_liquidacion"){
				if(isset($permisos["liquidacion"]["crear"]) and $permisos["liquidacion"]["crear"] == "1"){

					echo json_encode( $cl->registrar_liquidacion_s(
						$_POST["trabajador_id"]
						,$_POST["liquidaciones_fecha"]
						,$_POST["liquidacion_motivo"]
						,$_POST["liquidacion_monto_total"]
					) );

				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "eliminar_liquidacion"){
				if(isset($permisos["liquidacion"]["eliminar"]) and $permisos["liquidacion"]["eliminar"] == "1"){

					echo json_encode( $cl->eliminar_liquidacion_s(
						$_POST["id"]
					) );

				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "enviar_correo"){

					echo json_encode( $cl->notificar_liquidacion_s(
						$_POST["id"]
					) );

			}
			else if ($accion == "get_liquidacion"){
				echo json_encode( $cl->get_liquidacion_s(
					$_POST["id"]
				) );
			}

			else if ($accion == "modificar_liquidacion"){
				echo json_encode( $cl->modificar_liquidacion_s(
					$_POST['liquidacion_id'],
					$_POST['trabajador_id'],
					$_POST['liquidaciones_fecha'],
					$_POST['liquidacion_motivo'],
					$_POST['liquidacion_monto_total'],
				) );
			}

			else{
				echo json_encode(["resultado" => "error","mensaje" => "Acción no programada"]);
			}

			$cl->set_con(null);
			exit;
		}



		$cl->set_con(null);
		Bitacora::ingreso_modulo("Liquidación");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>