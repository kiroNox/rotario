<?php 

/**
 * 
 */
class Sueldo extends Conexion
{
	PRIVATE $con, $id_trabajador, $sueldo_base, $cargo, $cargo_codigo, $sector_salud, $id_escalafon, $tipo_nomina, $id_sueldo ;
	private $Testing;

	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

	PUBLIC function load_sueldos(){

		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT
											    t.cedula,
											    t.nombre,
											    (IF (sb.sueldo_base IS NULL,'Por Asignar',sb.sueldo_base)) as sueldo_base,
											    sb.cargo,
											    (IF(sb.sector_salud IS TRUE, 'Si', 'No')) AS sector_salud,
											    e.escala,
											    sb.tipo_nomina,
											    NULL AS extra,
											    t.apellido,
											    t.id_trabajador
											FROM
											    trabajadores AS t
											LEFT JOIN sueldo_base AS sb
											ON
											    sb.id_trabajador = t.id_trabajador
											LEFT JOIN escalafon AS e
											ON
											    e.id_escalafon = sb.id_escalafon
											WHERE
   											1 ORDER BY sb.sueldo_base = NULL,sb.tipo_nomina;");
			$consulta->execute();
			$r['resultado'] = 'load_sueldos';
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
	PUBLIC function load_escalafon(){

		try {
			$this->validar_conexion($this->con);
			
			$consulta = $this->con->prepare("SELECT id_escalafon, escala FROM escalafon WHERE 1 order by valor_escala");
			$consulta->execute();
			$r['resultado'] = 'load_escalafon';
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

	PUBLIC function asignar_sueldo_s($id_trabajador ,$sueldo_base ,$cargo ,$sector_salud ,$id_escalafon ,$tipo_nomina){
		$this->set_id_trabajador($id_trabajador);
		$this->set_sueldo_base($sueldo_base);
		$this->set_cargo($cargo);
		$this->set_sector_salud($sector_salud);
		$this->set_id_escalafon($id_escalafon);
		$this->set_tipo_nomina($tipo_nomina);

		return $this->asignar_sueldo();
	}

	PUBLIC function eliminar_sueldo_s($id_trabajador){
		$this->set_id_trabajador($id_trabajador);

		return $this->eliminar_sueldo();
	}

	PUBLIC function get_sueldo_s($id_trabajador){
		$this->set_id_trabajador($id_trabajador);

		return $this->get_sueldo();
	}

	PRIVATE function get_sueldo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			Validaciones::numero($this->id_trabajador,"1,","El trabajador es invalido");
			
			$consulta = $this->con->prepare("SELECT * FROM sueldo_base WHERE id_trabajador = ?;");
			$consulta->execute([$this->id_trabajador]);

			if(!($resp = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El trabajador no existe o fue eliminado", 1);
			}
			
			$r['resultado'] = 'get_sueldo';
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


	PRIVATE function asignar_sueldo()	{
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			Validaciones::monto($this->sueldo_base,"EL Sueldo base no es valido");
			Validaciones::numero($this->cargo,"1,","El cargo contiene caracteres inválidos");
			Validaciones::numero($this->id_escalafon,"1,","El escalafón es invalido");
			Validaciones::numero($this->id_trabajador,"1,","El trabajador es invalido");
			Validaciones::booleano($this->sector_salud,"El valor para indicar que el trabajador es medico es invalido");

			Validaciones::validar($this->tipo_nomina,"/^(?:1|2|3|4)$/","El tipo de nomina no es valido ($this->tipo_nomina)");


			$consulta = $this->con->prepare("SELECT 1 FROM cargos WHERE codigo = ?;");
			$consulta->execute([$this->cargo]);

			if(!$consulta->fetch()){
				throw new Exception("El cargo seleccionado no existe o fue eliminado", 1);
			}


			$consulta = $this->con->prepare("SELECT cedula FROM trabajadores WHERE id_trabajador = ?;");
			$consulta->execute([$this->id_trabajador]);

			if (!($cedula = $consulta->fetch(PDO::FETCH_ASSOC))) {
				throw new Exception("EL trabajador seleccionado no existe o fue eliminado", 1);
			}

			$cedula = $cedula["cedula"];


			$consulta = $this->con->prepare("INSERT into sueldo_base 
				(id_trabajador, sueldo_base, cargo,sector_salud,id_escalafon,tipo_nomina) VALUES 
				(:id_trabajador, :sueldo_base, :cargo,:sector_salud,:id_escalafon,:tipo_nomina) ON DUPLICATE KEY UPDATE
				sueldo_base = :sueldo_base, cargo = :cargo, sector_salud = :sector_salud, id_escalafon =:id_escalafon, tipo_nomina = :tipo_nomina ");

			$consulta->bindValue(":id_trabajador",$this->id_trabajador);
			$consulta->bindValue(":sueldo_base",$this->sueldo_base);
			$consulta->bindValue(":cargo",$this->cargo);
			$consulta->bindValue(":sector_salud",$this->sector_salud);
			$consulta->bindValue(":id_escalafon",$this->id_escalafon);
			$consulta->bindValue(":tipo_nomina",$this->tipo_nomina);

			$consulta->execute();

			Bitacora::reg($this->con,"Asigno el sueldo del trabajador $cedula");

			
			
			$r['resultado'] = 'asignar_sueldo';
			$r['titulo'] = 'Éxito';
			if($this->Testing===true){
				$this->con->rollBack(); 
			}
			else{
				$this->con->commit();
			}
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
		}
		return $r;
	}

	PRIVATE function eliminar_sueldo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			Validaciones::numero($this->id_trabajador,"1,","El trabajador es invalido");


			$consulta = $this->con->prepare("SELECT t.cedula  FROM sueldo_base as sb LEFT join trabajadores as t on t.id_trabajador = sb.id_trabajador WHERE sb.id_trabajador = ?;");
			$consulta->execute([$this->id_trabajador]);

			if(!($cedula = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El sueldo no esta asignado a este trabajador", 1);
			}

			$cedula = $cedula["cedula"];

			$consulta = $this->con->prepare("DELETE FROM sueldo_base WHERE id_trabajador = ?");

			$consulta->execute([$this->id_trabajador]);

			Bitacora::reg($this->con,"Borro el sueldo del trabajador $cedula");
			// code
			
			$r['resultado'] = 'eliminar_sueldo';
			$r['titulo'] = 'Éxito';
			if($this->Testing===true){
				$this->con->rollBack(); 
			}
			else{
				$this->con->commit();
			}
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
		}
		return $r;
	}


	PUBLIC function new_cargo_s($codigo,$cargo,$replace){


		// if($replace !== true and $replace !== false){
		// 	if($replace == "false"){
		// 		$replace = false;
		// 	}
		// 	else if ($replace == "true"){
		// 		$replace = true;
		// 	}
		// 	else{
		// 		$replace = false;
		// 	}
		// }

		$this->set_cargo($cargo);
		$this->set_cargo_codigo($codigo);
		return $this->new_cargo($replace);
	}

	PRIVATE function new_cargo($replace){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			Validaciones::removeWhiteSpace($this->cargo);
			Validaciones::removeWhiteSpace($this->cargo_codigo);
			Validaciones::numero($this->cargo_codigo,"3,10","El código no es valido debe tener al menos 3 números y no puede contener letras");
			Validaciones::booleano($replace,"replace invalido contacte con un administrador");
			Validaciones::validar($this->cargo,"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ\/]{1,50}$/u","El nombre del cargo es invalido, solo puede tener letras");

			

			$consulta = $this->con->prepare("SELECT 1 from cargos where codigo = ?");
			$consulta->execute([$this->cargo_codigo]);
			$respuesta = $consulta->fetch();

			

			if($respuesta and !$replace){
				throw new Exception("Codigo encontrado", 999);
			}

			$consulta = $this->con->prepare("SELECT 1 from cargos where cargo = ?");
			$consulta->execute([$this->cargo]);

			if($consulta->fetch()){
				throw new Exception("El nombre del cargo ya existe ", 1);
			}

			$consulta = $this->con->prepare("INSERT Into cargos (codigo,cargo) VALUES (:codigo,:cargo) ON DUPLICATE KEY UPDATE cargo = :cargo");

			$consulta->bindValue(":cargo",$this->cargo);
			$consulta->bindValue(":codigo",$this->cargo_codigo);
			$consulta->execute();


			$r['resultado'] = 'new_cargo';
			$r['titulo'] = 'Éxito';
			$r["mensaje"] = $this->cargo;

			if($this->Testing===true){
				$this->con->rollBack(); 
			}
			else{
				$this->con->commit();
			}
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
			if($e->getCode() == 999){
				$r["resultado"] = 'old_cargo_found';
			}
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}

	public function load_cargos()
	{
		try {
			$this->validar_conexion($this->con);
			// $this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT codigo, cargo from cargos where 1");

			$consulta->execute();
			
			$r['resultado'] = 'load_cargos';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);

			//$this->con->commit();
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



	PUBLIC function get_id_trabajador(){
		return $this->id_trabajador;
	}
	PUBLIC function set_id_trabajador($value){
		$this->id_trabajador = $value;
	}
	PUBLIC function get_sueldo_base(){
		return $this->sueldo_base;
	}
	PUBLIC function set_sueldo_base($value){
		$value = preg_replace("/\D/", "", (String) $value);
		$value = preg_replace("/(\d\d)$/", ".$1", $value);
		$this->sueldo_base = $value;
	}
	PUBLIC function get_cargo(){
		return $this->cargo;
	}
	PUBLIC function set_cargo($value){
		$this->cargo = $value;
	}
	PUBLIC function get_sector_salud(){
		return $this->sector_salud;
	}
	PUBLIC function set_sector_salud($value){
		$this->sector_salud = $value;
	}
	PUBLIC function get_id_escalafon(){
		return $this->id_escalafon;
	}
	PUBLIC function set_id_escalafon($value){
		$this->id_escalafon = $value;
	}
	PUBLIC function get_tipo_nomina(){
		return $this->tipo_nomina;
	}
	PUBLIC function set_tipo_nomina($value){
		$this->tipo_nomina = $value;
	}
	PUBLIC function get_id_sueldo(){
		return $this->id_sueldo;
	}
	PUBLIC function set_id_sueldo($value){
		$this->id_sueldo = $value;
	}
	PUBLIC function get_cargo_codigo(){
		return $this->cargo_codigo;
	}
	PUBLIC function set_cargo_codigo($value){
		$this->cargo_codigo = $value;
	}
	PUBLIC function get_Testing(){
		return $this->Testing;
	}
	PUBLIC function set_Testing($value){
		$this->Testing = $value;
	}

}

 ?>