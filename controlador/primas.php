<?php
	if(is_file("vista/".$pagina.".php")){


		

		if(!empty($_POST)){// si hay alguna consulta tipo POST

			$cl = new Primas;
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

			else if($accion == "modificar_prima_hijo"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					echo json_encode( $cl->modificar_prima_hijo_s(
						$_POST["id"]
						,$_POST["hijo_descripcion"]
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

			else if($accion == "registrar_prima_antiguedad"){
				if(isset($permisos["primas"]["crear"]) and $permisos["primas"]["crear"] == "1"){

					echo json_encode( $cl->registrar_prima_antiguedad_s(
						$_POST["anio"]
						,$_POST["porcentaje_monto"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_prima_antiguedad"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){

					echo json_encode( $cl->get_prima_antiguedad_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "modificar_prima_antiguedad"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					echo json_encode( $cl->modificar_prima_antiguedad_s(
						$_POST["id"]
						,$_POST["anio"]
						,$_POST["porcentaje_monto"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "eliminar_prima_antiguedad"){
				if(isset($permisos["primas"]["eliminar"]) and $permisos["primas"]["eliminar"] == "1"){

					echo json_encode( $cl->eliminar_prima_antiguedad_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "registrar_prima_escalafon"){
				if(isset($permisos["primas"]["eliminar"]) and $permisos["primas"]["eliminar"] == "1"){

					echo json_encode( $cl->registrar_prima_escalafon_s(
						$_POST["escala"]
						,$_POST["tiempo"]
						,$_POST["porcentaje"]

					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "get_prima_escalafon"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					echo json_encode( $cl->get_prima_escalafon_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "modificar_prima_escalafon"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					echo json_encode( $cl->modificar_prima_escalafon_s(
						$_POST["id"]
						,$_POST["escala"]
						,$_POST["tiempo"]
						,$_POST["porcentaje"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "eliminar_prima_escalafon"){
				if(isset($permisos["primas"]["eliminar"]) and $permisos["primas"]["eliminar"] == "1"){

					echo json_encode( $cl->eliminar_prima_escalafon_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "valid_cedula_trabajador"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){

					echo json_encode( $cl->valid_cedula_trabajador_s(
						$_POST["cedula"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "registra_prima_general"){
				if(isset($permisos["primas"]["crear"]) and $permisos["primas"]["crear"] == "1"){

					echo json_encode( $cl->registra_prima_general_s(
						$_POST["descripcion"]
						,$_POST["monto"]
						,$_POST["porcentaje"]
						,$_POST["mensual"]
						,$_POST["dedicada"]
						,$_POST["trabajadores"]
						,$_POST["sector_salud"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "modificar_prima_general"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					echo json_encode( $cl->modificar_prima_general_s(
						$_POST["id"]
						,$_POST["descripcion"]
						,$_POST["monto"]
						,$_POST["porcentaje"]
						,$_POST["mensual"]
						,$_POST["dedicada"]
						,$_POST["trabajadores"]
						,$_POST["sector_salud"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "get_prima_general"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					echo json_encode( $cl->get_prima_general_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}

		
			else if($accion == "eliminar_prima_general"){
				if(isset($permisos["primas"]["eliminar"]) and $permisos["primas"]["eliminar"] == "1"){

					echo json_encode( $cl->eliminar_prima_general_s(
						$_POST["id"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			
			

			else{
				$r['resultado'] = 'error';
				$r['titulo'] = 'Error';
				$r['mensaje'] =  "Acción no programada";

				echo json_encode($r);
			}

			

			$cl->set_con(null);
			exit;
		}



		Bitacora::ingreso_modulo("Primas");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>