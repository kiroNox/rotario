<?php

class Loging extends Conexion
{
	PRIVATE $cedula,$correo,$pass,$con;
	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}


	PUBLIC function singing_c($user, $pass){// funcion publica que llama a la privada seteando los parametros
		$this->set_correo($user);
		$this->set_pass($pass);
		return $this->singing();
	}

	PRIVATE function singing(){
		$correo = $this->correo;
		$pass = $this->pass;
		try {
			$this->validar_conexion($this->con);// si falla lanza una exepcion

			Validaciones::validarEmail($correo);// si falla lanza una exepcion

			$this->con->beginTransaction();// inicia la transaccion

			$consulta = $this->con->prepare("SELECT p.id_trabajador, p.cedula, p.correo, p.clave
											FROM trabajadores as p 
											WHERE p.correo = ?");
			//creo la consulta
			$consulta->execute([$correo]);// la ejecuto mandando el correo


			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){// si la consulta devuelve algo

				if(password_verify($pass, $consulta["clave"])){// verifico el hash de la contraseña en la bd con la contraseña pasada
					$r['resultado'] = 'singing';
					$r['titulo'] = 'Éxito';
					$r['mensaje'] =  "Sesión iniciada";
					// "$r" es el resultado que retornara el metodo un array


					// para cambiar en el futuro para el jwt 
					$token = password_hash($consulta["id_trabajador"].$consulta["cedula"].date("Y-m-d h:i:s"), PASSWORD_DEFAULT);
					$id = $consulta["id_trabajador"];
					
					$consulta = $this->con->prepare("UPDATE trabajadores SET token = ? WHERE id_trabajador = ?");
					$consulta->execute([$token, $id]);

					$_SESSION["token_rotario"] = $token;
					$_SESSION["usuario_rotario"] = $id;

					Bitacora::registro($this->con, NULL, "Inicio de sesión");
					$this->con->commit();
				}
				else{// si la contraseña es erronea lanza la exception
					throw new Exception("La contraseña es invalida", 1);
				}

			}
			else{ // si la consulta no devuelve nada
				throw new Exception("El correo no existe", 1);
			}
		
		} catch (Validaciones $e){ // exepcion lanzada por las validaciones ejemplo linea 30
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack(); 
					// en caso de que se lanze la exepcion 
					// y halla una transaccion en progreso
					// se aplique el rollback
				}
			}
			if(isset($_SESSION["token_rotario"])){

				session_unset();
				session_destroy();
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
			if(isset($_SESSION["token_rotario"])){

				session_unset();
				session_destroy();
			}
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r["trace"] = $e->getTrace();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;


	}

	// setters y getters 


	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}
	PUBLIC function get_correo(){
		return $this->correo;
	}
	PUBLIC function set_correo($value){
		$this->correo = $value;
	}
	PUBLIC function get_pass(){
		return $this->pass;
	}
	PUBLIC function set_pass($value){
		$this->pass = $value;
	}
	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
} 