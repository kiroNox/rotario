<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Liquidación</title>
	<style type="text/css">
		*{margin: 0;padding: 0;font-family: arial;}
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
			/*border-bottom: 1px solid #CCC;*/
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
		.d-row{
			display: table-row !important;
		}
		.d-cell{
			display: table-cell !important;
		}
		.text-bold{
			font-weight: bold;
		}
		.text-capitalize{
			text-transform: capitalize;
		}
		.d-flex{
			display: flex;
		}
		.justify-content-around{
			justify-content: space-around;
		}
		.justify-content-between{
			justify-content: space-between;
		}
		.p-1{
			padding: 0.5rem;
		}
		.p-2{
			padding: 1rem;
		}

		table{
			border-spacing: 0;
			border-collapse: collapse;
		}

		table th,
		table td{
			border:1px solid black;
			padding: 0.8rem;
		}
		table td{
			padding: 0.5rem;
		}
		tbody td:nth-child(2){
			text-align: right;
			padding-right: 15px;
		}
		tbody td:nth-child(2)::after{
			content: ' Bs'
		}

	</style>
</head>
<body style="padding: 1rem;max-width: 700px ;margin: 0 auto">
	<div class="d-table">
		<div class="d-row">
			<div class="d-cell">
				<div class="x_sgwrap x_title_blue d-flex justify-content-around">
					<div style="display: flex; vertical-align: middle; align-items: center;">
						<h1 style="text-align: left; font-size: 16px"><?= NOMBRE_EMPRESA ?></h1>
					</div>
					<div>
						<img src="../../assets/img/comun/rotario_logo.jpg" style="width: 140px;height: 140px">
					</div>
				</div>
				<div class="x_sgwrap x_title_blue d-flex" style="justify-content: flex-end;">
					
					<div class="d-flex">
						<div style="font-weight: bold;" class="p-1">
							Fecha: <br>
							Factura Nº
						</div>
						<div class="p-1">
							<span style="white-space: nowrap;">
							<?= $data["fecha"] ?> <br>
							<?= $data["id_factura"] ?> 

								
							</span>
						</div>
					</div>
				</div>
				<div>
					<p>Rif: G-00000000</p>
				</div>
				<div>
					Para
					<div style="font-weight: bold;"><?=$data["nombre"] ?></div>
					<div style="font-weight: bold;"><?=$data["cedula"] ?></div>

				</div>
			</div>
		</div>
		<div class="d-row">
			<div class="d-cell">
				<div>
					<div>
						<table style="width: 100%">
							<thead>
								<th>Descipcion</th>
								<th style="width: 20%">Monto</th>
							</thead>
							<tbody>
								<?php 
									foreach ($data["detalles"] as $elem) {
										echo "<tr>
											<td>".$elem["descripcion"]."</td>
											<td>".$elem["monto"]."</td>
										</tr>";
									}
								 ?>

								 <tr>
								 	<td style="border:none; text-align: right; font-weight: bold;">Sueldo Base</td>
								 	<td><?=$data["sueldo_base"]  ?></td>
								 </tr>
								 <tr>
								 	<td style="border:none; text-align: right; font-weight: bold;">Sueldo Integral</td>
								 	<td><?=$data["sueldo_integral"]  ?></td>
								 </tr>
								 <tr>
								 	<td style="border:none; text-align: right; font-weight: bold;">Sueldo Deducido</td>
								 	<td><?=$data["sueldo_deducido"]  ?></td>
								 </tr>
								 <tr>
								 	<td style="border:none; text-align: right; font-weight: bold;">ISLR</td>
								 	<td><?=$data["islr"]  ?></td>
								 </tr><tr>
								 	<td style="border:none; text-align: right; font-weight: bold;">Total a Pagar</td>
								 	<td><?=$data["sueldo_total"]  ?></td>
								 </tr>
								 
								 
							</tbody>
						</table>
					</div>
					<br>
					<div style="text-align: center;">Para cualquier consulta, dirigirse al departamento de Recursos Humanos.</div>


				</div>
			</div>
		</div>
	</div>
</body>
</html>

