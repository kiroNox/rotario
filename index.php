<?php 
	session_start();



		if(isset($_GET['APP-REQUEST'])){
			$pagina_post_temp=json_decode(file_get_contents('php://input'), true);
			if(isset($pagina_post_temp)){
				$_POST = $pagina_post_temp;
			}
		}

	$pagina = "log"; 
	 if (!empty($_GET['p'])){
	   $pagina = $_GET['p'];
	 }
	require_once("vendor/autoload.php");
	if(is_file("controlador/$pagina.php")){
		$excepciones_p=[
			"principal",
			"login",
			"reset-pass",
			"out",
			"404"
		];


		require_once "modelo/verificador.php";
		require_once "modelo/notificacion.php";
		require_once("controlador/$pagina.php");
		
		
	}
	else{
		require_once("vista/404.php");
	}
 ?>