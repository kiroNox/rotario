<!DOCTYPE html>
<html lang="es">

<head>  
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Notificacion - Servicio Desconcentrado Hospital Rotario</title>

	<!-- Custom fonts for this template-->
	<?php require_once 'assets/comun/head.php'; ?>
	<!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->

	<!-- Custom styles for this template-->
	<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">


	<!-- Page Wrapper -->
	<div id="wrapper">
	<!-- Un require Para el menu  -->
		<?php require_once 'assets/comun/menu.php'; ?>

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content" class="d-flex flex-column">

				<?php require_once 'assets/comun/navar.php'; ?>

				

				<!-- Begin Page Content -->
				
				<main class="main-content pt-2">
					<div class="container-fluid">

						<!-- Page Heading -->
						<h1 class="h3 mb-4 text-gray-800">Notificaciones</h1>

							<div class="container">
								<h1>Historial de Notificaciones</h1>
								<table id="tabla_notificaciones" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>Trabajador</th>
											<th>Mensaje</th>
											<th>Fecha</th>
											<th>Leída</th>
										</tr>
									</thead>
									<tbody id="tbody_notificaciones">
										<!-- Filas generadas dinámicamente -->
									</tbody>
								</table>
							</div>

							<script>
								$(document).ready(function() {
									cargarNotificaciones();
								});

								function cargarNotificaciones() {
									var datos = new FormData();
									datos.append("accion", "listar_notificaciones");
									enviaAjax(datos, function(respuesta, exito, fail) {
										if (exito) {
											var lee = JSON.parse(respuesta);
											if (lee.resultado == "exito") {
												var filas = '';
												lee.mensaje.forEach(function(notificacion) {
													filas += `
														<tr>
															<td>${notificacion.id_notificacion}</td>
															<td>${notificacion.nombre} ${notificacion.apellido}</td>
															<td>${notificacion.mensaje}</td>
															<td>${notificacion.fecha}</td>
															<td>${notificacion.leida ? 'Sí' : 'No'}</td>
														</tr>
													`;
												});
												$("#tbody_notificaciones").html(filas);
												$("#tabla_notificaciones").DataTable();
											} else {
												console.error(lee.mensaje);
											}
										} else {
											console.error(fail);
										}
									});
								}

								function enviaAjax(datos, callback) {
									$.ajax({
										url: '', 
										type: 'POST',
										data: datos,
										processData: false,
										contentType: false,
										success: function(respuesta) {
											callback(respuesta, true, false);
										},
										error: function(jqXHR, textStatus, errorThrown) {
											callback(jqXHR.responseText, false, true);
										}
									});
								}
							</script>
					


						
						

						
						<script type="text/javascript" src="assets/js/xxxxxxxx.js"></script>
					</div>
				</main>


			</div>
			<?php   require_once("assets/comun/footer.php"); ?>

		</div>
	</div>
	<!-- End of Page Wrapper -->

	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>

	

	 <!-- Page level plugins -->
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>