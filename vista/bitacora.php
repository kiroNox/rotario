<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Bitácora - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
		<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">                                                      

					<main class="main-content">
						<h1>Bitácora</h1>

						<table class="table table-bordered table-hover" id="table_bitacora">
							<thead>
								<th>Usuario</th>
								<th>Fecha</th>
								<th>Acción</th>
							</thead>
							<tbody id="tbody_bitacora">
								
							</tbody>
							
						</table>
						
					</main>
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>
	<script src="assets/js/bitacora.js"></script>

	
</body>
</html>
