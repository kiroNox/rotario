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

	PRIVATE function valid_cedula_trabajador(){
		try {
			$this->validar_conexion($this->con);


			Validaciones::validarCedula($this->cedula);

			$consulta = $this->con->prepare("SELECT nombre, apellido, id_trabajador FROM trabajadores WHERE cedula = ?;");
			$consulta->execute([$this->cedula]);
			if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
				$nombre = preg_replace("/^\s*\b(\w+).*/u", "$1", $resp["nombre"]);
				$nombre .= preg_replace("/^\s*\b(\w+).*/u", " $1", $resp["apellido"]);
				
				

				$r['resultado'] = 'valid_cedula_trabajador';
				$r['mensaje'] =  $nombre;
				$r['id'] = $resp["id_trabajador"];

				$consulta = null;
				$consulta = $this->con->prepare("SELECT 1 FROM sueldo_base WHERE id_trabajador = ?;");
				$consulta->execute([$r['id']]);

				if(!$consulta->fetch()){
					$r["resultado"] = "no_existe";
					$r["mensaje"] = "El trabajador tiene no tiene un sueldo base registrado";
				}




			}
			else{
				$r["resultado"] = "no_existe";
				$r["mensaje"] = "La cedula del trabajador no existe";
			}
			$this->close_bd($this->con);
		
		} catch (Validaciones $e){
			
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
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

	PUBLIC function nueva_liquidacion_s($cedula){
		$this->set_cedula($cedula);
		return $this->nueva_liquidacion();
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

	PUBLIC function get_liquidacion_s($id){
		$this->set_id_liquidacion($id);
		return $this->get_liquidacion();
	}

	PRIVATE function get_liquidacion(){
		try {
			$this->validar_conexion($this->con);
			
			Validaciones::numero($this->id_liquidacion,"1,","El id de la liquidación no es valido"); 

			$consulta = $this->con->prepare("SELECT l.*,t.cedula,t.nombre,t.apellido,t.id_trabajador FROM liquidacion as l LEFT JOIN trabajadores as t on t.id_trabajador = l.id_liquidacion WHERE id_liquidacion = ?;");
			$consulta->execute([$this->id_liquidacion]);

			$r["datos_liquidacion"] = $datos_liquidacion = $consulta->fetch(PDO::FETCH_ASSOC);
			$consulta = null;


			$consulta = $this->con->prepare("
				SELECT
				    YEAR(fecha) AS anio,
				    MONTH(fecha) AS mes,
				    SUM( (sueldo_base + sueldo_integral) - sueldo_deducido ) AS total_pagos
				FROM
				    factura
				    LEFT JOIN trabajadores as t on t.id_trabajador = factura.id_trabajador
				    WHERE t.id_trabajador = :id_trabajador and fecha between :fecha_contrato and :fecha_liquidacion
				GROUP BY
				    YEAR(fecha), MONTH(fecha)
				ORDER BY
				    anio, mes");
			$consulta->bindValue(":id_trabajador",$datos_liquidacion["id_trabajador"]);
			$consulta->bindValue(":fecha_contrato",$datos_liquidacion["fecha_contrato"]);
			$consulta->bindValue(":fecha_liquidacion",$datos_liquidacion["fecha"]);

			$consulta->execute();


			$lista = $consulta->fetchall(PDO::FETCH_ASSOC);

			foreach ($lista as &$elem) {
				$elem["dias"] = cal_days_in_month(CAL_GREGORIAN, intval($elem["mes"]),intval($elem["anio"]));
				$elem["fecha"] = $elem["anio"]."-". MESES[$elem["mes"] - 1];
			}




			
			$r['resultado'] = 'get_liquidacion';
			$r["lista"] = $lista;
			//$this->con->commit();
			$this->close_bd($this->con);
		
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




	

	PRIVATE function nueva_liquidacion($onlyActive=true){
		try {
			$cedula = $this->cedula;
			$this->validar_conexion($this->con);

			Validaciones::validarCedula($cedula);


			if($onlyActive){
				$consulta = $this->con->prepare("SELECT id_trabajador FROM trabajadores WHERE cedula = ? AND estado_actividad IS TRUE;");
			}
			else{
				$consulta = $this->con->prepare("SELECT id_trabajador FROM trabajadores WHERE cedula = ?;");

			}
			$consulta->execute([$cedula]);

			if(!$id_trabajador = $consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("EL trabajador no existe o fue eliminado", 1);
			}

			$id_trabajador = $id_trabajador["id_trabajador"];

			$consulta = null;

			$consulta = $this->con->prepare("
				SELECT
				    YEAR(fecha) AS anio,
				    MONTH(fecha) AS mes,
				    SUM( (sueldo_base + sueldo_integral) - sueldo_deducido ) AS total_pagos
				FROM
				    factura
				    LEFT JOIN trabajadores as t on t.id_trabajador = factura.id_trabajador
				    WHERE t.id_trabajador = ? 
				GROUP BY
				    YEAR(fecha), MONTH(fecha)
				ORDER BY
				    anio, mes");
			$consulta->execute([$id_trabajador]);

			$lista = $consulta->fetchall(PDO::FETCH_ASSOC);

			foreach ($lista as &$elem) {
				$elem["dias"] = cal_days_in_month(CAL_GREGORIAN, intval($elem["mes"]),intval($elem["anio"]));
				$elem["fecha"] = $elem["anio"]."-". MESES[$elem["mes"] - 1];
			}



					




			$consulta = null;


			$consulta = $this->con->prepare("SELECT
			    t.creado
			FROM
				trabajadores as t
			WHERE
				t.cedula = ?");

			$consulta->execute([$cedula]);

						
			$r['resultado'] = 'nueva_liquidacion';
			$r["temp"] = $id_trabajador;
			$r['mensaje'] =  $consulta->fetch(PDO::FETCH_ASSOC);
			$r["lista"] = $lista;
			//$this->con->commit();

			$this->close_bd($this->con);
		
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
			$consulta = $this->con->prepare("SELECT t.*,sb.id_sueldo_base FROM trabajadores as t LEFT JOIN sueldo_base as sb on sb.id_trabajador = t.id_trabajador WHERE t.id_trabajador = ?;");
			$consulta->execute([$this->id_trabajador]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El trabajador no existe o fue eliminado", 1);
			}

			$consulta = $this->con->prepare("SELECT id_rol FROM trabajadores WHERE id_trabajador = ?;");

			//$consulta->execute([$this->id_trabajador]);

			if($resp["id_rol"] == '1'){
				throw new Exception("No es posible realizar el proceso de liquidación del trabajador debido al rol de administrador del trabajador. Por favor modifique el rol del trabajador", 1);
			}
			

			if($resp["estado_actividad"] =='0'){
				throw new Exception("EL trabajador no esta activo y por lo tanto no se puede completar la liquidación", 1);
			}

			if(!isset($resp["id_sueldo_base"])){
				throw new Exception("EL trabajador no tiene asignado un sueldo base y por lo tanto no se puede completar la liquidación", 1);
			}

			$fecha1 = $resp["creado"];
			$fecha2 = $this->fecha;

			$fecha1 = $fecha1.' 00:00:00';
			$fecha2 = $fecha2.' 23:59:59';
			if(strtotime($fecha1) > strtotime($fecha2)){
				throw new Exception("La fecha de la liquidación no puede anterior a la fecha de la contratación", 1);
			}

			$consulta = $this->con->prepare("INSERT INTO liquidacion (id_trabajador, monto, descripcion, fecha, fecha_contrato) VALUES (:id_trabajador, :monto, :descripcion, :fecha, :fecha_contrato);");
			$consulta->bindValue(":id_trabajador",$this->id_trabajador);
			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":descripcion",$this->motivo);
			$consulta->bindValue(":fecha",$this->fecha);
			$consulta->bindValue(":fecha_contrato",$resp["creado"]);

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


			$consulta = $this->con->prepare("UPDATE liquidacion set monto = :monto, descripcion = :descripcion, fecha = :fecha WHERE id_liquidacion = :id_liquidacion" );

			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":descripcion",$this->motivo);
			$consulta->bindValue(":fecha",$this->fecha);
			$consulta->bindValue(":id_liquidacion",$this->id_liquidacion);

			$consulta->execute();


			$liquidaciones = $this->load_liquidaciones();

			if($liquidaciones["resultado"]!='load_liquidaciones'){
				throw new Exception($liquidaciones["mensaje"], 1);
			}


			



			Bitacora::reg($this->con,"La liquidacion Nº$this->id_liquidacion fue modificada");
			$r['resultado'] = 'modificar_liquidacion';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "La liquidación ha sido modificada exitosamente";
			$r['lista'] =  $liquidaciones["mensaje"];
			$this->con->commit();
			$this->close_bd($this->con);
		
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

			// $consulta = $this->con->prepare("SELECT id_trabajador FROM liquidacion l WHERE l.id_liquidacion = ? and l.fecha = (select max(fecha) from liquidacion WHERE 1)");
			// $consulta->execute([$this->id_liquidacion]);

			// if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){

			// 	$consulta = $this->con->prepare("UPDATE trabajadores set estado_actividad = 1 WHERE id_trabajador = ?");
			// 	$consulta->execute([$resp["id_trabajador"]]);


			// }
		
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