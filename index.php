<?php 
	session_start();
	


	$pagina = "log"; 
	 if (!empty($_GET['p'])){
	   $pagina = $_GET['p'];
	 }
	require_once("assets/config/config.php");
	if(is_file("controlador/$pagina.php")){
		$excepciones_p=[
			"principal",
			"login",
			"reset-pass",
			"out"
		];
		
		require_once("vendor/autoload.php");
		require_once ("modelo/loader.php");
		require_once "modelo/verificador.php";
		require_once "modelo/notificacion.php";
		require_once("controlador/$pagina.php");
		
		
	}
	else{
		require_once("vista/404.php");
		//echo "pagina en construcción I <br>";
	}
	// Para preguntar:
	/*
		Nivel de precicion de los calculos (el redondeo)
		para las deducciones quincenales como se hace en febrero
	*/
 ?>