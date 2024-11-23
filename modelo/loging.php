<?php

class Loging extends Conexion
{
	PRIVATE $id,$cedula,$correo,$pass,$con,$keyword,$Testing;
	use Correos;
	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}
		$this->keyword = "Akdi3ac-1d53Ñdlaóeahewcxzxcjasi9eñlslñjdf";
	}


	PUBLIC function singing_c($user, $pass){// funcion publica que llama a la privada seteando los parametros
		$this->set_correo($user);
		$this->set_pass($pass);
		return $this->singing();
	}

	PRIVATE function singing(){
		$correo = $this->correo;
		$pass = ($this->pass===null)?"":$this->pass;
		try {
			$this->validar_conexion($this->con);// si falla lanza una exepcion

			Validaciones::validarEmail($correo);// si falla lanza una exepcion
			Validaciones::removeWhiteSpace($correo);


			$this->con->beginTransaction();// inicia la transaccion

			$consulta = $this->con->prepare("SELECT p.id_trabajador, p.cedula, p.correo, p.clave, p.nombre, p.apellido
											FROM trabajadores as p 
											WHERE p.correo = ? AND estado_actividad = 1");
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

					$nombre = preg_replace("/^\s*\b(\w+).*/", "$1", $consulta["nombre"]);
					$nombre .= preg_replace("/^\s*\b(\w+).*/", " $1", $consulta["apellido"]);

					$_SESSION["usuario_rotario_name"] = ucwords($nombre);
					
					$consulta = $this->con->prepare("UPDATE trabajadores SET token = ? WHERE id_trabajador = ?");
					$consulta->execute([$token, $id]);

					$_SESSION["token_rotario"] = $token;
					$_SESSION["usuario_rotario"] = $id;

					if(isset($_GET["APP-REQUEST"])){
						header("user:".$_SESSION["usuario_rotario"]);
						Bitacora::registro($this->con, NULL, "Inicio de sesión desde app");
					}
					else{
						Bitacora::registro($this->con, NULL, "Inicio de sesión");
					}


					if($this->Testing===true){
						session_unset();
						session_destroy();
						$this->con->rollBack(); 
					}
					else{
						$this->con->commit();
					}
					$this->close_bd($this->con);
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

	PUBLIC function reset_pass_request_s($correo){
		$this->set_correo($correo);
		return $this->reset_pass_request();
	}

	PRIVATE function reset_pass_request(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			

			$consulta = $this->con->prepare("SELECT * FROM trabajadores WHERE correo = ?;");
			$consulta->execute([$this->correo]);

			if($this->Testing===true){
				$r["testLogin"] = "fail";
			}


			if(($resp = $consulta->fetch(PDO::FETCH_ASSOC))){

				$resp["time"] = time();

				$id = $resp["id_trabajador"];

				$token = $resp = json_encode($resp);

				$consulta = $this->con->prepare("UPDATE trabajadores SET token = ? WHERE id_trabajador = ?");
				$consulta->execute([$token, $id]);







				$iv_size = openssl_cipher_iv_length("aes-256-cbc");  // Obtener el tamaño del IV con OpenSSL
				$iv = openssl_random_pseudo_bytes($iv_size);        // Generar un IV aleatorio con OpenSSL
				$cifrado = openssl_encrypt($resp, "aes-256-cbc", $this->keyword, 0, $iv);

				$texto_final = urlencode($cifrado.$iv);

				$url = "?a=".$texto_final;

				$url = URL_PROD.$url;
				$data["email"] = $this->correo;
				$data["url"] = $url;

				
				if($this->Testing===true){
					$r["testLogin"] = "success";
				}
				else{
					$this->enviar_correo($data,"reset_pass",$asunto='Restablecer Contraseña');
				}

			}
			$this->close_bd($this->con);
			
			$r['resultado'] = 'reset_pass_request';
			$r['titulo'] = 'Éxito';
			//$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			if($this->Testing===true){
				$r["testLogin"] = "fail";
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
			if($this->Testing===true){
				$r["testLogin"] = "fail";
			}


		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}

	PUBLIC function valid_token_reset($token){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			
			$consulta = $this->con->prepare("SELECT * FROM trabajadores WHERE id_trabajador = ? and token = ?;");
			$consulta->execute([$token->id_trabajador,$token->token]);


			if(!$consulta->fetch()){
				throw new Exception("EL token es invalido o ya expiro", 1);
			}
			
			$r['resultado'] = true;
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "";
			//$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = false;
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = false;
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
		}
		return $r;
	}


	PUBLIC function change_pass($pass,$id){
		$this->set_id($id);
		$this->set_pass($pass);

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("UPDATE trabajadores set clave = ?, token = 1 WHERE id_trabajador = ?");

			$this->pass = password_hash($this->pass, PASSWORD_DEFAULT);

			$consulta->execute([$this->pass,$this->id]);


			
			$r['resultado'] = 'change_pass';
			$r['titulo'] = 'Éxito';
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

	PUBLIC function get_keyword(){
		return $this->keyword;
	}
	PUBLIC function set_keyword($value){
		$this->keyword = $value;
	}
	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_Testing(){
		return $this->Testing;
	}
	PUBLIC function set_Testing($value){
		$this->Testing = $value;
	}
} 