<?php
	if(is_file("vista/".$pagina.".php")){


		

		if(!empty($_POST)){// si hay alguna consulta tipo POST

			$cl = new Primas;
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_primas_generales"){
				if(isset($permisos["primas"]["consultar"]) and $permisos["primas"]["consultar"] == "1"){
					echo json_encode( $cl->load_primas_generales() );
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

					//showvar($_POST,'table');

					$cl->calc_init();

					echo json_encode( $cl->registra_prima_general_s(
						$_POST["descripcion"]
						,$_POST["mensual"]
						,$_POST["dedicada"]
						,$_POST["trabajadores"]
						,$_POST["formula"]
					) );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "modificar_prima_general"){
				if(isset($permisos["primas"]["modificar"]) and $permisos["primas"]["modificar"] == "1"){

					$cl->calc_init();
					echo json_encode( $cl->modificar_prima_general_s(
						$_POST["id"]
						,$_POST["descripcion"]
						,$_POST["mensual"]
						,$_POST["dedicada"]
						,$_POST["trabajadores"]
						,$_POST["formula"]
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
					echo json_encode($r);
				}
				else if(isset($_POST["calc_condicional_check"])){

					$formula["variables"] = json_decode($formula["variables"],true);

					$r = $cl->leer_formula_condicional($formula["condicional"],$formula["formula"],$formula["variables"]);

					if($r["resultado"] == "leer_formula_condicional") $r["resultado"] = "leer_formula";

					echo json_encode($r);
				}
				else{
					$formula["variables"] = json_decode($formula["variables"],true);

					$r = $cl->leer_formula($formula["formula"],$formula["variables"]);

					echo json_encode($r);
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

		if(isset($_GET["calc_form_1"])){
			require_once("vista/calculadora-form.php");
			exit;
		}



		Bitacora::ingreso_modulo("Primas");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>