<?php

class Areas extends Conexion
{
    private $id, $descripcion;

    function __construct($con = '')
    {
        if (!($con instanceof PDO)) {// si "con" no es una instancia de PDO
            $this->con = $this->conecta();// crea la conexion 
        }
    }

    public function registrar($descripcion, $id)
    {
        $this->set_id($id);
        $this->set_descripcion($descripcion);

        return $this->registrar_areas();
    }


    //Metodos

    private function registrar_areas()
    {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();

            $consulta = $this->con->prepare("INSERT INTO `areas` (`id_areas`, `descripcion`) VALUES (:id_areas, :descripcion)");
            $consulta->bindValue(":id_areas", $this->id);
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->execute();

            $this->con->commit();
            $r['resultado'] = 'registrar';
            $r['titulo'] = 'Éxito';
            $r['mensaje'] = "Vacaciones registradas con éxito";
        } catch (Validaciones $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'is-invalid';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
            $r['console'] = $e->getMessage() . ": Code : " . $e->getLine();
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'error';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function set_descripcion($value)
    {
        $this->descripcion = $value;
    }
    public function get_id()
    {
        return $this->id;
    }
    public function set_id($value)
    {
        $this->id = $value;
    }
    PUBLIC function set_con($value){
		$this->con = $value;
	}

}

?>