<?php
	if(is_file("vista/".$pagina.".php")){

		$cl = new Facturar;

		if(!empty($_POST)){// si hay alguna consulta tipo POST

			$cl = new Conexion;
			$cl->no_permision_msg();
			exit;
		}

		require_once("vista/".$pagina.".php");
	}
	else{
		require_once("vista/404.php"); 
	}
?>