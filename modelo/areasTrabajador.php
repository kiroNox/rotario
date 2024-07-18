<?php


class areasTrabajador extends Conexion
{

    private $con, $id_area,$id_trabajador, $codigo, $descripcion, $desde, $hasta, $id_trabajador_area, $id;

    public function __construct($con = '')
    { {
            if (!($con instanceof PDO)) {
                $this->con = $this->conecta();
            }
        }

    }

    //crear un metodo para listar areasTrabajador   
    public function listar_areasTrabajador()
    {
        try {
            $this->validar_conexion($this->con);

            $sql = "SELECT 
            CONCAT(t.nombre,'',t.apellido) as nombre
            ,t.cedula
            ,a.descripcion
            ,a.codigo
            ,NULL as extra
            ,ta.id_trabajador_area as id


            FROM trabajadores as t 
            join trabajador_area as ta on ta.id_trabajador = t.id_trabajador
            join areas as a on a.id_area = ta.id_area;
            ";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            

            
            
            $r['resultado'] = 'listar_areasTrabajador';
            $r['mensaje'] =  $result;
            //$this->con->commit();
        
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

    public function eliminar_trabajadorArea($id){
        $this->set_id($id);
        return $this->eliminar_trabajadorAreas();
    }
    
    /* crea la funcion privada de eliminar */
    private function eliminar_trabajadorAreas(){
        try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("DELETE FROM trabajador_area WHERE id_trabajador_area = ?");
			$consulta->execute([$this->id]);

			$r['resultado'] = 'eliminar_trabajadorArea';
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

    

    PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
public function get_con(){
    return $this->con;
}
public function get_id_area(){

    return $this->id_area;
}
 public function get_id_trabajador(){

    return $this->id_trabajador;
}
public function get_desde(){
    return $this->desde;
}
public function get_hasta(){
    return $this->hasta;
}
public function get_descripcion(){
    return $this->descripcion;
}
public function get_codigo(){
    return $this->codigo;
}
public function set_con($value){
    return $this->con;
}
public function set_id_area($value){
    return $this->id_area;
}
public function set_id_trabajador($value){
    return $this->id_trabajador;
}
public function set_desde($value){
    return $this->desde;
}
public function set_hasta($value){
    return $this->hasta;
}
public function set_descripcion($value){
    return $this->descripcion;
}
public function set_codigo($value){
    return $this->codigo;
}


}