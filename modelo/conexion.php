<?php 

class Conexion{
    // DATOS DE LA DB
	PRIVATE $ip = BD_IP;// constantes definidas en model/const.php
    PRIVATE $bd = BD_NAME;
    PRIVATE $usuario = BD_USER;
    PRIVATE $contrasena = BD_PASS;
    // FUNCION PARA ESTABLECER CONEXION
    PUBLIC function conecta(){
        try {
            
            $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
            $pdo->exec("set names utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
            } catch (Exception $e) {
                return $e->getMessage();
            }
    }   
    PUBLIC function validar_conexion($pdo){
        if(!($pdo instanceof PDO)){
            throw new Exception("Error al conectar con la BD", 1);
        }

    }

    PUBLIC function no_permision_msg(){
        echo json_encode(["resultado" => "error", "titulo" => "Sin Permisos", "mensaje" => "No posee los permisos para realizar la acción"]);
    }
} 


 ?>