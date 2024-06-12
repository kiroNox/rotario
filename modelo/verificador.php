<?php 
if( !in_array($pagina, $excepciones_p) ){
	if(isset($_SESSION["usuario_rotario"])){
		$clase = new Conexion;
		$con = $clase->conecta();
		try {
			$consulta = $con->prepare("SELECT 
											m.nombre, perm.crear,perm.modificar,perm.eliminar,perm.consultar
											FROM
											usuarios as u
											LEFT JOIN roles as r 
											on r.id_rol = u.`id_rol`
											LEFT JOIN permisos as perm
											on perm.id_rol = r.id_rol
											LEFT JOIN modulos as m
											on m.id_modulos = perm.id_modulos

											WHERE 
											u.id_persona = ? AND
											u.token = ?;");

			$consulta->execute([ $_SESSION["usuario_rotario"], $_SESSION["token_rotario"] ]);
			$consulta = $consulta->fetchall(PDO::FETCH_ASSOC);
			if($consulta){// si el token es valido retorna los permisos del usuario
				$permisos = array();
				foreach ($consulta as $elem) {
					$permisos[$elem['nombre']] = array('crear' => $elem["crear"], "modificar" => $elem["modificar"], "eliminar" => $elem["eliminar"], "consultar" => $elem["consultar"] );
				}
				// guarda en la variable global "$permisos" los permisos del usuario de tal modo que
				// $permisos["inicio"]["consultar"] me retornara el permiso de consultar en el modulo de incio
				if($pagina == 'log'){
					//$pagina = 'dashboard';
				}
			}
			else{

				$pagina = "out";

				if(!empty($_POST)){
					session_unset();
					session_destroy();
					
					die("close_sesion_user");
				}
			}

		} catch (Exception $e) {
			if(empty($_POST)){
				echo $e->getTrace()."<br>";
				echo $e->getMessage()."at line: ".$e->getLine();
			}
			else{
				$r['resultado'] = 'error';
				$r['titulo'] = 'Error';
				$r['mensaje'] =  $e->getMessage();
				$r["trace"] = $e->getTrace();
				echo json_encode($r);
			}
		}
		// si hay una sesion abierta pero no es valida
		#$pagina="principal"

		#$pagina="home" // si hay una sesion abierta
	}
	else{
		$pagina = "log";
	}

}
?>