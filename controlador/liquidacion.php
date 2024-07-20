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
					$cl2 = new Primas;

					echo json_encode( $cl2->valid_cedula_trabajador_s(
						$_POST["cedula"]
					) );

					$cl2->set_con(null);

					unset($cl2);
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "calcular_liquidacion"){
				if(isset($permisos["liquidacion"]["crear"]) and $permisos["liquidacion"]["crear"] == "1"){

					echo json_encode( $cl->calcular_liquidacion_s(
						$_POST["cedula"],
						(isset($_POST["id"]))?$_POST["id"]:false
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