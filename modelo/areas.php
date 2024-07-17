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
                $consulta = $this->con->prepare("SELECT *, NULL as extra FROM areas");
                $consulta->execute();
                return $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                throw $e;
            }
		
	}
      // crea una funcion publicar para eliminar 
      public function eliminar_area($id){
        $this->set_id($id);
        return $this->eliminar_area_privada();
    }
    
    /* crea la funcion privada de eliminar */
    private function eliminar_area_privada(){
        try {
            $this->con->beginTransaction();
            // Eliminar de la base de datos
            $consulta = $this->con->prepare("DELETE FROM `areas` WHERE id_area = :id");
            $consulta->bindValue(":id", $this->id);
            $consulta->execute();
            $this->con->commit();
            return [
                'resultado' => 'eliminado',
                'titulo' => 200,
                'mensaje' => 'Área eliminada correctamente.'
            ];
        } catch (Exception $e) {
            if ($this->con instanceof PDO && $this->con->inTransaction()) {
                $this->con->rollBack();
            }
            throw $e;
        }        
    }
    
    public function actualizar_area($id, $descripcion, $codigo){
        $this->set_id($id);
        $this->set_descripcion($descripcion);
        $this->set_descripcion($codigo);
        return $this->actualizar_area_privada();            

    }
    private function actualizar_area_privada(){
        try {
            $this->con->beginTransaction();
            // Actualizar en la base de datos
            $consulta = $this->con->prepare("UPDATE areas SET codigo = :codigo, descripcion = :descripcion WHERE id = :id");
            $consulta->bindValue(":id", $this->id);
            $consulta->bindValue(":codigo", $this->codigo);
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->execute();
            $this->con->commit();
            return [
                'resultado' => 'actualizar',
                'titulo' => 'Éxito',
                'mensaje' => 'Área actualizada correctamente.'
            ];
        } catch (Exception $e) {
            if ($this->con instanceof PDO && $this->con->inTransaction()) {
                $this->con->rollBack();
            }
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