<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Deducciones;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_deducciones"){
				if(isset($permisos["deducciones"]["consultar"]) and $permisos["deducciones"]["consultar"] == "1"){
					echo json_encode( $cl->load_deducciones() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_deduccion"){
				if(isset($permisos["deducciones"]["modificar"]) and $permisos["deducciones"]["modificar"] == "1"){
					echo json_encode( $cl->get_deduccion_s($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "registrar_deduccion"){
				if(isset($permisos["deducciones"]["crear"]) and $permisos["deducciones"]["crear"] == "1"){
					echo json_encode( $cl->registrar_deduccion_s(
						$_POST["deducciones_descripcion"]
						,$_POST["deducciones_monto"]
						,$_POST["deducciones_procentaje"]
						,$_POST["deducciones_quincena"]
						,$_POST["deducciones_multi_dia"]
						,$_POST["deducciones_islr"]
						,$_POST["deducciones_sector_salud"]
						,$_POST["deducciones_dedicada"]
						,$_POST["deducciones_meses"]
						,$_POST["deducciones_semanas"]
						,$_POST["trabajadores"]

					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "modificar_deduccion"){
				if(isset($permisos["deducciones"]["modificar"]) and $permisos["deducciones"]["modificar"] == "1"){
					echo json_encode( $cl->modificar_deduccion_s(
						$_POST["id"]
						,$_POST["deducciones_descripcion"]
						,$_POST["deducciones_monto"]
						,$_POST["deducciones_procentaje"]
						,$_POST["deducciones_quincena"]
						,$_POST["deducciones_multi_dia"]
						,$_POST["deducciones_islr"]
						,$_POST["deducciones_sector_salud"]
						,$_POST["deducciones_dedicada"]
						,$_POST["deducciones_meses"]
						,$_POST["deducciones_semanas"]
						,$_POST["trabajadores"]

					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "eliminar_deduccion"){
				if(isset($permisos["deducciones"]["modificar"]) and $permisos["deducciones"]["modificar"] == "1"){
					echo json_encode( $cl->eliminar_deduccion_s($_POST["id"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			



			else if($accion == "valid_cedula_trabajador"){
				if(isset($permisos["deducciones"]["consultar"]) and $permisos["deducciones"]["consultar"] == "1"){
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

			exit;
		}

		$cl->set_con(null);
		Bitacora::ingreso_modulo("Deducciones");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>