<?php 
	spl_autoload_register(function($class){
			$lcClass = strtolower($class);
		if(file_exists("modelo/".$lcClass.".php")){
			require_once(__DIR__."/".$lcClass.".php");
		}
		else{
			echo "hola  ".__DIR__."/".$lcClass.".php\n";
		}
		
	});
 ?>
