<?php 

/**
 * 
 */
class Usuarios extends Conexion
{
	PRIVATE $numero_cuenta, $cedula, $nombre, $apellido, $telefono, $correo, $id_rol, $pass, $con, $id, $nivel_profesional, $creado;
	PRIVATE $comision_servicios, $discapacitado, $discapacidad, $genero_trabajador;

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
			$consulta = $this->con->query("SELECT id_rol as id, descripcion as rol FROM rol WHERE 1;")->fetchall(PDO::FETCH_ASSOC);

			
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

	PUBLIC function get_niveles_educativos(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT descripcion as prof,id_prima_profesionalismo as id FROM prima_profesionalismo WHERE 1;");
			$consulta->execute();
			
			$r['resultado'] = 'nivel_profesional';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
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

	PUBLIC function registrar_usuario_s($cedula, $nombre, $apellido, $telefono, $correo,$id_rol, $pass, $numero_cuenta, $nivel_profesional, $creado, $comision_servicios, $discapacitado, $discapacidad, $genero_trabajador){
		$this->set_cedula($cedula);
		$this->set_nombre($nombre);
		$this->set_apellido($apellido);
		$this->set_telefono($telefono);
		$this->set_correo($correo);
		$this->set_id_rol($id_rol);
		$this->set_pass($pass);
		$this->set_numero_cuenta($numero_cuenta);
		$this->set_nivel_profesional($nivel_profesional);
		$this->set_creado($creado);
		$this->set_comision_servicios($comision_servicios);
		$this->set_discapacitado($discapacitado);
		$this->set_discapacidad($discapacidad);
		$this->set_genero_trabajador($genero_trabajador);



		return $this->registrar_usuario();
	}

	PUBLIC function modificar_usuario_s($modificar_id, $cedula, $nombre, $apellido, $telefono, $correo, $rol, $pass, $numero_cuenta, $nivel_profesional, $creado, $comision_servicios, $discapacitado, $discapacidad, $genero_trabajador){
		$this->set_id($modificar_id);
		$this->set_cedula($cedula);
		$this->set_nombre($nombre);
		$this->set_apellido($apellido);
		$this->set_telefono($telefono);
		$this->set_correo($correo);
		$this->set_id_rol($rol);
		$this->set_pass($pass);
		$this->set_numero_cuenta($numero_cuenta);
		$this->set_nivel_profesional($nivel_profesional);
		$this->set_creado($creado);
		$this->set_comision_servicios($comision_servicios);
		$this->set_discapacitado($discapacitado);
		$this->set_discapacidad($discapacidad);
		$this->set_genero_trabajador($genero_trabajador);

		return $this->modificar_usuario();
	}

	PUBLIC function eliminar_usuario_s($id){
		$this->set_id($id);

		return $this->eliminar_usuario();
	}



	PUBLIC function valid_cedula ($cedula){
		$this->set_cedula($cedula);
		try {
			Validaciones::validarCedula($this->cedula);
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT 1 FROM trabajadores as t WHERE cedula = ?;");
			$consulta->execute([$this->cedula]);

			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
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
			$consulta = $this->con->query("SELECT cedula,nombre,apellido,telefono,correo, r.descripcion as rol, numero_cuenta,  NULL as extra, p.id_trabajador FROM trabajadores as p left join rol as r on r.id_rol = p.id_rol WHERE estado_actividad = 1;")->fetchall(PDO::FETCH_NUM);


			
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
			$r['trace'] = $e->getTrace();
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
			$consulta = $this->con->prepare("SELECT p.*,r.id_rol as rol FROM trabajadores as p left join rol as r on r.id_rol = p.id_rol WHERE p.id_trabajador = ?;");
			$consulta->execute([$id]);

			$resp = $consulta->fetch(PDO::FETCH_ASSOC);

			unset($resp["token"]);
			unset($resp["clave"]);
			
			$r['resultado'] = 'get_user';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  $resp;
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

	PRIVATE function registrar_usuario(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			Validaciones::validarCedula($this->cedula);
			Validaciones::validarNombre($this->nombre);
			Validaciones::validarNombre($this->apellido,"1,50","El apellido no es valido");
			Validaciones::validarTelefono($this->telefono, "El teléfono no es valido", true);
			Validaciones::validarEmail($this->correo);
			Validaciones::numero($this->id_rol,"1,","El rol seleccionado no es valido");
			Validaciones::numero($this->nivel_profesional,"1,","El nivel profesional no es valido");
			Validaciones::validarContrasena($this->pass);
			Validaciones::numero($this->numero_cuenta,"20","El numero de cuenta no es valido");
			Validaciones::fecha($this->creado,"Fecha de Ingreso");
			Validaciones::alfanumerico($this->discapacidad,"0,50");
			Validaciones::validar($this->genero_trabajador,"/^(?:F|M)$/","El genero seleccionado no es valido");

			if(!preg_match("/true|false/", $this->comision_servicios)){throw new Exception("La comision servicios seleccionada no es valida", 1);}
			else{$this->comision_servicios = (preg_match("/true/", $this->comision_servicios)?true:false);}

			$this->pass = password_hash($this->pass, PASSWORD_DEFAULT);

			$consulta = $this->con->prepare("SELECT 1 FROM rol WHERE id_rol = ?;");
			$consulta->execute([$this->id_rol]);
			if(!$consulta->fetch()){
				throw new Exception("El rol seleccionado no existe", 1);
			}

			$consulta = $this->con->prepare("SELECT 1 FROM prima_profesionalismo WHERE id_prima_profesionalismo = ?;");
			$consulta->execute([$this->nivel_profesional]);
			if(!$consulta->fetch()){
				throw new Exception("El nivel profesional seleccionado no existe", 1);
			}

			$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE correo = ?;");
			$consulta->execute([$this->correo]);

			if($consulta->fetch()){
				throw new Exception("El correo ya esta en uso por otro usuario", 1);
			}

			$consulta = $this->con->prepare("SELECT estado_actividad as status, cedula FROM trabajadores WHERE cedula = ?;");
			$consulta->execute([$this->cedula]);

			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				if($consulta["status"] == "1"){
					throw new Exception("La cedula ya esta registrada y no puede repetirse", 1);
				}
				else{


					$consulta = $this->con->prepare("UPDATE `trabajadores` SET `id_prima_profesionalismo`=:id_prima_profesionalismo,`id_rol`=:id_rol,`numero_cuenta`=:numero_cuenta,`creado`=:creado,`nombre`=:nombre,`apellido`=:apellido,`telefono`=:telefono,`correo`=:correo,`clave`=:clave,`token`= '',`estado_actividad`= 1 `comision_servicios` = :comision_servicios, `discapacidad` = :discapacidad, `discapacitado` = :discapacitado, `genero` = :genero WHERE cedula = :cedula");

					$consulta->bindValue(":id_prima_profesionalismo",$this->nivel_profesional);
					$consulta->bindValue(":id_rol",$this->id_rol);
					$consulta->bindValue(":cedula",$this->cedula);
					$consulta->bindValue(":numero_cuenta",$this->numero_cuenta);
					$consulta->bindValue(":creado",$this->creado);
					$consulta->bindValue(":nombre",$this->nombre);
					$consulta->bindValue(":apellido",$this->apellido);
					$consulta->bindValue(":telefono",$this->telefono);
					$consulta->bindValue(":correo",$this->correo);
					$consulta->bindValue(":clave",$this->pass);
					$consulta->bindValue(":comision_servicios",$this->comision_servicios);
					$consulta->bindValue(":discapacidad",$this->discapacidad);
					$consulta->bindValue(":discapacitado",$this->discapacitado);
					$consulta->bindValue(":genero",$this->genero_trabajador);


					$consulta->execute();

				}
			}

			$consulta = $this->con->prepare("INSERT INTO `trabajadores`
				(`id_prima_profesionalismo`, `id_rol`, `cedula`, `numero_cuenta`, `creado`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `token`, `estado_actividad`, `comision_servicios`,`discapacidad`,`discapacitado`,`genero`) 
				VALUES 
				(:id_prima_profesionalismo, :id_rol, :cedula, :numero_cuenta, :creado, :nombre, :apellido, :telefono, :correo, :clave, :token, :estado_actividad, :comision_servicios, :discapacidad, :discapacitado, :genero);");


			$consulta->bindValue(":id_prima_profesionalismo",$this->nivel_profesional);
			$consulta->bindValue(":id_rol",$this->id_rol);
			$consulta->bindValue(":cedula",$this->cedula);
			$consulta->bindValue(":numero_cuenta",$this->numero_cuenta);
			$consulta->bindValue(":creado",$this->creado);
			$consulta->bindValue(":nombre",$this->nombre);
			$consulta->bindValue(":apellido",$this->apellido);
			$consulta->bindValue(":telefono",$this->telefono);
			$consulta->bindValue(":correo",$this->correo);
			$consulta->bindValue(":clave",$this->pass);
			$consulta->bindValue(":comision_servicios",$this->comision_servicios);
			$consulta->bindValue(":token",1);
			$consulta->bindValue(":estado_actividad",1);
			$consulta->bindValue(":discapacidad",$this->discapacidad);
			$consulta->bindValue(":discapacitado",$this->discapacitado);
			$consulta->bindValue(":genero",$this->genero_trabajador);


			$consulta->execute();


			
			$r['resultado'] = 'registrar';
			$r['titulo'] = 'Éxito';

			Bitacora::registro($this->con, 2, "Registro al usuarios ($this->cedula)");
			
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
			$r["temp"] = $e->getTrace();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;
	}

	PRIVATE function modificar_usuario(){
		try {

			Validaciones::numero($this->id,"1,","El id no es valido contacte con un administrador");
			Validaciones::validarCedula($this->cedula);
			Validaciones::validarNombre($this->nombre);
			Validaciones::validarNombre($this->apellido,"1,50","El apellido no es valido");
			Validaciones::validarTelefono($this->telefono, "El teléfono no es valido", true);
			Validaciones::validarEmail($this->correo);
			Validaciones::numero($this->id_rol,"1,","El rol seleccionado no es valido");
			Validaciones::numero($this->nivel_profesional,"1,","El nivel profesional no es valido");
			
			Validaciones::numero($this->numero_cuenta,"20","El numero de cuenta no es valido");
			Validaciones::fecha($this->creado,"Fecha de Ingreso");

			Validaciones::alfanumerico($this->discapacidad,"0,50");

			if($this->pass!=''){
				Validaciones::validarContrasena($this->pass);
				$this->pass = password_hash($this->pass, PASSWORD_DEFAULT);
			}

			if(!preg_match("/true|false/", $this->comision_servicios)){throw new Exception("La comision servicios seleccionada no es valida", 1);}
			else{$this->comision_servicios = (preg_match("/true/", $this->comision_servicios)?true:false);}

			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM trabajadores WHERE id_trabajador = ?;");
			$consulta->execute([$this->id]);

			if(!($usuario = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El trabajdor seleccionado no ", 1);
				
			}

			if($usuario["cedula"] != $this->cedula){
				$consulta = $this->con->prepare("SELECT 1 FROM trabajadores WHERE cedula = ? LIMIT 1;");
				$consulta->execute([$this->cedula]);

				if($consulta->fetch()){
					$cedula = $usuario["cedula"];
					$cedula = $this->id;
					throw new Exception("La nueva cedula ($this->cedula) ya existe :: $cedula", 1);
				}
			}

			if($this->pass != ''){
				$consulta = $this->con->prepare("UPDATE `trabajadores` SET cedula = :cedula, `id_prima_profesionalismo`=:id_prima_profesionalismo,`id_rol`=:id_rol,`numero_cuenta`=:numero_cuenta,`creado`=:creado,`nombre`=:nombre,`apellido`=:apellido,`telefono`=:telefono,`correo`=:correo,`clave`=:clave,`estado_actividad`= 1, `discapacidad` = :discapacidad, `discapacitado` = :discapacitado, `genero` = :genero WHERE id_trabajador = :id");
				$consulta->bindValue(":clave",$this->pass);
			}
			else{
				$consulta = $this->con->prepare("UPDATE `trabajadores` SET cedula = :cedula, `id_prima_profesionalismo`=:id_prima_profesionalismo,`id_rol`=:id_rol,`numero_cuenta`=:numero_cuenta,`creado`=:creado,`nombre`=:nombre,`apellido`=:apellido,`telefono`=:telefono,`correo`=:correo,`estado_actividad`= 1, `discapacidad` = :discapacidad, `discapacitado` = :discapacitado, `genero` = :genero WHERE id_trabajador = :id");
			}
			$consulta->bindValue(":id",$this->id);
			$consulta->bindValue(":id_prima_profesionalismo",$this->nivel_profesional);
			$consulta->bindValue(":id_rol",$this->id_rol);
			$consulta->bindValue(":cedula",$this->cedula);
			$consulta->bindValue(":numero_cuenta",$this->numero_cuenta);
			$consulta->bindValue(":creado",$this->creado);
			$consulta->bindValue(":nombre",$this->nombre);
			$consulta->bindValue(":apellido",$this->apellido);
			$consulta->bindValue(":telefono",$this->telefono);
			$consulta->bindValue(":correo",$this->correo);
			$consulta->bindValue(":discapacitado",$this->discapacitado);
			$consulta->bindValue(":discapacidad",$this->discapacidad);
			$consulta->bindValue(":genero",$this->genero_trabajador);

			$consulta->execute();

			if($usuario["cedula"] != $this->cedula){
				Bitacora::registro($this->con,2,"Modifico al usuario ($this->cedula => ".$usuario['cedula'].")");
			}
			else{
				Bitacora::registro($this->con,2,"Modifico al usuario ($this->cedula)");
			}


			
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
	PRIVATE function eliminar_usuario($disable = false){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			if($this->id == $_SESSION["usuario_rotario"] ){
				throw new Exception("No puede eliminar su propio usuario", 1);
				
			}
			
			$consulta = $this->con->prepare("SELECT cedula FROM trabajadores WHERE id_trabajador = ?");
			$consulta->execute([$this->id]);

			if(!($consulta = $consulta->fetch())){
				throw new Exception("El usuario seleccionado no existe", 1);
			}
			$cedula = $consulta["cedula"];



			if($disable){
				$consulta = $this->con->prepare("UPDATE trabajadores set estado_actividad = 0 WHERE id_trabajador = ?");
			}
			else{
				$consulta = $this->con->prepare("DELETE FROM trabajadores WHERE id_trabajador = ?");
			}
			$consulta->execute([$this->id]);

			Bitacora::registro($this->con, 2, "Elimino al usuario ($cedula)");
			
			$r['resultado'] = 'eliminar_usuario';
			$r["mensaje"] = '';
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
				;
			$r['mensaje'] =  $e->getMessage();
			if($e->getCode() == "23000"){
				if($disable == false){
					return $this->eliminar_usuario(true);
				}
				else{
					$r['mensaje'] =  "El usuario no puede ser eliminado debido a que tiene registros relacionados";
					$r["mensaje_2"] = $e->getMessage();
				}
			}
			
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
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
		$this->nombre = ucwords($value);
	}
	PUBLIC function get_apellido(){
		return $this->apellido;
	}
	PUBLIC function set_apellido($value){
		$value = Validaciones::removeWhiteSpace($value);
		$this->apellido = ucwords($value);
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

		$value = Validaciones::removeWhiteSpace($value);
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
	PUBLIC function get_numero_cuenta(){
		return $this->numero_cuenta;
	}
	PUBLIC function set_numero_cuenta($value){
		$this->numero_cuenta = $value;
	}

	PUBLIC function get_nivel_profesional(){
		return $this->nivel_profesional;
	}
	PUBLIC function set_nivel_profesional($value){
		$this->nivel_profesional = $value;
	}

	PUBLIC function get_creado(){
		return $this->creado;
	}
	PUBLIC function set_creado($value){
		$this->creado = $value;
	}

	PUBLIC function get_comision_servicios(){
		return $this->comision_servicios;
	}
	PUBLIC function set_comision_servicios($value){
		$this->comision_servicios = $value;
	}

	PUBLIC function get_discapacitado(){
		return $this->discapacitado;
	}
	PUBLIC function set_discapacitado($value){
		$this->discapacitado = $value;
	}
	PUBLIC function get_discapacidad(){
		return $this->discapacidad;
	}
	PUBLIC function set_discapacidad($value){
		$this->discapacidad = $value;
	}
	PUBLIC function get_genero_trabajador(){
		return $this->genero_trabajador;
	}
	PUBLIC function set_genero_trabajador($value){
		$this->genero_trabajador = $value;
	}
}

 ?>