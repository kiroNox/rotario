<?php 
/**
 * 
 */
class Liquidaciones extends Conexion
{

	PRIVATE $con, $cedula, $fecha ,$motivo ,$monto ,$id_trabajador ,$id_liquidacion;
	use Correos;
	
	
	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}
	}


	PUBLIC function load_liquidaciones(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->query("SELECT 
				l.id_liquidacion as id
				,l.id_trabajador
				,l.monto
				,l.descripcion as motivo
				,l.fecha
				,t.nombre
				,t.apellido
				,t.cedula
				,NULL as extra
				FROM liquidacion as l
				LEFT JOIN trabajadores as t on t.id_trabajador = l.id_trabajador
				WHERE 1;");
			
			$r['resultado'] = 'load_liquidaciones';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);;
		
		} catch (Exception $e) {
			
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
		}
		finally{
			$consulta = null;
		}
		return $r;
	}


	PUBLIC function valid_cedula_trabajador_s($cedula){
		$this->set_cedula($cedula);
		return $this->valid_cedula_trabajador();
	}

	PUBLIC function calcular_liquidacion_s($cedula,$id_liquidacion){
		$this->set_cedula($cedula);
		$this->set_id_liquidacion($id_liquidacion);
		return $this->calcular_liquidacion();
	}

	PUBLIC function registrar_liquidacion_s($id_trabajador ,$fecha ,$motivo ,$monto ){
		$this->set_id_trabajador($id_trabajador);
		$this->set_fecha($fecha);
		$this->set_motivo($motivo);
		$this->set_monto($monto);
		return $this->registrar_liquidacion();
	}

	PUBLIC function modificar_liquidacion_s($id_liquidacion, $id_trabajador ,$fecha ,$motivo ,$monto ){
		$this->set_id_liquidacion($id_liquidacion);
		$this->set_id_trabajador($id_trabajador);
		$this->set_fecha($fecha);
		$this->set_motivo($motivo);
		$this->set_monto($monto);
		return $this->modificar_liquidacion();
	}

	PUBLIC function eliminar_liquidacion_s($id_liquidacion){
		$this->set_id_liquidacion($id_liquidacion);
		return $this->eliminar_liquidacion();
	}




	

	PRIVATE function calcular_liquidacion(){
		try {
			$cedula = $this->cedula;
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			if($this->id_liquidacion===false){


				Validaciones::validarCedula($cedula);



				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE cedula = ? AND estado_actividad IS TRUE;");
				$consulta->execute([$cedula]);

				if(!$consulta->fetch()){
					throw new Exception("EL trabajador no existe o fue eliminado", 1);
				}

				$consulta = null;

			}
			else{
				$consulta = $this->con->prepare("SELECT 1 FROM liquidacion WHERE id_liquidacion = ?;");
				$consulta->execute([$this->id_liquidacion]);

				if(!$consulta->fetch()){
					throw new Exception("La liquidación no existe o fue eliminada", 1);
					
				}
			}






			$consulta = $this->con->prepare("SELECT
			    f.id_factura
			    ,f.id_trabajador
			    ,DATE_FORMAT(f.fecha,'%Y/%m') as fecha
			    ,SUM(f.sueldo_base) as sueldo_base
			    ,SUM(f.sueldo_integral + f.sueldo_base) as sueldo_integral
			    ,SUM(f.sueldo_deducido) as sueldo_deducido
			    ,f.status
			    ,t.creado
			FROM
			    factura AS f
			    LEFT JOIN trabajadores as t on t.id_trabajador = f.id_trabajador
			WHERE
				t.cedula = ?
			    GROUP BY id_trabajador, YEAR(fecha),MONTH(fecha)
			    ORDER BY id_trabajador,fecha ASC;");

			$consulta->execute([$cedula]);

			$lista = $consulta->fetchall(PDO::FETCH_ASSOC);
			$prestaciones = array();
			$acumulado = 0;
			for($i=0; $i<count($lista); $i = $i+3){
				$sueldo_integral_diario = floatval($lista[$i]["sueldo_integral"]) / 30 ;



				$acumulado = round($sueldo_integral_diario * 15,2) + $acumulado;
				$lista[$i]["acumulado"] = number_format($acumulado,2,'.','');

				$prestaciones[] = $lista[$i];
			}
			

			
			$r['resultado'] = 'calcular_liquidacion';
			$r['mensaje'] =  $prestaciones;
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
			$consulta = null;
		}
		return $r;
	}

	PRIVATE function registrar_liquidacion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			// TODO validaciones

			$consulta = $this->con->prepare("SELECT id_rol FROM trabajadores WHERE id_trabajador = ?;");

			$consulta->execute([$this->id_trabajador]);

			if($consulta->fetch(PDO::FETCH_ASSOC)["id_rol"] == '1'){
				throw new Exception("No es posible realizar el proceso de liquidación del trabajador por el rol de administrador del trabajador", 1);
			}
			
			$consulta = $this->con->prepare("SELECT t.*,sb.id_sueldo_base FROM trabajadores as t LEFT JOIN sueldo_base as sb on sb.id_trabajador = t.id_trabajador WHERE t.id_trabajador = ?;");
			$consulta->execute([$this->id_trabajador]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El trabajador no existe o fue eliminado", 1);
			}

			if($resp["estado_actividad"] =='0'){
				throw new Exception("EL trabajador no esta activo y por lo tanto no se puede completar la liquidación", 1);
			}

			if(!isset($resp["id_sueldo_base"])){
				throw new Exception("EL trabajador no tiene asignado un sueldo base y por lo tanto no se puede completar la liquidación", 1);
			}

			$consulta = $this->con->prepare("INSERT INTO liquidacion (id_trabajador, monto, descripcion, fecha) VALUES (:id_trabajador, :monto, :descripcion, :fecha);");
			$consulta->bindValue(":id_trabajador",$this->id_trabajador);
			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":descripcion",$this->motivo);
			$consulta->bindValue(":fecha",$this->fecha);
			$consulta->execute();

			$lastId = $this->con->lastInsertId();

			$consulta = $this->con->prepare("UPDATE trabajadores set estado_actividad = 0 WHERE id_trabajador = ?");
			$consulta->execute([$this->id_trabajador]);






			$liquidaciones = $this->load_liquidaciones();

			if($liquidaciones["resultado"]!='load_liquidaciones'){
				throw new Exception($liquidaciones["mensaje"], 1);
			}






			
			$r['resultado'] = 'registrar_liquidacion';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "La liquidación fue registra con éxito y el usuario fue deshabilitado";
			$r["id_liquidacion_inserted"] = $lastId ;
			$r['lista'] =  $liquidaciones["mensaje"];

			Bitacora::reg($this->con,"Registró la liquidación con el Nº$lastId ");
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
			$consulta = null;
		}
		return $r;
	}

	PUBLIC function notificar_liquidacion_s($id_liquidacion){
		$this->set_id_liquidacion($id_liquidacion);
		return $this->notificar_liquidacion();
	}

	PRIVATE function notificar_liquidacion(){
		try {
			$this->validar_conexion($this->con);

			$consulta = $this->con->prepare("SELECT l.monto, CONCAT(t.nombre,' ',t.apellido) as nombre, t.correo as email FROM liquidacion as l LEFT JOIN trabajadores as t on t.id_trabajador = l.id_trabajador WHERE id_liquidacion = ?;");			
			$consulta->execute([$this->id_liquidacion]);

			$resp = $consulta->fetch(PDO::FETCH_ASSOC);

			if($resp){
				$resp["asunto"] = "Notificación de procesamiento de liquidación de prestaciones sociales";
				$this->enviar_correo($resp,"liquidacion");
			}
			else{
				throw new Exception("No se pudo enviar el correo", 1);
				
			}

			
			$r['resultado'] = 'enviar_correo';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "EL correo fue enviado exitosamente";
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

				$consulta = $this->con->prepare("DELETE FROM liquidacion WHERE id_liquidacion = ? ");
				$consulta->execute([$this->id_liquidacion]);
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

	PRIVATE function modificar_liquidacion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT * FROM liquidacion WHERE id_liquidacion = ?;");
			$consulta->execute([$this->id_liquidacion]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La liquidación seleccionada no existe o fue eliminada", 1);
			}


			$consulta = $this->con->prepare("UPDATE liquidacion set monto = :monto, descripcion = :descripcion, fecha = :fecha" );

			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":descripcion",$this->motivo);
			$consulta->bindValue(":fecha",$this->fecha);

			$consulta->execute();
			Bitacora::reg($this->con,"La liquidacion Nº$this->id_liquidacion fue modificada");
			$r['resultado'] = 'modificar_liquidacion';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "La liquidación ha sido modificada exitosamente";
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
			$consulta = null;
		}
		return $r;
	}

	PRIVATE function eliminar_liquidacion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT * FROM liquidacion WHERE id_liquidacion = ?;");
			$consulta->execute([$this->id_liquidacion]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La liquidación no existe o fue eliminada", 1);
			}

			$consulta = $this->con->prepare("SELECT id_trabajador FROM liquidacion l WHERE l.id_liquidacion = ? and l.fecha = (select max(fecha) from liquidacion WHERE 1)");
			$consulta->execute([$this->id_liquidacion]);

			if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){

				$consulta = $this->con->prepare("UPDATE trabajadores set estado_actividad = 1 WHERE id_trabajador = ?");
				$consulta->execute([$resp["id_trabajador"]]);


			}
		
			$consulta = $this->con->prepare("DELETE FROM liquidacion WHERE id_liquidacion = ?");
			$consulta->execute([$this->id_liquidacion]);


			Bitacora::reg($this->con,"La liquidacion Nº$this->id_liquidacion fue eliminada");
			$r['resultado'] = 'eliminar_liquidacion';
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
	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}

	PUBLIC function get_fecha(){
		return $this->fecha;
	}
	PUBLIC function set_fecha($value){
		$this->fecha = $value;
	}
	PUBLIC function get_motivo(){
		return $this->motivo;
	}
	PUBLIC function set_motivo($value){
		$this->motivo = $value;
	}
	PUBLIC function get_monto(){
		return $this->monto;
	}
	PUBLIC function set_monto($value){
		$this->monto = $value;
	}
	PUBLIC function get_id_trabajador(){
		return $this->id_trabajador;
	}
	PUBLIC function set_id_trabajador($value){
		$this->id_trabajador = $value;
	}
	PUBLIC function get_id_liquidacion(){
		return $this->id_liquidacion;
	}
	PUBLIC function set_id_liquidacion($value){
		$this->id_liquidacion = $value;
	}

}
 ?>