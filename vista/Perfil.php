<!DOCTYPE html>
<html lang="es">
<head>  
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Usuarios - Servicio Desconcentrado Hospital Rotario</title>

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
						<div class="row mb-4">
							<div class="col">
								<h1 class="h3 mb-2 text-gray-800">Mi Perfil</h1>
							</div>
						</div>

						<style>
							.id-label{
								opacity: 0.7;
								font-size: .8rem;
							}
							.id-info{

/*								text-wrap: nowrap;*/
							}
						</style>

						
						<div class="container-fluid p-0">
							<?php if($datos_user["status-get"]){ ?>

							<div class="">
								<div class="card-body d-flex justify-content-center">
									<div class="user-icon card" style="border-top-right-radius: 0; border-bottom-right-radius: 0; align-items: middle;">
										<div class="card-body d-md-flex align-items-center px-5 d-none" >
											<span class="fas fa-user" style="font-size:3rem"></span>
										</div>
									</div>
									<div class="card" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
										<div class="card-body">
											<div class="row">
												<div class="col">
													<div class="id-label">Cedula</div>
													<div class="id-info" id="cedula"><?=$datos_user['cedula']?></div>
												</div>
												<div class="col">
													<div class="id-label">Cargo</div>
													<div class="id-info"><?=$datos_user["cargo"] ?></div>
												</div>
												<div class="col">
													<div class="id-label">Tipo de usuario</div>
													<div class="id-info"><?=$datos_user["rol_descripcion"] ?></div>
												</div>
												<div class="col">
													<div class="id-label">Nivel Educativo</div>
													<div class="id-info"><?=$datos_user["nivel_educativo"] ?></div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="id-label">Nombre y Apellido</div>
													<div class="id-info" ><?= $datos_user["nombre"]." ".$datos_user["apellido"] ?></div>
												</div>
												<div class="col">
													<div class="id-label">Fecha Contrato</div>
													<div class="id-info"><?=$datos_user["creado"] ?></div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="id-label">Teléfono</div>
													<div class="id-info"><?=$datos_user["telefono"] ?></div>
												</div>
												<div class="col">
													<div class="id-label">Correo</div>
													<div class="id-info"><?=$datos_user["correo"] ?></div>
												</div>
											</div>
											<div class="row">
												
											</div>
											<div class="row">
												<div class="col">
													<div class="id-label">Num. de Cuenta</div>
													<div class="id-info"><?=$datos_user["numero_cuenta"] ?></div>
												</div>
											</div>
										</div>
									</div>
									
								</div>
							</div>

						<?php }else{ ?>

							<h3 class="text-center">Error al cargar el perfil</h3>


							<h1 class="text-center">
								<?=$datos_user["mensaje"] ?>
							</h1>

						<?php } ?>
							
						</div>
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

	
	<script>
		var datos = new FormData();
		datos.append("accion","get_user");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "get_user"){
				console.log(lee.mensaje);

			}
			else if (lee.resultado == 'is-invalid'){
				muestraMensaje(lee.titulo, lee.mensaje,"error");
			}
			else if(lee.resultado == "error"){
				muestraMensaje(lee.titulo, lee.mensaje,"error");
				console.error(lee.mensaje);
			}
			else if(lee.resultado == "console"){
				console.log(lee.mensaje);
			}
			else{
				muestraMensaje(lee.titulo, lee.mensaje,"error");
			}
		});
	</script>
	 <!-- Page level plugins -->
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>