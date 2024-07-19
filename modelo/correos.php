<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * 
 */
trait Correos
{
	PRIVATE function enviar_correo($data,$template,$file_to_send = false)
	{
		$mail = new PHPMailer(true);
		ob_start();
		require_once("assets/templates/".$template.".php");
		$mensaje = ob_get_clean();

		try { 
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Debugoutput = 'html';
			$mail->Host = EMAIL_HOST;
			$mail->Port = 587;
			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;
			$mail->Username = EMAIL;
			$mail->Password = EMAIL_PASS;
			$mail->setFrom(EMAIL, EMAIL_NAME);
			$mail->addAddress($data['email']); 
			if(!empty($data['emailCopia'])){
				$mail->addBCC($data['emailCopia']);
			}
			if($file_to_send !== false and is_file($file_to_send[0])){
				$mail->addAttachment($file_to_send[0], $file_to_send[1]);
			}
			$mail->CharSet = 'UTF-8';
			$mail->isHTML(true);
			$mail->Subject = $data['asunto'];
			$mail->Body = $mensaje;
			$resp = $mail->send();
			if(!$resp){

				throw new Exception("No se pudo enviar el correo", 1);
				
			}
		}  finally{

		}

	}
}

?>