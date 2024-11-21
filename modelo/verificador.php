<?php 

(isset($_COOKIE["modo_oscuro"]))?$_COOKIE["modo_oscuro"]:'';

if(isset($_COOKIE["modo_oscuro"])){
	$modo_oscuro = 'dark-mode';
}
else{
	$modo_oscuro = "";
}

// TODO si no esta la tabla permisos deja entrar
if( !in_array($pagina, $excepciones_p) ){
	if(isset($_SESSION["usuario_rotario"])){
		// $clase = new Conexion;
		// $con = $clase->conecta();


		$resp = (new Autorizaciones)->get_list_permisos();
		if($resp["resultado"]=="get_list_permisos"){

			$permisos = $resp["permisos"];

			header("user:".$_SESSION["usuario_rotario"]);

		}
		else{
			if($resp["mensaje"] === "invalid_token"){
				$pagina = "out";

				session_unset();
				session_destroy();
				if(!empty($_POST)){
					die("close_sesion_user");
				}
			}
			else {
				if(empty($_POST)){
					// echo $e->getTrace()."<br>";
					// echo $e->getMessage()."at line: ".$e->getLine();

					$_POST['error'] = $resp["mensaje"];
					require_once "vista/404.php";
					die;
				}
				else{
					$r['resultado'] = 'error';
					$r['titulo'] = 'Error';
					$r['mensaje'] =  $resp["mensaje"];
					$r["trace"] = $resp["trace"];
					echo json_encode($r);
				}
			}
		}

		unset($resp);

		// si hay una sesion abierta pero no es valida
		#$pagina="principal"

		#$pagina="home" // si hay una sesion abierta
	}
	else{
		$pagina = "log";
	}

}
?>