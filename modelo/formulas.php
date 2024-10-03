
<?php

class Formulas extends Conexion
{
	use Calculadora;
	PRIVATE $con, $id, $descripcion, $nombre;
	PRIVATE $id_trabajador;

	function __construct($con = '')
	{
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}
		else{
			$this->con = $con;
		}
	}

	PUBLIC function get_formula_s($id){
		$this->set_id($id);
		return $this->get_formula();
	}

	PRIVATE function get_formula(){
		try {
			$this->validar_conexion($this->con);
			//$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT df.* ,f.nombre ,f.descripcion FROM detalles_formulas AS df LEFT JOIN formulas as f on f.id_formula = df.id_formula WHERE f.id_formula = ? ORDER BY df.orden;");
			$consulta->execute([$this->id]);

			if( $resp_formulas = $consulta->fetchall(PDO::FETCH_ASSOC) ){

				$resp["calc_formula"] = $resp_formulas;
				$r["id_formula"] = $resp_formulas[0]["id_formula"];
				$r['resultado'] = 'get_formula';
				$r['mensaje'] =  $resp;
			}
			else{
				throw new Exception("La Formula no existe o fue eliminada", 1);
				
			}
			//$this->con->commit();
		
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
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}

	PUBLIC function registrar_formula_s($formula){
		$this->set_obj_formula($formula);
		return $this->registrar_formula();
	}

	PRIVATE function registrar_formula(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$this->calc_init();
			
			if($this->obj_formula["tipo"] == "lista"){
				$id_formula = $this->calc_guardar_formula_lista($this->obj_formula["lista"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], false, true);
			}
			else{
				$id_formula = $this->calc_guardar_formula($this->obj_formula["formula"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], $this->obj_formula["variables"], $this->obj_formula["condicional"], 0, false, true);
			}


			if($id_formula["resultado"] == 'error'){
				throw new Exception($id_formula["mensaje"],$id_formula["code"]);
			}

			
			$r['resultado'] = 'registrar_formula';
			$r["mensaje"] = "La formula fue registrada exitosamente";

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
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}

		return $r;
	}


	PUBLIC function modificar_formula_s($formula,$id){
		$this->set_obj_formula($formula);
		$this->set_id($id);
		return $this->modificar_formula();
	}

	PRIVATE function modificar_formula(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$this->calc_init();

			$id_formula = $this->id;
			
			if($this->obj_formula["tipo"] == "lista"){
				$id_formula = $this->calc_guardar_formula_lista($this->obj_formula["lista"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], false, true, $id_formula);
			}
			else{
				$id_formula = $this->calc_guardar_formula($this->obj_formula["formula"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], $this->obj_formula["variables"], $this->obj_formula["condicional"], 0, false, true, $id_formula);
			}


			if($id_formula["resultado"] == 'error'){
				throw new Exception($id_formula["mensaje"],$id_formula["code"]);
			}
			
			$r['resultado'] = 'modificar_formula';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "La formula fue modificada exitosamente";
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
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}

	PUBLIC function eliminar_formula_s($id){
		$this->set_id($id);
		return $this->eliminar_formula();
	}

	PRIVATE function eliminar_formula(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT nombre FROM formulas WHERE id_formula = ?;");
			$consulta->execute([$this->id]);

			if(!$formula_org = $consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("La formula no existe o fue eliminada", 1);
			}

			$consulta =null; 

			$consulta = $this->con->prepare("SELECT
											    *
											FROM
											    formulas AS f
											LEFT JOIN deducciones AS d
											ON
											    d.id_formula = f.id_formula
											LEFT JOIN primas_generales AS pg
											ON
											    pg.id_formula = f.id_formula
											WHERE
											    (pg.id_formula IS NOT NULL OR d.id_formula IS NOT NULL) and f.id_formula = ?");

			$consulta->execute([$this->id]);

			if($resp_pd = $consulta->fetch()){
				throw new Exception("La formula no puede ser eliminada desde aquí, esta relacionada con una prima/deducción ", 1);
			}
			
			$consulta = $this->con->prepare("SELECT f.nombre FROM usando AS u LEFT JOIN formulas as f on f.id_formula = u.id_formula_uno WHERE u.id_formula_dos = ?");
			$consulta->execute([$this->id]);

			if($lista = $consulta->fetchall(PDO::FETCH_ASSOC)){
				$msg = '';
				foreach ($lista as $elem) {
					$nombre = $elem["nombre"];
					$msg .= "'$nombre'<ENDL>";
				}

				throw new Exception("La formula seleccionada no puede ser eliminada ya que esta siendo utilizada por las siguientes formulas.<ENDL>".$msg."<ENDL>", 1);
				
			}


			$consulta = $this->con->prepare("DELETE FROM formulas WHERE id_formula = ?");
			$consulta->execute([$this->id]);
			
			$r['resultado'] = 'eliminar_formula';
			$this->con->commit();

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



	PUBLIC function load_list_formulas(){
		try {
			$this->validar_conexion($this->con);

			$consulta = $this->con->query("SELECT
					f.id_formula
					,f.nombre
					,f.descripcion
					,NULL as extra
				FROM
					formulas AS f
				LEFT JOIN primas_generales AS pg
				ON
					pg.id_formula = f.id_formula
				LEFT JOIN deducciones AS d
				ON
					d.id_formula = f.id_formula
				WHERE
					d.id_formula IS NULL AND pg.id_formula IS NULL;");

			$resp = $consulta->fetchall(PDO::FETCH_ASSOC);


			$r['resultado'] = 'load_list_formulas';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $resp;
		
		} catch (Exception $e) {
		
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
	PUBLIC function get_descripcion(){
		return $this->descripcion;
	}
	PUBLIC function set_descripcion($value){
		$this->descripcion = $value;
	}
	PUBLIC function get_nombre(){
		return $this->nombre;
	}
	PUBLIC function set_nombre($value){
		$this->nombre = $value;
	}
	PUBLIC function get_id_trabajador(){
		return $this->id_trabajador;
	}
	PUBLIC function set_id_trabajador($value){
		$this->id_trabajador = $value;
	}


	
} 
?>