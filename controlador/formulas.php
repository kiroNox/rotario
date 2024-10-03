<?php
	if(is_file("vista/".$pagina.".php")){


		$cl = new Formulas;

		if(!empty($_POST)){// si hay alguna consulta tipo POST
			$accion = $_POST["accion"];// siempre se pasa un parametro con la accion que se va a realizar
			if($accion == "load_list_formulas"){
				if(isset($permisos["formulas"]["consultar"]) and $permisos["formulas"]["consultar"] == "1"){
					echo json_encode( $cl->load_list_formulas() );
				}
				else{
					$cl->no_permision_msg();
				}
			}
			else if($accion == "get_formula"){
				echo json_encode($cl->get_formula_s($_POST["id"]));
			}
			else if($accion == "registrar_formula"){
				echo json_encode( $cl->registrar_formula_s($_POST["formula"]));
			}
			else if($accion == "modificar_formula"){
				echo json_encode( $cl->modificar_formula_s($_POST["formula"],$_POST["id_formula"]));
			}
			else if($accion == "eliminar_formula"){
				echo json_encode( $cl->eliminar_formula_s($_POST["id"]));
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

			exit;
		}



		Bitacora::ingreso_modulo("Gestionar formulas");
		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>