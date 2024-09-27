<?php

class Primas extends Conexion
{
	
	PRIVATE $con, $id, $descripcion ,$monto ,$hijo_menor ,$hijo_discapacidad ,$porcentaje;
	PRIVATE $year, $escala, $cedula;
	PRIVATE $mensual, $dedicada, $sector_salud, $trabajadores;
	PRIVATE $id_trabajador;
	USE Calculadora;

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

	PUBLIC function registrar_prima_escalafon_s($escala, $tiempo, $porcentaje){
		$this->set_year($tiempo);
		$this->set_escala($escala);
		$this->set_monto($porcentaje);

		return $this->registrar_prima_escalafon();
	}

	PUBLIC function get_prima_escalafon_s($id){
		$this->set_id($id);

		return $this->get_prima_escalafon();
	}

	PUBLIC function modificar_prima_escalafon_s($id, $escala, $tiempo, $porcentaje){
		$this->set_id($id);
		$this->set_year($tiempo);
		$this->set_escala($escala);
		$this->set_monto($porcentaje);

		return $this->modificar_prima_escalafon();
	}

	PUBLIC function eliminar_prima_escalafon_s($id){
		$this->set_id($id);

		return $this->eliminar_prima_escalafon();
	}

	PUBLIC function valid_cedula_trabajador_s($cedula){


		$this->set_cedula($cedula);

		return $this->valid_cedula_trabajador();
	}

	PUBLIC function registra_prima_general_s($descripcion ,$mensual ,$dedicada ,$trabajadores ,$sector_salud, $formula ){


		$this->set_descripcion($descripcion);
		//$this->set_monto($monto);
		//$this->set_porcentaje($porcentaje);
		$this->set_mensual($mensual);
		$this->set_dedicada($dedicada);
		$this->set_trabajadores($trabajadores);
		$this->set_sector_salud($sector_salud);
		$this->set_obj_formula($formula);



		return $this->registra_prima_general();
	}
	PUBLIC function modificar_prima_general_s($id, $descripcion, $mensual ,$dedicada ,$trabajadores ,$sector_salud, $formula ){

		$this->set_id($id);
		$this->set_descripcion($descripcion);
		// $this->set_monto($monto);
		// $this->set_porcentaje($porcentaje);
		$this->set_mensual($mensual);
		$this->set_dedicada($dedicada);
		$this->set_trabajadores($trabajadores);
		$this->set_sector_salud($sector_salud);
		$this->set_obj_formula($formula);




		return $this->modificar_prima_general();
	}

	PUBLIC function get_prima_general_s($id){
		$this->set_id($id);

		return $this->get_prima_general();
	}

	PUBLIC function eliminar_prima_general_s($id){
		$this->set_id($id);

		return $this->eliminar_prima_general();
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

			// TODO Validaciones
			

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

	PRIVATE function registrar_prima_escalafon(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			// TODO validaciones
			
			$consulta = $this->con->prepare("SELECT * FROM escalafon WHERE escala = ?;");

			$consulta->execute([$this->escala]);

			if($consulta->fetch()){
				throw new Exception("La escala ya esta registrada", 1);
			}
			$consulta = null;

			$consulta = $this->con->prepare("INSERT INTO escalafon (anios_servicio,escala,monto) VALUES (:tiempo, :escala,:monto) ");
			$consulta->bindValue(":tiempo",$this->year);
			$consulta->bindValue(":escala",$this->escala);
			$consulta->bindValue(":monto",$this->monto);

			$consulta->execute();

			$consulta = null;


			$escalafon = $this->load_primas_escalafon();

			if($escalafon['resultado'] != "load_primas_escalafon"){throw new Exception($escalafon['mensaje'], 1); }

			Bitacora::reg($this->con,"Registró la prima por escalafón de escala  ($this->escala)");



			$r['resultado'] = 'registrar_prima_escalafon';
			$r['mensaje'] =  $escalafon["mensaje"];
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

	PRIVATE function get_prima_escalafon(){
		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT escala, anios_servicio as tiempo, monto FROM escalafon WHERE id_escalafon = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima seleccionada no existe o fue eliminada", 1);
			}

			$consulta = null;


			
			$r['resultado'] = 'get_prima_escalafon';
			$r['mensaje'] =  $resp;
		
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


	PRIVATE function modificar_prima_escalafon(){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			

			$consulta = $this->con->prepare("SELECT escala, monto FROM escalafon WHERE id_escalafon = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima seleccionada no existe o fue eliminada", 1);
			}

			$consulta = null;

			if($resp["escala"] != $this->escala){



				$consulta = $this->con->prepare("SELECT * FROM escalafon WHERE escala = ?;");

				$consulta->execute([$this->escala]);

				if($consulta->fetch()){
					throw new Exception("La escala ya esta registrada", 1);
				}
				$consulta = null;
			}

			$consulta = $this->con->prepare("UPDATE escalafon set anios_servicio = :year, escala = :escala, monto = :monto WHERE id_escalafon = :id");
			$consulta->bindValue(":year",$this->year);
			$consulta->bindValue(":escala",$this->escala);
			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":id",$this->id);

			$consulta->execute();

			$escalafon = $this->load_primas_escalafon();

			if($escalafon['resultado'] != "load_primas_escalafon"){throw new Exception($escalafon['mensaje'], 1); }

			Bitacora::reg($this->con,"Modificó la prima por escalafón de escala  (".$resp['escala'].")");
			
			$r['resultado'] = 'modificar_prima_escalafon';
			$r['mensaje'] =  $escalafon["mensaje"];
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

	PRIVATE function eliminar_prima_escalafon(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT escala, monto FROM escalafon WHERE id_escalafon = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima seleccionada no existe o fue eliminada", 1);
			}

			$consulta = null;

			$consulta = $this->con->prepare("DELETE FROM escalafon WHERE id_escalafon = ?");
			$consulta->execute([$this->id]);



			$escalafon = $this->load_primas_escalafon();

			if($escalafon['resultado'] != "load_primas_escalafon"){throw new Exception($escalafon['mensaje'], 1); }

			Bitacora::reg($this->con,"Eliminó la prima por escalafón (".$resp["escala"].")");


			
			$r['resultado'] = 'eliminar_prima_escalafon';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $escalafon["mensaje"];
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


	PRIVATE function registra_prima_general(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			// TODO validaciones

			if($this->obj_formula["tipo"] == "lista"){
				$id_formula = $this->calc_guardar_formula_lista($this->obj_formula["lista"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], false, true);
			}
			else{
				$id_formula = $this->calc_guardar_formula($this->obj_formula["formula"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], $this->obj_formula["variables"], $this->obj_formula["condicional"], 0, false, true);
			}


			if($id_formula["resultado"] == 'error'){
				throw new Exception($id_formula["mensaje"],$id_formula["code"]);
			}

			$id_formula = $id_formula["last"];



			$consulta = $this->con->prepare("INSERT INTO `primas_generales`
				(`descripcion`, `monto`, `porcentaje`, `sector_salud`, `dedicada`, `id_formula`) 
				VALUES 
				(:descripcion, NULL, NULL, :sector_salud, :dedicada, :id_formula)");

			$consulta->bindValue(":descripcion",$this->descripcion);
			//$consulta->bindValue(":monto",$this->monto);
			//$consulta->bindValue(":porcentaje",$this->porcentaje);
			$consulta->bindValue(":sector_salud",$this->sector_salud);
			$consulta->bindValue(":dedicada",$this->dedicada);
			$consulta->bindValue(":id_formula",$id_formula);

			$consulta->execute();

			$lastId = $this->con->lastInsertId();

			$consulta = null;

			if($this->dedicada == "1") {
				if(!count($this->trabajadores)>0){
					throw new Exception("Debe agregar al menos un trabajador si la prima esta seleccionada como \"dedicada\"", 1);
				}

				foreach ($this->trabajadores as $elem) {
					

					$this->set_cedula($elem);

					$id_trabajador = $this->valid_cedula_trabajador();

					if($id_trabajador['resultado'] != "valid_cedula_trabajador"){throw new Exception($id_trabajador['mensaje'], 1); }


					$id_trabajador = $id_trabajador["id"];



					$consulta = $this->con->prepare("INSERT INTO `trabajador_prima_general`
						(`id_primas_generales`, `id_trabajador`, `mensual`, `status`)
						VALUES 
						(:id_primas_generales,:id_trabajador,:mensual,:status)");

					$consulta->bindValue(":id_primas_generales",$lastId);
					$consulta->bindValue(":id_trabajador",$id_trabajador);
					$consulta->bindValue(":mensual",$this->mensual);
					$consulta->bindValue(":status", "1");

					$consulta->execute();

					$consulta= null;

				}




			}

			$generales = $this->load_primas_generales();
			if($generales['resultado'] != "load_primas_generales"){throw new Exception($generales['mensaje'], 1); }



			Bitacora::reg($this->con,"Registró la prima general ($this->descripcion)");


			
			$r['resultado'] = 'registra_prima_general';
			$r['mensaje'] = 'La prima fue registrada exitosamente';
			$r['lista'] =  $generales["mensaje"];
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
			$consulta=null;
		}
		return $r;
	}



	PRIVATE function modificar_prima_general(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			//TODO Validaciones

			$consulta = $this->con->prepare("SELECT descripcion FROM primas_generales WHERE id_primas_generales = ?;");
			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima seleccionada no existe o fue eliminada", 1);
			}
			$resp = $resp["descripcion"];
			$consulta = null;


			$diff = '';
			$consulta = $this->con->prepare("SELECT descripcion, dedicada, id_formula FROM primas_generales WHERE id_primas_generales = ?;");
			$consulta->execute([$this->id]);


			$temp = $consulta->fetch(PDO::FETCH_ASSOC);

			$id_formula = isset($temp["id_formula"]) ? $temp["id_formula"] : false;

			Bitacora::get_diff($diff,"Descripción", $temp["descripcion"], $this->descripcion);
			//Bitacora::get_diff($diff,"Status de medico", $temp["sector_salud"], $this->sector_salud, ["Verdadero", "Falso"] );
			Bitacora::get_diff($diff,"Dedicada", $temp["dedicada"], $this->dedicada, ["Dedicada", "Global"] );





			if($this->obj_formula["tipo"] == "lista"){
				$id_formula = $this->calc_guardar_formula_lista($this->obj_formula["lista"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], false, true, $id_formula);
			}
			else{
				$id_formula = $this->calc_guardar_formula($this->obj_formula["formula"], $this->obj_formula["nombre"], $this->obj_formula["descripcion"], $this->obj_formula["variables"], $this->obj_formula["condicional"], 0, false, true, $id_formula);
			}


			if($id_formula["resultado"] == 'error'){
				throw new Exception($id_formula["mensaje"],$id_formula["code"]);
			}

			$id_formula = $id_formula["last"];
			







			$consulta = $this->con->prepare("UPDATE primas_generales 
				SET 
				`descripcion`= :descripcion
				,`dedicada`= :dedicada
				, `id_formula` = :id_formula

				WHERE id_primas_generales = :id");


			$consulta->bindValue(":descripcion",$this->descripcion);
			// $consulta->bindValue(":sector_salud",$this->sector_salud);
			$consulta->bindValue(":dedicada",$this->dedicada);
			$consulta->bindValue(":id_formula",$id_formula);
			$consulta->bindValue(":id",$this->id);

			$consulta->execute();

			$consulta = null;

			$consulta = $this->con->prepare("SELECT CONCAT('user_',t.id_trabajador) as id,cedula FROM trabajador_prima_general as pgt LEFT JOIN trabajadores as t on t.id_trabajador = pgt.id_trabajador WHERE id_primas_generales = ?;");
			$consulta->execute([$this->id]);

			$valor = $consulta->fetchall(PDO::FETCH_ASSOC);
			$lista_trabajadores_tabla = [];

			foreach ($valor as $elem) {
				$lista_trabajadores_tabla[$elem['id']] = ["control"=> 'Eliminado',"mensaje"=>"'".$elem["cedula"]."' fue eliminado de la lista dedicada <br>"];
			}







			$consulta = $this->con->prepare("DELETE FROM trabajador_prima_general WHERE id_primas_generales = :id");
			$consulta->bindValue(":id",$this->id);
			$consulta->execute();

			if($this->dedicada == "1") {
				if(!count($this->trabajadores)>0){
					throw new Exception("Debe agregar al menos un trabajador si la prima esta seleccionada como \"dedicada\"", 1);
				}

				foreach ($this->trabajadores as $elem) {
					

					$this->set_cedula($elem);

					$id_trabajador = $this->valid_cedula_trabajador();


					if($id_trabajador['resultado'] != "valid_cedula_trabajador"){throw new Exception($id_trabajador['mensaje'], 1); }


					$id_trabajador = $id_trabajador["id"];



					if(isset($lista_trabajadores_tabla["user_".$id_trabajador])){
						$lista_trabajadores_tabla["user_".$id_trabajador]["control"] = 'ignorar';
					}
					else{
						$lista_trabajadores_tabla["user_".$id_trabajador]["control"] = 'add';
						$lista_trabajadores_tabla["user_".$id_trabajador]["mensaje"] = "'$elem' fue agregada a la lista dedicada<br>";
					}


					$consulta = $this->con->prepare("INSERT INTO `trabajador_prima_general`
						(`id_primas_generales`, `id_trabajador`, `mensual`, `status`)
						VALUES 
						(:id_primas_generales,:id_trabajador,:mensual,:status)");

					$consulta->bindValue(":id_primas_generales",$this->id);
					$consulta->bindValue(":id_trabajador",$id_trabajador);
					$consulta->bindValue(":mensual",$this->mensual);
					$consulta->bindValue(":status", "1");

					$consulta->execute();

					$consulta= null;

				}




			}

			$generales = $this->load_primas_generales();
			if($generales['resultado'] != "load_primas_generales"){throw new Exception($generales['mensaje'], 1); }
			

			
			$r['resultado'] = 'modificar_prima_general';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "La prima fue modificada exitosamente";
			$r['lista'] =  $generales["mensaje"];

			foreach ($lista_trabajadores_tabla as $elem) {
				if($elem["control"] !== 'ignorar'){
					$diff .= $elem["mensaje"];
				}
			}

			Bitacora::reg($this->con,"Modificó la prima general ($resp) <br>".$diff);

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
	PRIVATE function get_prima_general(){
		try {
			$this->validar_conexion($this->con);

			$consulta = $this->con->prepare("SELECT
					pg.id_primas_generales as id
				    ,pg.descripcion
				    ,pg.porcentaje
				    ,pg.monto
				    ,pg.sector_salud
				    ,pg.dedicada
				FROM primas_generales AS pg
				WHERE
				    pg.id_primas_generales = ?;");

			$consulta->execute([$this->id]);



			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima no existe o fue eliminada", 1);
			}

			$consulta = null;

			if($resp["dedicada"] == '1'){

				$consulta = $this->con->prepare("SELECT
							    t.cedula
							    ,t.nombre
							    ,t.apellido
							    ,t.id_trabajador as id
							FROM
							    trabajador_prima_general tp
							JOIN trabajadores as t on t.id_trabajador = tp.id_trabajador
							WHERE
							    tp.id_primas_generales = ?");
				$consulta->execute([$this->id]);

				$trabajadores = $consulta->fetchall(PDO::FETCH_ASSOC);

				foreach ($trabajadores as &$elem) {

					$nombre_temp = preg_replace("/^\s*\b(\w+).*/", "$1", $elem["nombre"]);
					$nombre_temp .= preg_replace("/^\s*\b(\w+).*/", " $1", $elem["apellido"]);

					$elem["nombre"] = $nombre_temp;
				}

				$r["lista"] = $trabajadores;
			}


			$consulta = $this->con->prepare("SELECT df.* ,f.nombre ,f.descripcion FROM detalles_formulas AS df LEFT JOIN primas_generales AS pg ON pg.id_formula = df.id_formula LEFT JOIN formulas as f on f.id_formula = df.id_formula WHERE pg.id_primas_generales = ?;");
			$consulta->execute([$this->id]);

			$resp["calc_formula"] = null;
			if( $resp_formulas = $consulta->fetchall(PDO::FETCH_ASSOC) ){

				$resp["calc_formula"] = $resp_formulas;

			}
			$consulta = null;



			
			$r['resultado'] = 'get_prima_general';
			$r['mensaje'] =  $resp;
		
		}  catch (Exception $e) {
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



	PRIVATE function eliminar_prima_general(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT descripcion, id_formula FROM primas_generales WHERE id_primas_generales = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La prima seleccionada no existe o fue eliminada", 1);
			}

			$consulta = null;

			$consulta = $this->con->prepare("SELECT f.nombre FROM usando AS u LEFT JOIN formulas as f on f.id_formula = u.id_formula_uno WHERE u.id_formula_dos = ?");
			$consulta->execute([$resp["id_formula"]]);

			if($lista = $consulta->fetchall(PDO::FETCH_ASSOC)){
				$msg = '';
				foreach ($lista as $elem) {
					$nombre = $elem["nombre"];
					$msg .= "'$nombre'<ENDL>";
				}

				throw new Exception("La prima no puede ser eliminada ya que su formula esta siendo utilizada por las siguientes formulas.<ENDL>".$msg, 1);
				
			}



			$consulta = $this->con->prepare("DELETE FROM primas_generales WHERE id_primas_generales = ?");
			$consulta->execute([$this->id]);


			$consulta = $this->con->prepare("DELETE FROM formulas WHERE id_formula = ?");
			$consulta->execute([$resp["id_formula"]]);







			$generales = $this->load_primas_generales();

			if($generales['resultado'] != "load_primas_generales"){throw new Exception($generales['mensaje'], 1); }

			Bitacora::reg($this->con,"Eliminó la prima general (".$resp['descripcion'].")");


			
			$r['resultado'] = 'eliminar_prima_general';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $generales["mensaje"];
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
	PUBLIC function get_escala(){

		return $this->escala;
	}
	PUBLIC function set_escala($value){
		$value = strtoupper($value);
		$this->escala = $value;
	}
	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}
	PUBLIC function get_mensual(){
		return $this->mensual;
	}
	PUBLIC function set_mensual($value){
		$this->mensual = $value;
	}
	PUBLIC function get_dedicada(){
		return $this->dedicada;
	}
	PUBLIC function set_dedicada($value){
		$this->dedicada = $value;
	}
	PUBLIC function get_sector_salud(){
		return $this->sector_salud;
	}
	PUBLIC function set_sector_salud($value){
		$this->sector_salud = $value;
	}
	PUBLIC function get_trabajadores(){
		return $this->trabajadores;
	}
	PUBLIC function set_trabajadores($value){
		$value = json_decode($value);
		$this->trabajadores = $value;
	}
	PUBLIC function get_id_trabajador(){
		return $this->id_trabajador;
	}
	PUBLIC function set_id_trabajador($value){
		$this->id_trabajador = $value;
	}
} 