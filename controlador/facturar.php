<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new Facturar;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			
			if($accion == "load_facturas"){
				if(isset($permisos["facturas"]["consultar"]) and $permisos["facturas"]["consultar"] == "1"){
					echo json_encode( $cl->load_facturas() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "detalles_factura"){
				if(isset($permisos["facturas"]["consultar"]) and $permisos["facturas"]["consultar"] == "1"){
					echo json_encode( $cl->detalles_factura_s($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "calcular_facturas"){
				if(isset($permisos["facturas"]["crear"]) and $permisos["facturas"]["crear"] == "1"){
					echo json_encode( $cl->calcular_facturas_s( $_POST["calcular_anio"] ,$_POST["calcular_mes"] ) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "imprimir_txt"){
				if(isset($permisos["facturas"]["consultar"]) and $permisos["facturas"]["consultar"] == "1"){
					echo json_encode( $cl->imprimir_txt() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "concluir_facturas"){
				if(isset($permisos["facturas"]["crear"]) and $permisos["facturas"]["crear"] == "1"){
					echo json_encode( $cl->concluir_facturas() );
				}	
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion =="check_quincena" ){
				if(isset($permisos["facturas"]["consultar"]) and $permisos["facturas"]["consultar"] == "1"){
					echo json_encode( $cl->check_quincena_s($_POST["anio"], $_POST["mes"]));
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "notificar_pagos"){
				if(isset($permisos["facturas"]["crear"]) and $permisos["facturas"]["crear"] == "1"){
					echo json_encode( $cl->notificar_pagos() );
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
		Bitacora::ingreso_modulo("Gestionar Facturas");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>