
<?php

class Deducciones extends Conexion
{
	use Calculadora;
	PRIVATE $con, $id, $descripcion ,$quincena ,$islr ,$dedicada ,$trabajadores;
	PRIVATE $id_trabajador;




	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

	PUBLIC function load_deducciones(){

		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare('SELECT
		    d.id_deducciones
		    ,d.descripcion
		    ,IF(d.islr IS true,"Si","No") as islr_temp
		    ,IF(d.dedicada IS FALSE,"Todos",IF(dt.total_trabajadores IS NULL,0,dt.total_trabajadores)) as dedic
		    ,NULL as extra
		    
		FROM
		    deducciones as d 
		    LEFT JOIN 
		    (
		        SELECT COUNT(td.id_deducciones)as total_trabajadores, td.id_deducciones 
		        FROM trabajador_deducciones as td 
		        WHERE 1 GROUP BY td.id_deducciones
		    ) AS dt ON dt.id_deducciones = d.id_deducciones
		WHERE
		    d.status is true;');

		    $consulta->execute();
			
			$r['resultado'] = 'load_deducciones';
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

	PUBLIC function registrar_deduccion_s($descripcion ,$islr ,$dedicada ,$trabajadores, $formula, $quincena ){

		$this->set_descripcion($descripcion);
		$this->set_islr($islr);
		$this->set_dedicada($dedicada);
		$this->set_trabajadores($trabajadores);
		$this->set_obj_formula($formula);
		$this->set_quincena($quincena);

		return $this->registrar_deduccion();
	}

	PUBLIC function get_deduccion_s($id){
		$this->set_id($id);
		return $this->get_deduccion();
	}


	PUBLIC function modificar_deduccion_s($id, $descripcion, $islr, $dedicada, $trabajadores, $formula, $quincena ){

		$this->set_id($id);
		$this->set_descripcion($descripcion);
		$this->set_islr($islr);
		$this->set_dedicada($dedicada);
		$this->set_trabajadores($trabajadores);
		$this->set_obj_formula($formula);
		$this->set_quincena($quincena);

		return $this->modificar_deduccion();
	}


	PUBLIC function eliminar_deduccion_s($id){
		$this->set_id($id);
		return $this->eliminar_deduccion();
	}







	PRIVATE function registrar_deduccion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
				
			// TODO validaciones

			if($this->dedicada == '1'){
				if(!(is_array($this->trabajadores) and count($this->trabajadores) > 0)){
					throw new Exception("Debe seleccionar al menos un trabajador si esta habilitada la opcion \"Dedicada\"", 1);
				}
			}


			$consulta = $this->con->prepare("SELECT 1 FROM deducciones WHERE descripcion = ?;");

			$consulta->execute([$this->descripcion]);

			if($consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Exception("Una deducción con el nombre ($this->descripcion) ya existe", 1);
			}

			$consulta = null;

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



			$consulta = $this->con->prepare("INSERT INTO `deducciones`
				(`descripcion`, `islr`, `dedicada`, `id_formula`, `quincena`) 
				VALUES 
				(:descripcion, :islr, :dedicada, :id_formula, ,:quincena)");


			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":islr",$this->islr);
			$consulta->bindValue(":dedicada",$this->dedicada);
			$consulta->bindValue(":id_formula",$id_formula);
			$consulta->bindValue(":quincena",$this->quincena);

			$consulta->execute();

			$lastId = $this->con->lastInsertId();

			$consulta = null;


			if($this->dedicada == "1") {

				$cl = new Primas;

				foreach ($this->trabajadores as $elem) {
					


					$id_trabajador = $cl->valid_cedula_trabajador_s($elem);

					if($id_trabajador['resultado'] != "valid_cedula_trabajador"){throw new Exception($id_trabajador['mensaje'], 1); }


					$id_trabajador = $id_trabajador["id"];




					$consulta = $this->con->prepare("INSERT INTO `trabajador_deducciones`
						(`id_deducciones`, `id_trabajador`) 
						VALUES 
						(:id_deducciones,:id_trabajador)");

					$consulta->bindValue(":id_deducciones",$lastId);
					$consulta->bindValue(":id_trabajador",$id_trabajador);

					$consulta->execute();

					$consulta= null;

				}



			

			}




			$deducciones = $this->load_deducciones();

			if($deducciones['resultado'] != "load_deducciones"){throw new Exception($deducciones['mensaje'], 1); }
			

			Bitacora::reg($this->con,"Registró la deducción de  ($this->descripcion) año(s)");

			
			$r['resultado'] = 'registrar_deduccion';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] = 'La deducción ha sido registrada exitosamente';
			$r['lista'] =  $deducciones["mensaje"];
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
			if(isset($cl)){
				if($cl instanceof Primas){
					$cl->set_con(null);
					$cl = null;
				}
			}
		}
		return $r;
	}

	PRIVATE function get_deduccion(){
		try {
			$this->validar_conexion($this->con);

			$consulta = $this->con->prepare("SELECT
					    `descripcion`,
					    `islr`,
					    `dedicada`,
					    `quincena`
					FROM
					    `deducciones`
					WHERE
					    id_deducciones = ?");

			$consulta->execute([$this->id]);



			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La deducción no existe o fue eliminada", 1);
			}

			$consulta = null;

			if($resp["dedicada"] == '1'){

				$consulta = $this->con->prepare("SELECT
							    t.cedula
							    ,t.nombre
							    ,t.apellido
							    ,t.id_trabajador as id
							FROM
				    			`trabajador_deducciones` as tp
							JOIN trabajadores as t on t.id_trabajador = tp.id_trabajador
							WHERE
							    tp.id_deducciones = ?");

				$consulta->execute([$this->id]);

				$trabajadores = $consulta->fetchall(PDO::FETCH_ASSOC);

				foreach ($trabajadores as &$elem) {

					$nombre_temp = preg_replace("/^\s*\b(\w+).*/", "$1", $elem["nombre"]);
					$nombre_temp .= preg_replace("/^\s*\b(\w+).*/", " $1", $elem["apellido"]);

					$elem["nombre"] = $nombre_temp;
				}

				$r["lista"] = $trabajadores;
			}



			$consulta = $this->con->prepare("SELECT df.* ,f.nombre ,f.descripcion FROM detalles_formulas AS df LEFT JOIN deducciones AS pg ON pg.id_formula = df.id_formula LEFT JOIN formulas as f on f.id_formula = df.id_formula WHERE pg.id_deducciones = ? ORDER BY df.orden;");
			$consulta->execute([$this->id]);

			$resp["calc_formula"] = null;
			if( $resp_formulas = $consulta->fetchall(PDO::FETCH_ASSOC) ){

				$resp["calc_formula"] = $resp_formulas;

			}
			$consulta = null;



			
			$r['resultado'] = 'get_deduccion';
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

	PRIVATE function modificar_deduccion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT descripcion,id_formula FROM deducciones WHERE id_deducciones = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La deducción no existe o fue eliminada", 1);
			}

			$consulta = null;

			$id_formula = isset($resp["id_formula"]) ? $resp["id_formula"] : false;

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



			$consulta = $this->con->prepare("UPDATE `deducciones` 
				SET 
				`descripcion`= :descripcion
				,`islr`= :islr
				,`dedicada`= :dedicada 
				,`id_formula` = :id_formula
				,`quincena` = :quincena
				WHERE id_deducciones = :id");

			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":islr",$this->islr);
			$consulta->bindValue(":dedicada",$this->dedicada);
			$consulta->bindValue(":id_formula",$id_formula);
			$consulta->bindValue(":quincena",$this->quincena);
			$consulta->bindValue(":id",$this->id);

			$consulta->execute();

			$consulta = null;

			$consulta = $this->con->prepare("DELETE FROM trabajador_deducciones WHERE id_deducciones = ?");
			$consulta->execute([$this->id]);
			$consulta = null;


			if($this->dedicada == "1") {

				$cl = new Primas;

				foreach ($this->trabajadores as $elem) {
					

					

					$id_trabajador = $cl->valid_cedula_trabajador_s($elem);

					if($id_trabajador['resultado'] != "valid_cedula_trabajador"){throw new Exception($id_trabajador['mensaje'], 1); }


					$id_trabajador = $id_trabajador["id"];




					$consulta = $this->con->prepare("INSERT INTO `trabajador_deducciones`
						(`id_deducciones`, `id_trabajador`) 
						VALUES 
						(:id_deducciones,:id_trabajador)");

					$consulta->bindValue(":id_deducciones",$this->id);
					$consulta->bindValue(":id_trabajador",$id_trabajador);

					$consulta->execute();

					$consulta= null;

				}




			}

			$deducciones = $this->load_deducciones();

			if($deducciones['resultado'] != "load_deducciones"){throw new Exception($deducciones['mensaje'], 1); }
			

			Bitacora::reg($this->con,"Modificó la deducción de  (".$resp["descripcion"].") año(s)");

			
			$r['resultado'] = 'modificar_deduccion';
			$r['titulo'] = 'Éxito';
			$r["mensaje"] = "La deducción fue modificada exitosamente";
			$r['lista'] =  $deducciones["mensaje"];
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

	PRIVATE function eliminar_deduccion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT descripcion,id_formula FROM deducciones WHERE id_deducciones = ?;");

			$consulta->execute([$this->id]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("La deducción no existe o fue eliminada", 1);
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

				throw new Exception("La deducción no puede ser eliminada ya que su formula esta siendo utilizada por las siguientes formulas.<ENDL>".$msg."<ENDL>", 1);
				
			}


			$consulta = $this->con->prepare("SELECT 1 FROM factura_deducciones WHERE id_deduccion = ?;");
			$consulta->execute([$this->id]);

			if(!$consulta->fetch()){
				$consulta = $this->con->prepare("DELETE FROM deducciones WHERE id_deducciones = ?");

				$consulta->execute([$this->id]);

				$consulta = $this->con->prepare("DELETE FROM formulas WHERE id_formula = ?");
				$consulta->execute([$resp["id_formula"]]);
			}
			else{

				$consulta = $this->con->prepare("UPDATE deducciones set status = 0, id_formula = NULL WHERE id_deducciones = ?");
				$consulta->execute([$this->id]);


				$consulta = $this->con->prepare("DELETE FROM formulas WHERE id_formula = ?");
				$consulta->execute([$resp["id_formula"]]);
				$consulta = null;


			}



			$deducciones = $this->load_deducciones();

			if($deducciones['resultado'] != "load_deducciones"){throw new Exception($deducciones['mensaje'], 1); }
			

			Bitacora::reg($this->con,"Eliminó la deducción de  (".$resp["descripcion"].") año(s)");

			
			$r['resultado'] = 'eliminar_deduccion';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $deducciones["mensaje"];
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





	// setters y getters 

	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
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
	PUBLIC function get_porcentaje(){
		return $this->porcentaje;
	}
	PUBLIC function set_porcentaje($value){
		$this->porcentaje = $value;
	}
	PUBLIC function get_quincena(){
		return $this->quincena;
	}
	PUBLIC function set_quincena($value){
		$this->quincena = $value;
	}
	PUBLIC function get_multi_dia(){
		return $this->multi_dia;
	}
	PUBLIC function set_multi_dia($value){
		$this->multi_dia = $value;
	}
	PUBLIC function get_islr(){
		return $this->islr;
	}
	PUBLIC function set_islr($value){
		$this->islr = $value;
	}
	PUBLIC function get_sector_salud(){
		return $this->sector_salud;
	}
	PUBLIC function set_sector_salud($value){
		$this->sector_salud = $value;
	}
	PUBLIC function get_dedicada(){
		return $this->dedicada;
	}
	PUBLIC function set_dedicada($value){
		$this->dedicada = $value;
	}
	PUBLIC function get_meses(){
		return $this->meses;
	}
	PUBLIC function set_meses($value){
		$this->meses = $value;
	}
	PUBLIC function get_semanas(){
		return $this->semanas;
	}
	PUBLIC function set_semanas($value){
		$this->semanas = $value;
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
?>