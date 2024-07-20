<?php 
/**
 * 
 */
class Facturar extends Conexion
{

	PRIVATE $con, $id, $anio, $mes;
	use Correos;
	
	
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
			//$this->con = null;
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

	





}
 ?>