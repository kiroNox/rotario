<?php 
/**
 * 
 */
class Facturar extends Conexion
{

	PRIVATE $con, $id, $anio, $mes, $from_noti,$to_noti;
	PRIVATE $id_trabajador;
	use Correos,Calculadora;
	
	
	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}
	}

	PUBLIC function load_facturas(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();




			$consulta = $this->con->prepare("SELECT if(f.status IS TRUE,'activo','inactivo') as status
				,t.cedula,CONCAT(t.nombre,' ',t.apellido) as nombre, f.fecha, ROUND((f.sueldo_base + f.sueldo_integral ) - f.sueldo_deducido,2) as sueldo_total, NULL as extra,f.id_factura, f.notificado FROM factura as f join trabajadores as t on t.id_trabajador = f.id_trabajador WHERE 1;");
			$consulta->execute();

			$resp = $consulta->fetchall(PDO::FETCH_GROUP);
			
			$r['resultado'] = 'load_facturas';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $resp;
			//$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
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
			$consulta = null;
		}
		return $r;
	}


	PUBLIC function detalles_factura_s($id){
		$this->set_id($id);

		return $this->detalles_factura();
	}


	PRIVATE function detalles_factura(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT t.cedula,CONCAT(t.nombre,' ',t.apellido) as nombre, ROUND((f.sueldo_base + f.sueldo_integral ) - f.sueldo_deducido,2) as sueldo_total,ROUND(f.sueldo_base + f.sueldo_integral,2) as suma_integral, NULL as extra,sb.tipo_nomina, f.* FROM factura as f join trabajadores as t on t.id_trabajador = f.id_trabajador left join sueldo_base as sb on sb.id_trabajador = t.id_trabajador WHERE f.id_factura = ?;");
			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La factura no existe o fue eliminada", 1);
			}

			$consulta = null;

			



			$consulta = $this->con->prepare("SELECT pg.descripcion,fpg.monto FROM factura_primas_generales fpg 
LEFT JOIN primas_generales as pg on pg.id_primas_generales = fpg.id_primas_generales 
LEFT JOIN factura f on f.id_factura = fpg.id_factura
WHERE f.id_factura = :id ;");


			$consulta->bindValue(":id",$this->id);;
			$consulta->execute();

			$resp2 = $consulta->fetchall(PDO::FETCH_ASSOC);


			
			$r['resultado'] = 'detalles_factura';
			$r["detalles"] = $resp2;
			$r["factura"] = $resp;
		
		}  catch (Exception $e) {
			
		
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

	PUBLIC function calcular_facturas_s($anio,$mes){
		$this->set_anio($anio);
		$this->set_mes($mes);
		return $this->calcular_facturas();
	}

	PRIVATE function calcular_facturas(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$fecha = $this->anio.'-'.$this->mes.'-01';
			$resp = $this->check_quincena(false);

			if($resp["resultado"] != "check_quincena" ){
				throw new Exception($resp["mensaje"], 1);
			}
			else if($resp["mensaje"] == "Mensualidad Pagada"){
				throw new Exception("Ya se han calculado los pagos del mes $this->anio-$this->mes", 1);
			}
			else{
				$quincena = $resp["mensaje"];
			}


			$consulta = $this->con->prepare("set @fecha_pago_inicio = ?, @quincena_pago = ?;");
			$consulta->execute([$fecha,$quincena]);

			$consulta = null;


			// elimino facturas que no fueron concluidas

			$consulta = $this->con->query("DELETE FROM factura WHERE status is false;");
			$consulta = null;

			// obtengo la lista de primas

				$consulta = $this->con->prepare("SELECT
					    p.id_primas_generales,
					    p.id_formula,
					    p.dedicada,
					    '[]' AS trabajadores
					FROM
					    primas_generales AS p
					WHERE
					    p.status IS TRUE AND id_formula IS NOT NULL AND p.dedicada IS FALSE AND ( (p.quincena IS FALSE AND :pagando = 2 ) OR (p.quincena IS TRUE) )
					UNION
					SELECT
					    p2.id_primas_generales,
					    p2.id_formula,
					    p2.dedicada,
					    CONCAT(\"[\", GROUP_CONCAT(tp.id_trabajador SEPARATOR ','), \"]\")
					FROM
					    trabajador_prima_general AS tp
					LEFT JOIN primas_generales AS p2
					ON
					    p2.id_primas_generales = tp.id_primas_generales
					WHERE
					    p2.status IS TRUE AND p2.id_formula IS NOT NULL AND tp.status IS TRUE and p2.dedicada IS TRUE AND ( (p2.quincena IS FALSE AND :pagando = 2 ) OR (p2.quincena IS TRUE) ) GROUP BY tp.id_primas_generales;");

				$consulta->execute([":pagando"=>$quincena]);
				$primas = $consulta->fetchall(PDO::FETCH_ASSOC);
				$consulta = null;

			// obtengo la lista de deducciones

				$consulta = $this->con->prepare("SELECT
					    d.id_deducciones,
					    d.id_formula,
					    d.dedicada,
					    d.islr,
					    '[]' AS trabajadores
					FROM
					    deducciones AS d
					WHERE
					    d.status IS TRUE AND d.id_formula IS NOT NULL AND d.dedicada IS FALSE AND ( (d.quincena IS FALSE AND :pagando = 2 ) OR (d.quincena IS TRUE) )
					UNION
					SELECT
					    dd.id_deducciones,
					    d.id_formula,
					    d.dedicada,
					    d.islr,
					    CONCAT(\"[\",GROUP_CONCAT(dd.id_trabajador SEPARATOR ','),\"]\")
					FROM
					    trabajador_deducciones AS dd
					JOIN deducciones AS d
					ON
					    d.id_deducciones = dd.id_deducciones
					WHERE
					d.status IS TRUE AND d.id_formula IS NOT NULL AND d.dedicada IS TRUE AND ( (d.quincena IS FALSE AND :pagando = 2 ) OR (d.quincena IS TRUE) ) GROUP BY dd.id_deducciones");

				$consulta->execute([":pagando"=>$quincena]);
				$deducciones = $consulta->fetchall(PDO::FETCH_ASSOC);
				$consulta = null;

			// obtengo la lista de trabajadores
			
				$consulta_trabajadores = $this->con->prepare("SELECT
						t.id_trabajador,
						sb.sueldo_base,
						sb.tipo_nomina

					FROM
						trabajadores AS t
					JOIN sueldo_base AS sb
					ON
						sb.id_trabajador = t.id_trabajador
					WHERE
						t.estado_actividad = TRUE
					GROUP BY
						t.id_trabajador;");

				$consulta_trabajadores->execute();


			// itero sobre la lista de trabajadores
			$this->calc_init();
			while ($trabajador = $consulta_trabajadores->fetch(PDO::FETCH_ASSOC)) {

				$this->set_id_trabajador($trabajador["id_trabajador"]);

				$consulta = $this->con->prepare("INSERT INTO factura 
					(id_trabajador,fecha , sueldo_base, sueldo_integral, sueldo_deducido, status,quincena)
					VALUES
					(
						:id_trabajador,
						LAST_DAY(:fecha),
						:sueldo,
						DEFAULT,
						DEFAULT,
						0,
						:quincena
					);");

				$consulta->bindValue(":id_trabajador", $trabajador["id_trabajador"]);
				$consulta->bindValue(":fecha", $fecha);
				$consulta->bindValue(":sueldo", $trabajador["sueldo_base"]);
				$consulta->bindValue(":quincena", $quincena);

				$consulta->execute();
				$consulta=null;


				$id_factura_last = $this->con->lastInsertId();

				
				foreach ($primas as $prima_elem) {
					$prima_elem["trabajadores"] = json_decode($prima_elem["trabajadores"]);
					if($prima_elem["dedicada"] == "1" and !in_array($trabajador["id_trabajador"], $prima_elem["trabajadores"]))
					{
						continue;
					}

					$valor_prima = $this->bd_leer_formula($prima_elem["id_formula"]);
					if($valor_prima!==NULL){
						$consulta = $this->con->prepare("INSERT INTO `factura_primas_generales`(
							    `id_primas_generales`,
							    `id_factura`,
							    `monto`
							)
							VALUES( 
								:id_primas_generales,
								:id_factura,
								:monto)");
						$consulta->bindValue(":id_primas_generales",$prima_elem["id_primas_generales"]);
						$consulta->bindValue(":id_factura",$id_factura_last);
						$consulta->bindValue(":monto",$valor_prima);
						$consulta->execute();
					}
				}

				foreach ($deducciones as $deduc_elem) {
					$deduc_elem["trabajadores"] = json_decode($deduc_elem["trabajadores"]);

					if($deduc_elem["dedicada"] == "1" and !in_array($trabajador["id_trabajador"], $deduc_elem["trabajadores"])){
						continue;
					}

					$valor_deduccion = $this->bd_leer_formula($deduc_elem["id_formula"],"777");
					if($valor_deduccion!== NULL){
						$consulta = $this->con->prepare("INSERT INTO `factura_deducciones`(
								`id_deduccion`,
								`id_factura`,
								`monto`,
								`islr`
							)
							VALUES(
								:id_deduccion
								,:id_factura
								,:monto
								,:islr
							)");

						$consulta->bindValue(":id_deduccion",$deduc_elem["id_deducciones"]);
						$consulta->bindValue(":id_factura",$id_factura_last);
						$consulta->bindValue(":monto",$valor_deduccion);
						$consulta->bindValue(":islr",$deduc_elem["islr"]);

						$consulta->execute();
					}


				}




				////******************************************
				////******************************************
				////******************************************
				////******************************************
				////******************************************
				////******************************************
				////******************************************
				////******************************************
				////******************************************



			}

			$consulta_trabajadores = null;

			
			$r['resultado'] = 'calcular_facturas';
			$this->con->commit();
		
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
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


	PRIVATE function calcular_facturas_new(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$fecha = $this->anio.'-'.$this->mes.'-01';
			
			$consulta = $this->con->prepare("call calcular_primas(?)");
			$consulta->execute([$fecha]);

			
			$r['resultado'] = 'calcular_facturas';
			$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
		}
		finally{
			$consulta = null;
		}
		return $r;
	}


	PUBLIC function imprimir_txt(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			$consulta = $this->con->prepare("SELECT
			SUM(
				ROUND(
					(
						f.sueldo_base + f.sueldo_integral
					) - f.sueldo_deducido,
					2
				)
                ) AS sueldos_totales
			FROM
				factura AS f
			JOIN trabajadores AS t
			ON
				t.id_trabajador = f.id_trabajador
			WHERE
				f.status is false;
                ");
			$consulta->execute();

			$sueldos_totales = $consulta->fetch(PDO::FETCH_ASSOC);
			$sueldos_totales = $sueldos_totales["sueldos_totales"];
			$sueldos_totales = str_replace(".", '', $sueldos_totales);
			$sueldos_totales = str_pad((string)$sueldos_totales, 15, "0", STR_PAD_LEFT);

			$consulta=null;


			$rows = [];

			$consulta = $this->con->prepare("SELECT
				t.numero_cuenta
				,t.cedula,
				CONCAT(t.nombre, ' ', t.apellido) AS nombre,
				DATE_FORMAT(f.fecha,'%d/%m/%y') as fecha,
				ROUND(
					(
						f.sueldo_base + f.sueldo_integral
					) - f.sueldo_deducido,
					2
				) AS sueldo_total,
				NULL AS extra,
				f.id_factura
			FROM
				factura AS f
			JOIN trabajadores AS t
			ON
				t.id_trabajador = f.id_trabajador
			WHERE
				f.status is false;");

						$consulta->execute();
						
						$vocales = ["a", "A", "e", "E", "i", "I", "o", "O", "u", "U", "n", "N"];
						$vocales_tildes = ["á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú", "ñ", "Ñ"];

						while ($el = $consulta->fetch(PDO::FETCH_ASSOC)) {



							$pago_str = str_replace("-", "", $el["cedula"]);
							$pago_str .= $el["numero_cuenta"];
							$el["sueldo_total"] = str_replace(".", '', $el["sueldo_total"]);
							$el["sueldo_total"] = str_pad((string)$el["sueldo_total"], 11, "0", STR_PAD_LEFT);
							$pago_str .= $el["sueldo_total"];

							$el["nombre"] = str_replace($vocales_tildes, $vocales, $el["nombre"]);
							$el["nombre"] = strtoupper($el["nombre"]);

							$pago_str .= $el["nombre"];

							$rows[]=$pago_str;
















							// $cedula = preg_replace("/^\D\D/", "", $el['cedula']);
							// $end = $cedula."003291";

							// $width = 16;
							// $padded_End = str_pad((string)$end, $width, "0", STR_PAD_LEFT);

							// $start = "00".$el["numero_cuenta"];
							// $start.= preg_replace("/\D/", "", $el["sueldo_total"]);
							// $start.= $el["nombre"];
							// $padded_start = str_pad((string)$start, 76, " ", STR_PAD_RIGHT);

						//	$rows[]=$padded_start.$padded_End;
						}

						if(count($rows)<=0){
							throw new Exception("No hay pagos pendientes por culminar", 1);
						}

						$fechaActual = date("Y-m-d-H-i-s");
						$filename = "archivo txt".$fechaActual.".txt";
						$filetemp = "assets/log/$filename";
						$file = fopen($filetemp, "a");
						$total_operaciones=count($rows);

						$total_operaciones = str_pad((string)$total_operaciones, 7, "0", STR_PAD_LEFT);

						if ($file) {
							//fwrite($file,"HSERVICIO DESCONCENTRADO HOSPITAL ROTARIO0102042245000060139902".date("d/m/y")."000000022111303291 \r\n");
							fwrite($file,"ONTNOM".EMPRESA_RIF_CLEAN.$total_operaciones.$sueldos_totales."VES".date("Ymd")."\r\n");
							
							foreach ($rows as $li) {
								fwrite($file, $li."\r\n");
							}
							
							fclose($file);
						}




						// Enviar el archivo como respuesta
						header('Content-Description: File Transfer');
						header('Content-Disposition: attachment; filename="' . $filename.'"');
						header('Content-Transfer-Encoding: binary');
						header('Content-Type: text/plain');
						header('Content-Length: ' . filesize($filetemp));
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');



						readfile($filetemp);
						unlink($filetemp);
						exit();

						


			
			$r['resultado'] = 'console';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $rows;
			//$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
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
		}
		return $r;
	}



	PUBLIC function concluir_facturas(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			$consulta = $this->con->prepare("SELECT 
				t.correo as email
				,t.cedula
				,CONCAT(t.nombre,' ',t.apellido) as nombre
				,f.id_trabajador
				,f.id_factura
				, f.fecha
				, f.sueldo_base
				, (f.sueldo_base + f.sueldo_integral) as sueldo_integral
				, sueldo_deducido 
				, ((f.sueldo_base + f.sueldo_integral) - f.sueldo_deducido) as sueldo_total
				,f.quincena
				FROM factura as f left JOIN trabajadores as t on t.id_trabajador = f.id_trabajador
				WHERE status = 0");

			$consulta->execute();
			$facturas = $consulta->fetchall(PDO::FETCH_ASSOC);
			$consulta = null;

			if(!$facturas){
				throw new Exception("No hay pagos a culminar", 1);
				
			}

			

	







			
			$consulta = $this->con->prepare("UPDATE factura set status = 1 WHERE status = 0");
			$consulta->execute();
			
			$r['resultado'] = 'concluir_facturas';
			$r["facturas"] = $facturas;
			$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
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
			$consulta=null;
		}
		return $r;
	}


	public function notificar_pagos(){
		try {
			$this->validar_conexion($this->con);
			//$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT 
				t.correo as email
				,t.cedula
				,CONCAT(t.nombre,' ',t.apellido) as nombre
				,f.id_trabajador
				,f.id_factura
				, f.fecha
				, f.sueldo_base
				, (f.sueldo_base + f.sueldo_integral) as sueldo_integral
				, sueldo_deducido 
				, ((f.sueldo_base + f.sueldo_integral) - f.sueldo_deducido) as sueldo_total
				,f.quincena
				FROM factura as f left JOIN trabajadores as t on t.id_trabajador = f.id_trabajador
				WHERE f.notificado = 0");

			$consulta->execute();
			$facturas = $consulta->fetchall(PDO::FETCH_ASSOC);
			$consulta = null;

			

			foreach ($facturas as &$elem) {
				$this->id = $elem["id_factura"];
				$elem["detalles"] = $this->detalles_factura()["detalles"];
				$consulta = $this->con->prepare("SELECT COALESCE(sum(monto),0) as islr FROM detalles_factura WHERE id_factura = ? AND islr IS TRUE;");
				$consulta->execute([$elem["id_factura"]]);;
				$elem["islr"] = $consulta->fetch(PDO::FETCH_ASSOC)["islr"];
				$consulta = null;

				if($elem["cedula"] = "V-27250544"){ // TODO solo envia correo a xavier


					//$this->enviar_correo($elem,"factura","Factura de Sueldo");


				}

				sleep(1);

				$consulta = $this->con->prepare("UPDATE factura set notificado = 1 WHERE id_factura = :id_factura");
				$consulta->execute([":id_factura"=>$this->id]);

				break;


			}
			if(!$facturas){
				$r["mensaje"] = "complete";
			}
			else{
				$r["mensaje"] = ["to"=>count($facturas)];
			}
			
			$r['resultado'] = 'notificar_pagos';
			$r['titulo'] = 'Éxito';
			//$this->con->commit();
			$this->close_bd($this->con);
		
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


	PUBLIC function check_quincena_s($anio,$mes){
		$this->set_anio($anio);
		$this->set_mes($mes);

		return $this->check_quincena();
	}


	PRIVATE function check_quincena($bd=true){// devuelve la siguiente quincena y "complete si ya estan las dos"
		try {
			$this->validar_conexion($this->con);
			
			Validaciones::numero($this->anio,"4","El año no es valido");
			Validaciones::numero($this->mes,"1,2","El mes no es valido");

			$consulta = $this->con->prepare("SELECT 1 from factura WHERE YEAR(fecha) = ? and MONTH(fecha) = ? and quincena = ? and status = 1 LIMIT 1; ");
			$consulta->execute([$this->anio,$this->mes,1]);
			$quincena_1 = $consulta->fetch();

			$consulta->execute([$this->anio,$this->mes,2]);
			$quincena_2 = $consulta->fetch();


			if($quincena_2 == false and $quincena_1 == false ){
				$r["mensaje"] = 1;
			}
			else if($quincena_1 == false ){
				$r["mensaje"] = 1;
			}
			else if($quincena_2 == false){
				$r["mensaje"] = 2;
			}
			else if ($quincena_1 !=false and $quincena_2 != false ){
				$r["mensaje"] = "Mensualidad Pagada";
			}
			
			$r['resultado'] = 'check_quincena';
			$r['titulo'] = 'Éxito';
			if($bd){
				$this->close_bd($this->con);
			}
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
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









	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_anio(){
		return $this->anio;
	}
	PUBLIC function set_anio($value){
		$this->anio = $value;
	}
	PUBLIC function get_mes(){
		return $this->mes;
	}
	PUBLIC function set_mes($value){
		$this->mes = $value;
	}

	PUBLIC function get_id_trabajador(){
		return $this->id_trabajador;
	}
	PUBLIC function set_id_trabajador($value){
		$this->id_trabajador = $value;
	}
	PUBLIC function get_from_noti(){
		return $this->from_noti;
	}
	PUBLIC function set_from_noti($value){
		$this->from_noti = $value;
	}
	PUBLIC function get_to_noti(){
		return $this->to_noti;
	}
	PUBLIC function set_to_noti($value){
		$this->to_noti = $value;
	}

	





}
 ?>