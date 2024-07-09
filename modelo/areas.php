<?php

class Areas extends Conexion
{
    private $id, $codigo,$descripcion,$con;

    function __construct($con = ''){
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}
	}

    public function registrar_areas($descripcion,$codigo)
    {
        $this->set_descripcion($descripcion);
        $this->set_descripcion($codigo);
        return $this->registrar_area_privada($descripcion, $codigo);
    }

    //Metodos
    PUBLIC function listar_areas(){
            try {
                $consulta = $this->con->prepare("SELECT * FROM areas");
                $consulta->execute();
                return $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                throw $e;
            }
		
	}
    private function registrar_area_privada($codigo, $descripcion) {
        try {
            $this->con->beginTransaction();
            // Insertar en la base de datos
            $consulta = $this->con->prepare("INSERT INTO areas (codigo, descripcion) VALUES (:codigo, :descripcion)");
            $consulta->bindValue(":codigo", $codigo);
            $consulta->bindValue(":descripcion", $descripcion);
            $consulta->execute();
            $this->con->commit();
            return [
                'resultado' => 'registrar',
                'titulo' => 'Éxito',
                'mensaje' => 'Área registrada correctamente.'
            ];
        } catch (Exception $e) {
            if ($this->con instanceof PDO && $this->con->inTransaction()) {
                $this->con->rollBack();
            }
            throw $e;
        }
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
    PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
    

}

?>