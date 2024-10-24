<?php 

class estadisticas extends Conexion
{
	PRIVATE $id, $desde;

	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

	public function obtenerFechaMinimaVacaciones() {
		$this->validar_conexion($this->con);
		$sql = "SELECT MIN(desde) AS fecha_minima FROM vacaciones";
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['fecha_minima'];
	}
	
	public function obtenerFechaMaximaVacaciones() {
		$this->validar_conexion($this->con);
		$sql = "SELECT MAX(hasta) AS fecha_maxima FROM vacaciones";
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['fecha_maxima'];
	}

	public function obtenerVacacionesAnuales() {
		$this->validar_conexion($this->con);
        $sql = "SELECT MONTH(vacaciones.desde) AS mes, COUNT(*) AS total_empleados
                FROM vacaciones
                GROUP BY MONTH(vacaciones.desde)";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function obtenerVacacionesYRepososPorRangoFechas($fecha_inicio, $fecha_fin) {
		$this->validar_conexion($this->con);
		// Obtener vacaciones
		$sql_vacaciones = "SELECT MONTH(desde) as mes, COUNT(*) as total_empleados
						   FROM vacaciones
						   WHERE desde BETWEEN :fecha_inicio AND :fecha_fin
						   GROUP BY MONTH(desde)";
		$stmt_vacaciones = $this->con->prepare($sql_vacaciones);
		$stmt_vacaciones->bindParam(':fecha_inicio', $fecha_inicio);
		$stmt_vacaciones->bindParam(':fecha_fin', $fecha_fin);
		$stmt_vacaciones->execute();
		$vacaciones = $stmt_vacaciones->fetchAll(PDO::FETCH_ASSOC);
	
		// Obtener reposos
		$sql_reposos = "SELECT MONTH(desde) as mes, COUNT(*) as total_empleados
						FROM reposo
						WHERE desde BETWEEN :fecha_inicio AND :fecha_fin
						GROUP BY MONTH(desde)";
		$stmt_reposos = $this->con->prepare($sql_reposos);
		$stmt_reposos->bindParam(':fecha_inicio', $fecha_inicio);
		$stmt_reposos->bindParam(':fecha_fin', $fecha_fin);
		$stmt_reposos->execute();
		$reposos = $stmt_reposos->fetchAll(PDO::FETCH_ASSOC);
	
		// Obtener el total de empleados y trabajadores
		$sql_total_empleados = "SELECT COUNT(DISTINCT id_trabajador) as total_empleados
								FROM vacaciones
								WHERE desde BETWEEN :fecha_inicio AND :fecha_fin";
		$stmt_total_empleados = $this->con->prepare($sql_total_empleados);
		$stmt_total_empleados->bindParam(':fecha_inicio', $fecha_inicio);
		$stmt_total_empleados->bindParam(':fecha_fin', $fecha_fin);
		$stmt_total_empleados->execute();
		$total_empleados = $stmt_total_empleados->fetch(PDO::FETCH_ASSOC)['total_empleados'];
	
		$sql_total_trabajadores = "SELECT COUNT(*) as total_trabajadores FROM trabajadores";
		$stmt_total_trabajadores = $this->con->prepare($sql_total_trabajadores);
		$stmt_total_trabajadores->execute();
		$total_trabajadores = $stmt_total_trabajadores->fetch(PDO::FETCH_ASSOC)['total_trabajadores'];
	
		return [
			'vacaciones' => $vacaciones,
			'reposos' => $reposos,
			'total_empleados' => $total_empleados,
			'total_trabajadores' => $total_trabajadores
		];
	}
	
	
	

    public function obtenerNivelesEducativos() {
		$this->validar_conexion($this->con);
        $sql = "SELECT pp.descripcion AS nivel_educativo, COUNT(*) AS total_empleados
                FROM trabajadores t
                JOIN prima_profesionalismo pp ON t.id_prima_profesionalismo = pp.id_prima_profesionalismo
                GROUP BY pp.descripcion";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

	
	}
	


 ?>