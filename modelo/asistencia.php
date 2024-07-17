<?php


class Asistencia extends Conexion
{

    private $con, $id_area,$id_trabajador, $codigo, $descripcion, $desde, $hasta;

    public function __construct($con = '')
    { {
            if (!($con instanceof PDO)) {
                $this->con = $this->conecta();
            }
        }

    }

    //crea un metodo para cargar las asistencias que es una tabla pivot entre area y trabajador
    public function load_asistencia()
    {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            $sql = "SELECT * FROM asistencia";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
            $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->con->commit();
            return $result;
        } catch (Exception $e) {    



            $this->con->rollBack();
            return $e->getMessage();            
        }
    }   

    //crea un metodo para crear las asistencias que es una tabla pivot entre area y trabajador 
    public function create_asistencia($id_trabajador, $id_area, $desde, $hasta) 
    {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            $sql = "INSERT INTO asistencia (id_area, id_trabajador) VALUES (:id_area, :id_trabajador)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
            $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
            $stmt->execute();
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollBack();
            return $e->getMessage();
        }
    }   

    //crea un metodo para eliminar las asistencias que es una tabla pivot entre area y trabajador
    public function delete_asistencia($id_asistencia)
    {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            $sql = "DELETE FROM asistencia WHERE id_area = :id_area AND id_trabajador = :id_trabajador";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
            $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
            $stmt->execute();
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollBack();
            return $e->getMessage();
        }
    }

    //crea el metodo actualizar las asistencias que es una tabla pivot entre area y trabajador
    public function update_asistencia($id_trabajador, $id_area, $desde, $hasta)
    {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            $sql = "UPDATE asistencia SET id_area = :id_area, id_trabajador = :id_trabajador WHERE id_area = :id_area AND id_trabajador = :id_trabajador";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
            $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
            $stmt->execute();
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollBack();
            return $e->getMessage();
        }
    }   

    //crea un metodo para buscar una asistencia segun el trabajodor y el area
    public function show_asistencia($id_asistencia)
    {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            $sql = "SELECT * FROM asistencia WHERE id_area = :id_area AND id_trabajador = :id_trabajador";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
            $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->con->commit();
            return $result;
        } catch (Exception $e) {
            $this->con->rollBack();
            return $e->getMessage();
        }
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