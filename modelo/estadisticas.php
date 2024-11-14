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

	public function listarEmpleados($start,$length,$search) {
       

        $this->validar_conexion($this->con);

        // Construir la consulta SQL
        $sql = "SELECT id_trabajador, nombre, apellido, correo, creado, cedula FROM trabajadores";
        
        // Aplicar filtro de búsqueda si existe
        if (!empty($search)) {
            $sql .= " WHERE nombre LIKE :search OR apellido LIKE :search OR correo LIKE :search OR cedula LIKE :search";
        }
        
        $stmt =  $this->con->prepare($sql);
        
        if (!empty($search)) {	
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener el total de registros
        $totalData = $this->con->query("SELECT COUNT(*) FROM trabajadores")->fetchColumn();
        $totalFiltered = empty($search) ? $totalData : count($data);

        // Estructura de respuesta para DataTables
        $json_data = array(
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function obtenerResumenTrabajador($fecha_desde, $fecha_hasta, $cedula_trabajador) {
        $this->validar_conexion($this->con);
    
        // Validación de fechas
        if (!strtotime($fecha_desde) || !strtotime($fecha_hasta)) {
            throw new Exception("Datos de entrada inválidos.");
        }
    
        // Obtener el ID del trabajador usando su cédula
        $sql_id_trabajador = "SELECT id_trabajador, creado AS fecha_ingreso 
                              FROM trabajadores 
                              WHERE cedula = :cedula";
        $stmt_id_trabajador = $this->con->prepare($sql_id_trabajador);
        $stmt_id_trabajador->bindParam(':cedula', $cedula_trabajador);
        $stmt_id_trabajador->execute();
        $trabajador = $stmt_id_trabajador->fetch(PDO::FETCH_ASSOC);
    
        if (!$trabajador) {
            throw new Exception("Trabajador no encontrado.");
        }
    
        $id_trabajador = $trabajador['id_trabajador'];
        $fecha_ingreso = $trabajador['fecha_ingreso'];
        $tiempo_trabajo = date_diff(date_create($fecha_ingreso), date_create('now'))->y;
    
        // Obtener vacaciones del trabajador en el rango de fechas
        $sql_vacaciones = "SELECT desde, hasta, descripcion, 
                           DATEDIFF(hasta, desde) + 1 AS dias
                           FROM vacaciones 
                           WHERE id_trabajador = :id_trabajador 
                           AND desde >= :fecha_desde AND hasta <= :fecha_hasta";
        $stmt_vacaciones = $this->con->prepare($sql_vacaciones);
        $stmt_vacaciones->bindParam(':id_trabajador', $id_trabajador);
        $stmt_vacaciones->bindParam(':fecha_desde', $fecha_desde);
        $stmt_vacaciones->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt_vacaciones->execute();
        $vacaciones = $stmt_vacaciones->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener reposos del trabajador en el rango de fechas
        $sql_reposos = "SELECT desde, hasta, descripcion, tipo_reposo, 
                        DATEDIFF(hasta, desde) + 1 AS dias
                        FROM reposo 
                        WHERE id_trabajador = :id_trabajador 
                        AND desde >= :fecha_desde AND hasta <= :fecha_hasta";
        $stmt_reposos = $this->con->prepare($sql_reposos);
        $stmt_reposos->bindParam(':id_trabajador', $id_trabajador);
        $stmt_reposos->bindParam(':fecha_desde', $fecha_desde);
        $stmt_reposos->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt_reposos->execute();
        $reposos = $stmt_reposos->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener permisos del trabajador en el rango de fechas
        $sql_permisos = "SELECT desde, descripcion                        
                         FROM permisos_trabajador 
                         WHERE id_trabajador = :id_trabajador 
                         AND desde >= :fecha_desde";
        $stmt_permisos = $this->con->prepare($sql_permisos);
        $stmt_permisos->bindParam(':id_trabajador', $id_trabajador);
        $stmt_permisos->bindParam(':fecha_desde', $fecha_desde);
        $stmt_permisos->execute();
        $permisos = $stmt_permisos->fetchAll(PDO::FETCH_ASSOC);
    
        // Construir y retornar el JSON
        $json_data = [
            "vacaciones" => $vacaciones,
            "reposos" => $reposos,
            "permisos" => $permisos,
            "fecha_ingreso" => $fecha_ingreso,
            "tiempo_trabajo" => $tiempo_trabajo
        ];
    
        echo json_encode($json_data);
    }
    
    

    private function validarFecha($fecha) {
        $formato = 'Y-m-d';
        $fechaObj = DateTime::createFromFormat($formato, $fecha);
        return $fechaObj && $fechaObj->format($formato) === $fecha;
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