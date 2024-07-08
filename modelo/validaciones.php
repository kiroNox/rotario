<?php 
class Validaciones extends Exception
{
	PUBLIC static function validarCedula(&$string,$type=true, $allow_empty = false, $mensaje = "La cedula no es valida"){//rif y cedula (true para tipo de cedula obligatorio)

		$cedula_1 = "/(?:(?:^[ve][-][0-9]{7,8}$)|(?:^[jg][-][0-9]{8,10}$))/i";
		$cedula_2 = "/(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i";
		$pattern = ($type) ? $cedula_1 : $cedula_2;
		//$pattern = '/(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i';

		
		if($allow_empty == true and $string == ''){
			return true;
		}
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}

	// n es igual al maximo de caracteres, si se coloca cero (0) no tendría limite
	PUBLIC static function validarNombre($string,$n="1,50",$mensaje="El Nombre no es valido",$allow_empty = false){

		$pattern=$n?"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{".$n."}$/u":"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]+$/u";
		if($allow_empty == true and $string == ''){
			return true;
		}
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}
	PUBLIC static function alfanumerico ($string,$n="1,50",$mensaje="caracteres no permitidos", $allow_empty = false){
		$pattern=$n?"/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{".$n."}$/u":"/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]+$/u";
		if($allow_empty == true and $string == ''){
			return true;
		}
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}

	}
	PUBLIC static function validarTelefono($string,$mensaje = "El teléfono no es valido", $allow_empty = false){// valida telefono con formato "0414-5555555" o "04145555555" o "0414 5555555"

		$pattern='/^[0-9]{4}[-\s]?[0-9]{7}$/';
		if($allow_empty == true and $string == ''){
			return true;
		}
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}
	PUBLIC static function validarEmail($string,$mensaje = "El correo no es valido",$allow_empty = false){
		if($allow_empty == true and $string == ''){
			return true;
		}
		if(!filter_var( $string , FILTER_VALIDATE_EMAIL)){
			throw new Validaciones($mensaje, 1);
		}


	}
	PUBLIC static function validarContrasena($string, $mensaje = "La contraseña no es valida")
	{
		$pattern='/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,20}$/';
		if(!preg_match($pattern, $string)){
			$mensaje_temp = "";

			if(!preg_match("/^.{6,20}$/", $string)) $mensaje_temp = "entre 6 y 20 caracteres";
			if(!preg_match("/^(?=.*?[A-Z]).{1,}$/", $string)) $mensaje_temp .= ($mensaje_temp == '')?"una letra mayúscula": ", una letra mayúscula";
			if(!preg_match("/^(?=.*?[a-z]).{1,}$/", $string)) $mensaje_temp .= ($mensaje_temp == '')?"una letra minúscula": ", una letra minúscula";
			if(!preg_match("/^(?=.*?[0-9]).{1,}$/", $string)) $mensaje_temp .= ($mensaje_temp == '')?"un numero": " y un numero";
			throw new Validaciones($mensaje, 1);
		}
		//Debe tener entre 6 y 20 caracteres y al menos **un numero, una letra minúscula y una mayúscula**
	}

	PUBLIC static function fecha($string, $mensaje = "fecha"){
		if(is_string($string)){
			if(!preg_match("/^[\d][\d][\d][\d][\D][\d][\d][\D][\d][\d]$/", $string)){
				// si no es un string con el formato
				throw new Validaciones("La $mensaje no tiene el formato adecuado (yyyy-mm-dd) :: $string", 1);
			}
			$string = preg_split("/\D/", $string);

		}else{throw new Validaciones("la $mensaje no es una cadena de caracteres", 1);}

		$a = $string[0];// año
		$m = $string[1];// mes
		$d = $string[2];// dia
		if($a < 1900){throw new Validaciones("El año de la $mensaje no puede ser menor a 1900", 1);}
		if(($m < 1) || $m > 12){throw new Validaciones("El mes de la $mensaje no puede ser menor a 1 o mayor a 12", 1);}
		if(($d < 1) || $d > 31){throw new Validaciones("El día de la $mensaje no puede ser menor a 1 o mayor a 31", 1);}
		if(($a%4 != 0) and ($m == 2) and ($d > 28)){throw new Validaciones("($mensaje) Solo el año bisiesto tiene mas de 28 días en febrero", 1);}
		if((($m == 4) || ($m == 6 )|| ($m == 9 )|| ($m == 11) ) and $d>30){throw new Validaciones("En la $mensaje el mes seleccionado no puede tener mas de 30 días", 1);}
		if($m==2 and $d>29){throw new Validaciones("En la $mensaje el mes de febrero no puede tener mas de 29 días", 1);}
	}

	PUBLIC static function hora($string,$mensaje = "La hora no es valida"){
		if(!preg_match("/^(?:[01]?[0-9]|2[0-3]):[0-5][0-9]$/", $string)){
			throw new Validaciones($mensaje, 1);
		}
	}

	PUBLIC static function monto_Miles($string, $mensaje = "El monto es invalido"){
		if(!preg_match("/^[0-9]{1,3}(?:[\.][0-9]{3}){0,6}[,][0-9]{2}$/", $string)){
			throw new Validaciones($mensaje, 1);
			
		}
	}
	PUBLIC static function monto($string, $mensaje = "El monto es invalido" ){
		if(!preg_match("/^[0-9]{1,18}(?:[\,\.][0-9]{2})?$/", $string)){
			throw new Validaciones($mensaje, 1);
			
		}
	}
	PUBLIC static function numero($string,$n = "1,50",$mensaje = "El numero es invalido"){
		if(!preg_match("/^[0-9]{".$n."}$/", $string)){
			throw new Validaciones($mensaje, 1);
		}
	}




	PUBLIC static function validar($string,$pattern,$mensaje){// para otros personalizado
		if(!preg_match($pattern, $string)){
			throw new Validaciones($mensaje, 1);
		}
	}



	PUBLIC static function removeWhiteSpace($string){
		// elimina espacios al principio y al final de una cadena
		// también elimina espacios seguidos de otro espacio
		// ej: "   hola      como estas   " pasa a "hola como estas"

		if(preg_match("/(?:^\s)|(?:[\s][\s])|(?:[\s]+$)/", $string)){
			$string = preg_replace("/\n/m", "---WHITE_ENDL_SPACE---", $string);
			$string = preg_replace("/(?:^\s+)|(?:\s+$)/m", "", $string);

			while (preg_match("/[\s][\s]/", $string)) {
				$string = preg_replace("/[\s][\s]/", " ", $string);
			}
			$string = preg_replace("/---WHITE_ENDL_SPACE---/", "\n", $string);
		}
		return $string;

	}
}
?>