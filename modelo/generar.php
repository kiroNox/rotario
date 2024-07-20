<?php

class generar extends Conexion
{
    private $id;

    function __construct($con = '')
    {
        if(!($con instanceof PDO)){
            $this->con = $this->conecta();
        }
    }

    public function obtenerDatosTrabajador($id_trabajador) {

        $sql = "SELECT nombre, apellido, cedula, creado FROM trabajadores WHERE id_trabajador = :id_trabajador";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    PUBLIC function listar_usuarios(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT cedula,nombre,apellido,telefono,correo, r.descripcion as rol, numero_cuenta,  NULL as extra, p.id_trabajador FROM trabajadores as p left join rol as r on r.id_rol = p.id_rol WHERE estado_actividad = 1;")->fetchall(PDO::FETCH_NUM);
			

			
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

    public function get_id(){
        return $this->id;
    }

    public function set_id($value){
        $this->id = $value;
    }

    public function get_con(){
        return $this->con;
    }

    public function set_con($value){
        $this->con = $value;
    }
}
?>
