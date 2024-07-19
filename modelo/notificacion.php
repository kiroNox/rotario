<?php 

class notificaciones extends Conexion {
    private $con;

    function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

    public function crearNotificacion($idTrabajador, $mensaje) {
        try {
            $this->validar_conexion($this->con);
            $consulta = $this->con->prepare("INSERT INTO notificaciones (id_trabajador, mensaje, fecha) VALUES (:id_trabajador, :mensaje, NOW())");
            $consulta->bindValue(":id_trabajador", $idTrabajador);
            $consulta->bindValue(":mensaje", $mensaje);
            $consulta->execute();
            return ['resultado' => 'exito', 'mensaje' => 'Notificación creada exitosamente'];
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    public function getUpcomingVacations() {
        $consulta = $this->con->prepare("SELECT v.*, t.cedula 
        FROM vacaciones v
        JOIN trabajadores t ON v.id_trabajador = t.id_trabajador
        WHERE v.hasta BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
        $consulta->execute();

        // Cambia a fetchAll para obtener todas las filas de una vez
        $vacaciones = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $vacaciones;
    }

    public function obtenerNotificaciones() {
        try {
            $this->validar_conexion($this->con);
            $consulta = $this->con->prepare("SELECT id, id_usuario, status, mensaje, fecha FROM notificaciones ORDER BY fecha DESC");
            $consulta->execute();
            $notificaciones = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $notificaciones;
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
}



 ?>