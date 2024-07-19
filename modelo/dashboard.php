<?php 

class dashboard extends Conexion
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

	public function totalTrabajadores() {
        $sql = "SELECT COUNT(*) as total FROM trabajadores";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function totalVacacionesActivas() {
        $sql = "SELECT COUNT(*) as total FROM vacaciones WHERE hasta > CURDATE()";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function totalAreas() {
        $sql = "SELECT COUNT(*) as total FROM areas";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function totalFacturas() {
        $sql = "SELECT COUNT(*) as total FROM factura";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function totalPermisos() {
        $sql = "SELECT COUNT(*) as total FROM permisos_trabajador";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function totalReposos() {
        $sql = "SELECT COUNT(*) as total FROM reposo";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function totalHijos() {
        $sql = "SELECT COUNT(*) as total FROM hijos";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
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