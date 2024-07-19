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

	public function obtenerVacacionesAnuales() {
        $sql = "SELECT MONTH(vacaciones.desde) AS mes, COUNT(*) AS total_empleados
                FROM vacaciones
                GROUP BY MONTH(vacaciones.desde)";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerNivelesEducativos() {
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