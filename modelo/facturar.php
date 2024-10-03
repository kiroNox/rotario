<?php 
/**
 * 
 */
class Facturar extends Conexion
{

	PRIVATE $con, $id, $anio, $mes;
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
				,t.cedula,CONCAT(t.nombre,' ',t.apellido) as nombre, f.fecha, ROUND((f.sueldo_base + f.sueldo_integral ) - f.sueldo_deducido,2) as sueldo_total, NULL as extra,f.id_factura FROM factura as f join trabajadores as t on t.id_trabajador = f.id_trabajador WHERE 1;");
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

			 $consulta = $this->con->prepare("SELECT descripcion, monto from detalles_factura as d WHERE d.id_factura = :id AND d.prima IS true
			 UNION
			 SELECT descripcion, CONCAT('-',monto) from detalles_factura as d WHERE d.id_factura = :id AND (d.prima IS FALSE OR d.islr IS TRUE);");
			$consulta->bindValue(":id",$this->id);;
			$consulta->execute();

			if(!($resp2 = $consulta->fetchall(PDO::FETCH_ASSOC))){// TODO quitar esto


			$consulta = $this->con->prepare("call calcular_detalles(:id)");


			$consulta->bindValue(":id",$this->id);;
			$consulta->execute();

			$resp2 = $consulta->fetchall(PDO::FETCH_ASSOC);

			}

			
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

			$consulta = $this->con->prepare("SELECT 1 from factura WHERE status is true and fecha = LAST_DAY(?)");
			$consulta->execute([$fecha]);
			if($consulta->fetch()){
				throw new Exception("Ya se han calculado los pagos del mes $this->anio-$this->mes", 1);
			}

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
				    p.status IS TRUE AND id_formula IS NOT NULL
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
				    p2.status IS TRUE AND p2.id_formula IS NOT NULL AND tp.status IS TRUE GROUP BY tp.id_primas_generales;");

			$consulta->execute();
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
				    d.status IS TRUE AND d.id_formula IS NOT NULL
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
				    d.status IS TRUE AND d.id_formula IS NOT NULL GROUP BY dd.id_deducciones");

			$consulta->execute();
			$deducciones = $consulta->fetchall(PDO::FETCH_ASSOC);
			$consulta = null;

			// obtengo la lista de trabajadores
			
			$consulta_trabajadores = $this->con->prepare("SELECT
					t.id_trabajador,
					sb.sueldo_base
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
			while ($trabajador = $consulta_trabajadores->fetch(PDO::FETCH_ASSOC)) {

				$this->set_id_trabajador($trabajador["id_trabajador"]);

				$consulta = $this->con->prepare("INSERT INTO factura 
					(id_trabajador,fecha , sueldo_base, sueldo_integral, sueldo_deducido, status)
					VALUES
					(
						:id_trabajador,
						LAST_DAY(:fecha),
						:sueldo,
						DEFAULT,
						DEFAULT,
						0
					);");

				$consulta->bindValue(":id_trabajador", $trabajador["id_trabajador"]);
				$consulta->bindValue(":fecha", $fecha);
				$consulta->bindValue(":sueldo", $trabajador["sueldo_base"]);

				$consulta->execute();
				$consulta=null;


				$id_factura_last = $this->con->lastInsertId();

				$this->calc_init();


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

						while ($el = $consulta->fetch(PDO::FETCH_ASSOC)) {
							$cedula = preg_replace("/^\D\D/", "", $el['cedula']);
							$end = $cedula."003291";

							$width = 16;
							$padded_End = str_pad((string)$end, $width, "0", STR_PAD_LEFT);

							$start = "00".$el["numero_cuenta"];
							$start.= preg_replace("/\D/", "", $el["sueldo_total"]);
							$start.= $el["nombre"];
							$padded_start = str_pad((string)$start, 76, " ", STR_PAD_RIGHT);

							$rows[]=$padded_start.$padded_End;
						}

						$fechaActual = date("Y-m-d-H-i-s");
						$filename = "archivo txt".$fechaActual.".txt";
						$filetemp = "assets/log/$filename";
						$file = fopen($filetemp, "a");

						if ($file) {
							fwrite($file,"HSERVICIO DESCONCENTRADO HOSPITAL ROTARIO0102042245000060139902".date("d/m/y")."000000022111303291 \r\n");
							
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


	public function concluir_facturas(){
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
				FROM factura as f left JOIN trabajadores as t on t.id_trabajador = f.id_trabajador
				WHERE status = 0");

			$consulta->execute();
			$facturas = $consulta->fetchall(PDO::FETCH_ASSOC);
			$consulta = null;

			$this->con->commit();

			foreach ($facturas as &$elem) {
				$this->id = $elem["id_factura"];
				$elem["detalles"] = $this->detalles_factura()["detalles"];
				$consulta = $this->con->prepare("SELECT COALESCE(sum(monto),0) as islr FROM detalles_factura WHERE id_factura = ? AND islr IS TRUE;");
				$consulta->execute([$elem["id_factura"]]);;
				$elem["islr"] = $consulta->fetch(PDO::FETCH_ASSOC)["islr"];
				$consulta = null;

				$this->enviar_correo($elem,"factura","Factura de Sueldo");





			}







			
			$consulta = $this->con->prepare("UPDATE factura set status = 1 WHERE status = 0");
			
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

	





}
 ?>