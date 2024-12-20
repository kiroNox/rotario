<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Sueldo;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_sueldos"){
				if(isset($permisos["sueldo"]["consultar"]) and $permisos["sueldo"]["consultar"] == "1"){
					echo json_encode( $cl->load_sueldos() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "load_escalafon"){
				if(isset($permisos["sueldo"]["consultar"]) and $permisos["sueldo"]["consultar"] == "1"){
					echo json_encode( $cl->load_escalafon() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_sueldo"){
				if(isset($permisos["sueldo"]["consultar"]) and $permisos["sueldo"]["consultar"] == "1"){
					echo json_encode( $cl->get_sueldo_s($_POST["id_trabajador"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "asignar_sueldo"){
				if(isset($permisos["sueldo"]["modificar"]) and $permisos["sueldo"]["modificar"] == "1"){
					$_POST['medico_bool'] = (isset($_POST['medico_bool']))?true:false;
					echo json_encode( $cl->asignar_sueldo_s(
						$_POST["id_trabajador"]
						,$_POST["sueldo"]
						,$_POST["cargo"]
						,$_POST["medico_bool"]
						,$_POST["escalafon"]
						,$_POST["tipo_nomina"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "eliminar_sueldo"){
				if(isset($permisos["sueldo"]["eliminar"]) and $permisos["sueldo"]["eliminar"] == "1"){
					
					echo json_encode( $cl->eliminar_sueldo_s($_POST["id_trabajador"]) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "new_cargo"){
				if($permisos["validar_permisos"]("sueldo","crear")){
					echo json_encode( $cl->new_cargo_s(
						$_POST['cargo_codigo'],
						$_POST['new_cargo'],
						$_POST['replace']
					));
				}
			}
			else if($accion == "load_cargos"){
				if($permisos["validar_permisos"]("sueldo","crear")){
					echo json_encode( $cl->load_cargos());
				}
			}


			exit;
		}



		Bitacora::ingreso_modulo("Bitácora");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>