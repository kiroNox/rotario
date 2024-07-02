<?php 

/**
 * 
 */
class administrar_empleados extends Conexion
{
	PRIVATE $id, $desde, $hasta, $dias_totales, $descripcion, $tipo_reposo, $tipo_de_permiso, $cedula, $nombre, $apellido, $telefono, $correo, $numero_cuenta, $fecha_nacimiento, $sexo, $salario;

	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

	PUBLIC function registrar_vacaciones($desde, $hasta, $dias_totales, $descripcion,$id){
		$this->set_id($id);
		$this->set_desde($desde);
		$this->set_hasta($hasta);
		$this->set_dias_totales($dias_totales);
		$this->set_descripcion($descripcion);

		return $this->registrar_vacacion();
	}

	PUBLIC function modificar_vacaciones($desde, $hasta, $dias_totales, $descripcion, $id_tabla ){
		$this->set_desde($desde);
		$this->set_hasta($hasta);
		$this->set_dias_totales($dias_totales);
		$this->set_descripcion($descripcion);
		$this->set_id_tabla($id_tabla);

		return $this->modificar_vacacion();
	}

	PUBLIC function registrar_reposo( $id, $tipo_reposo, $descripcion, $desde, $hasta){
		$this->set_id($id);
		$this->set_desde($desde);
		$this->set_hasta($hasta);
		$this->set_tipo_reposo($tipo_reposo);
		$this->set_descripcion($descripcion);

		return $this->registrar_repo();
	}

	PUBLIC function modificar_reposo($desde, $hasta, $tipo_reposo, $descripcion ){
		$this->set_desde($desde);
		$this->set_hasta($hasta);
		$this->set_tipo_reposo($tipo_reposo);
		$this->set_descripcion($descripcion);

		return $this->modificar_usuario();
	}

	PUBLIC function registrar_permiso($id, $tipo_permiso, $descripcion, $desde, $hasta){
		$this->set_id($id);
		$this->set_tipo_de_permiso($tipo_permiso);
		$this->set_descripcion($descripcion);
		$this->set_desde($desde);
		$this->set_hasta($hasta);

		return $this->registrar_perm();
	}

	PUBLIC function modificar_permiso($desde, $hasta, $dias_totales, $descripcion ){
		$this->set_desde($desde);
		$this->set_hasta($hasta);
		$this->set_tipo_permiso($tipo_permiso);
		$this->set_descripcion($descripcion);

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
			$consulta = $this->con->query("SELECT cedula,nombre,apellido,telefono,correo, r.descripcion as rol, numero_cuenta,  NULL as extra, p.id_trabajador FROM trabajadores as p left join rol as r on r.id_rol = p.id_rol WHERE estado_actividad = 1;")->fetchall(PDO::FETCH_NUM);
			

			
			$r['resultado'] = 'listar';
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

	 private function registrar_vacacion() {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            
            $consulta = $this->con->prepare("INSERT INTO `vacaciones` (`id_trabajador`, `descripcion`, `dias_totales`, `desde`, `hasta`) VALUES (:id_trabajador, :descripcion, :dias_totales, :desde, :hasta)");
            $consulta->bindValue(":id_trabajador", $this->id);
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->bindValue(":dias_totales", $this->dias_totales);
            $consulta->bindValue(":desde", $this->desde);
            $consulta->bindValue(":hasta", $this->hasta);
            $consulta->execute();
            
            $this->con->commit();
            $r['resultado'] = 'registrar';
            $r['titulo'] = 'Éxito';
            $r['mensaje'] = "Vacaciones registradas con éxito";
        } catch (Validaciones $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'is-invalid';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
            $r['console'] = $e->getMessage() . ": Code : " . $e->getLine();
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'error';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
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

	private function modificar_vacacion() {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            
            $consulta = $this->con->prepare("UPDATE `vacaciones` 
				SET 
				`descripcion`=:descripcion,`dias_totales`=:dias_totales,`desde`=:desde,`hasta`=:hasta WHERE id_vacaciones  = :id_vacaciones ;");

			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":dias_totales",$this->dias_totales);
			$consulta->bindValue(":desde",$this->desde);
			$consulta->bindValue(":hasta",$this->hasta);
			$consulta->bindValue(":id_vacaciones",$this->id_tabla);
			$consulta->execute();
            
            $this->con->commit();
            $r['resultado1'] = $this->descripcion;
            $r['resultado2'] = $this->dias_totales;
            $r['resultado3'] = $this->desde;
            $r['resultado4'] = $this->hasta;
            $r['resultado5'] = $this->id_tabla;
            $r['resultado'] = 'modificar';
            $r['titulo'] = 'Éxito';
            $r['mensaje'] = "Vacaciones modificadas con éxito";
        } catch (Validaciones $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'is-invalid';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
            $r['console'] = $e->getMessage() . ": Code : " . $e->getLine();
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'error';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

	private function registrar_repo() {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            
            $consulta = $this->con->prepare("INSERT INTO `reposo` (`id_trabajador`, `tipo_reposo`, `descripcion`, `desde`, `hasta`) VALUES (:id_trabajador, :tipo_reposo, :descripcion, :desde, :hasta)");
            $consulta->bindValue(":id_trabajador", $this->id);
			$consulta->bindValue(":tipo_reposo", $this->tipo_reposo);
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->bindValue(":desde", $this->desde);
            $consulta->bindValue(":hasta", $this->hasta);
            $consulta->execute();
            
            $this->con->commit();
            $r['resultado'] = 'registrar';
            $r['titulo'] = 'Éxito';
            $r['mensaje'] = "Vacaciones registradas con éxito";
        } catch (Validaciones $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'is-invalid';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
            $r['console'] = $e->getMessage() . ": Code : " . $e->getLine();
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'error';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

	private function registrar_perm() {
        try {
            $this->validar_conexion($this->con);
            $this->con->beginTransaction();
            
            $consulta = $this->con->prepare("INSERT INTO `permisos_trabajador` (`id_trabajador`, `tipo_de_permiso`, `descripcion`, `desde`, `hasta`) VALUES (:id_trabajador, :tipo_de_permiso, :descripcion, :desde, :hasta)");
            $consulta->bindValue(":id_trabajador", $this->id);
			$consulta->bindValue(":tipo_de_permiso", $this->tipo_de_permiso);
            $consulta->bindValue(":descripcion", $this->descripcion);
            $consulta->bindValue(":desde", $this->desde);
            $consulta->bindValue(":hasta", $this->hasta);
            $consulta->execute();
            
            $this->con->commit();
            $r['resultado'] = 'registrar';
            $r['titulo'] = 'Éxito';
            $r['mensaje'] = "Vacaciones registradas con éxito";
        } catch (Validaciones $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'is-invalid';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
            $r['console'] = $e->getMessage() . ": Code : " . $e->getLine();
        } catch (Exception $e) {
            if ($this->con instanceof PDO) {
                if ($this->con->inTransaction()) {
                    $this->con->rollBack();
                }
            }
            $r['resultado'] = 'error';
            $r['titulo'] = 'Error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

	public function listar_vacaciones() {
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT t.cedula, t.nombre, t.apellido, v.descripcion, v.desde, v.hasta FROM vacaciones AS v JOIN trabajadores AS t ON v.id_trabajador = t.id_trabajador ")->fetchAll(PDO::FETCH_NUM);
			$r['resultado'] = 'listar';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] = $consulta;
			$this->con->commit();
		} catch (Exception $e) {
			if ($this->con instanceof PDO && $this->con->inTransaction()) {
				$this->con->rollBack();
			}
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}
	
	public function listar_reposos() {
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT t.cedula, t.nombre, t.apellido, r.tipo_reposo, r.descripcion, r.desde, r.hasta FROM reposo AS r JOIN trabajadores AS t ON r.id_trabajador = t.id_trabajador ")->fetchAll(PDO::FETCH_NUM);
			$r['resultado'] = 'listar';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] = $consulta;
			$this->con->commit();
		} catch (Exception $e) {
			if ($this->con instanceof PDO && $this->con->inTransaction()) {
				$this->con->rollBack();
			}
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}
	
	public function listar_permisos() {
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT t.cedula, t.nombre, t.apellido, p.tipo_de_permiso, p.descripcion, p.desde, p.hasta FROM permisos_trabajador AS p JOIN trabajadores AS t ON p.id_trabajador = t.id_trabajador ")->fetchAll(PDO::FETCH_NUM);
			$r['resultado'] = 'listar';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] = $consulta;
			$this->con->commit();
		} catch (Exception $e) {
			if ($this->con instanceof PDO && $this->con->inTransaction()) {
				$this->con->rollBack();
			}
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function obtener_detalles_vacaciones($id_trabajador){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("
			SELECT t.cedula, t.nombre, t.apellido, v.descripcion, v.desde, v.hasta, v.id_vacaciones, v.dias_totales, v.id_trabajador FROM vacaciones AS v JOIN trabajadores AS t ON v.id_trabajador = t.id_trabajador WHERE v.id_trabajador = :id_trabajador order by v.id_trabajador desc limit 1;
        ");
			$consulta->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
			$consulta->execute();
			$resultado = $consulta->fetch(PDO::FETCH_ASSOC);
	
			if ($resultado) {
				$r['resultado'] = 'listar';
				$r['mensaje'] = $resultado;
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] = 'No se encontraron vacaciones activas para este trabajador.';
			}
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
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	
	PUBLIC function get_intruccion(){
		return $this->intruccion;
	}
	PUBLIC function set_intruccion($value){
		$this->intruccion = $value;
	}
	PUBLIC function get_salario(){
		return $this->salario;
	}
	PUBLIC function set_salario($value){
		$this->salario = $value;
	}
	PUBLIC function get_fecha_nacimiento(){
		return $this->fecha_nacimiento;
	}
	PUBLIC function set_fecha_nacimiento($value){
		$this->fecha_nacimiento = $value;
	}
	PUBLIC function get_sexo(){
		return $this->sexo;
	}
	PUBLIC function set_sexo($value){
		$this->sexo = $value;
	}
	PUBLIC function get_desde(){
		return $this->desde;
	}
	PUBLIC function set_desde($value){
		$this->desde = $value;
	}
	PUBLIC function get_hasta(){
		return $this->hasta;
	}
	PUBLIC function set_hasta($value){
		$this->hasta = $value;
	}
	PUBLIC function get_dias_totales(){
		return $this->dias_totales;
	}
	PUBLIC function set_dias_totales($value){
		$this->dias_totales = $value;
	}
	PUBLIC function get_descripcion(){
		return $this->descripcion;
	}
	PUBLIC function set_descripcion($value){
		$this->descripcion = $value;
	}
	PUBLIC function get_tipo_reposo(){
		return $this->tipo_reposo;
	}
	PUBLIC function set_tipo_reposo($value){
		$this->tipo_reposo = $value;
	}
	PUBLIC function get_tipo_de_permiso(){
		return $this->tipo_de_permiso;
	}
	PUBLIC function set_tipo_de_permiso($value){
		$this->tipo_de_permiso = $value;
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
	PUBLIC function set_apellido($value){
		$value = Validaciones::removeWhiteSpace($value);
		$this->apellido = $value;
	}
	PUBLIC function get_apellido(){
		return $this->apellido;
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
	PUBLIC function get_numero_cuenta(){
		return $this->numero_cuenta;
	}
	PUBLIC function set_numero_cuenta($value){
		$this->numero_cuenta = $value;
	}
	PUBLIC function get_id_rol(){
		return $this->id_rol;
	}
	PUBLIC function set_id_rol($value){
		$this->id_rol = $value;
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

	PUBLIC function get_id_tabla(){
		return $this->id_tabla;
	}
	PUBLIC function set_id_tabla($value){
		$this->id_tabla = $value;
	}
}

 ?>