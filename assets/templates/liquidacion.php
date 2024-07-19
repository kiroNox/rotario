<?php if(file_exists('../../assets/config/config.php')){
	require_once '../../assets/config/config.php'; 
} ?>
<?php 
if(!isset($data)){
	$data["nombre"] = 'Ingrese nombre';
	$data["monto"] = "XXXX";
}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Liquidación</title>
	<style type="text/css">
		p{
			font-family: arial;
			letter-spacing: 1px;
			color: #7f7f7f;
			font-size: 15px;
		}
		a{
			color: #3b74d7;
			font-family: arial;
			text-decoration: none;
			text-align: center;
			display: block;
			font-size: 18px;
		}
		.x_sgwrap p{
			font-size: 20px;
		    line-height: 32px;
		    color: #244180;
		    font-family: arial;
		    text-align: center;
		}
		.x_title_gray {
		    color: #0a4661;
		    padding: 5px 0;
		    font-size: 15px;
			border-top: 1px solid #CCC;
		}
		.x_title_blue {
		    padding: 08px 0;
		    line-height: 25px;
		    text-transform: uppercase;
			border-bottom: 1px solid #CCC;
		}
		.x_title_blue h1{
			color: #0a4661;
			font-size: 25px;
			font-family: 'arial';
		}
		.x_bluetext {
		    color: #244180 !important;
		}
		.x_title_gray a{
			text-align: center;
			padding: 10px;
			margin: auto;
			color: #0a4661;
		}
		.x_text_white a{
			color: #FFF;
		}
		.x_button_link {
		    width: 100%;
			max-width: 470px;
		    height: 40px;
		    display: block;
		    color: #FFF;
		    margin: 20px auto;
		    line-height: 40px;
		    text-transform: uppercase;
		    font-family: Arial Black,Arial Bold,Gadget,sans-serif;
		}
		.x_link_blue {
		    background-color: #307cf4;
		}
		.x_textwhite {
		    background-color: rgb(50, 67, 128);
		    color: #ffffff;
		    padding: 10px;
		    font-size: 15px;
		    line-height: 20px;
		}
		.d-table{
			display: table !important;
			width: 100%;
		}
		.d-table-row{
			display: table-row !important;
		}
		.d-table-cell{
			display: table-cell !important;
		}
		.text-bold{
			font-weight: bold;
		}
		.text-capitalize{
			text-transform: capitalize;
		}

	</style>
</head>
<body>
	<table align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="padding: 0 50px;">
		<tbody>
			<tr>
				<td>
					<div class="x_sgwrap x_title_blue">
						<h1 style="text-align: center;"><?= NOMBRE_EMPRESA ?></h1>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						Notificación de procesamiento de liquidación de prestaciones sociales
						Asunto: Notificación de procesamiento de liquidación de prestaciones sociales
					</p>
					<p>
						Estimado/a <span style="font-weight: bold;"><?=$data["nombre"] ?>.</span>
					</p>
						
					<p>
						Por medio de la presente, le informamos que su liquidación de prestaciones sociales se encuentra en proceso de elaboración. Su pago incluirá todos los beneficios aplicables.
					</p>
					<p>
						Se estima un monto de <span class="text-bold"><?=$data["monto"] ?> Bs</span>
					</p>

					<p>
						Si tiene alguna pregunta sobre su liquidación de prestaciones sociales, no dude en comunicarse con nuestro departamento de Recursos Humanos. Estamos aquí para ayudarlo y garantizar una transición sin problemas de su empleo en nuestra empresa.
					</p>
					<p>
						Agradecemos su comprensión y cooperación.
					</p>

					<p>
						Atentamente,
					</p>
					<p class="text-capitalize text-bold" style="font-size: 1.2rem;"><?=NOMBRE_EMPRESA ?></p>
					<p>
						Departamento de Recursos Humanos
					</p>

				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>