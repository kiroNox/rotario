<?php 
/**
 * 
 */
class Autorizaciones extends Conexion
{
	use Calculadora;
	PRIVATE $con, $id, $nombre, $crear, $modidficar, $eliminar, $consultar, $datos, $modulo;
	PRIVATE $permisos;
	
	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}
		else{
			$this->con = $con;
		}

	}


	PUBLIC function registrar_roles_s($rol,$permisos){
		$this->set_nombre($rol);
		$this->set_permisos($permisos);
		return $this->registrar_roles();
	}

	PUBLIC function modificar_roles_s($rol,$id,$permisos){
		$this->set_nombre($rol);
		$this->set_id($id);
		$this->set_permisos($permisos);
		return $this->modidficar_roles();

	}
	PUBLIC function eliminar_roles_s($id){
		$this->set_id($id);

		return $this->eliminar_roles();

	}

	PRIVATE function registrar_roles(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			// TODO validaciones
			
			$consulta = $this->con->prepare("SELECT 1 FROM rol WHERE descripcion = ?;");
			$consulta->execute([$this->nombre]);

			if($consulta->fetch()){
				throw new Exception("El rol ya existe", 1);
			}

			$consulta = $this->con->prepare("INSERT INTO rol (descripcion) values (?)");
			$consulta->execute([$this->nombre]);

			$last = $this->con->lastInsertId();

			$bit = "Registro de nuevo rol ($this->nombre) con los siguientes permisos<br><br>";

			$this->permisos = json_decode($this->permisos);



			foreach ($this->permisos as $elem) {
				if(!is_array($elem)){
					$elem = json_decode($elem);
				}

				// showvar($elem);


				$resp = $this->cambiar_permiso_s($elem->check,$last,$elem->modulo);
				$bit .= "Modulo (".$resp["modulo"]["nombre"].") <br>";
				$bit .= "Consultar: ".(($elem->check->consultar)?"Permitido<br>":"Rechazado <br>");
				$bit .= "Crear: ".(($elem->check->crear)?"Permitido<br>":"Rechazado <br>");
				$bit .= "Modificar: ".(($elem->check->modificar)?"Permitido<br>":"Rechazado <br>");
				$bit .= "Eliminar: ".(($elem->check->eliminar)?"Permitido<br>":"Rechazado <br>");


			}

			Bitacora::registro($this->con,"permisos",$bit);


			
			$r['resultado'] = 'registrar_roles';
			

			
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
			$r["trace"] = $e->getTrace();
		}
		finally{
			//$this->con = null;
		}
		return $r;
	}

	PRIVATE function eliminar_roles(){
		try {

			//TODO Validaciones
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			if($this->id == "1") {
				throw new Exception("No se puede eliminar el rol del Administrador", 1);
				
			}
			
			$consulta = $this->con->prepare("SELECT descripcion FROM rol WHERE id_rol = ?;");
			$consulta->execute([$this->id]);

			if(!($consulta = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El rol no existe", 1);
			}
			$this->nombre = $consulta["descripcion"];

			$consulta = $this->con->prepare("DELETE FROM rol WHERE id_rol = ?");
			$consulta->execute([$this->id]);

			
			$r['resultado'] = 'eliminar_roles';

			Bitacora::registro($this->con,"roles","Elimino el rol ($this->nombre)");
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
			if(preg_match("/Integrity constraint violation/", $e->getMessage())){
				$r["mensaje"] = 'El rol no puede ser eliminado ya que existen usuarios con este rol asignado';
			}
			else{

				$r['mensaje'] =  $e->getMessage();
			}
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
			$consulta = null;
		}
		return $r;
	}
	

	PRIVATE function modidficar_roles(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("SELECT * FROM rol WHERE id_rol = ?;");
			$consulta->execute([$this->id]);

			if(!($consulta = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Exception("El rol no existe", 1);
			}



			$descripcion_rol = $consulta["descripcion"];

			if($descripcion_rol =="Administrador"){
				throw new Exception("El rol del Administrador no puede ser modificado", 1);
			}

			$bit = "";

			if(isset($this->nombre)){


				if($consulta["descripcion"] != $this->nombre){
					$consulta = $this->con->prepare("SELECT * FROM rol WHERE id_rol <> ? and descripcion = ?;");
					$consulta->execute([$this->id, $this->nombre]);

					if($consulta->fetch()){
						throw new Exception("EL nombre no se puede modificar ya que existe un rol con ese nombre", 1);
					}
				}

				$consulta = $this->con->prepare("UPDATE rol SET descripcion = ? WHERE id_rol = ?");
				$consulta->execute([$this->nombre, $this->id]);

				$bit = "Modifico nombre del rol ($descripcion_rol) a ($this->nombre)";
			}


			$this->permisos = json_decode($this->permisos);

			if(count($this->permisos)>0){

				if($bit==''){
					$bit = "Modifico los permisos del rol ($descripcion_rol)<br><br>";
				}
				else{
					$bit .= "<br><br> Modifico los siguientes permisos <br><br>";
				}





				foreach ($this->permisos as $elem) {
					if(!is_array($elem)){
						$elem = json_decode($elem);
					}


					if(!isset($elem->check->crear)){
						continue;
					}






					$resp = $this->cambiar_permiso_s($elem->check,$this->id,$elem->modulo,true);
					if($resp["permisos"] !== false){

						foreach ($resp["permisos"] as &$elem_permisos) {

							$elem_permisos = boolval($elem_permisos);
						}
						unset($elem_permisos);

						$bit .= "Modulo (".$resp["modulo"]["descripcion"].") <br>";

						if($resp["permisos"]["consultar"] !== $elem->check->consultar){
							$bit .= "Consultar: ".(($resp["permisos"]["consultar"])?"Permitido":"Rechazado")." => ".(($elem->check->consultar)?"Permitido<br>":"Rechazado <br>");
						}

						if($resp["permisos"]["crear"] !== $elem->check->crear){
							$bit .= "Crear: ".(($resp["permisos"]["crear"])?"Permitido":"Rechazado")." => ".(($elem->check->crear)?"Permitido<br>":"Rechazado <br>");
						}

						if($resp["permisos"]["modificar"] !== $elem->check->modificar){
							$bit .= "Modificar: ".(($resp["permisos"]["modificar"])?"Permitido":"Rechazado")." => ".(($elem->check->modificar)?"Permitido<br>":"Rechazado <br>");
						}

						if($resp["permisos"]["eliminar"] !== $elem->check->eliminar){
							$bit .= "Eliminar: ".(($resp["permisos"]["eliminar"])?"Permitido":"Rechazado")." => ".(($elem->check->eliminar)?"Permitido<br>":"Rechazado <br>");
						}
					}
					else{

						$bit .= "Se agregaron los permisos<br>Modulo (".$resp["modulo"]["descripcion"].")<br><br>";
						$bit .= "Consultar: ".(($elem->check->consultar)?"Permitido":"Rechazado");
						$bit .= "Crear: ".(($elem->check->crear)?"Permitido":"Rechazado");
						$bit .= "Modificar: ".(($elem->check->modificar)?"Permitido":"Rechazado");
						$bit .= "Eliminar: ".(($elem->check->eliminar)?"Permitido":"Rechazado");

					}

				}


			}
			
			
			Bitacora::registro($this->con,"roles",$bit);


			
			$r['resultado'] = 'modificar_roles';
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





	PUBLIC function listar_roles(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();


			$consulta = $this->con->prepare("SELECT r.id_rol as id, r.descripcion as rol, COUNT(u.id_trabajador) as usuarios, NULL as extra FROM `rol` AS r LEFT JOIN trabajadores as u on u.id_rol = r.id_rol WHERE 1 GROUP BY r.id_rol");
			$consulta->execute();




			// code
			
			$r['resultado'] = 'listar_roles';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
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

	PUBLIC function listar_modulos_s($rol){
		if($rol == "no_rol"){
			$this->set_id(false);
			return $this->listar_modulos();
		}
		else{
			$this->set_id($rol);
			return $this->listar_modulos();
		}
	}

	PRIVATE function listar_modulos(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			if($this->id === false){// lista de permisos y modulos

				$consulta = $this->con->prepare("SELECT
				m.id_modulos,
				m.nombre AS modulo,
				m.nombre as nombre,
				1 as crear,
				1 as modificar,
				1 as eliminar,
				1 as consultar
				FROM
					modulos AS m
				LEFT JOIN permisos AS p
				ON
					p.id_modulos = m.id_modulos 
				WHERE
				1 GROUP BY m.id_modulos;");

				$consulta->execute();

				$r['resultado'] = 'listar_modulos_roles';
				$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);

			}
			else{// lista de modulos con permisos de un determinado rol


				$consulta = $this->con->prepare("SELECT descripcion,id_rol FROM rol WHERE id_rol = ?;");

				$consulta->execute([$this->id]);

				if(!$rol = $consulta->fetch(PDO::FETCH_ASSOC)){
					throw new Exception("El rol seleccionado no existe o fue eliminado", 1);
				}

						
						$consulta = $this->con->prepare("SELECT
							m.id_modulos,
							r.id_rol,
				    m.nombre AS modulo,
				    m.nombre as nombre,
				    IF(p.crear IS NULL,0,p.crear) as crear,
				    IF(p.modificar IS NULL, 0 , p.modificar) as modificar,
				    IF(p.eliminar IS NULL, 0 , p.eliminar) as eliminar,
				    IF(p.consultar IS NULL, 0 , p.consultar) as consultar
				FROM
				    modulos AS m
				    
				CROSS JOIN rol as r
				LEFT JOIN permisos AS p
				ON
				    p.id_modulos = m.id_modulos AND
				    p.id_rol = r.id_rol
				WHERE
				    r.id_rol = ?");

				$consulta->execute([$this->id]);

				$r['resultado'] = 'listar_modulos';
				$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
				$r["rol"] = $rol;

			}

			
			
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

	PRIVATE function cambiar_permiso_s($datos, $rol, $modulo, $control=false){
		$this->set_datos($datos);
		$this->set_id($rol);
		$this->set_modulo($modulo);

		return $this->cambiar_permiso($control);
	}


	PUBLIC function cambiar_permiso($modificando){

		$datos = $this->datos;
		$rol = $this->id;
		$modulo = $this->modulo;

		if($rol == '1'){
			throw new Exception("No se pueden cambiar los permisos del Administrador", 1);
		}
		$this->validar_conexion($this->con);

		if($modificando){


			$consulta = $this->con->prepare("SELECT
											    1
											FROM
											    trabajadores AS t
											JOIN permisos as p on p.id_rol = t.id_rol
											JOIN modulos as m on m.id_modulos = p.id_modulos
											WHERE
											    t.id_trabajador = ? AND m.nombre like '%permisos%' AND m.id_modulos = ? AND p.id_rol = ?");

			$consulta->execute([$_SESSION["usuario_rotario"], $modulo, $rol]);

			if($consulta->fetch()){
				throw new Exception("No puede modificar los permisos del modulo de 'permisos' de su propio rol", 1);
			}
			$consulta = null;
		}

		$consulta = $this->con->prepare("SELECT p.crear,p.modificar,p.eliminar,p.consultar from modulos as m LEFT JOIN permisos as p on p.id_modulos = m.id_modulos WHERE m.id_modulos = :id_modulos and p.id_rol = :id_rol");
		$consulta->bindValue(":id_modulos",$modulo);
		$consulta->bindValue(":id_rol",$rol);

		$consulta->execute();

		$permisos = $consulta->fetch(PDO::FETCH_ASSOC);
		$consulta =

		$consulta = $this->con->prepare("SELECT m.* from modulos as m WHERE m.id_modulos = :id_modulos ");
		$consulta->bindValue(":id_modulos",$modulo);

		$consulta->execute();
		$modulo_select = $consulta->fetch(PDO::FETCH_ASSOC);



		
		$consulta = $this->con->prepare("INSERT INTO `permisos`(`id_rol`, `id_modulos`, `crear`, `modificar`, `eliminar`, `consultar`) VALUES (:id_rol, :id_modulos, :crear, :modificar, :eliminar, :consultar) ON DUPLICATE KEY UPDATE crear = :crear, modificar = :modificar, eliminar = :eliminar, consultar = :consultar");


		$consulta->bindValue(":id_rol",$rol);
		$consulta->bindValue(":id_modulos",$modulo);
		$consulta->bindValue(":crear",$datos->crear);
		$consulta->bindValue(":modificar",$datos->modificar);
		$consulta->bindValue(":eliminar",$datos->eliminar);
		$consulta->bindValue(":consultar",$datos->consultar);

		$consulta->execute();
		
		$r['permisos'] = $permisos;
		$r['modulo'] = $modulo_select;
		

		//Bitacora::registro($this->con,"permisos", "cambio los permiso de un rol");// TODO de que rol
		
		return $r;

	}





	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_nombre(){
		return $this->nombre;
	}
	PUBLIC function set_nombre($value){
		$this->nombre = $value;
	}
	PUBLIC function get_crear(){
		return $this->crear;
	}
	PUBLIC function set_crear($value){
		$this->crear = $value;
	}
	PUBLIC function get_modidficar(){
		return $this->modidficar;
	}
	PUBLIC function set_modidficar($value){
		$this->modidficar = $value;
	}
	PUBLIC function get_eliminar(){
		return $this->eliminar;
	}
	PUBLIC function set_eliminar($value){
		$this->eliminar = $value;
	}
	PUBLIC function get_consultar(){
		return $this->consultar;
	}
	PUBLIC function set_consultar($value){
		$this->consultar = $value;
	}
	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
	PUBLIC function get_datos(){
		return $this->datos;
	}
	PUBLIC function set_datos($value){
		$this->datos = $value;
	}
	PUBLIC function get_modulo(){
		return $this->modulo;
	}
	PUBLIC function set_modulo($value){
		$this->modulo = $value;
	}

	PUBLIC function get_permisos(){
		return $this->permisos;
	}
	PUBLIC function set_permisos($value){
		$this->permisos = $value;
	}

}
 ?>