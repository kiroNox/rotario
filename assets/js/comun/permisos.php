<?php 
	$final_permisos = "{}";
	session_start();
	require_once "../../../vendor/autoload.php";
	$lista_permisos = (new Autorizaciones)->get_list_permisos();

	if($lista_permisos["resultado"] == "get_list_permisos") {
		unset($lista_permisos["permisos"]["validar_permisos"]);
		//$lista_permisos["permisos"]["primas"]["modificar"] = "0";
		$final_permisos = json_encode($lista_permisos["permisos"]);

	}
	

 ?>

const PERMISOS = JSON.parse('<?=$final_permisos ?>');

const checkPermisos = function(modulo,permiso) {
	if(PERMISOS && PERMISOS[modulo] && PERMISOS[modulo][permiso] == '1'){
		return true;
	}
	return false;
}