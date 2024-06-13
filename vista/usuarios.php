<!DOCTYPE html>
<html lang="es">

<head>  
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Usuarios</title>

	<!-- Custom fonts for this template-->
	<?php require_once 'assets/comun/head.php'; ?>
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

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
			<div id="content">

				<?php require_once 'assets/comun/nav_head.php'; ?>

				

				<!-- Begin Page Content -->
				<div class="container-fluid">

					<!-- Page Heading -->
					<h1 class="h3 mb-4 text-gray-800">Usuarios</h1>

					<div class="container">
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="nav-registrar_usuario-tab" data-toggle="tab" href="#nav-registrar_usuario" role="tab" aria-controls="nav-registrar_usuario" aria-selected="true">Registrar</a>
								<a class="nav-item nav-link" id="nav-consultar_usuarios-tab" data-toggle="tab" href="#nav-consultar_usuarios" role="tab" aria-controls="nav-consultar_usuarios" aria-selected="false">listar</a>
							</div>
						</nav>
					</div>
					<div class="container">
							
						<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-registrar_usuario" role="tabpanel" aria-labelledby="nav-registrar_usuario-tab">
								<div style="max-width: 500px" class="m-auto">
									<div class="container text-center">
										<form action="" method="POST" onsubmit="return false" id="f1">
											<label for="cedula">Ingrese la cedula del nuevo usuario</label>
											<input type="text" class="form-control" id="cedula" name="cedula" data-span="invalid-span-cedula">
											<span id="invalid-span-cedula" class="invalid-span text-danger"></span>

											<div class="container pl-0 pr-0" id="fields">
													<label class="d-block" for="nombre">Nombre</label>
													<input required type="text" class="form-control" id="nombre" name="nombre" data-span="invalid-span-nombre">
													<span id="invalid-span-nombre" class="invalid-span text-danger"></span>

													<label class="d-block" for="apellido">Apellido</label>
													<input required type="text" class="form-control" id="apellido" name="apellido" data-span="invalid-span-apellido">
													<span id="invalid-span-apellido" class="invalid-span text-danger"></span>

													<label class="d-block" for="telefono">Teléfono</label>
													<input type="text" class="form-control" id="telefono" name="telefono" data-span="invalid-span-telefono">
													<span id="invalid-span-telefono" class="invalid-span text-danger"></span>
													<label class="d-block" for="correo">Correo</label>
													<input required type="email" class="form-control" id="correo" name="correo" data-span="invalid-span-correo">
													<span id="invalid-span-correo" class="invalid-span text-danger"></span>

													<label for="rol">Rol</label>
													<select required class="form-control" id="rol" name="rol" data-span="invalid-span-rol">
														<option value="">Seleccione un rol</option>
													</select>
													<span id="invalid-span-rol" class="invalid-span text-danger"></span>

													<label class="d-block" for="pass">Clave</label>
													<div class="show-password-container">
														<input required type="password" class="form-control" id="pass" name="pass" data-span="invalid-span-pass">
														<span class="show-password-btn" data-inputpass="pass" aria-label="show password button"></span>
													</div>
													<span id="invalid-span-pass" class="invalid-span text-danger"></span>
													<div class="text-center mt-4">
														<button type="submit" class="btn btn-success">Registrar</button>
													</div>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="nav-consultar_usuarios" role="tabpanel" aria-labelledby="nav-consultar_usuarios-tab">
								<table class="table table-bordered table-hover" id="tabla_usuarios">
									<thead class="thead-dark">
										<tr>
											<th>id</th>
											<th>Cedula</th>
											<th>Nombre</th>
											<th>Apellido</th>
											<th>Telefono</th>
											<th>Correo</th>
											<th>Rol</th>
										</tr>
									</thead>
									<tbody id="tbody_usuarios" class="row-cursor-pointer">
										
									</tbody>
								</table>
							</div>

						</div>
					</div>

					<div class="modal fade" tabindex="-1" role="dialog" id="modal_modificar_usaurio">
						<div class="modal-dialog modal-xl" role="document">
							<div class="modal-content">
								<div class="modal-header text-light bg-info">
									<h5 class="modal-title">MODAL_TITLE</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="container">

									<form action="" method="POST" onsubmit="return false" id="f1_modificar" style="max-width: 500px" class="m-auto">
										<input type="hidden" name="id" readonly id="modificar_id">
										<label for="cedula">Cedula</label>
										<input type="text" class="form-control" id="cedula_modificar" name="cedula" data-span="invalid-span-cedula">
										<span id="invalid-span-cedula_modificar" class="invalid-span text-danger"></span>

										<div class="container pl-0 pr-0" id="fields_modificar">
												<label class="d-block" for="nombre">Nombre</label>
												<input required type="text" class="form-control" id="nombre_modificar" name="nombre" data-span="invalid-span-nombre">
												<span id="invalid-span-nombre_modificar" class="invalid-span text-danger"></span>

												<label class="d-block" for="apellido">Apellido</label>
												<input required type="text" class="form-control" id="apellido_modificar" name="apellido" data-span="invalid-span-apellido">
												<span id="invalid-span-apellido_modificar" class="invalid-span text-danger"></span>

												<label class="d-block" for="telefono">Teléfono</label>
												<input type="text" class="form-control" id="telefono_modificar" name="telefono" data-span="invalid-span-telefono">
												<span id="invalid-span-telefono_modificar" class="invalid-span text-danger"></span>
												<label class="d-block" for="correo">Correo</label>
												<input required type="email" class="form-control" id="correo_modificar" name="correo" data-span="invalid-span-correo">
												<span id="invalid-span-correo_modificar" class="invalid-span text-danger"></span>

												<label for="rol">Rol</label>
												<select required class="form-control" id="rol_modificar" name="rol" data-span="invalid-span-rol">
													<option value="">Seleccione un rol</option>
												</select>
												<span id="invalid-span-rol_modificar" class="invalid-span text-danger"></span>

												<label class="d-block" for="pass">Clave</label>
												<div class="show-password-container">
													<input type="password" class="form-control" id="pass_modificar" name="pass" data-span="invalid-span-pass" placeholder=" Sin Modificar">
													<span class="show-password-btn" data-inputpass="pass" aria-label="show password button"></span>
												</div>
												<span id="invalid-span-pass_modificar" class="invalid-span text-danger"></span>
												<div class="text-center mt-4">
													<button type="submit" class="btn btn-warning">Modificar</button>
													<button type="button" class="btn btn-danger" onclick="alert('nop, no hace nada');">Eliminar</button>

												</div>
										</div>
									</form>
									
								</div>
								<div class="modal-footer bg-light">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								</div>
							</div>
						</div>
					</div>
					<script type="text/javascript" src="assets/js/usuarios.js"></script>
				</div>

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
	

	<!-- Page level custom scripts -->
	<script src="assets/js/datatables-demo.js"></script>

</body>

</html>