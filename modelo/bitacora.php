<?php 
/**
 * 
 */
class Bitacora extends Conexion
{
	 private $temp_con;
	 public static $last;
	function __construct($temp_con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if($temp_con instanceof PDO){// si "con" no es una instancia de PDO
			$this->temp_con = $temp_con;
		}

	}

	public function load_bitacora(){
		try {
			$this->con = $this->conecta();
			$this->validar_conexion($this->con);
			
				$consulta = $this->con->prepare("SELECT t.cedula, b.fecha, b.descripcion,t.nombre,t.apellido FROM bitacora AS b LEFT JOIN trabajadores AS t ON t.id_trabajador = b.id_trabajador WHERE 1 ORDER BY fecha DESC limit 200;");
				$consulta->execute();
			
			$r['resultado'] = 'load_bitacora';
			$r["mensaje"] = $consulta->fetchall(PDO::FETCH_ASSOC);

		
		} catch (Exception $e) {
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			$this->con = null;
		}
		
		return $r;
	}


	public static function  registro ($con, $modul, $descrip, $user = false){
		try {
			if($user === false){
				$user = $_SESSION["usuario_rotario"];
			}
			if(is_string($modul)){
				$consulta = $con->prepare("INSERT INTO bitacora (id_trabajador, descripcion) VALUES (?, ?)");
			}
			else{
				$consulta = $con->prepare("INSERT INTO bitacora (id_trabajador, descripcion) VALUES (?, ?)");
			}

			if($modul == '') $modul = null;

			$consulta->execute([$user, $descrip]);
		}
		finally{
			
		}
	}
	public static function reg($con,$descrip, $user = false){
		try {
			if($user === false){
				$user = $_SESSION["usuario_rotario"];
			}
				$consulta = $con->prepare("INSERT INTO bitacora (id_trabajador, descripcion) VALUES (?, ?)");

			$consulta->execute([$user, $descrip]);
		}
		finally{
			
		}

	}
	public static function ingreso_modulo($modulo){
		$bitacora = new Bitacora;
		$con = $bitacora->conecta();
		try {
			$bitacora->validar_conexion($con);
			$con->beginTransaction();

			$consulta = $con->prepare("INSERT INTO bitacora (id_trabajador, descripcion) VALUES (?, ?)");
			$consulta->execute([ $_SESSION["usuario_rotario"], "Ingreso en el modulo ($modulo)" ]);
			
			
			$con->commit();
		
		}  catch (Exception $e) {
			if($con instanceof PDO){
				if($con->inTransaction()){
					$con->rollBack();
				}
			}
		
			$file = fopen("assets/log/bitacora_error_log.log", "a");

			if ($file) {
				fwrite($file, (new DateTime("now", new DateTimeZone("UTC")))->format("Y-m-d H:i:s")." UTC \n");
				$list_array = $e->getTrace();
				foreach ($list_array as $li) {
				    fwrite($file, $li["file"]." LINE ".$li["line"]."\n");
				}
				fwrite($file, $e->getMessage()."\n\n");
			  	
				fclose($file);
			}
		}
		finally{
			$con = null;
		}
	}
}


 ?>