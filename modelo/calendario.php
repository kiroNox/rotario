<?php 

/**
 * 
 */
class calendario extends Conexion
{
    PRIVATE $id, $fecha, $descripcion, $con, $recurrente;

    function __construct($con = '')
    {
        if(!($con instanceof PDO)){
            $this->con = $this->conecta();
        }
    }

    PUBLIC function agregar_dia($descripcion, $fecha, $recurrente = 0) {
        $this->set_descripcion($descripcion);
        $this->set_fecha($fecha);
        $this->set_recurrente($recurrente);
        return $this->agregar_d();
    }

    PUBLIC function modificar_dia($descripcion, $fecha, $recurrente = 0) {
        $this->set_descripcion($descripcion);
        $this->set_fecha($fecha);
        $this->set_recurrente($recurrente);
        return $this->modificar_d();
    }

    PUBLIC function eliminar_dia($fecha){
        $this->set_fecha($fecha);
        return $this->eliminar_d();
    }

    PUBLIC function obtener_dia($year, $month) {
        return $this->obtener_d($year, $month);
    }

    PRIVATE function agregar_d() {
        try {
            $this->validar_conexion($this->con);    
            // Validaciones de los datos del evento
            Validaciones::alfanumerico($this->descripcion, "1,200", "La descripción no es válida");
            Validaciones::fecha($this->fecha, "Fecha del evento no válida");
            Validaciones::numero($this->recurrente, "1", "El valor de recurrencia no es válido");
    
            // Verificar si ya existe un evento con la misma fecha
            $consultaDuplicado = $this->con->prepare("SELECT 1 FROM `calendario` WHERE `fecha` = :fecha");
            $consultaDuplicado->bindValue(":fecha", $this->fecha);
            $consultaDuplicado->execute();
            
            if ($consultaDuplicado->fetchColumn() > 0) {
                throw new Validaciones("Ya existe un evento en la fecha especificada", 1);
            }
    
            $this->con->beginTransaction();
    
            $consulta = $this->con->prepare("INSERT INTO `calendario`(`descripcion`, `fecha`, `recurrente`) VALUES (:descripcion, :fecha, :recurrente)");
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->bindValue(":fecha", $this->fecha);
            $consulta->bindValue(":recurrente", $this->recurrente);
            $consulta->execute();
    
            $r['resultado'] = 'exito';
            $r['mensaje'] = 'Evento agregado';
            $this->con->commit();
            $this->close_bd($this->con);
        
        } catch (Exception $e) {

            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                    $this->close_bd($this->con);
                }
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    PRIVATE function modificar_d() {
        try {
            // Validaciones para los datos del evento
            $this->validar_conexion($this->con);
            Validaciones::alfanumerico($this->descripcion, "1,200", "La descripción no es válida");
            Validaciones::fecha($this->fecha, "Fecha del evento no válida");
            Validaciones::numero($this->recurrente, "1", "El valor de recurrencia no es válido");
    
            // Verificar si ya existe un evento con la misma fecha
            $consultaDuplicado = $this->con->prepare("SELECT 1 FROM `calendario` WHERE `fecha` = :fecha AND `fecha` != :fecha_actual");
            $consultaDuplicado->bindValue(":fecha", $this->fecha);
            $consultaDuplicado->bindValue(":fecha_actual", $this->fecha); // Actual fecha del evento
            $consultaDuplicado->execute();
            
            if ($consultaDuplicado->fetchColumn() > 0) {
                throw new Validaciones("Ya existe un evento en la fecha especificada", 1);
            }
    
            $this->con->beginTransaction();
    
            $consulta = $this->con->prepare("UPDATE calendario SET descripcion = :descripcion, recurrente = :recurrente WHERE fecha = :fecha");
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->bindValue(":fecha", $this->fecha);
            $consulta->bindValue(":recurrente", $this->recurrente);
            $consulta->execute();
    
            $r['resultado'] = 'exito';
            $r['mensaje'] = 'Evento modificado';
            $this->con->commit();
            $this->close_bd($this->con);
        
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                    $this->close_bd($this->con);
                }
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    
    PRIVATE function eliminar_d() {
        try {
            // Validación para eliminar evento
            Validaciones::fecha($this->fecha, "Fecha del evento no válida");
    
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
    
            $consulta = $this->con->prepare("DELETE FROM `calendario` WHERE `fecha` = :fecha");
            $consulta->bindValue(":fecha", $this->fecha);
            $consulta->execute();
    
            $r['resultado'] = 'exito';
            $r['mensaje'] = 'Evento eliminado';
            $this->con->commit();
            $this->close_bd($this->con);
        
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                    $this->close_bd($this->con);
                }
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
            
        }
        return $r;
    }
    
    PRIVATE function obtener_d($year, $month) {
        try {
            // Validaciones para obtener eventos
            Validaciones::numero($year, "4", "El año no es válido");
            Validaciones::numero($month, "1,2", "El mes no es válido");
    
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
    
            $consulta = $this->con->prepare("SELECT fecha, descripcion, recurrente FROM calendario WHERE MONTH(fecha) = :month AND (YEAR(fecha) = :year OR recurrente = 1)");
            $consulta->bindValue(":month", $month, PDO::PARAM_INT);
            $consulta->bindValue(":year", $year, PDO::PARAM_INT);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($resultados as &$evento) {
                if ($evento['recurrente'] == 1) {
                    $fecha = new DateTime($evento['fecha']);
                    $fecha->setDate($year, $fecha->format('m'), $fecha->format('d'));
                    $evento['fecha'] = $fecha->format('Y-m-d');
                }
            }
    
            $r['resultado'] = 'exito';
            $r['evento'] = $resultados;
            $this->con->commit();

            $this->close_bd($this->con);
        
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                    $this->close_bd($this->con);
                }
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    

    PUBLIC function get_descripcion(){
        return $this->descripcion;
    }
    PUBLIC function set_descripcion($value){
        $this->descripcion = $value;
    }
    PUBLIC function get_fecha(){
        return $this->fecha;
    }
    PUBLIC function set_fecha($value){
        $this->fecha = $value;
    }
    PUBLIC function get_con(){
        return $this->con;
    }
    PUBLIC function set_con($value){
        $this->con = $value;
    }
    PUBLIC function get_recurrente(){
        return $this->recurrente;
    }
    PUBLIC function set_recurrente($value){
        $this->recurrente = $value;
    }
}
 ?>