<?php 
/**
 * 
 */
class Bitacora extends Conexion
{
	function __construct()
	{
		
	}

	public function load_bitacora(){
		try {
			$con = $this->conecta();
			$this->validar_conexion($con);
			
				$consulta = $con->prepare("SELECT t.cedula, b.fecha, b.descripcion,t.nombre,t.apellido FROM bitacora AS b LEFT JOIN trabajadores AS t ON t.id_trabajador = b.id_trabajador WHERE 1 ORDER BY fecha DESC limit 200;");
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
			$con = null;
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

	public static function get_diff(&$diff,$nombre , $dato_1, $dato_2, $BOOL = null){

		if($dato_1 !== $dato_2){
			if(is_array($BOOL)){

				$dato_1 = (boolval($dato_1))?$BOOL[0] : $BOOL[1];
				$dato_2 = (boolval($dato_2))?$BOOL[0] : $BOOL[1];

			}


			$diff .= "'$nombre' se modifico de '$dato_1' a '$dato_2'<br>";

		}

	}


}


 ?>