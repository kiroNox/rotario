<?php 

class Profesionalismo extends Conexion
{

	PRIVATE $con, $id, $descripcion, $monto;



	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}
		else{
			$this->con = $con;
		}

	}



	PUBLIC function load_niveles(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->query("SELECT *,NULL as extra FROM prima_profesionalismo WHERE 1;");
			
			$r['resultado'] = 'load_niveles';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
		
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

	PUBLIC function registrar_nivel_educativo_s($descripcion, $monto){
		$this->set_descripcion($descripcion);
		$this->set_monto($monto);

		return $this->registrar_nivel_educativo();
	}
	PUBLIC function modificar_nivel_educativo_s($id, $descripcion, $monto){

		$this->set_id($id);
		$this->set_descripcion($descripcion);
		$this->set_monto($monto);

		return $this->modificar_nivel_educativo();
	}
	PUBLIC function eliminar_nivel_educativo_s($id){

		$this->set_id($id);

		return $this->eliminar_nivel_educativo();
	}
	PUBLIC function get_nivel_educativo_s($id){

		$this->set_id($id);

		return $this->get_nivel_educativo();
	}


	PRIVATE function registrar_nivel_educativo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			// TODO validaciones

			$consulta = $this->con->prepare("SELECT 1 FROM prima_profesionalismo WHERE descripcion = ?;");

			$consulta->execute([$this->descripcion]);

			if($consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("El nivel educativo ya esta registrado", 1);
			}

			$consulta = null;



			$consulta = $this->con->prepare("INSERT INTO prima_profesionalismo (descripcion, incremento) VALUES (?,?) ");
			$consulta->execute([$this->descripcion, $this->monto]);

			$niveles = $this->load_niveles();

			if($niveles['resultado'] != "load_niveles"){throw new Exception($niveles['mensaje'], 1); }


			Bitacora::reg($this->con,"Registró el nivel educativo ($this->descripcion)");

			
			$r['resultado'] = 'registrar_nivel_educativo';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "El nivel educativo fue registrado exitosamente";
			$r{"lista"} = $niveles["mensaje"];

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
			$consulta = null;
		}
		return $r;
	}
	PRIVATE function modificar_nivel_educativo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			// TODO validaciones


			$consulta = $this->con->prepare("SELECT descripcion FROM prima_profesionalismo WHERE id_prima_profesionalismo = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El nivel educativo no existe o fue eliminado", 1);
			}

			$consulta = null;

			$consulta = $this->con->prepare("UPDATE prima_profesionalismo set descripcion = ?, incremento = ? WHERE id_prima_profesionalismo = ? ");

			$consulta->execute([ $this->descripcion, $this->monto, $this->id ]);

			$consulta = null;

			$niveles = $this->load_niveles();

			if($niveles['resultado'] != "load_niveles"){throw new Exception($niveles['mensaje'], 1); }


			Bitacora::reg($this->con,"Modificó el nivel educativo (".$resp["descripcion"].")");



			$r['resultado'] = 'modificar_nivel_educativo';
			$r['titulo'] = 'Éxito';
			$r["mensaje"] = "El nivel educativo fue modificado exitosamente";
			$r['lista'] =  $niveles["mensaje"];
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
	PRIVATE function eliminar_nivel_educativo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			// TODO validaciones


			$consulta = $this->con->prepare("SELECT descripcion FROM prima_profesionalismo WHERE id_prima_profesionalismo = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El nivel educativo no existe o fue eliminado", 1);
			}

			$consulta = null;

			$consulta = $this->con->prepare("DELETE FROM prima_profesionalismo WHERE id_prima_profesionalismo = ? ");

			$consulta->execute([ $this->id ]);

			$consulta = null;

			$niveles = $this->load_niveles();

			if($niveles['resultado'] != "load_niveles"){throw new Exception($niveles['mensaje'], 1); }


			Bitacora::reg($this->con,"Eliminó el nivel educativo (".$resp["descripcion"].")");



			$r['resultado'] = 'eliminar_nivel_educativo';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $niveles["mensaje"];
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
			$consulta = null;
		}
		return $r;
	}
	PRIVATE function get_nivel_educativo(){
		try {
			$this->validar_conexion($this->con);


			// TODO Validaciones


			$consulta = $this->con->prepare("SELECT * FROM prima_profesionalismo WHERE id_prima_profesionalismo = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El nivel educativo no existe o fue eliminado", 1);
			}

			$consulta = null;





			$r['resultado'] = 'get_nivel_educativo';
			$r['mensaje'] =  $resp;
		
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
	PUBLIC function get_descripcion(){
		return $this->descripcion;
	}
	PUBLIC function set_descripcion($value){
		$this->descripcion = $value;
	}
	PUBLIC function get_monto(){
		return $this->monto;
	}
	PUBLIC function set_monto($value){
		$this->monto = $value;
	}

}


 ?>