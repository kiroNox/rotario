<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * 
 */
trait Correos
{
	PRIVATE function enviar_correo($data,$template,$asunto='',$file_to_send = false)
	{
		$mail = new PHPMailer(true);
		ob_start();
		require("assets/templates/".$template.".php");
		$mensaje = ob_get_clean();


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
			$mail->Subject = $asunto;
			//$mail->Body = $mensaje;
			$mail->MsgHTML($mensaje);
			$resp = $mail->send();
			$mail->clearAllRecipients();
			$mail->clearAttachments();
			$mail->clearCustomHeaders();

			$mail = null;
			
		

	}
}

?>