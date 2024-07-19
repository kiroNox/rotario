<?php

class notificaciones extends Conexion {
    private $con;

    function __construct($con = '') {
        // al instanciar la clase puede hacerse con una conexión vieja o no
        if (!($con instanceof PDO)) {
            $this->con = $this->conecta(); // crea la conexión
        }
    }

    public function getUpcomingVacations() {
        $consulta = $this->con->prepare("SELECT * FROM vacaciones WHERE hasta BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
        $consulta->execute();
        $vacaciones = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $vacaciones;
    }

    public function obtenerNotificaciones() {
        try {
            $this->validar_conexion($this->con);
            $consulta = $this->con->prepare("SELECT n.id, t.cedula, n.mensaje, n.fecha 
                                             FROM notificaciones n 
                                             JOIN trabajadores t ON n.id_usuario = t.id_trabajador 
                                             ORDER BY n.fecha DESC");
            $consulta->execute();
            $notificaciones = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $notificaciones;
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    public function get_con() {
        return $this->con;
    }

    public function set_con($value) {
        $this->con = $value;
    }
}

?>
