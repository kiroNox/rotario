<?php 

/**
 * 
 */
class Usuarios extends Conexion
{
	PRIVATE $cedula, $nombre, $apellido, $telefono, $correo, $id_rol, $pass, $con, $id;

	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

	PUBLIC function get_roles(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT id_rol as id, descripcion as rol FROM roles WHERE 1;")->fetchall(PDO::FETCH_ASSOC);

			
			$r['resultado'] = 'get_roles';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $consulta;
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

	PUBLIC function registrar_s($cedula, $nombre, $apellido, $telefono, $correo,$id_rol, $pass){
		$this->set_cedula($cedula);
		$this->set_nombre($nombre);
		$this->set_apellido($apellido);
		$this->set_telefono($telefono);
		$this->set_correo($correo);
		$this->set_id_rol($id_rol);
		$this->set_pass($pass);

		return $this->registrar();
	}

	PUBLIC function modificar_usuario_s($modificar_id, $cedula, $nombre, $apellido, $telefono, $correo, $rol, $pass ){
		$this->set_id($modificar_id);
		$this->set_cedula($cedula);
		$this->set_nombre($nombre);
		$this->set_apellido($apellido);
		$this->set_telefono($telefono);
		$this->set_correo($correo);
		$this->set_id_rol($rol);
		$this->set_pass($pass);

		return $this->modificar_usuario();
	}

	PUBLIC function valid_cedula ($cedula){
		try {
			Validaciones::validarCedula($cedula);
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT 1 FROM personas WHERE cedula = ?;");
			$consulta->execute([$cedula]);

			if($consulta->fetch()){
				$r["mensaje"] = 1;//existe
			}
			else{
				$r["mensaje"] = 0;//no existe
			}
			// code
			
			$r['resultado'] = 'valid_cedula';
			
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

	PUBLIC function listar_usuarios(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT p.id_persona,cedula,nombre,apellido,telefono,correo, r.descripcion as rol FROM personas as p join usuarios as u on u.id_persona = p.id_persona left join roles as r on r.id_rol = u.id_rol WHERE 1;")->fetchall(PDO::FETCH_NUM);


			
			$r['resultado'] = 'listar_usuarios';
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

	PUBLIC function get_user($id){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT p.*,r.id_rol as rol FROM personas as p left join usuarios as u on u.id_persona = p.id_persona left join roles as r on r.id_rol = u.id_rol WHERE u.id_persona = ?;");
			$consulta->execute([$id]);


			
			$r['resultado'] = 'get_user';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $consulta->fetch(PDO::FETCH_ASSOC);
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

	PRIVATE function modificar_usuario(){
		try {

			// TODO Validaciones

			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM personas WHERE id_persona = ?;");
			$consulta->execute([$this->id]);

			$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

			if($usuario["cedula"] != $this->cedula){
				$consulta = $this->con->prepare("SELECT 1 FROM personas WHERE cedula = ? LIMIT 1;");
				$consulta->execute([$this->cedula]);

				if($consulta->fetch()){
					throw new Exception("La nueva cedula ($this->cedula) ya existe", 1);
				}
			}

			$consulta = $this->con->prepare("UPDATE `personas` 
				SET 
				`nombre`=:nombre,`apellido`=:apellido,`telefono`=:telefono,`correo`=:correo WHERE id_persona = :id_persona;");

			$consulta->bindValue(":nombre",$this->nombre);
			$consulta->bindValue(":apellido",$this->apellido);
			$consulta->bindValue(":telefono",$this->telefono);
			$consulta->bindValue(":correo",$this->correo);
			$consulta->bindValue(":id_persona",$this->id);
			$consulta->execute();


			
			$r['resultado'] = 'modificar_usuario';
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

	PRIVATE function registrar(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			Validaciones::validarCedula($this->cedula);
			Validaciones::validarNombre($this->nombre);
			Validaciones::validarNombre($this->apellido,"1,50","El apellido no es valido");
			Validaciones::validarTelefono($this->telefono, "El teléfono no es valido", true);
			Validaciones::validarEmail($this->correo);
			Validaciones::numero($this->id_rol,"1,","El rol seleccionado no es valido");
			Validaciones::validarContrasena($this->pass);

			$this->pass = password_hash($this->pass, PASSWORD_DEFAULT);

			$consulta = $this->con->prepare("SELECT 1 FROM roles WHERE id_rol = ?;");
			$consulta->execute([$this->id_rol]);
			if(!$consulta->fetch()){
				throw new Exception("El rol seleccionado no existe", 1);
			}

			$consulta = $this->con->prepare("SELECT IF(u.id_persona is NULL,'null',u.id_persona) as usuario FROM personas AS p left join usuarios as u on p.id_persona = u.id_persona WHERE cedula = ?;");
			$consulta->execute([$this->cedula]);
			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				if($consulta["usuario"] != 'null'){
					throw new Exception("El usuario con la cedula \"$this->cedula\" ya existe", 1);
				}
				else{
					$id = $consulta["usuario"];

					$consulta = $this->con->prepare("UPDATE `personas` 
						SET 
						`nombre`=:nombre,`apellido`=:apellido,`telefono`=:telefono,`correo`=:correo WHERE id_persona = :id_persona;");

					$consulta->bindValue(":nombre",$this->nombre);
					$consulta->bindValue(":apellido",$this->apellido);
					$consulta->bindValue(":telefono",$this->telefono);
					$consulta->bindValue(":correo",$this->correo);
					$consulta->bindValue(":id_persona",$id);
					$consulta->execute();


					$consulta = $this->con->prepare("INSERT INTO `usuarios`(`id_persona`, `id_rol`, `clave`, `token`) VALUES (:id_persona, :id_rol, :clave, :token)");
					$consulta->bindValue(":id_persona",$id);
					$consulta->bindValue(":id_rol",$this->id_rol);
					$consulta->bindValue(":clave",$this->pass);
					$consulta->bindValue(":token","1");
					$consulta->execute();
				}
				
			}
			else{

				$consulta = $this->con->prepare("INSERT INTO `personas`( `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `liquidacion`) VALUES (:cedula, :nombre, :apellido, :telefono, :correo, 0)");

				$consulta->bindValue(":cedula",$this->cedula);
				$consulta->bindValue(":nombre",$this->nombre);
				$consulta->bindValue(":apellido",$this->apellido);
				$consulta->bindValue(":telefono",$this->telefono);
				$consulta->bindValue(":correo",$this->correo);
				$consulta->execute();

				$lastId = $this->con->lastInsertId();

				$consulta = $this->con->prepare("INSERT INTO `usuarios`(`id_persona`, `id_rol`, `clave`, `token`) VALUES (:id_persona, :id_rol, :clave, :token)");
				$consulta->bindValue(":id_persona",$lastId);
				$consulta->bindValue(":id_rol",$this->id_rol);
				$consulta->bindValue(":clave",$this->pass);
				$consulta->bindValue(":token","1");
				$consulta->execute();

			}
			// code
			
			$r['resultado'] = 'registrar';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "";
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
		return $r;
	}

	


	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}
	PUBLIC function get_nombre(){
		return $this->nombre;
	}
	PUBLIC function set_nombre($value){
		$value = Validaciones::removeWhiteSpace($value);
		$this->nombre = $value;
	}
	PUBLIC function get_apellido(){
		return $this->apellido;
	}
	PUBLIC function set_apellido($value){
		$value = Validaciones::removeWhiteSpace($value);
		$this->apellido = $value;
	}
	PUBLIC function get_telefono(){
		return $this->telefono;
	}
	PUBLIC function set_telefono($value){
		$this->telefono = $value;
	}
	PUBLIC function get_correo(){
		return $this->correo;
	}
	PUBLIC function set_correo($value){
		$this->correo = $value;
	}
	PUBLIC function get_id_rol(){
		return $this->id_rol;
	}
	PUBLIC function set_id_rol($value){
		$this->id_rol = $value;
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

	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
}

 ?>