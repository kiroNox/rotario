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
					$cl->calc_init();
					echo json_encode( $cl->registrar_deduccion_s(
						$_POST["deducciones_descripcion"]
						,$_POST["deducciones_islr"]
						,$_POST["deducciones_dedicada"]
						,$_POST["trabajadores"]
						,$_POST["formula"]


					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}


			else if($accion == "modificar_deduccion"){
				if(isset($permisos["deducciones"]["modificar"]) and $permisos["deducciones"]["modificar"] == "1"){
					$cl->calc_init();
					echo json_encode( $cl->modificar_deduccion_s(
						$_POST["id"]
						,$_POST["deducciones_descripcion"]
						,$_POST["deducciones_islr"]
						,$_POST["deducciones_dedicada"]
						,$_POST["trabajadores"]
						,$_POST["formula"]

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

					//unset($cl2);
				}
				else{
					$cl->no_permision_msg();
				}
			}

			else if($accion == "get_calc_reserved_words"){
				$cl->calc_init();
				echo json_encode($cl->get_calc_reserved_words());
			}
			else if($accion == "get_lista_trabajadores"){
				$cl->calc_init();
				echo json_encode($cl->get_lista_trabajadores());
			}
			else if($accion == "test_formula"){// test formula
				$cl->calc_init();
				$cl->set_id_trabajador($_POST["trabajador_prueba"]);


				$formula = json_decode($_POST["formula"],true);


				if($formula["tipo"] === 'lista'){
					$r = $cl->leer_formula_condicional($formula["lista"]);
					//echo json_encode($r);
				}
				else if(isset($_POST["calc_condicional_check"])){

					$formula["variables"] = json_decode($formula["variables"],true);

					$r = $cl->leer_formula_condicional($formula["condicional"],$formula["formula"],$formula["variables"]);

					if($r["resultado"] == "leer_formula_condicional") $r["resultado"] = "leer_formula";

					//echo json_encode($r);
				}
				else{
					$formula["variables"] = json_decode($formula["variables"],true);

					$r = $cl->leer_formula($formula["formula"],$formula["variables"]);

				} 
					$r["iteraciones"] = $cl->get_counter_loop();
					echo json_encode($r);

			}

			exit;
		}

		$cl->set_con(null);
		if(isset($_GET["calc_form_1"])){
			require_once("vista/calculadora-form.php");
			exit;
		}


		Bitacora::ingreso_modulo("Deducciones");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>