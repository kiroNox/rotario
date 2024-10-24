<?php 

	namespace production{
		spl_autoload_register(function($class){
				$lcClass = strtolower($class);
			if(file_exists("modelo/".$lcClass.".php")){
				require_once("modelo/".$lcClass.".php");
			}
		});
	}
	namespace test{
		spl_autoload_register(function($class){
				$lcClass = strtolower($class);
			if(file_exists("modelo/".$lcClass.".php")){
				require_once("../../modelo/".$lcClass.".php");
			}
		});
	}
 ?>
