<?php
	if(is_file("vista/".$pagina.".php")){

		require_once("vista/".$pagina.".php");
		
	}
	else{
		require_once("vista/404.php"); 
	}
?>