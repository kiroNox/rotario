<?php 
/**
 * 
 */
class Autorizaciones extends Conexion
{
	PRIVATE $id, $nombre, $crear, $modidficar, $eliminar, $consultar,$con;
	
	function __construct($con = '')
	{
		if(!($con instanceof PDO)){
			$this->con = $this->conecta();
		}

	}


	PUBLIC function registrar_roles_s($rol){
		$this->set_nombre($rol);
		return $this->registrar_roles();
	}

	PUBLIC function modificar_roles_s($rol,$id){
		$this->set_nombre($rol);
		$this->set_id($id);
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

			
			$r['resultado'] = 'registrar_roles';
			

			Bitacora::registro($this->con,"roles","Registro de nuevo rol ($this->nombre)");
			
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
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
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

			if($consulta["descripcion"] != $this->nombre){
				$consulta = $this->con->prepare("SELECT * FROM rol WHERE id_rol <> ? and descripcion = ?;");
				$consulta->execute([$this->id, $this->nombre]);

				if($consulta->fetch()){
					throw new Exception("EL nombre no se puede modificar ya que existe un rol con ese nombre", 1);
				}
			}

			$consulta = $this->con->prepare("UPDATE rol SET descripcion = ? WHERE id_rol = ?");
			$consulta->execute([$this->nombre, $this->id]);

			Bitacora::registro($this->con,"roles","Modifico el rol ($this->nombre)");


			
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


			$consulta = $this->con->prepare("SELECT r.id_rol as id, r.descripcion as rol, COUNT(u.id_trabajador) as usuarios FROM `rol` AS r LEFT JOIN trabajadores as u on u.id_rol = r.id_rol WHERE 1 GROUP BY r.id_rol");
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
		$this->set_id($rol);
		return $this->listar_modulos();
	}

	PRIVATE function listar_modulos(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
					
					$consulta = $this->con->prepare("SELECT
						m.id_modulos,
						r.id_rol,
		    m.nombre AS modulo,
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


	PUBLIC function cambiar_permiso($datos,$rol,$modulo){
		$datos = json_decode($datos);

		try {
			if($rol == '1' ){ //TODO quitar eso
				throw new Exception("No se pueden cambiar los permisos del Administrador", 1);
			}
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare("INSERT INTO `permisos`(`id_rol`, `id_modulos`, `crear`, `modificar`, `eliminar`, `consultar`) VALUES (:id_rol, :id_modulos, :crear, :modificar, :eliminar, :consultar) ON DUPLICATE KEY UPDATE crear = :crear, modificar = :modificar, eliminar = :eliminar, consultar = :consultar");

			


			$consulta->bindValue(":id_rol",$rol);
			$consulta->bindValue(":id_modulos",$modulo);
			$consulta->bindValue(":crear",$datos->crear);
			$consulta->bindValue(":modificar",$datos->modificar);
			$consulta->bindValue(":eliminar",$datos->eliminar);
			$consulta->bindValue(":consultar",$datos->consultar);

			$consulta->execute();
			
			$r['resultado'] = 'cambiar_permiso';

			Bitacora::registro($this->con,"permisos", "cambio los permiso de un rol");// TODO de que rol
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

}
 ?>