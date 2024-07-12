<?php

class Primas extends Conexion
{
	
	PRIVATE $con, $id, $descripcion ,$monto ,$hijo_menor ,$hijo_discapacidad ,$porcentaje;
	PRIVATE $year;

	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}

	}

	PUBLIC function load_all_primas(){
		try {

			$generales = $this->load_primas_generales();
			$hijos = $this->load_primas_hijos();
			$antiguedad = $this->load_primas_antiguedad();
			$escalafon = $this->load_primas_escalafon();

			if($generales['resultado'] != "load_primas_generales"){throw new Exception($generales['mensaje'], 1); }
			if($hijos['resultado'] != "load_primas_hijos"){throw new Exception($hijos['mensaje'], 1); }
			if($antiguedad['resultado'] != "load_primas_antiguedad"){throw new Exception($antiguedad['mensaje'], 1); }
			if($escalafon['resultado'] != "load_primas_escalafon"){throw new Exception($escalafon['mensaje'], 1); }

			$r["mensaje"]["generales"] = $generales["mensaje"];
			$r["mensaje"]["hijos"] = $hijos["mensaje"];
			$r["mensaje"]["antiguedad"] = $antiguedad["mensaje"];
			$r["mensaje"]["escalafon"] = $escalafon["mensaje"];
			
			$r['resultado'] = 'load_all_primas';
			$r['titulo'] = 'Éxito';
		
		} catch (Exception $e) {

			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function load_primas_generales(){
		try {
			$this->validar_conexion($this->con);


			$consulta = $this->con->prepare("SELECT
					pg.id_primas_generales as id
				    ,pg.descripcion
				    ,IF (pg.porcentaje IS TRUE,CONCAT(pg.monto,'%'), CONCAT(pg.monto,' Bs')) as monto
				    ,IF (pg.sector_salud IS TRUE,'Si','No') as sector_salud
				    ,IF (pg.dedicada IS FALSE,'Todos',IF(temp.total_trabajadores IS NOT NULL, temp.total_trabajadores,0)) as dedicada
				    ,NULL as extra
				FROM primas_generales AS pg
				LEFT JOIN (
				    SELECT
				    tp.id_primas_generales
				    ,COUNT(tp.id_primas_generales) as total_trabajadores
				    FROM trabajador_prima_general as tp
				    WHERE
				    tp.status IS TRUE GROUP BY tp.id_primas_generales
				) as temp ON temp.id_primas_generales = pg.id_primas_generales
				WHERE
				    1;");


			$consulta->execute();





			$r['resultado'] = 'load_primas_generales';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
			//$this->con->commit();
		
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


	PUBLIC function load_primas_hijos(){
		try {
			$this->validar_conexion($this->con);


			$consulta = $this->con->prepare("SELECT
			    p.id_prima_hijos as id
			    ,p.descripcion
			    ,IF (p.porcentaje IS TRUE,CONCAT(p.monto,'%'), CONCAT(p.monto,' Bs')) as monto
			    ,IF (p.menor_edad IS TRUE,'Si','No') as menor_edad
			    ,IF (p.discapacidad IS TRUE,'Si','No') as discapacidad
			    ,NULL as extra
			FROM
			    primas_hijos as p
			WHERE
			    1;");


			$consulta->execute();





			$r['resultado'] = 'load_primas_hijos';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
			//$this->con->commit();
		
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

	PUBLIC function load_primas_antiguedad(){
		try {
			$this->validar_conexion($this->con);


			$consulta = $this->con->prepare("SELECT
			    a.id_prima_antiguedad as id
			    ,IF (a.anios_antiguedad = 1,CONCAT(a.anios_antiguedad,' Año'),CONCAT(a.anios_antiguedad,' Años')) as tiempo
			    ,CONCAT(a.monto,'%') as monto
			    ,NULL as extra
			FROM
			    prima_antiguedad AS a
			WHERE
			    1 ORDER BY a.anios_antiguedad;");


			$consulta->execute();





			$r['resultado'] = 'load_primas_antiguedad';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
			//$this->con->commit();
		
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


	PUBLIC function load_primas_escalafon(){
		try {
			$this->validar_conexion($this->con);


			$consulta = $this->con->prepare("SELECT
					e.id_escalafon as id
				    ,e.anios_servicio as tiempo
				    ,e.escala
				    ,CONCAT(e.monto,'%') as monto
				    ,NULL as extra
				FROM escalafon as e 
				WHERE 1
				;");


			$consulta->execute();





			$r['resultado'] = 'load_primas_escalafon';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
			//$this->con->commit();
		
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


	PUBLIC function registrar_prima_hijo_s($descripcion ,$monto ,$hijo_menor ,$hijo_discapacidad ,$porcentaje){
		$this->set_descripcion($descripcion);
		$this->set_monto($monto);
		$this->set_hijo_menor($hijo_menor);
		$this->set_hijo_discapacidad($hijo_discapacidad);
		$this->set_porcentaje($porcentaje);

		return $this->registrar_prima_hijo();
	}

	PUBLIC function eliminar_prima_hijo_s($id){
		$this->set_id($id);
		return $this->eliminar_prima_hijo();
	}

	PUBLIC function get_prima_hijos_s($id){
		$this->set_id($id);

		return $this->get_prima_hijos();
	}

	PUBLIC function modificar_prima_hijo_s($id, $descripcion ,$monto ,$hijo_menor ,$hijo_discapacidad ,$porcentaje)
	{
		$this->set_id($id);
		$this->set_descripcion($descripcion);
		$this->set_monto($monto);
		$this->set_hijo_menor($hijo_menor);
		$this->set_hijo_discapacidad($hijo_discapacidad);
		$this->set_porcentaje($porcentaje);

		return $this->modificar_prima_hijo();
	}

	PUBLIC function registrar_prima_antiguedad_s($year,$monto){

		$this->set_year($year);
		$this->set_monto($monto);

		return $this->registrar_prima_antiguedad();
	}

	PUBLIC function modificar_prima_antiguedad_s($id,$year,$monto){
		$this->set_id($id);
		$this->set_year($year);
		$this->set_monto($monto);

		return $this->modificar_prima_antiguedad();	
	}

	PUBLIC function eliminar_prima_antiguedad_s($id){
		$this->set_id($id);
		return $this->eliminar_prima_antiguedad();	
	}

	PUBLIC function get_prima_antiguedad_s($id){
		$this->set_id($id);
		return $this->get_prima_antiguedad();	
	}

	

	



	PRIVATE function get_prima_hijos(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT * FROM primas_hijos WHERE id_prima_hijos = ?;");
			$consulta->execute([$this->id]);

			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){


				$r['resultado'] = 'get_prima_hijos';
				$r['titulo'] = 'Éxito';
				$r['mensaje'] =  $consulta;
			}
			else{
				throw new Exception("La prima no existe o fue eliminada", 1);
				
			}
		
		}catch (Exception $e) {
			
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PRIVATE function registrar_prima_hijo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			
			// TODO validar back


			$consulta = $this->con->prepare("SELECT 1 FROM primas_hijos WHERE descripcion = ?;");

			$consulta->execute([$this->descripcion]);

			if($consulta->fetch()){
				throw new Exception("Ya existe una prima con esta descripción", 1);
			}


			$consulta = $this->con->prepare("INSERT INTO `primas_hijos`
				(`descripcion`, `menor_edad`, `porcentaje`, `monto`, `discapacidad`) 
				VALUES 
				(:descripcion,:menor_edad,:porcentaje,:monto,:discapacidad)");


			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":menor_edad",$this->hijo_menor);
			$consulta->bindValue(":porcentaje",$this->porcentaje);
			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":discapacidad",$this->hijo_discapacidad);

			$consulta->execute();

			$hijos = $this->load_primas_hijos();

			if($hijos['resultado'] != "load_primas_hijos"){throw new Exception($hijos['mensaje'], 1); }

			Bitacora::reg($this->con,"Registro la prima por hijo ($this->descripcion)");
			$r['resultado'] = 'registrar_prima_hijo';
			$r['mensaje'] =  $hijos["mensaje"];



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

	PRIVATE function eliminar_prima_hijo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$prima = $this->con->prepare("SELECT descripcion FROM primas_hijos WHERE id_prima_hijos = ?;");
			$prima->execute([$this->id]);

			if(!($prima = $prima->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima no existe o fue eliminada", 1);
			}

			$consulta = $this->con->prepare("DELETE FROM primas_hijos WHERE id_prima_hijos = ?");
			$consulta->execute([$this->id]);




			Bitacora::reg($this->con,"Elimino la prima (".$prima["descripcion"].")");

			$r['resultado'] = 'eliminar_prima_hijo';
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

	PRIVATE function modificar_prima_hijo(){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			// TODO Validaciones
			
			$consulta = $this->con->prepare("SELECT * FROM primas_hijos WHERE id_prima_hijos = ?;");

			$consulta->execute([$this->id]);

			if(!$consulta->fetch()){
				throw new Exception("La prima no existe o fue eliminada", 1);
			}

			$consulta = $this->con->prepare("UPDATE `primas_hijos` SET `descripcion`= :descripcion,`menor_edad`= :menor_edad,`porcentaje`= :porcentaje,`monto`= :monto,`discapacidad`= :discapacidad WHERE id_prima_hijos = :id");


			$consulta->bindValue(":id",$this->id);
			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":menor_edad",$this->hijo_menor);
			$consulta->bindValue(":porcentaje",$this->porcentaje);
			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":discapacidad",$this->hijo_discapacidad);

			$consulta->execute();


			$hijos = $this->load_primas_hijos();

			if($hijos['resultado'] != "load_primas_hijos"){throw new Exception($hijos['mensaje'], 1); }

			Bitacora::reg($this->con,"Modificó la prima por hijo ($this->descripcion)");


			
			$r['resultado'] = 'modificar_prima_hijo';
			$r["mensaje"] = $hijos["mensaje"];
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

	PRIVATE function registrar_prima_antiguedad(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			

			$consulta = $this->con->prepare("SELECT 1 FROM prima_antiguedad WHERE anios_antiguedad = ?;");
			$consulta->execute([$this->year]);

			if($consulta->fetch()){
				throw new Exception("El año ya tiene registrado una prima", 1);
			}

			$consulta = $this->con->prepare("INSERT INTO prima_antiguedad (anios_antiguedad, monto) VALUES (?, ?)");

			$consulta->execute([$this->year, $this->monto]);

			$antiguedad = $this->load_primas_antiguedad();

			if($antiguedad['resultado'] != "load_primas_antiguedad"){throw new Exception($antiguedad['mensaje'], 1); }

			Bitacora::reg($this->con,"Registro la prima por antigüedad de  ($this->year) año(s)");

			$r['resultado'] = 'registrar_prima_antiguedad';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $antiguedad["mensaje"];
			$this->con->commit();
		
		}  catch (Exception $e) {
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
		
		return $r;
	}

	PRIVATE function modificar_prima_antiguedad(){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			// TODO Validaciones
			
			$consulta = $this->con->prepare("SELECT * FROM prima_antiguedad WHERE id_prima_antiguedad = ?;");

			$consulta->execute([$this->id]);

			if(!$old = $consulta->fetch()){
				throw new Exception("La prima no existe o fue eliminada", 1);
			}

			if($old["anios_antiguedad"] != $this->year){
				$consulta = $this->con->prepare("SELECT 1 FROM prima_antiguedad WHERE anios_antiguedad = ?;");
				$consulta->execute([$this->year]);
				if($consulta->fetch()){
					throw new Exception("La prima para el año $this->year ya existe", 1);
				}
			}


			$consulta = $this->con->prepare("UPDATE prima_antiguedad SET anios_antiguedad = ?, monto = ? WHERE id_prima_antiguedad = ?");
			$consulta->execute([$this->year, $this->monto, $this->id]);


			$antiguedad = $this->load_primas_antiguedad();

			if($antiguedad['resultado'] != "load_primas_antiguedad"){throw new Exception($antiguedad['mensaje'], 1); }
			Bitacora::reg($this->con,"Modificó la prima por antigüedad de  ($this->year) año(s)");

			$r['resultado'] = 'modificar_prima_antiguedad';
			$r['mensaje'] =  $antiguedad["mensaje"];

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
		}
		return $r;
	}

	PRIVATE function eliminar_prima_antiguedad(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT anios_antiguedad as year FROM prima_antiguedad WHERE id_prima_antiguedad = ?;");

			$consulta->execute([$this->id]);

			if(!($old = $consulta->fetch())){
				throw new Exception("La prima no existe o fue eliminada", 1);
			}

			$consulta = $this->con->prepare("DELETE FROM prima_antiguedad WHERE id_prima_antiguedad = ?");
			$consulta->execute([$this->id]);


			$antiguedad = $this->load_primas_antiguedad();

			if($antiguedad['resultado'] != "load_primas_antiguedad"){throw new Exception($antiguedad['mensaje'], 1); }
			Bitacora::reg($this->con,"Elimino la prima por antigüedad de ".$old['year']." año(s)");


			
			$r['resultado'] = 'eliminar_prima_antiguedad';
			$r['mensaje'] =  $antiguedad["mensaje"];
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
		}
		
		return $r;
	}

	PRIVATE function get_prima_antiguedad(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT anios_antiguedad as year, monto FROM prima_antiguedad WHERE id_prima_antiguedad = ?;");
			$consulta->execute([$this->id]);

			if(!$consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("La prima no existe o fue eliminada", 1);
			}


			
			$r['resultado'] = 'get_prima_antiguedad';
			$r['mensaje'] =  $consulta;
		
		} catch (Exception $e) {
				
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		
		return $r;
	}











	// setters y getters 

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
	PUBLIC function get_hijo_menor(){
		return $this->hijo_menor;
	}
	PUBLIC function set_hijo_menor($value){
		$this->hijo_menor = $value;
	}
	PUBLIC function get_hijo_discapacidad(){
		return $this->hijo_discapacidad;
	}
	PUBLIC function set_hijo_discapacidad($value){
		$this->hijo_discapacidad = $value;
	}
	PUBLIC function get_porcentaje(){
		return $this->porcentaje;
	}
	PUBLIC function set_porcentaje($value){
		$this->porcentaje = $value;
	}
	PUBLIC function get_year(){
		return $this->year;
	}
	PUBLIC function set_year($value){
		$this->year = $value;
	}

	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}

} 