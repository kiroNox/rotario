<?php 
trait Calculadora{
	PRIVATE $calc_status = false;
	PRIVATE $calc_var;
	PRIVATE $calc_f;
	PRIVATE $calc_formula;
	PRIVATE $calc_list_formulas;
	PRIVATE $calc_separadores;
	PRIVATE $calc_posicion;
	PRIVATE $calc_items;
	PRIVATE $counter_loop;
	PRIVATE $calc_error;
	PRIVATE $calc_evaluando;
	PRIVATE $calc_diff_var_formula;
	private $calc_decimales_recibidos;
	private $calc_decimales_respuesta;
	private $obj_formula;



	PUBLIC function calc_init(){
		// TODO sacarlo de los controladores
		$this->calc_f = new stdClass();
		$this->calc_var = new stdClass();
		$this->calc_evaluando = new stdClass();
		$this->calc_list_formulas = new stdClass();
		$this->calc_error = null;
		$this->calc_decimales_recibidos = 2;
		$this->calc_decimales_respuesta = 2;

		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\+';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\-';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\/';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\*';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\(';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\)';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\[';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\]';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\{';
		$this->calc_diff_var_formula[] = $this->calc_separadores[] = '\\}';
		$this->calc_separadores[] = '[_]*[a-zA-Z]+(?:[_]*[a-zA-Z]*)*';
		//$this->calc_separadores[] = '[0-9]+([\\.][0-9]+)?';
		//$this->calc_separadores[] = '__.*__';

		$this->calc_posicion = 0;
		$this->counter_loop = 0;

		$this->calc_status = true;



		$this->add_calc_system_function();

		$this->update_list_formulas();
	}

	PRIVATE function add_calc_system_function(){// añade palabras reservadas de forma manual
		$fun = function(){ // sera pasada como argumento  devuelve el tiempo del trabajador en años
			$this->validar_conexion($this->con);
			$resultado = 0;
			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT TIMESTAMPDIFF(YEAR, t.creado ,CURRENT_DATE) as tiempo from trabajadores as t WHERE id_trabajador = ?");
					$consulta->execute([$this->id_trabajador]);
					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = intval($resp["tiempo"]);
					}
					$consulta = null;
				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
			}
			return $resultado;
		};
		$descrip = "Devuelve el tiempo del trabajador en años";
		$this->set_calc_function("TIEMPO_TRABAJADOR",$descrip,$fun,false,true);
		$fun = function(){ // devuelve el sueldo base del trabajador
			$this->validar_conexion($this->con);
			$resultado = 0;
			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT sb.sueldo_base FROM trabajadores as t LEFT JOIN sueldo_base as sb ON sb.id_trabajador = t.id_trabajador WHERE t.id_trabajador = ?;");
					$consulta->execute([$this->id_trabajador]);
					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = floatval(number_format($resp["sueldo_base"],$this->calc_decimales_recibidos,'.',''));
					}

					$consulta = null;
				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;
		};
		$descrip = "Devuelve el sueldo base del trabajador";
		$this->set_calc_function("SUELDO_BASE",$descrip,$fun,false,true);


		
		$descrip = "Devuelve uno (1) si el trabajador es un medico cero (0) si no";
		$this->set_calc_function("MEDICO",$descrip, function(){ 
			$this->validar_conexion($this->con);


			$resultado = 0;

			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT sb.sector_salud FROM trabajadores as t LEFT JOIN sueldo_base as sb ON sb.id_trabajador = t.id_trabajador WHERE t.id_trabajador = ?;");
					$consulta->execute([$this->id_trabajador]);

					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = floatval(number_format($resp["sector_salud"],$this->calc_decimales_recibidos,'.',''));
					}

					$consulta = null;
				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;
		}
		,false,true);

		$this->set_calc_function("HIJOS","Devuelve el número total de hijos",function(){
			$this->validar_conexion($this->con);
			$resultado = 0;

			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT DISTINCT COUNT(h.id_hijo) hijos FROM trabajadores AS t JOIN hijos as h ON (h.id_trabajador_madre = t.id_trabajador OR h.id_trabajador_padre = t.id_trabajador) WHERE t.id_trabajador = ?;");
					$consulta->execute([$this->id_trabajador]);

					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = floatval(number_format($resp["hijos"],$this->calc_decimales_recibidos,'.',''));
					}

					$consulta = null;
				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;

		},false,true);
		$this->set_calc_function("HIJOS_MENORES","Devuelve el numero total de hijos menores de edad",function(){
			$this->validar_conexion($this->con);
			$resultado = 0;

			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT DISTINCT COUNT(h.id_hijo) hijos_menores FROM trabajadores AS t JOIN hijos as h ON (h.id_trabajador_madre = t.id_trabajador OR h.id_trabajador_padre = t.id_trabajador) WHERE t.id_trabajador = ? AND TIMESTAMPDIFF(YEAR, h.fecha_nacimiento ,CURRENT_DATE) < 18;");
					$consulta->execute([$this->id_trabajador]);

					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = floatval(number_format($resp["hijos_menores"],$this->calc_decimales_recibidos,'.',''));
					}

					$consulta = null;
				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;

		},false,true);
		$this->set_calc_function("HIJOS_MAYORES","Devuelve el numero total de hijos mayores de edad",function(){
			$this->validar_conexion($this->con);
			$resultado = 0;

			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT DISTINCT COUNT(h.id_hijo) hijos_mayores FROM trabajadores AS t JOIN hijos as h ON (h.id_trabajador_madre = t.id_trabajador OR h.id_trabajador_padre = t.id_trabajador) WHERE t.id_trabajador = ? AND TIMESTAMPDIFF(YEAR, h.fecha_nacimiento ,CURRENT_DATE) > 18;");
					$consulta->execute([$this->id_trabajador]);

					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = floatval(number_format($resp["hijos_mayores"],$this->calc_decimales_recibidos,'.',''));
					}

					$consulta = null;
				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;

		},false,true);
		$this->set_calc_function("HIJOS_DISCAPACIDAD","Devuelve el numero total de hijos con una discapacidad",function(){
			$this->validar_conexion($this->con);
			$resultado = 0;

			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$consulta = $this->con->prepare("SELECT DISTINCT COUNT(h.id_hijo) hijos_discapacidad FROM trabajadores AS t JOIN hijos as h ON (h.id_trabajador_madre = t.id_trabajador OR h.id_trabajador_padre = t.id_trabajador) WHERE t.id_trabajador = ? AND h.discapacidad = 1;");
					$consulta->execute([$this->id_trabajador]);

					if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
						$resultado = floatval(number_format($resp["hijos_discapacidad"],$this->calc_decimales_recibidos,'.',''));
					}

					$consulta = null;

				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;

		},false,true);

		$fn_general = function($fn_interna){
			$this->validar_conexion($this->con);
			$resultado = 0;

			if(isset($this->id_trabajador)){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ?;");
				$consulta->execute([$this->id_trabajador]);
				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){// el trabajador existe
					$consulta = null;
					$resultado = $fn_interna();

				}
				else{
					throw new Exception("El trabajador no existe ($this->id_trabajador)", 1);
				}
			}
			else{
				throw new Exception("EL id del trabajador no esta definido", 1);
				
			}
			return $resultado;
		};





		$this->set_calc_function("LUNES_MES","Devuelve el total de lunes en el mes actual en la prueba, al calcular el sueldo se hará con la fecha ingresada",function() use ($fn_general){

			return $fn_general(function(){

				$consulta = $this->con->query("SELECT 
					COALESCE(DATE_FORMAT(@fecha_pago_inico, '%Y-%m-1'),DATE_FORMAT(CURRENT_DATE, '%Y-%m-1') ),
					if(@quincena_pago is not null,
					   if(@quincena_pago=1,DATE_FORMAT(@fecha_pago_inicio, '%Y-%m-15'),
					      if(@quincena_pago=2,LAST_DAY(@fecha_pago_inicio),false)
					     )
					   ,LAST_DAY(CURRENT_DATE))
					INTO 
					@fecha_pago_inicio_function,
					@fecha_pago_fin_function;
					SELECT DATE_FORMAT(CURRENT_DATE, '%Y-%m-1') as test;");






				$consulta = $this->con->prepare("SELECT IF(DAYOFWEEK(:inicio)=2,1,0) as lunes, DATE_ADD(:inicio, INTERVAL 1 DAY) as nex FROM trabajadores WHERE :inicio <> :fin LIMIT 1;");




				$consulta = $this->con->query("SET @fecha_pago_inicio = null,@fecha_pago_fin= NULL;");




















				$consulta = $this->con->query("SELECT f_contar_lunes(CURRENT_DATE,3) lunes;");

				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
					return $resp["lunes"];
				}
				else{
					return 0;
				}

			});
		},false,true);

		$this->set_calc_function("LUNES_QUINCENA_UNO_DOS","Devuelve el total de lunes en la primera quincena del mes actual",function() use ($fn_general){

			return $fn_general(function(){
				$consulta = $this->con->query("SELECT f_contar_lunes(CURRENT_DATE,1) lunes;");

				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
					return $resp["lunes"];
				}
				else{
					return 0;
				}

			});
		},false,true);

		$this->set_calc_function("LUNES_QUINCENA","Devuelve el total de lunes en la primera quincena del mes actual, Al calcular el sueldo de los trabajadores lo hará según la fecha ingresada y quincena correspondiente",function() use ($fn_general){

			return $fn_general(function(){

				//$consulta = $this->con->query("set @fecha_pago_inicio = '2024-12-14', @quincena_pago = 2;");

				//$consulta = null;


				$consulta = $this->con->query("SELECT 
					COALESCE(DATE_FORMAT(@fecha_pago_inicio, if( @quincena_pago is null or @quincena_pago = 1, '%Y-%m-1','%Y-%m-16') ),DATE_FORMAT(CURRENT_DATE, '%Y-%m-1') ),


					if(@quincena_pago is not null,
					   if(@quincena_pago=1,DATE_FORMAT(@fecha_pago_inicio, '%Y-%m-15'),
					      if(@quincena_pago=2,LAST_DAY(@fecha_pago_inicio),false)
					     )
					   ,DATE_FORMAT(CURRENT_DATE, '%Y-%m-15'))
					INTO 
					@fecha_pago_inicio_function,
					@fecha_pago_fin_function;");






				$consulta = $this->con->prepare("SELECT IF(DAYOFWEEK(@fecha_pago_inicio_function)=2,1,0) as lunes, @fecha_pago_inicio_function := DATE_ADD(@fecha_pago_inicio_function, INTERVAL 1 DAY) as nex FROM trabajadores WHERE @fecha_pago_inicio_function <= @fecha_pago_fin_function LIMIT 1;");


				$consulta->execute();

				$resp = $consulta->fetch(PDO::FETCH_ASSOC);

				$contador = 0;

				while ($resp!=false) {

					if($contador>100){
						throw new Exception("bucle infinito", 1);
					}

					if($resp["lunes"]=='1'){$contador++;}
					$consulta->execute();

					$resp = $consulta->fetch(PDO::FETCH_ASSOC);
				}

				$consulta = $this->con->query("set @fecha_pago_inicio_function = NULL,
					@fecha_pago_fin_function = NULL;");
				$consulta = null;


				return $contador;






				// $consulta = $this->con->query("SELECT f_contar_lunes(CURRENT_DATE,2) lunes;");

				// if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
				// 	return $resp["lunes"];
				// }
				// else{
				// 	return 0;
				// }

			});
		},false,true);

		$this->set_calc_function("HOMBRE","Devuelve 1 si el trabajador es Hombre 0 si no lo es",function() use ($fn_general){

			return $fn_general(function(){
				$consulta = $this->con->prepare("SELECT genero FROM trabajadores WHERE id_trabajador = ? AND genero = 'M';");
				$consulta->execute([$this->id_trabajador]);

				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
					return 1;
				}
				else{
					return 0;
				}

			});
		},false,true);

		$this->set_calc_function("MUJER","Devuelve 1 si la trabajadora es mujer 0 si no lo es",function() use ($fn_general){

			return $fn_general(function(){
				$consulta = $this->con->prepare("SELECT genero FROM trabajadores WHERE id_trabajador = ? AND genero = 'F';");
				$consulta->execute([$this->id_trabajador]);

				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
					return 1;
				}
				else{
					return 0;
				}

			});
		},false,true);


		$this->set_calc_function("DISCAPACIDAD_TRABAJADOR","Devuelve 1 si el trabajador esta discapacitado 0 si no",function() use ($fn_general){

			return $fn_general(function(){
				$consulta = $this->con->prepare("SELECT discapacitado FROM trabajadores WHERE id_trabajador = ? AND discapacitado IS TRUE;");
				$consulta->execute([$this->id_trabajador]);

				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
					return 1;
				}
				else{
					return 0;
				}

			});
		},false,true);

		$this->set_calc_function("ESCALA_ESCALAFON","Devuelve el valor numérico de la escala asignada si la tiene(ej 'III' = 3), devuelve 0 de no tenerla",function() use ($fn_general){

			return $fn_general(function(){
				$consulta = $this->con->prepare("SELECT e.valor_escala FROM sueldo_base AS sb LEFT JOIN escalafon e ON e.id_escalafon = sb.id_escalafon WHERE sb.id_escalafon IS NOT NULL and sb.id_trabajador = ?");
				$consulta->execute([$this->id_trabajador]);

				if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
					return $resp["valor_escala"];
				}
				else{
					return 0;
				}

			});
		},false,true);


		$this->validar_conexion($this->con);


		$consulta = $this->con->query("SELECT descripcion, id_prima_profesionalismo FROM prima_profesionalismo WHERE 1");


		if($resp = $consulta->fetchall(PDO::FETCH_ASSOC)){
			foreach ($resp as $elem) {


				$campo = $elem["descripcion"];
				$id_campo = $elem["id_prima_profesionalismo"];

				$campo = preg_replace("/^\s|\s$|[\s][\s]+/", "", $campo);
				$campo = preg_replace("/\s/", "_", $campo);



				$campo = str_replace(["á","é","í","ó","ú"], ['a','e',"i","o","u"], $campo);

				$campo = preg_replace("/[^a-zA-Z_]/u", 'X', $campo);

				$campo = strtoupper($campo);




				$this->set_calc_function($campo,"Devuelve 1 la Profesionalización del trabajador coincide con la solicitada",function() use ($fn_general,$id_campo){

					return $fn_general(function() use ($id_campo){

						$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE id_trabajador = ? and id_prima_profesionalismo = ?");
						$consulta->execute([$this->id_trabajador,$id_campo]);

						if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
							return 1;
						}
						else{
							return 0;
						}

					});
				},false,true);



				
			}
		}

		$this->close_bd($this->con);

		// no se usa como tal esta en otro lado
		$this->set_calc_function("DEDICADA","Se aconseja utilizar solo en la(s) condicional(es), al crear una formula cuya prima/deducción esta dedicada a un trabajador en especifico, devuelve 1 si el trabajador esta en la lista, 0 si no",function() use ($fn_general){
			return null;
		},false,true);







	}



	PRIVATE function calc_f_clean_cache(){
		foreach ($this->calc_f as $elem) {
			$elem->cl_cache();
		}
	}

	PUBLIC function leer_formula($formula,$variables=null,$cl_cache=true,$var_formu=false){

		try {
			if($variables === null){
				$variables = [];
			}
			$this->calc_check_status(); // check constructor
			$this->set_calc_formula($formula); // limpio la formula de los espacios en blanco
			$formula_array = $this->calc_separador($formula); // separo los elementos



			$this->calc_variables($formula_array,$variables); // remplazo las variables




			
			
			$formula_array = $this->calc_groups($formula_array); // asigno los grupos





			$r["resultado"] = "leer_formula";
			$total = $this->resolve_groups($formula_array,$formula);

			if(is_string($total) and preg_match("/%/", $total)){
				$r["porcentaje"] = true;
				$r["total"] = $total;
			}
			else{
				$r["porcentaje"] = false;
				$r["total"] = floatval(number_format($total, $this->calc_decimales_respuesta, '.', ''));
			}


			$r["formula"] = $this->calc_formula;
			$r["tipo"] = "normal";
			$r["variables"] = $this->get_all_var($variables);

			$r[] = $formula_array;

		} catch (Exception $e) {

			$r['resultado'] = 'error';
			$r['titulo'] = "La formula no pudo ser calculada";
			$r["formula"] = $formula;

			$r['mensaje'] =  $e->getMessage();
			if($e->getCode() == 999){
				$r["mensaje"] = "Error al calcular la formula Contacte con un administrador";
				$r["ERROR_CALC"] = $e->getMessage();
			}
			$r["line"] = $e->getLine();
			
			$r["calc_error"] = $e->getCode();
			// 103 = Las agrupaciones no pueden estar vaciás
			// 118 = La variable/function ... no existe

			if($var_formu!==false){// si no es false significa que estaba evaluando algo y eso seria $var_formu
				if(preg_match("/^\{LANZAR\}\:/", $var_formu)){
					$var_formu = preg_replace("/^\{LANZAR\}\:/", "", $var_formu);
					//throw $e;
					
					throw new Exception("Error al evaluar '$var_formu' :: ".$e->getMessage(), $e->getCode());

				}else{
					$r["mensaje"] = "Error al evaluar '$var_formu'. ".$e->getMessage();
				}
			}
			$r["path2"] = $e->getTraceAsString();
		}

		return $r;
	}

	PUBLIC function leer_formula_condicional($condiciones,$formula= null,$variables=null,$condicional_n = null){
		try {
			if(is_array($condiciones)){// Significa que es una matriz con los datos (asociativo de los argumentos)

				$n_lista_control = 1;
				foreach ($condiciones as $lista) {
					if(!isset($lista["variables"])) $lista["variables"] = null;

					if(is_string($lista["variables"]) and preg_match("/^\[.*\]$/", $lista["variables"])){
						$lista["variables"] = json_decode($lista["variables"],true);
					}


					$leer_formula_condicional = $this->leer_formula_condicional($lista["condiciones"],$lista["formula"],$lista["variables"],$n_lista_control++);
					if($leer_formula_condicional["resultado"] == 'error'){
						throw new Exception($leer_formula_condicional["mensaje"], $leer_formula_condicional["calc_error"]);
					}

					if($leer_formula_condicional["total"] === NULL){ // no se cumplió ninguna condicional
						continue;
					}

					return $leer_formula_condicional;
				}

				$r = [];
				$r["resultado"] = "leer_formula_condicional";
				$r["total"] = null;
				$r["tipo"] = "condicional";
				return $r;

			}
			else if(!isset($formula)){
				throw new Exception("Debe introducir una formula valida para la condicional '$condiciones' ", 1);
				
			}

			$condiciones = preg_replace("/\s*/", "", $condiciones);

			if(preg_match("/[<][>]|[<][=]|[>][=]|[<]|[>]|[=]/", $condiciones)){

				$condiciones_separadas = preg_split("/([\<][\>])|([<][=])|([>][=])|([\<])|([\>])|([\=])/", $condiciones,-1,PREG_SPLIT_DELIM_CAPTURE);



				$condiciones_separadas = array_filter($condiciones_separadas,"calc_filter_vacio");
				$condiciones_separadas = array_values($condiciones_separadas);

				if(count($condiciones_separadas) != 3 ){
					throw new Exception("Error en '$condiciones' solo puede usar los siguientes caracteres de comparación (<, >, =, >=, <=)", 1);
				}


				preg_match("/[\<][\>]|[<][=]|[>][=]|[\<]|[\>]|[\=]/", $condiciones,$igualdad);// obtengo la igualdad

				if($condicional_n===null){
					$entorno = "Condicional";
				}
				else if(is_numeric($condicional_n)){
					$entorno = "Condicional $condicional_n";
				}
				else{
					$entorno = false;
				}


				
				$condicion_1 = $this->leer_formula($condiciones_separadas[0],$variables,true,$entorno);

				$condicion_2 = $this->leer_formula($condiciones_separadas[2],$variables,true,$entorno);

				if($condicion_1["resultado"] == "error"){
					throw new Exception($condicion_1["mensaje"], $condicion_1["calc_error"]);
				}
				if($condicion_1["porcentaje"]===true){
					throw new Exception("fue devuelto un porcentaje (".$condicion_1["total"].") y no es valido como resultado entre las condiciones ", 1);
				}

				
				if($condicion_2["resultado"] == "error"){
					throw new Exception($condicion_2["mensaje"], $condicion_2["calc_error"]);
				}
				if($condicion_2["porcentaje"]===true){
					throw new Exception("fue devuelto un porcentaje (".$condicion_2["total"].") y no es valido como resultado entre las condiciones ", 2);
				}

				$respuesta = false;

				switch ($igualdad[0]) {
					case '<>':
					if($condicion_1 != $condicion_2){
						$respuesta = true;
					}
					break;
					case '=':
					if($condicion_1 == $condicion_2){
						$respuesta = true;
					}
					break;
					case '<':
					if($condicion_1 < $condicion_2){
						$respuesta = true;
					}
					break;
					case '>':
					if($condicion_1 > $condicion_2){
						$respuesta = true;
					}
					break;
					case '>=':
					if($condicion_1 >= $condicion_2){
						$respuesta = true;
					}
					break;
					case '<=':
					if($condicion_1 <= $condicion_2){
						$respuesta = true;
					}
					break;
					default:
					throw new Exception("ERROR al recibir el signo de igualdad de la condición", 1);
				}

				if($respuesta === true){

					if(is_numeric($condicional_n)){
						$entorno = "Formula - $condicional_n";
					}
					else{
						$entorno = false;
					}

					$r = $this->leer_formula($formula, $variables, true, $entorno);


					if($r["resultado"] == 'error'){
						throw new Exception($r["mensaje"], $r["calc_error"]);
					}
					$r["resultado"] = "leer_formula_condicional";
					$r["tipo"] = "condicional";
					$r["formula"] = $formula;
					$r["n_formula"] = (is_numeric($condicional_n))?$condicional_n:null;
				}
				else{
					$r =[];
					$r["resultado"] = "leer_formula_condicional";
					$r["total"] = NULL;
					$r["formula"] = $formula;
					$r["variables"] = $variables;
					$r["tipo"] = "condicional";
					$r["msm"] = "Condicionales falsas";
				}


			}
			else{

				
				if($condicional_n === null){
					$entorno = "Condicional";
				}

				else if(is_numeric($condicional_n)){
					$entorno = "Condicional - $condicional_n";
				}
				else{
					$entorno = false;
				}

				$cond = $this->leer_formula($condiciones,$variables,true,$entorno); // lee la condicional

				if(is_numeric($condicional_n)){
					$entorno = "Formula - $condicional_n";
				}
				else{
					$entorno = false;
				}

				if($cond["resultado"] == "leer_formula"){// si la evaluación de la condicional fue exitosa
					if($cond["porcentaje"]==true){
						throw new Exception("fue devuelto un porcentaje (".$cond["total"].") y no es valido como resultado entre las condiciones ", 1);
					}	
					if(($cond["total"] > 0)){ // si el resultado es mayor a cero (verdadero)



						$r = $this->leer_formula($formula,$variables);
						if($r["resultado"] == 'error'){
							throw new Exception($r["mensaje"], $r["calc_error"]);
						}
						$r["resultado"] = "leer_formula_condicional";
						$r["tipo"] = "condicional";
						$r["formula"] = $formula;
						$r["n_formula"] = (is_numeric($condicional_n))?$condicional_n:null;

					}
					else{
						$r =[];
						$r["resultado"] = "leer_formula_condicional";
						$r["total"] = NULL;
						$r["formula"] = $formula;
						$r["tipo"] = "condicional";

					}
				}
				else{
					throw new Exception($cond["mensaje"], $cond["calc_error"]);
				}

				
			}

		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['titulo'] = 'La formula no pudo ser calculada';
			$r["formula"] = $formula;

			$r['mensaje'] =  $e->getMessage();
			$r["calc_error"] = $e->getCode();
			$r["path2"] = $e->getTraceAsString();
		}








		return $r;
	}

	PRIVATE function resolve_groups($formula_array,$formula = null){

		for($i=0;$i<count($formula_array);$i++){
			$token = $formula_array[$i];
			if(is_array($token)){
				$formula_array[$i] = $this->resolve_groups($token);

				if(isset($formula_array[($i-1)]) and in_array($formula_array[($i-1)], ['+','-'])){
					$operador[] = '^[\\/]$';
					$operador[] = '^[\\*]$';
					$operador = implode("|", $operador);

					if(!isset($formula_array[($i-2)]) or preg_match("/$operador/", $formula_array[($i-2)])){
						if($formula_array[($i-1)] == '+'){
							$formula_array[$i] = 1 * $formula_array[$i];
							unset($formula_array[($i-1)]);
							$formula_array = array_values($formula_array);
							$i = -1;
							continue;
						}
						if($formula_array[($i-1)] == '-'){
							$formula_array[$i] = -1 * $formula_array[$i];
							unset($formula_array[($i-1)]);
							$formula_array = array_values($formula_array);
							$i = -1;
							continue;
						}
					}
				}

				//showvar($formula_array);
			}
		}


		$this->calc_nodes($formula_array,$formula);
		return $this->resolve_nodes($formula_array);

	}

	PRIVATE function calc_check_status(){
		if(!$this->calc_status) throw new Exception("Calculadora no inicializada", 1);
		if($this->calc_error) throw new Exception($this->calc_error, 1);
	}

	PRIVATE function calc_separador ($string){


		try {



			if($string ==''){
				throw new Exception("No hay una formula valida", 1);
			}

			$lista_invalido = [
				"script"
			];

			$lista_invalido = implode('|', $lista_invalido);
			$lista_invalido = "/$lista_invalido/";

			if(preg_match($lista_invalido, $string, $found) ){
				throw new Exception("'$found[0]' no es valido en la formula", 1);
			}








			$separadores = $this->calc_separadores;



			$separadores = implode("|", $separadores);

			$separadores = "/($separadores)/";

			$r = preg_split($separadores, $string, -1, PREG_SPLIT_DELIM_CAPTURE);



			$r = array_filter($r,"calc_filter_vacio");

			$operador[] = '^[\\+]$';
			$operador[] = '^[\\-]$';
			$operador[] = '^[\\/]$';
			$operador[] = '^[\\*]$';
			$operador[] = '^[\\(]$';
			$operador[] = '^[\\[]$';
			$operador[] = '^[\\{]$';
			$operador = implode("|", $operador);





			
			







			$r = array_values($r);

			$end = count($r);

			for($i = 0;$i<$end;$i++){

				

				
				if( isset($r[$i]) and ($r[$i] === '-' or $r[$i] === '+')){
					if( !isset($r[($i-1)] ) or ( isset($r[($i - 1)]) and preg_match("/$operador/", $r[($i - 1)]) ) ){

						if(isset($r[($i + 1)]) and (is_numeric($r[($i+1)]) or in_array($r[($i+1)], ["+","-"]) ) ){

							if(is_numeric($r[($i+1)])){
								$r[($i+1)] = $r[$i].$r[($i+1)];
								unset($r[$i]);
								continue;
							}
							else if($r[($i+1)] === $r[$i]) {
								$r[($i+1)] = "+";
								unset($r[$i]);
								continue;	
							}
							else if($r[($i+1)] !== $r[$i]) {
								$r[($i+1)] = "-";
								unset($r[$i]);
								continue;	
							}



						}

						
					}





					// if($i !== 0){
					// 	if(isset($r[($i - 1)]) and preg_match("/$operador/", $r[($i - 1)]) and is_numeric($r[($i + 1)])){ // anterior
					// 		$r[($i+1)] = $r[$i].$r[($i+1)];
					// 		unset($r[$i]);
					// 		continue;
					// 	}
					// }
				}

				if (isset($r[$i])){
					if (is_numeric($r[$i])){
						//$r[$i] = floatval(number_format($r[$i],2,'.',''));
						$r[$i] = floatval($r[$i]);
					}
				}
			}

			$r = array_values($r);

			return $r;
			
		} catch (Exception $e) {

			throw $e;
		}
	}


	PRIVATE function calc_variables(&$formula,$variables){

		$operador = [
			"^[\\+]$",
			"^[\\-]$",
			"^[\\/]$",
			"^[\\*]$",
			"^[\\(]$",
			"^[\\[]$",
			"^[\\{]$"
		];
		$operador = implode("|", $operador);

		for ($key = 0;$key < count($formula);$key++ ) {





			

			

			if(isset($formula[$key]) and preg_match("/[a-zA-Z]+(?:[_]*[a-zA-Z]*)?/", $formula[$key] )){// si encuentra nombres de variables o funciones en la formula
				if($formula[$key] !== "DEDICADA" and ($temp = $this->get_calc_function($formula[$key])) !== null){// si es una funcion
					$temp = floatval(number_format($temp,$this->calc_decimales_recibidos,'.',''));

					







					$temp_key = intval($key);

					if (isset($formula[$temp_key - 1]) and preg_match("/^(?:\+|\-)$/", $formula[$temp_key - 1])){
						$anterior = $formula[$temp_key - 1];
						// si el anterior al actual existe y si es un signo de "+" o "-"

						


						if ((isset($formula[$temp_key - 2]) and preg_match("/$operador/", $formula[$temp_key - 2])) or ($temp_key - 2) < 0 ){
							if($anterior =='-'){
								$temp = -1 * $temp;
							}
							else{
								$temp = 1 * $temp;
							}

							$formula[$key] = $temp;
							unset($formula[$temp_key - 1]);
							$formula = array_values($formula);
							$key = -1;
							continue;
						}
					}


					$formula[$key] = $temp;

					continue;
				}

				else if(($temp = $this->get_var($formula[$key],$variables) ) !== false){ // si es una variable del usuario





					$temp = floatval(number_format($temp,$this->calc_decimales_recibidos,'.',''));

					$temp_key = intval($key);

					if (isset($formula[$temp_key - 1]) and preg_match("/^(?:\+|\-)$/", $formula[$temp_key - 1])){

						$anterior = $formula[$temp_key - 1];
								// si el anterior al actual existe y si es un signo de "+" o "-"




						if ((isset($formula[$temp_key - 2]) and preg_match("/$operador/", $formula[$temp_key - 2])) or ($temp_key - 2) < 0 ){
							if($anterior =='-'){
								$temp = -1 * $temp;
							}
							else{
								$temp = 1 * $temp;
							}

							$formula[$key] = $temp;
							unset($formula[$temp_key - 1]);
							$formula = array_values($formula);
							$key = -1;
							continue;
						}
					}


					$formula[$key] = $temp;
					continue;
				}
				else if(isset($this->calc_list_formulas->{$formula[$key] })){// si es una formula almacenada con un nombre

					
					if( isset($this->calc_evaluando->{$formula[$key] }) and $this->calc_evaluando->{$formula[$key] } === 1 ){
						
						throw new Exception("Se esta pidiendo evaluar la formula '$formula[$key] ' en ciclo infinito revise la formula", 1);
					}
					else{
						$this->calc_evaluando->{$formula[$key] } = 1;
					}




					$name_form = $this->calc_list_formulas->{$formula[$key] }["name"];
					$formula_form = $this->calc_list_formulas->{$formula[$key] }["formula"];

					

					if(!is_array($formula_form)){// si no es una lista de formulas con condicionales
						// ESTOY Aqui 
						// ESTOY Aqui 
						// ESTOY Aqui 
						// ESTOY Aqui 
						$variables_form = $this->calc_list_formulas->{$formula[$key] }["variables"];

						if(isset( $this->calc_list_formulas->{$formula[$key] }["condiciones"] )){

							$condiciones = $this->calc_list_formulas->{$formula[$key] }["condiciones"];

							$form_resp = $this->leer_formula_condicional($condiciones, $formula_form, $variables_form);

							
						}
						else{
							$form_resp = $this->leer_formula($formula_form, $variables_form, false);
						}

						if($form_resp["resultado"] != "leer_formula" and $form_resp["resultado"] != "leer_formula_condicional" ){
							throw new Exception($form_resp["mensaje"], $form_resp["calc_error"]);
						}

						$temp = $form_resp["total"];
						//$formula[$key] = $form_resp["total"];

					}
					else{




						$form_resp = $this->leer_formula_condicional($formula_form);
						if($form_resp["resultado"] == "error"){
							throw new Exception($form_resp["mensaje"], $form_resp["calc_error"]);

						}
						
						$temp = $form_resp["total"];
						//$formula[$key] = $form_resp["total"];
					}
					$temp_key = intval($key);

					if (isset($formula[$temp_key - 1]) and preg_match("/^(?:\+|\-)$/", $formula[$temp_key - 1])){
						$anterior = $formula[$temp_key - 1];
						// si el anterior al actual existe y si es un signo de "+" o "-"

						


						if ((isset($formula[$temp_key - 2]) and preg_match("/$operador/", $formula[$temp_key - 2])) or ($temp_key - 2) < 0 ){
							if($anterior =='-'){
								$temp = -1 * $temp;
							}
							else{
								$temp = 1 * $temp;
							}

							$formula[$key] = $temp;
							unset($formula[$temp_key - 1]);
							$formula = array_values($formula);
							continue;
						}
					}


					if($temp === null){
						$temp = 0;
					}

					$before_formula_key = $formula[$key];
					$formula[$key] = $temp;

					$this->calc_evaluando->{$before_formula_key} = 0;
					continue;

				}
				else if($formula[$key] === 'DEDICADA'){
					$formula[$key] = 0;
					continue;
				}
				else{

					throw new Exception("La variable/función '".$formula[$key]."' no existe", 118);
					
				}
			}
		}
	}

	PRIVATE function get_formula_named($key){

		if(isset($this->calc_list_formulas->{$key})){

			if(isset($this->calc_evaluando->{$key })){
				throw new Exception("Se esta pidiendo evaluar la formula '$key ' en ciclo infinito revise la formula", 1);
			}
			else{
				$this->calc_evaluando->{$key } = 1;
			}

			$name_form = $this->calc_list_formulas->{$key}["name"];
			$formula_form = $this->calc_list_formulas->{$key}["formula"];

			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/



			
			if(!is_array($formula_form)){// si no es una lista de formulas con condicionales
				$variables_form = $this->calc_list_formulas->{$key }["variables"];

				if(isset( $this->calc_list_formulas->{$key }["condiciones"] )){

					$condiciones = $this->calc_list_formulas->{$key }["condiciones"];

					$form_resp = $this->leer_formula_condicional($condiciones, $formula_form, $variables_form);

				}
				else{
					$form_resp = $this->leer_formula($formula_form, $variables_form, false);
				}

				if($form_resp["resultado"] != "leer_formula" and $form_resp["resultado"] != "leer_formula_condicional" ){
					throw new Exception($form_resp["mensaje"], $form_resp["calc_error"]);
				}

				$temp = $form_resp;

			}
			else{
				$form_resp = $this->leer_formula_condicional($formula_form);
				if($form_resp["resultado"] == "error"){
					throw new Exception($form_resp["mensaje"], $form_resp["calc_error"]);

				}
				
				$temp = $form_resp;
			}

			return $temp;






			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/
			/****************************************************************************/




		}
		else{
			return NULL;
		}

	}

	PRIVATE function add_list_formulas($name,$formula,$descrip,$variables=null,$condiciones = null,$id_formula=null,$replace=false){

		if(!isset($this->calc_list_formulas->{$name}) or ( isset($this->calc_list_formulas->{$name}) and $replace = true ) ){
			if(!isset($this->calc_f->{$name})){// si la funcion no existe
				if(!isset($variables)){
					$variables = [];
				}
				$this->calc_list_formulas->{$name}["formula"] = $formula;
				$this->calc_list_formulas->{$name}["variables"] = $variables;
				$this->calc_list_formulas->{$name}["name"] = $name;
				$this->calc_list_formulas->{$name}["descrip"] = $descrip;
				$this->calc_list_formulas->{$name}["condiciones"] = $condiciones;
				$this->calc_list_formulas->{$name}["id_formula"] = $id_formula;
			}
			else{
				$this->calc_error = "La formula con el nombre '$name' esta tomando una palabra reservada del sistema necesita ser modificada antes de continuar";
			}
		}
		else {
			$this->calc_error = "La formula con el nombre '$name' esta duplicada y debe ser modificada antes de continuar";
		}
	}

	PRIVATE function update_list_formulas(){
		try {


			$this->validar_conexion($this->con);

			$consulta = $this->con->prepare("SELECT f.nombre, f.id_formula, f.descripcion, df.formula, df.variables, df.condicional FROM detalles_formulas AS df LEFT JOIN formulas AS f ON f.id_formula = df.id_formula WHERE 1;");
			$consulta->execute();
			$resp = $consulta->fetchall(PDO::FETCH_GROUP);

			if($resp){


				$this->calc_list_formulas = new stdClass();
				foreach ($resp as $key => $lista) {
					if(count($lista)>1){
						$formula_array = [];
						foreach ($lista as $elem) {
							$temp_array = [];
							$temp_array["condiciones"] = $elem["condicional"];
							$temp_array["formula"] = $elem["formula"];
							$elem["variables"] = ($elem["variables"] !== null)?json_decode($elem["variables"],true):NULL;
							$temp_array["variables"] = $elem["variables"];
							$temp_array["id_formula"] = $elem["id_formula"];
							$formula_array[] = $temp_array;


						}
						$descripcion = $lista[0]["descripcion"];
						$this->add_list_formulas($key, $formula_array, $descripcion);
					}
					else {
						$lista[0]["variables"] = ($lista[0]["variables"] !== NULL)?json_decode($lista[0]["variables"],true):NULL;
						$this->add_list_formulas($key, $lista[0]["formula"], $lista[0]["descripcion"], $lista[0]["variables"], $lista[0]["condicional"], $lista[0]["id_formula"]);
					}
				}


			}
		} catch (Exception $e) {

			$this->calc_error = "Error al actualizar la lista de formulas almacenadas";
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
	}

	PRIVATE function calc_groups($formula_array){












		$this->counter_loop++;

		$new_formula_array = [];

		$end = count($formula_array);
		$i = 0;
		$ignore = 0;
		$open[0] = false; // se abre un grupo
		$close[0] = false; // se cierra un grupo

		$group_found = [];


		for ($i;$i<$end;$i++){



			$token_switch = $token = $formula_array[$i];

			$token_switch = strval($token_switch);
			



			

			if($open[0] === false ){ // si todavia no ha encontrado un grupo
				switch ($token_switch) {
					case '(':
						$open[0] = '('; // abre el grupo
						$open[1] = $i; // guarda donde abrio
						$close[0] = ')'; // guarda como cierra
						
						break;
						case '[':
						$open[0] = '[';
						$open[1] = $i;
						$close[0] = ']';
						break;
						case '{':
						$open[0] = '{';
						$open[1] = $i;
						$close[0] = '}';
						break;

					default: // si no esta abierto ningun grupo y no encuentra apertura
					$new_formula_array[] = $token;
				}
				

			}
			else{ // si esta abierto un grupo
				


				if($token == $open[0]){ // si por ejemplo esta abierto un grupo con "(" pero encuentra otro "(" abierto
					$ignore++;
					$group_found[] = $token; // lo guarda y se prepara para ignorar el cierre del mismo
				}
				else if ($token == $close[0]){ // si encuentra el cierre
					if($ignore>0){ // si hay que ignorar algun cierre
						$ignore--;
						$group_found[] = $token;
					}
					else{// si no hay nada que ignorar
						if(count($group_found)<=0){
							throw new Exception("Las agrupaciones no pueden estar vaciás, existe una agrupación de tipo '".$open[0].$close[0]."' ", 103);
							
						}
						$open[0] = false; // elimina la apertura 
						$close[0] = false; // elimina el cierre 
						$close[1] = $i; // guarda donde cerro
					}
				}
				else{
					$group_found[] = $token;
				}
				

				if($open[0] === false){ // si se abrio un grupo pero ahora esta cerrado

					$group_found = $this->calc_groups($group_found); // reviso si hay otro grupo entre el grupo abierto antes


					if($open[1] == 0 and $close[1] == ($end - 1)){ // si el grupo coincide con todo el array
						$new_formula_array =  array_merge($new_formula_array,$group_found);
					}
					else{
						$new_formula_array[] = $group_found;
					}
					
				}
			}

			//echo "<pre>$i < $end and $open[0]</pre>";



		}
		if($open[0] !== false){
			throw new Exception("Se encontro un`'".$open[0]."' sin cerrar", 1);
		}

		return $new_formula_array;
	}


	PUBLIC function get_calc_reserved_words(){
		try {

			$this->calc_check_status();

			$lista = [];
			foreach ($this->calc_f as $key => $value) {
				$lista[] = ["name" => $key,"descrip"=> $value->descrip];
			}

			foreach ($this->calc_list_formulas as $key => $value) {
				$temp = ["name" => $key,"descrip" => $value["descrip"]];

				if(isset($value["id_formula"])){
					$temp["id"] = $value["id_formula"];
				}
				$lista[] = $temp;
			}
			
			$r['resultado'] = 'get_calc_reserved_words';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $lista;

		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		
		return $r;

	}
	PRIVATE function calc_nodes(&$formula_array = '' ,$formula=null){ // convierte todos los numero y los operadores en nodos
		$anterior = false;


		for($i=0;$i<count($formula_array);$i++){
			unset($token);
			$token = &$formula_array[$i];




			if(!$anterior){
				$anterior=true;
				$valor = $token;
				$token = new calc_nodo($token,true,$i);
				$token->set_decimales_internos($this->calc_decimales_recibidos);
				$token->formula = $formula;
				$token->set_value($valor);

				if(count($formula_array)==1){
					$token->set_unique(true);
				}
			}
			else{
				if( is_numeric( $formula_array[($i-1)]->get_value() ) and is_numeric($formula_array[$i]) ){// si encuentra dos numeros seguidos es resultado de una agrupacion similar a 2(5+3) o algo como 3x
					unset($token);
					array_splice($formula_array, $i,0,"*" );
					$i--;
				}
				else if($formula_array[($i-1)]->is_leaft()){
					$valor = $token;
					$token_anterior = &$formula_array[($i-1)];
					$token = new calc_nodo($token_anterior,$token,null,$i);
					$token->set_decimales_internos($this->calc_decimales_recibidos);
					$token->formula = $formula;
					$token_anterior->add_operador($token);
					$token->set_value($valor);

					$token->set_nodo_anterior($token_anterior);

				}
				else{
					$valor = $token;
					$token = new calc_nodo($token,true,$i);
					$token->set_decimales_internos($this->calc_decimales_recibidos);
					$token->formula = $formula;
					$token->set_value($valor);
					$formula_array[($i-1)]->add_right($token);
					$token->set_nodo_anterior($formula_array[($i-1)]);
				}


			}
		}
	}

	PRIVATE function resolve_nodes(&$formula_array = ''){// llama a que los nodos se resuelvan
		if($formula_array == ''){
			unset($formula_array);

			$formula_array = &$this->calc_items;
		}
		$formula_array[0]->resolver("*");
		$formula_array[0]->resolver("/");
		$formula_array[0]->resolver("+");
		$formula_array[0]->resolver("-");

		$total = $formula_array[0]->get_total();

		foreach ($formula_array as &$elem) {
			$elem = null;// los vacio por si acaso la memoria jode
		}
		

		return $total;
	}
	PRIVATE function set_calc_function($name,$descrip,$func,$arguments,$cache=false){//nombre de la funcion, la funcion misma, si tendra argumentos de la funcion si o no BOOL(no se usa),y si guarda el resultado en cache
		$this->calc_f->{$name} = new calc_functions($name,$descrip,$func,$arguments,$cache);
	}

	PRIVATE function get_calc_function($name){

		if(isset($this->calc_f->{$name})){
			return $this->calc_f->{$name}->execute();
		}
		else{
			return null;
		}
	}
	PRIVATE function set_calc_formula(&$value){
		$value = preg_replace("/\s+/", "", $value);
	}
	PRIVATE function get_var($key,$lista){


		$this->set_calc_formula($key);


		if($lista === null){
			$lista = [];
		}
		if(is_array($lista)){
			if(isset($lista[$key])){
				$resp = $lista[$key];

				if($key === "DEDICADA"){
					if(!isset($this->id_trabajador)){
						throw new Exception("EL id del trabajador no esta definido", 1);
					}



					$resp = 0;
					$lista_dedicada = json_decode($lista[$key],true);
					foreach ($lista_dedicada as $elem) {
						if($elem == $this->id_trabajador){
							$resp = 1;
							break;
						}
					}
					return $resp;
				}



				if($resp == '__!__'){
					throw new Exception("La variable '$key' no puede ser utilizada al calcular la variable '$key' ", 1);
				}

				$operadores_formula_var = implode("|", $this->calc_diff_var_formula);


				if(preg_match("/$operadores_formula_var/", $resp)){// si tiene operadores es una formula



					$temp_variables=$lista;

					$temp_variables[$key] = "__!__";

					$resp = $this->leer_formula($resp,$temp_variables,false,"{LANZAR}:".$key);

					$resp = $resp["total"];
				}






				else if(preg_match("/[a-zA-Z]+(?:[_]*[a-zA-Z]*)*/", $resp )){// si tiene letras es una variable o función
					
					$resp = preg_replace("/\s+/", "", $resp);
					$temp = $resp;


					if(isset($lista[$temp])){
						$temp_variables=$lista;

						$temp_variables[$key] = "__!__";

						$resp = $this->get_var($temp,$temp_variables);
					}
					else if($temp !== 'DEDICADA' and ($func_resp = $this->get_calc_function($temp) ) !== null){
						$resp = $func_resp;
					}
					else if($temp !== 'DEDICADA' and ($func_resp = $this->get_formula_named($temp) ) !== null){
						if(isset($func_resp["error"])){

							throw new Exception($func_resp["mensaje"], 1);
							
						}
						else{
							$func_resp = $func_resp["total"];
						}
						$resp = $func_resp;
					}


					else if ($temp === "DEDICADA"){
						$resp = 0;
					}
					else{
						throw new Exception("La variable/función '$temp' no existe", 118);
					}
				}

				return $resp;
			}
			else{
				return false;
			}
		}
		else{
			throw new Exception("la lista de variables debe ser un arreglo", 1);
			
		}

	}
	PUBLIC function get_all_var($lista){
		if(is_array($lista)){
			return json_encode($lista);
		}
		else{
			return null;
		}
	}
	PUBLIC function add_var($key,$value,&$lista=null){
		if(!isset($this->calc_f->{$key}) and !isset($this->calc_list_formulas->{$key})){
			if($lista===null){
				$lista = [];
			}

			$lista[$key] = $value;
			return $lista;
		}
		else{
			$this->calc_error = "El nombre de la variable '$key' no puede ser utilizada, es una variable/función del sistema reservada" ;
			return [];
		}
	}


	PUBLIC function get_lista_trabajadores(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT id_trabajador as id, CONCAT(nombre,' ',apellido) as nombre FROM trabajadores WHERE estado_actividad = true;");
			$consulta->execute();
			
			$r['resultado'] = 'get_lista_trabajadores';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);

		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}

			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}

	PUBLIC function calc_guardar_formula($formula, $nombre, $descripcion, $variables=NULL, $condicional=NULL, $orden=0, $commit_on_end=false, $lanzar_error=false, $update = false){

		$before_transaction = true;
		try {
			$this->calc_check_status(); // check constructor
			$this->validar_conexion($this->con);
			if(!$this->con->inTransaction()){
				$this->con->beginTransaction();
				$before_transaction = false;
			}

			if($variables !== NULL and !(is_string($variables)) ){
				$variables = json_encode($variables);
			}

			if(isset($this->get_calc_reserved_words()[$nombre])){
				throw new Exception("El nombre de la formulas '$nombre' ya existe o es una palabra reservada del sistema", 1);
			}

			if($update === false){
				$consulta = $this->con->prepare("SELECT 1 FROM formulas WHERE nombre = ?;"); // reviso el nombre en la bd
				$consulta->execute([$nombre]);

				if($consulta->fetch()){
					throw new Exception("La formula con el nombre '$nombre' ya existe", 1);
				}
				$consulta = null;



				$consulta = $this->con->prepare("INSERT INTO formulas  (nombre, descripcion) VALUES (?,?)");
				$consulta->execute([$nombre, $descripcion]);

				$last = $this->con->lastInsertId();

			}
			else{
				$consulta = $this->con->prepare("SELECT 1 FROM formulas WHERE id_formula = ?");
				$consulta->execute([$update]);

				if(!$consulta->fetch(PDO::FETCH_ASSOC)){
					throw new Exception("La formula a modificar no existe o fue eliminada", 1);
					
				}
				$consulta = null;

				$consulta = $this->con->prepare("UPDATE formulas set nombre = ?, descripcion = ? WHERE id_formula = ?");
				$consulta->execute([$nombre, $descripcion, $update]);
				$last = $update;

				$consulta = null;

				$consulta = $this->con->prepare("DELETE FROM detalles_formulas WHERE id_formula = ?");
				$consulta->execute([$update]);
				$consulta = null;

				$consulta = $this->con->prepare("DELETE FROM usando WHERE id_formula_uno = ?");
				$consulta->execute([$update]);
				$consulta = null;

			}

			$consulta = null;

			$consulta = $this->con->prepare("INSERT INTO detalles_formulas (id_formula, formula, variables, condicional, orden) VALUES (:id_formula, :formula, :variables, :condicional, :orden) ");
			$consulta->bindValue(":id_formula",$last);
			$consulta->bindValue(":formula",$formula);
			$consulta->bindValue(":variables",$variables);
			$consulta->bindValue(":condicional",$condicional);
			$consulta->bindValue(":orden",$orden);

			$consulta->execute();

			$usados = $this->get_named_form_used($formula);

			if(count($usados[0])>0){

				$consulta = $this->con->prepare("INSERT INTO usando VALUES (?,(SELECT id_formula FROM formulas WHERE nombre = ?))");

				foreach ($usados[0] as $elem) {
					$consulta->execute([$last,$elem]);
				}


			}






			// code
			
			$r['resultado'] = 'calc_guardar_formula';
			$r["last"] = $last;
			if($before_transaction === false or $commit_on_end === true){
				if($this->con->inTransaction()){
					$this->con->commit(); //TODO poner esto
				}
			}

		} catch (Exception $e) {
			if($before_transaction !== true){
				if($this->con instanceof PDO){
					if($this->con->inTransaction()){
						$this->con->rollBack();
					}
				}
			}

			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['code'] = $e->getCode();
			$r["line"] = $e->getLine();
			if($lanzar_error){
				$consulta = null;
				throw $e;
			}


		}
		finally{
			$consulta = null;
		}
		return $r;
	}

	PUBLIC function calc_guardar_formula_lista($formulas, $nombre, $descripcion, $commit_on_end=true, $lanzar_error = false, $update = false){
		$before_transaction = true;
		try {
			$this->calc_check_status(); // check constructor
			$this->validar_conexion($this->con);
			if(!$this->con->inTransaction()){
				$this->con->beginTransaction();
				$before_transaction = false;
			}
			if(!is_array($formulas)){
				throw new Exception("La lista de formulas debe de ser un array", 1);
			}
			if(isset($this->get_calc_reserved_words()[$nombre])){
				throw new Exception("El nombre de la formula '$nombre' ya existe o es una palabra reservada del sistema", 1);
			}

			if($update === false){

				$consulta = $this->con->prepare("SELECT 1 FROM formulas WHERE nombre = ?;"); // reviso el nombre en la bd
				$consulta->execute([$nombre]);

				if($consulta->fetch()){
					throw new Exception("La formula con el nombre '$nombre' ya existe", 1);
				}
				$consulta = null;

				$consulta = $this->con->prepare("INSERT INTO formulas  (nombre, descripcion) VALUES (?,?)");
				$consulta->execute([$nombre, $descripcion]);

				$last = $this->con->lastInsertId();
			}
			else{
				$consulta = $this->con->prepare("SELECT 1 FROM formulas WHERE id_formula = ?");
				$consulta->execute([$update]);

				if(!$consulta->fetch(PDO::FETCH_ASSOC)){
					throw new Exception("La formula a modificar no existe o fue eliminada", 1);
				}

				$consulta = null;

				$consulta = $this->con->prepare("SELECT 1 FROM formulas WHERE nombre = ? and id_formula <> ?");
				$consulta->execute([$nombre, $update]);

				if($consulta->fetch()){
					throw new Exception("El nombre de la formula '$nombre' ya esta siendo utilizado por otra formula", 1);
				}

				$consulta = null;

				$consulta = $this->con->prepare("UPDATE formulas set nombre = ?, descripcion = ? WHERE id_formula = ?");
				$consulta->execute([$nombre, $descripcion, $update]);

				$consulta = $this->con->prepare("DELETE FROM detalles_formulas WHERE id_formula = ?");
				$consulta->execute([$update]);
				$consulta = null;

				$consulta = $this->con->prepare("DELETE FROM usando WHERE id_formula_uno = ?");
				$consulta->execute([$update]);
				$consulta = null;


				$last = $update;
			}

			$query = "INSERT INTO detalles_formulas (id_formula, formula, variables, condicional, orden) VALUES ";
			$values_holder = array_fill(0, count($formulas), "(?,?,?,?,?)");
			$values_holder = implode(',', $values_holder);

			$query.=$values_holder;

			$consulta = $this->con->prepare($query);
			$i=1;
			$usando=[[],new stdClass()];
			foreach ($formulas as $elem) {
				$consulta->bindValue($i++,$last);
				$consulta->bindValue($i++,$elem["formula"]);
				$usando = $this->get_named_form_used($elem["formula"],$usando[1],$usando[0]);
				if(isset($elem["variables"])){
					$elem["variables"] = json_encode($elem["variables"]);
				}
				else{
					$elem["variables"] = NULL;	
				}
				$consulta->bindValue($i++,$elem["variables"]);
				$consulta->bindValue($i++,$elem["condiciones"]);
				$consulta->bindValue($i++,$elem["orden"]);
			}

			$consulta->execute();

			if(count($usando[0])>0){
				$consulta = $this->con->prepare("INSERT INTO usando VALUES (?,(SELECT id_formula FROM formulas WHERE nombre = ?))");
				foreach ($usando[0] as $elem) {
					$consulta->execute([$last, $elem]);
				}
			}


			$r['resultado'] = 'calc_guardar_formula_lista';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "";
			$r["last"] = $last;

			if($before_transaction === false or $commit_on_end === true){
				if($this->con->inTransaction()){
					$this->con->commit(); //TODO poner esto
				}
			}

		} catch (Exception $e) {
			if($lanzar_error===false){
				if($before_transaction === false or $commit_on_end === true){
					if($this->con instanceof PDO){
						if($this->con->inTransaction()){
							$this->con->rollBack();
						}
					}
				}
			}
			else{
				$consulta = null;
				throw $e;
			}

			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['code'] = $e->getCode();
			$r["path"] = $e->getTraceAsString();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r; 
	}

	PUBLIC function bd_leer_formula_s($id_formula,$lanzar=true){// probar formulas por id
		try {
			$this->validar_conexion($this->con);
			//$this->con->beginTransaction();
			
			$r = $this->bd_leer_formula($id_formula);
			
			$r['resultado'] = 'console';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "";
			//$this->con->commit();
		
		} catch (Exception $e) {
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r["line"] = $e->getLine();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}



	PRIVATE function bd_leer_formula($id_formula,$testing = '',$get_all_response=false){// lee la formula desde la bd por su id
		// devuelve nulo cuando es una formula con condicionales  y no se cumplió ninguna
		$this->calc_check_status();

		if(!isset($this->id_trabajador)){
			throw new Exception("ERROR de programación trabajador no seleccionado ", 1);
		}


		$consulta = $this->con->prepare("SELECT
			    f.nombre,
			    f.nombre,
			    df.id_formula,
			    df.formula,
			    df.variables,
			    df.condicional as condiciones,
			    df.orden
			    
			FROM
			    detalles_formulas AS df
			LEFT JOIN formulas AS f
			ON
			    f.id_formula = df.id_formula
			WHERE
			    df.id_formula = ?
			ORDER BY
			    df.orden ASC");
		$consulta->execute([$id_formula]);

		if($lista = $consulta->fetchall(PDO::FETCH_GROUP)){
			foreach ($lista as $elem) {
				if(count($elem)>1){

					$respuesta = $this->leer_formula_condicional($elem);
					
				}
				else{
					$formula = $elem[0];
					$formula["variables"] = json_decode($formula["variables"],true);
					if(isset($formula["condicional"])){
						$respuesta = $this->leer_formula_condicional($formula["condicional"],$formula["formula"],$formula["variables"]);
					}
					else{
						$respuesta = $this->leer_formula($formula["formula"],$formula["variables"]);
						
					}
				}

				break;
			}

			if($respuesta["resultado"] != 'leer_formula'  and $respuesta["resultado"] != "leer_formula_condicional"){
				$sms = "Error en '".$elem[0]["nombre"]."'<ENDL><ENDL>";
				$sms .= (isset($respuesta["resultado"]))?$respuesta["mensaje"]:'';
				throw new Exception($sms, 1);
			}


			if($get_all_response){
				return $respuesta;
			}
			else{
				return $respuesta["total"];
			}


		}
		else{
			throw new Exception("Formula inexistente", 1);
		}
	}



	PRIVATE function get_named_form_used($formula,$control_anterior=null,$arreglo_anterior=null){// obtiene las formulas nombradas por el usuario


		$this->set_calc_formula($formula); // limpio la formula de los espacios en blanco
		$formula_array = $this->calc_separador($formula); // separo los elementos
		if($control_anterior === null or $arreglo_anterior===null){
			$used = [];
			$control = new stdClass();
		}
		else{
			$used = $arreglo_anterior;
			$control = $control_anterior;
		}
		foreach ($formula_array as $key => $value) {
			if(isset($this->calc_list_formulas->{$value})){
				if(!isset($control->{$value})){
					$used[] = $value;
					$control->{$value} = 1;
				}
			}
		}
		return [$used,$control];
	}

	PUBLIC function set_obj_formula($obj){
		$obj = json_decode($obj,true);

		if($obj["tipo"] == 'unica'){
			$obj["variables"] = json_decode($obj["variables"],true);
		}
		$this->obj_formula = $obj;
	}


	PUBLIC function get_counter_loop(){
		return $this->counter_loop;
	}
	PUBLIC function set_counter_loop($value){
		$this->counter_loop = $value;
	}


}

	class calc_nodo{
		PRIVATE $left,$right,$operador,$leaft,$orden,$resolved,$value,$nodo_anterior,$total;
		PRIVATE $unique, $decimales_internos;
		PUBLIC $formula;

		PUBLIC function __construct($left,$operador,$right=null,$orden=null,$decimales_internos=4){


			if(is_numeric($operador)){
				$right = $operador;
				$operador = "*"; 
			}
			$this->resolved = false;

			if((!$left instanceof calc_nodo) and ( !is_numeric($left) )){

				if(!is_numeric( preg_replace("/%/", "", $left) )){
					throw new Exception("$left no es un numero valido para calcular", 1);
				}
			}
			if($right !=null and(!$right instanceof calc_nodo) and (!is_numeric($right))){
				if(!is_numeric( preg_replace("/%/", "", $right) )){
					throw new Exception("$right no es un numero valido para calcular", 1);
				}
			}
			$this->leaft = false;// es hoja
			$this->left = $left;
			$this->operador = $operador;
			$this->right = $right;
			$this->orden = $orden;
			$this->total = 0;
			$this->nodo_anterior = null;
			$this->unique = false;
			$this->decimales_internos = $decimales_internos;

			if($operador ===true){
				$this->leaft=true;
				$this->orden = $right;

				$this->right=null;
				$this->operador=null;
			}
		}

		PUBLIC function is_leaft(){
			return $this->leaft;
		}
		PUBLIC function add_right($token){
			$this->right=$token;
		}
		PUBLIC function add_operador($token){
			$this->operador=$token;
		}

		PUBLIC function get_operador(){
			return $this->operador;
		}

		PUBLIC function get_value(){
			return $this->value;
		}
		PUBLIC function set_value($value){
			$this->value = $value;
		}

		PUBLIC function resolver($control){

			if($this->unique){
				$valor = $this->get_value();
				if(is_numeric($valor)){

					$valor = floatval(number_format($valor,$this->decimales_internos,".",""));
					$this->set_total($valor);
					$this->set_resolved();
				}
				else if(is_numeric( preg_replace("/%/", "", $valor) )){
					$this->set_total($valor);
					$this->set_resolved();
				}
				else {throw new Exception("Fue devuelto un valor distinto de un numero", 999);
				}
				return true;
			}
			
			if(!$this->is_leaft() and $this->value == $control){


				$numeros_decimales = $this->decimales_internos;



				$left = ($this->left->resolved)?$this->left->total:$this->left->value;
				$right = ($this->right->resolved)?$this->right->total:$this->right->value;

				if(!is_numeric($left) ){
					if(preg_match("/%/", $left)){
						throw new Exception("El porcentaje ($left) no puede estar del lado izquierdo de la operación", 1);
						
					}
					else{
						throw new Exception("$left no es un numero valido para calcular", 1);
					}
				}

				$evaluando_porcentaje_derecho = false;

				if( is_string($right) and is_numeric( $righttemp = preg_replace("/%/", "", $right) ) ){
					$right = (Float) $righttemp;
					$evaluando_porcentaje_derecho = true;
					if($right === 0){
						$evaluando_porcentaje_derecho = false;
					}
				}
				else if (is_string($right)){
					throw new Exception("$right no es un numero valido para calcular", 1);

				}

				switch ($this->value) {
					case '*':

					if($evaluando_porcentaje_derecho===true){
						$right = $right / 100;
					}



					$total = $left*$right;
					$total = floatval(number_format($total, $numeros_decimales, '.',''));
					$this->set_total($total);
					$this->set_resolved();
					$this->right->resolver($control);
					break;

					case '/':
					
					if($right === 0){
						throw new Exception("No se puede dividir entre cero en la posicion (".($this->orden + 1).") de la formula '$this->formula'", 1);
					}

					if($evaluando_porcentaje_derecho===true){
						$right = $right / 100;
					}

					$total = $left / $right;
					$total = floatval(number_format($total, $numeros_decimales, '.',''));
					$this->set_total($total);
					$this->set_resolved();
					$this->right->resolver($control);
					break;

					case '+':

					if($evaluando_porcentaje_derecho===true){
						$right = ($right / 100) * $left;
					}

					$total = $left + $right;
					$total = floatval(number_format($total, $numeros_decimales, '.',''));
					$this->set_total($total);
					$this->set_resolved();
					$this->right->resolver($control);
					break;

					case '-':

					if($evaluando_porcentaje_derecho===true){
						$right = ($right / 100) * $left;
					}

					$total = $left - $right;
					$total = floatval(number_format($total, $numeros_decimales, '.',''));
					$this->set_total($total);
					$this->set_resolved();
					$this->right->resolver($control);
					break;
					
					default:
					throw new Exception("El operador (*,/,+,-) no es valido en la posicion ($this->orden)", 1);
				}
			}
			else if($this->operador instanceof calc_nodo){

				//echo "<pre>\nENTRO AQUI\n</pre>";
				$this->operador->resolver($control);// pasa al siguiente nodo
			}
			else if(!$this->is_leaft()){
				switch ($this->value) {
					case '*':
					case '+':
					case '-':
					case '/':
					$this->right->resolver($control);
					break;
					case ')':
					case ']':
					case '}':
					throw new Exception("hay un '$this->value' de cierre sin una apertura del mismo", 1);
					default:
					throw new Exception("El operador '$this->value' no es valido", 1);
					
				}
			}


			if($this->is_leaft() and $this->resolved !== true and $this->left===null and $this->right===null and $this->operador === null){

				$temp = ($this->is_leaft())?"true":"false";
				throw new Exception("Ocurrio un error con el nodo $this->orden is_leaft =  $temp valor = $this->value", 1);
				
			}
		}

		PRIVATE function set_resolved(){
			if($this->left instanceof calc_nodo) $this->left->resolved = true;
			if($this->right instanceof calc_nodo) $this->right->resolved = true;
			if($this->operador instanceof calc_nodo) $this->operador->resolved = true;
			$this->resolved = true;
		}

		PUBLIC function get_resolved(){
			return ($this->resolved)?"RESUELTO":"NO RESUELTO";
		}

		PUBLIC function set_nodo_anterior($nodo){
			if($nodo instanceof calc_nodo){
				$this->nodo_anterior = $nodo;
			}
			else{
				throw new Exception("El nodo anterior no es correcto ($this->orden)", 1);
			}
		}

		PUBLIC function get_nodo_anterior(){
			return (isset($this->nodo_anterior)) ? $this->nodo_anterior:NULL;
		}

		PUBLIC function set_total($total,$modificados=false){

			if($modificados===false){
				$modificados = new stdClass();
			}

			$modificados->{"nodo".$this->orden} = true;

			if((!$this->is_leaft()) and $this->resolved === false){
				$this->left->set_total($total, $modificados);
				$this->right->set_total($total, $modificados);
			}
			else{

				if( ($this->left instanceof calc_nodo) and $this->left->resolved === true and ( !isset( $modificados->{"nodo".$this->left->orden} ) ) ){
					$this->left->set_total($total, $modificados);
				}
				if(($this->operador instanceof calc_nodo) and $this->operador->resolved === true and ( !isset( $modificados->{"nodo".$this->operador->orden} ) ) ){
					$this->operador->set_total($total, $modificados);
				}
				if(($this->right instanceof calc_nodo) and $this->right->resolved === true and ( !isset( $modificados->{"nodo".$this->right->orden} ) ) ){
					$this->right->set_total($total, $modificados);
				}
				if(($this->nodo_anterior instanceof calc_nodo) and $this->nodo_anterior->resolved === true and ( !isset( $modificados->{"nodo".$this->nodo_anterior->orden} ) ) ){
					$this->nodo_anterior->set_total($total, $modificados);
				}
			}
			$this->total = $total;

		}
		PUBLIC function get_total(){
			return $this->total;
		}

		PUBLIC function get_unique(){
			return $this->unique;
		}
		PUBLIC function set_unique($value){
			$this->unique = $value;
		}
		PUBLIC function get_decimales_internos(){
			return $this->decimales_internos;
		}
		PUBLIC function set_decimales_internos($value){
			$value = intval($value);
			if(is_int($value)){
				$this->decimales_internos = $value;
			}
			else{
				echo "set_decimales_internos solo puede recibir números enteros\n\n";
				echo ["intento imprimir un array para ver la linea de codigo con el error"];
				die;
			}
		}
	}

	class calc_functions{
		PRIVATE $name, $func, $arguments, $cache, $control_cache;
		PUBLIC $descrip;
		function __construct($name,$descrip,$func,$arguments=false,$control_cache=false)
		{
			$this->name = $name;
			$this->func = $func;
			$this->arguments = $arguments;
			$this->cache = null;
			$this->control_cache=$control_cache;
			$this->descrip = $descrip;
		}

		PUBLIC function execute($arguments=null){

			if($this->control_cache===true and isset($this->cache)){
				$resp = $this->cache;
			}
			else{

				if($this->arguments){
					if(count($arguments)>0){
						foreach ($arguments as $elem) {
							if(!is_numeric($elem)){
								throw new Exception("Las funciones solo permiten números entre sus argumentos ($this->name)", 1);
							}
						}
						$f_temp = $this->func;
						$resp = $f_temp();
					}
					else{
						throw new Exception("Argumentos esperados en la la función '$this->name'", 1);
					}
				}
				else{
					$f_temp = $this->func;
					$resp = $f_temp();
				}

				if(!is_numeric($resp)){
					throw new Exception("La respuesta de la funcion '$this->name' debe ser un numero", 1);
				}
				if($this->control_cache===true){
					$this->cache = $resp;
				}
			}
			return $resp;
		}
		PUBLIC function has_arguments(){
			if($this->arguments){
				return true;
			}
			else{
				return false;
			}
		}
		PUBLIC function get_name(){
			return $this->name;
		}

		PUBLIC function cl_cache(){
			$this->cache=null;
		}
	}

	function calc_filter_vacio($string){
		if(!preg_match("/^\s*$/", $string)){
			return true;
		}
		else{
			return false;
		}
	}

	function showvar($var,$ret = true,$frase = ""){ // TODO quitar estos
		ob_start();
		
		echo "<pre>\n";
		var_dump($var);
		if($frase!=''){
			echo "\n$frase\n";
		}
		echo "</pre>";
		
		$valor = ob_get_clean();
		
		$r["resultado"] = "console";
		$r["mensaje"] = $valor;
		if($ret === "table" ){
			$r["resultado"] = "console-table";
			$r["mensaje"] =  json_encode($var);
			echo json_encode($r);
			exit;
		}

		if($ret==true){
			echo json_encode($r);
			exit;
		}
		else{
			echo $valor; exit;
		}
	}

?>