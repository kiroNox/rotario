<?php 

class Conexion{
	// DATOS DE LA DB
	PRIVATE $ip = BD_IP;// constantes definidas en model/const.php
	PRIVATE $bd = BD_NAME;
	PRIVATE $usuario = BD_USER;
	PRIVATE $contrasena = BD_PASS;
	PRIVATE $private_con;
	// FUNCION PARA ESTABLECER CONEXION
	PUBLIC function conecta(){
		try {
			
			$pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
			$pdo->exec("set names utf8");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->private_con = $pdo;
			
			return new Class{
				PUBLIC $attrexeption = true;
				PRIVATE function exception(){
					throw new Exception("Base de datos cerrada", 1);
				}
				PUBLIC function commit($string ='' ){
					$this->exception();
					return false;
				}
				PUBLIC function rollBack($string ='' ){
					$this->exception();
					return false;
				}
				PUBLIC function query($string ='' ){
					$this->exception();
					return false;
				}
				PUBLIC function prepare($string ='' ){
					$this->exception();
					return false;
				}
			};


			} catch (Exception $e) {
				return $e->getMessage();
			}
	}
	PUBLIC function validar_conexion(&$pdo){
		if(!($pdo instanceof PDO)){
			if($this->private_con instanceof PDO){
				$pdo = $this->private_con;
			}
			else if(isset($pdo->attrexeption)){
				$pdo->exception();
			}
			else{
				throw new Exception("Error al conectar con la BD", 1);
			}


		}

	}

	PUBLIC function no_permision_msg(){
		echo json_encode(["resultado" => "error", "titulo" => "Sin Permisos", "mensaje" => "No posee los permisos para realizar la acción"]);
	}

	PUBLIC function close_bd(&$con){
		if($con instanceof PDO){
			$this->private_con = $con;
			$con = null;
			$con = new Class{
				PUBLIC $attrexeption = true;
				PRIVATE function exception(){
					throw new Exception("Base de datos cerrada", 1);
				}
				PUBLIC function commit($string ='' ){
					$this->exception();
					return false;
				}
				PUBLIC function rollBack($string ='' ){
					$this->exception();
					return false;
				}
				PUBLIC function query($string ='' ){
					$this->exception();
					return false;
				}
				PUBLIC function prepare($string ='' ){
					$this->exception();
					return false;
				}
			};
		}
	}
} 


 ?>