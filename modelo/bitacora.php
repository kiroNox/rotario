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

	public function reg ($modul, $descrip, $user = false){
		try {
			if($user === false){
				$user = $_SESSION["usuario_rotario"];
			}
			if($temp_con instanceof PDO){
				$con = $this->temp_con;
			}
			else{
				$con = $this->conecta();
			}

			$consulta = $con->prepare("INSERT INTO bitacora (id_usuario, id_modulo, descripcion) VALUES (:id_usuario, :id_modulo, :descripcion)");

			if($modul == '') $modul = null;

			$consulta->execute([$user, $modul, $descrip]);
		}
		finally{
			$con = null;
		}
	}

	public static function  registro ($con, $modul, $descrip, $user = false){
		try {
			if($user === false){
				$user = $_SESSION["usuario_rotario"];
			}
			if(is_string($modul)){
				$consulta = $con->prepare("INSERT INTO bitacora (id_usuario, id_modulo, descripcion) VALUES (?, (SELECT id_modulos from modulos where nombre = ?), ?)");
			}
			else{
				$consulta = $con->prepare("INSERT INTO bitacora (id_usuario, id_modulo, descripcion) VALUES (?, ?, ?)");
			}

			if($modul == '') $modul = null;

			$consulta->execute([$user, $modul, $descrip]);
		}
		finally{
			$con = null;
		}
	}
	public static function ingreso_modulo($modulo){
		$bitacora = new Bitacora;
		$con = $bitacora->conecta();
		try {
			$bitacora->validar_conexion($con);
			$con->beginTransaction();
			if(!is_string($modulo)){
				$consulta = $con->prepare("INSERT INTO bitacora (id_usuario, id_modulo, descripcion) VALUES (?, ?, ?)");

				$consulta->execute([ $_SESSION["usuario_rotario"], $modulo, "Ingreso en el modulo" ]);
			}
			else{

				$consulta = $con->prepare("INSERT INTO bitacora (id_usuario, (id_modulo), descripcion) VALUES (?, (SELECT id_modulos from modulos where nombre = ?), ?)");

				$consulta->execute([ $_SESSION["usuario_rotario"], $modulo, "Ingreso en el modulo" ]);
			}
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