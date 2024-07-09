<?php 
/**
 * 
 */
class Hijos extends Conexion
{
	PRIVATE $con, $id_hijo, $cedula_madre, $cedula_padre, $nombre, $fecha_nacimiento, $genero, $discapacidad, $observacion, $cedula;
	
	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}

	}

	PUBLIC function valid_parent_s($cedula){
		$this->set_cedula($cedula);

		return $this->valid_parent();
	}

	PUBLIC function registrar_hijo_s($cedula_madre, $cedula_padre, $nombre, $fecha_nacimiento, $genero, $discapacidad, $observacion){

		$this->set_cedula_madre($cedula_madre);
		$this->set_cedula_padre($cedula_padre);
		$this->set_nombre($nombre);
		$this->set_fecha_nacimiento($fecha_nacimiento);
		$this->set_genero($genero);
		$this->set_discapacidad($discapacidad);
		$this->set_observacion($observacion);

		return $this->registrar_hijo();
	}

	PUBLIC function modificar_hijo_s($id, $cedula_madre, $cedula_padre, $nombre, $fecha_nacimiento, $genero, $discapacidad, $observacion){

		$this->set_id_hijo($id);
		$this->set_cedula_madre($cedula_madre);
		$this->set_cedula_padre($cedula_padre);
		$this->set_nombre($nombre);
		$this->set_fecha_nacimiento($fecha_nacimiento);
		$this->set_genero($genero);
		$this->set_discapacidad($discapacidad);
		$this->set_observacion($observacion);

		return $this->modificar_hijo();
	}

	PRIVATE function modificar_hijo(){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			

			Validaciones::validarCedula($this->cedula_madre, true, true, "La cedula de la madre es invalida");
			Validaciones::validarCedula($this->cedula_padre, true, true, "La cedula de la madre es invalida");
			if($this->cedula_padre == '' and $this->cedula_madre == ''){
				throw new Exception("Debe registrar al menos un padre", 1);
			}
			Validaciones::validarNombre($this->nombre, "1,60");
			Validaciones::fecha($this->fecha_nacimiento,"fecha de nacimiento");
			Validaciones::alfanumerico($this->observacion,"0,100","caracteres no permitidos en la observación");
			if(!($this->discapacidad === true or $this->discapacidad === false)){
				throw new Exception("El valor para discapacidad no es valida", 1);
			}

			if($this->cedula_madre != ''){

				$madre_resp = $this->valid_parent_s($this->cedula_madre);

				if($madre_resp["resultado"] != "valid_cedula_parent"){
					throw new Exception($madre_resp["mensaje"]." (madre)", 1);
				}
			}
			else{
				$madre_resp["id"] = null;
			}

			if($this->cedula_padre != ''){

				$padre_resp = $this->valid_parent_s($this->cedula_padre);
				if($padre_resp["resultado"] != "valid_cedula_parent"){
					throw new Exception($padre_resp["mensaje"]." (padre)", 1);
				}

			}
			else{
				$padre_resp["id"] = null;
			}

			$consulta = $this->con->prepare("SELECT 1 FROM hijos WHERE id_hijo = ?;");
			$consulta->execute([$this->id_hijo]);

			if(!$consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("El hijo seleccionado no existe o fue eliminado", 1);
				
			}


			$consulta = $this->con->prepare("UPDATE `hijos` SET 
				`id_trabajador_madre`= :id_trabajador_madre,
				`id_trabajador_padre`= :id_trabajador_padre,
				`nombre`= :nombre,
				`fecha_nacimiento`= :fecha_nacimiento,
				`genero`= :genero,
				`discapacidad`= :discapacidad,
				`observacion`= :observacion 
				WHERE id_hijo = :id_hijo");


				$consulta->bindValue(":id_trabajador_madre",$madre_resp["id"]);
				$consulta->bindValue(":id_trabajador_padre",$padre_resp["id"]);
				$consulta->bindValue(":nombre",$this->nombre);
				$consulta->bindValue(":fecha_nacimiento",$this->fecha_nacimiento);
				$consulta->bindValue(":genero",$this->genero);
				$consulta->bindValue(":discapacidad",$this->discapacidad);
				$consulta->bindValue(":observacion",$this->observacion);
				$consulta->bindValue(":id_hijo",$this->id_hijo);

				$consulta->execute();


				if(isset($padre_resp["id"])){
					Bitacora::reg($this->con,"registro un hijo para $this->cedula_padre");
				}

				if(isset($madre_resp["id"])){
					Bitacora::reg($this->con,"registro un hijo para $this->cedula_madre");
				}







			
			$r['resultado'] = 'modificar_hijo';
			
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
		}
		return $r;

	}



	PUBLIC function eliminar_hijo_s($id){
		$this->set_id_hijo($id);

		return $this->eliminar_hijo();
	}

	PRIVATE function eliminar_hijo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT 1 FROM hijos WHERE id_hijo = ?;");

			$consulta->execute([$this->id_hijo]);

			if(!$consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("El hijo seleccionado no existe o fue eliminado", 1);
			}

			$consulta = $this->con->prepare("DELETE FROM hijos WHERE id_hijo = ?");
			$consulta->execute([$this->id_hijo]);


			

			
			$r['resultado'] = 'eliminar_hijo';
			Bitacora::reg($this->con,"Elimino un hijo del registro");
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
		}
		return $r;
	}

	PUBLIC function listar_hijos(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT 
		h.id_hijo as id,
		h.nombre as nombreHijo,
		h.fecha_nacimiento,
		m.cedula as cedulaMadre,
		p.cedula as cedulaPadre,
		h.genero,
		h.discapacidad,
		h.observacion,
		NULL as extra,
		m.nombre as nombreMadre,
		p.nombre as nombrePadre

		    
		FROM
		    `hijos` AS h
		LEFT JOIN trabajadores AS m
		ON m.id_trabajador = h.id_trabajador_madre
		LEFT JOIN trabajadores as p 
		on p.id_trabajador = h.id_trabajador_padre
		    
		WHERE
		    1 GROUP BY h.id_hijo,p.nombre,m.nombre ORDER BY h.id_hijo DESC;");

			$consulta->execute();
			
			$r['resultado'] = 'listar_hijos';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);;
			//$this->con->commit();
		
		} catch (Validaciones $e){
			
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			
		
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

	PUBLIC function get_hijo_s($id_hijo){
		$this->set_id_hijo($id_hijo);
		return $this->get_hijo();
	}

	PRIVATE function valid_parent(){
		try {
			$this->validar_conexion($this->con);
			
			Validaciones::validarCedula($this->cedula);

			$consulta = $this->con->prepare("SELECT nombre, apellido, id_trabajador FROM trabajadores WHERE cedula = ?;");
			$consulta->execute([$this->cedula]);
			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				$nombre = preg_replace("/^\s*\b(\w+).*/", "$1", $consulta["nombre"]);
				$nombre .= preg_replace("/^\s*\b(\w+).*/", " $1", $consulta["apellido"]);
				
				$r['resultado'] = 'valid_cedula_parent';
				$r['mensaje'] =  $nombre;
				$r['id'] = $consulta["id_trabajador"];
			}
			else{
				$r["resultado"] = "no_existe";
				$r["mensaje"] = "La cedula del trabajador no existe";
			}
		
		} catch (Validaciones $e){
			
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
					
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


	PRIVATE function registrar_hijo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			Validaciones::validarCedula($this->cedula_madre, true, true, "La cedula de la madre es invalida");
			Validaciones::validarCedula($this->cedula_padre, true, true, "La cedula de la madre es invalida");
			if($this->cedula_padre == '' and $this->cedula_madre == ''){
				throw new Exception("Debe registrar al menos un padre", 1);
			}
			Validaciones::validarNombre($this->nombre, "1,60");
			Validaciones::fecha($this->fecha_nacimiento,"fecha de nacimiento");
			Validaciones::alfanumerico($this->observacion,"0,100","caracteres no permitidos en la observación");
			if(!($this->discapacidad === true or $this->discapacidad === false)){
				throw new Exception("El valor para discapacidad no es valida", 1);
			}

			if($this->cedula_madre != ''){

				$madre_resp = $this->valid_parent_s($this->cedula_madre);

				if($madre_resp["resultado"] != "valid_cedula_parent"){
					throw new Exception($madre_resp["mensaje"]." (madre)", 1);
				}
			}
			else{
				$madre_resp["id"] = null;
			}

			if($this->cedula_padre != ''){

				$padre_resp = $this->valid_parent_s($this->cedula_padre);
				if($padre_resp["resultado"] != "valid_cedula_parent"){
					throw new Exception($padre_resp["mensaje"]." (padre)", 1);
				}

			}
			else{
				$padre_resp["id"] = null;
			}

			$consulta = $this->con->prepare("INSERT INTO hijos (id_trabajador_padre, id_trabajador_madre, nombre, fecha_nacimiento, genero, discapacidad, observacion) VALUES (:id_trabajador_padre, :id_trabajador_madre, :nombre, :fecha_nacimiento, :genero, :discapacidad, :observacion)");
			$consulta->bindValue(":id_trabajador_padre",$padre_resp["id"]);
			$consulta->bindValue(":id_trabajador_madre",$madre_resp["id"]);
			$consulta->bindValue(":nombre",$this->nombre);
			$consulta->bindValue(":fecha_nacimiento",$this->fecha_nacimiento);
			$consulta->bindValue(":genero",$this->genero);
			$consulta->bindValue(":discapacidad",$this->discapacidad);
			$consulta->bindValue(":observacion",$this->observacion);

			$consulta->execute();
			if(isset($padre_resp["id"])){
				Bitacora::reg($this->con,"registro un hijo para $this->cedula_padre");
			}

			if(isset($madre_resp["id"])){
				Bitacora::reg($this->con,"registro un hijo para $this->cedula_madre");
			}



			// code
			
			$r['resultado'] = 'registrar_hijo';
			$r['titulo'] = 'Éxito';
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
		}
		return $r;
	}

	PRIVATE function get_hijo(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT 
		h.id_hijo,
		h.nombre,
		h.fecha_nacimiento,
		m.cedula as cedulaMadre,
		p.cedula as cedulaPadre,
		h.genero,
		h.discapacidad,
		h.observacion,
		m.nombre as nombreMadre,
		p.nombre as nombrePadre

		    
		FROM
		    `hijos` AS h
		LEFT JOIN trabajadores AS m
		ON m.id_trabajador = h.id_trabajador_madre
		LEFT JOIN trabajadores as p 
		on p.id_trabajador = h.id_trabajador_padre
		    
		WHERE
		    id_hijo = ? GROUP BY h.id_hijo,p.nombre,m.nombre;");

		    $consulta->execute([$this->id_hijo]);

			
			$r['resultado'] = 'get_hijo';
			$r['mensaje'] =  $consulta->fetch(PDO::FETCH_ASSOC);
			//$this->con->commit();
		
		} catch (Validaciones $e){
			
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			
		
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






	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
	PUBLIC function get_id_hijo(){
		return $this->id_hijo;
	}
	PUBLIC function set_id_hijo($value){
		$this->id_hijo = $value;
	}
	PUBLIC function get_nombre(){
		return $this->nombre;
	}
	PUBLIC function set_nombre($value){
		$value = ucwords($value);
		$this->nombre = $value;
	}
	PUBLIC function get_fecha_nacimiento(){
		return $this->fecha_nacimiento;
	}
	PUBLIC function set_fecha_nacimiento($value){
		$this->fecha_nacimiento = $value;
	}
	PUBLIC function get_genero(){
		return $this->genero;
	}
	PUBLIC function set_genero($value){
		$this->genero = $value;
	}
	PUBLIC function get_discapacidad(){
		return $this->discapacidad;
	}
	PUBLIC function set_discapacidad($value){
		$this->discapacidad = $value;
	}
	PUBLIC function get_observacion(){
		return $this->observacion;
	}
	PUBLIC function set_observacion($value){
		$this->observacion = $value;
	}


	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}

	PUBLIC function get_cedula_madre(){
		return $this->cedula_madre;
	}
	PUBLIC function set_cedula_madre($value){
		$this->cedula_madre = $value;
	}
	PUBLIC function get_cedula_padre(){
		return $this->cedula_padre;
	}
	PUBLIC function set_cedula_padre($value){
		$this->cedula_padre = $value;
	}
}
 ?>