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
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT p.cedula, p.nombre, p.apellido, NULL as extra, t.id_trabajador FROM trabajadores t INNER JOIN personas p ON t.id_persona = p.id_persona;")->fetchall(PDO::FETCH_NUM);
			

			
			$r['resultado'] = 'listar';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] = $consulta;
			$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
		}
		return $r;
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