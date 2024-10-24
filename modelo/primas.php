<?php

class Primas extends Conexion
{
	
	PRIVATE $con, $id, $descripcion , $monto , $hijo_menor , $hijo_discapacidad , $porcentaje;

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


	PUBLIC function load_primas_generales(){
		try {
			$this->validar_conexion($this->con);


			$consulta = $this->con->prepare("SELECT
					pg.id_primas_generales as id
				    ,pg.descripcion
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
				    pg.status is true;");


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

	PUBLIC function valid_cedula_trabajador_s($cedula){


		$this->set_cedula($cedula);

		return $this->valid_cedula_trabajador();
	}

	PUBLIC function registra_prima_general_s($descripcion ,$mensual ,$dedicada ,$trabajadores , $formula ){


		$this->set_descripcion($descripcion);
		$this->set_mensual($mensual);
		$this->set_dedicada($dedicada);
		$this->set_trabajadores($trabajadores);
		$this->set_obj_formula($formula);



		return $this->registra_prima_general();
	}
	PUBLIC function modificar_prima_general_s($id, $descripcion, $mensual ,$dedicada ,$trabajadores , $formula ){

		$this->set_id($id);
		$this->set_descripcion($descripcion);
		$this->set_mensual($mensual);
		$this->set_dedicada($dedicada);
		$this->set_trabajadores($trabajadores);
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
				(`descripcion`, `dedicada`, `id_formula`, `quincena`) 
				VALUES 
				(:descripcion, :dedicada, :id_formula, :quincena)");

			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":dedicada",$this->dedicada);
			$consulta->bindValue(":id_formula",$id_formula);
			$consulta->bindValue(":quincena",$this->mensual);

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
						(`id_primas_generales`, `id_trabajador`, `status`)
						VALUES 
						(:id_primas_generales,:id_trabajador,:status)");

					$consulta->bindValue(":id_primas_generales",$lastId);
					$consulta->bindValue(":id_trabajador",$id_trabajador);
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
				, `quincena` = :quincena

				WHERE id_primas_generales = :id");


			$consulta->bindValue(":descripcion",$this->descripcion);
			// $consulta->bindValue(":sector_salud",$this->sector_salud);
			$consulta->bindValue(":quincena",$this->mensual);
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
						(`id_primas_generales`, `id_trabajador`, `status`)
						VALUES 
						(:id_primas_generales,:id_trabajador,:status)");

					$consulta->bindValue(":id_primas_generales",$this->id);
					$consulta->bindValue(":id_trabajador",$id_trabajador);
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
				    ,pg.dedicada
				    ,pg.quincena
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


			$consulta = $this->con->prepare("SELECT df.* ,f.nombre ,f.descripcion FROM detalles_formulas AS df LEFT JOIN primas_generales AS pg ON pg.id_formula = df.id_formula LEFT JOIN formulas as f on f.id_formula = df.id_formula WHERE pg.id_primas_generales = ? ORDER BY df.orden;");
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

				throw new Exception("La prima no puede ser eliminada ya que su formula esta siendo utilizada por las siguientes formulas.<ENDL>".$msg."<ENDL>", 1);
				
			}


			$consulta = $this->con->prepare("SELECT 1 from factura_primas_generales WHERE id_primas_generales = ?");
			$consulta->execute([$this->id]);

			if(!$consulta->fetch()){

				$consulta = $this->con->prepare("DELETE FROM primas_generales WHERE id_primas_generales = ?");
				$consulta->execute([$this->id]);


				$consulta = $this->con->prepare("DELETE FROM formulas WHERE id_formula = ?");
				$consulta->execute([$resp["id_formula"]]);
			}
			else{
				$consulta = $this->con->prepare("UPDATE primas_generales set status = 0, id_formula = NULL WHERE id_primas_generales = ?");
				$consulta->execute([$this->id]);
				$consulta = $this->con->prepare("DELETE FROM formulas WHERE id_formula = ?");
				$consulta->execute([$resp["id_formula"]]);

			}







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