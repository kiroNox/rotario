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
						<h1 class="h3 mb-4 text-gray-800">Trabajador</h1>

						<div class="container-fluid p-0">
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" id="nav-registrar_usuario-tab" data-toggle="tab" href="#nav-registrar_usuario" role="tab" aria-controls="nav-registrar_usuario" aria-selected="true">Registrar</a>
									<a class="nav-item nav-link" id="nav-consultar_usuarios-tab" data-toggle="tab" href="#nav-consultar_usuarios" role="tab" aria-controls="nav-consultar_usuarios" aria-selected="false">listar</a>
								</div>
							</nav>
						</div>
						<div class="container-fluid p-0">
								
							<div class="tab-content" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-registrar_usuario" role="tabpanel" aria-labelledby="nav-registrar_usuario-tab">
									<div class="m-auto">
										<div class="container">
											<form action="" method="POST" onsubmit="return false" id="f1">
												<div class="row">
													<div class="col-lg-4 col-12">
														<label for="cedula">Ingrese la cedula del nuevo trabajador</label>
														<input type="text" class="form-control" id="cedula" name="cedula" data-span="invalid-span-cedula" value="V-2725051">
														<span id="invalid-span-cedula" class="invalid-span text-danger"></span>
													</div>
												</div>

												<div class="container pl-0 pr-0" id="fields">

													<div class="row">
														<div class="col-lg-4 col-12">
															<label class="d-block" for="nombre">Nombre</label>
															<input required type="text" class="form-control" id="nombre" name="nombre" data-span="invalid-span-nombre">
															<span id="invalid-span-nombre" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label class="d-block" for="apellido">Apellido</label>
															<input required type="text" class="form-control" id="apellido" name="apellido" data-span="invalid-span-apellido">
															<span id="invalid-span-apellido" class="invalid-span text-danger"></span>
															
														</div>
														<div class="col-lg-4 col-12">
															<label class="d-block" for="telefono">Teléfono</label>
															<input type="text" class="form-control" id="telefono" name="telefono" data-span="invalid-span-telefono">
															<span id="invalid-span-telefono" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label class="d-block" for="correo">Correo</label>
															<input required type="email" class="form-control" id="correo" name="correo" data-span="invalid-span-correo">
															<span id="invalid-span-correo" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label for="numero_cuenta">Numero de cuenta</label>
															<input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" data-span="invalid-span-numero_cuenta">
															<span id="invalid-span-numero_cuenta" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label for="fecha_ingreso">Fecha de Ingreso</label>
															<input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" data-span="invalid-span-fecha_ingreso">
															<span id="invalid-span-fecha_ingreso" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label for="nivel_educativo">Nivel Educativo</label>
															<select required name="nivel_educativo" class="form-control" id="nivel_educativo" data-span="invalid-span-nivel_educativo">
																	<option value="">Seleccione un nivel educativo</option>
															</select>
															<span id="invalid-span-nivel_educativo" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label for="rol">Rol</label>
															<select required class="form-control" id="rol" name="rol" data-span="invalid-span-rol">
																<option value="">Seleccione un rol</option>
															</select>
															<span id="invalid-span-rol" class="invalid-span text-danger"></span>
															
														</div>
														<div class="col-lg-4 col-12">
															<label for="comision_servicios" class="d-block mb-3" >Comisión de servicios?</label>
															<label for="comision_servicios" class="d-inline-block cursor-pointer">Si</label>
															<input type="radio" class="form-check-inline" id="comision_servicios" name="comision_servicios" data-span="invalid-span-comision_servicios" value="true">
															<label for="comision_servicios_no" class="d-inline-block cursor-pointer">No</label>
															<input type="radio" class="form-check-inline" id="comision_servicios_no" name="comision_servicios" data-span="invalid-span-comision_servicios" value="false">
															<span id="invalid-span-comision_servicios" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-4 col-12">
															<label for="discapacidad" class="d-block mb-3">Discapacidad</label>
															<input type="checkbox" id="discapacidad" name="discapacidad" data-span="invalid-span-discapacidad">
															<span id="invalid-span-discapacidad" class="invalid-span text-danger"></span>
														</div>
														<div class="col-lg-8 col-12">

															<label for="discapacidad_info">Discapacidad</label>
															<input type="text" class="form-control" id="discapacidad_info" name="discapacidad_info" data-span="invalid-span-discapacidad_info" maxlength="50">
															<span id="invalid-span-discapacidad_info" class="invalid-span text-danger"></span>
															
														</div>
														<div class="col-lg-6 col-12">
															<label class="d-block" for="pass">Clave</label>
															<div class="show-password-container">
																<input required type="password" class="form-control" id="pass" name="pass" data-span="invalid-span-pass">
																<span class="show-password-btn" data-inputpass="pass" aria-label="show password button"></span>
															</div>
															<span id="invalid-span-pass" class="invalid-span text-danger"></span>
															
														</div>
													</div>




														<div class="text-center mt-4">
															<button type="submit" class="btn btn-success">Registrar</button>
														</div>
												</div>
											</form>
										</div>
									</div>
								</div>


								<div class="tab-pane fade" id="nav-consultar_usuarios" role="tabpanel" aria-labelledby="nav-consultar_usuarios-tab">

									<table class="table table-bordered table-middle scroll-bar-style" id="tabla_usuarios">
										<thead class="bg-primary text-light w-100">
											<tr>
												<th>Cedula</th>
												<th>Nombre</th>
												<th>Apellido</th>
												<th>Teléfono</th>
												<th>Correo</th>
												<th>Rol</th>
												<th>Num. Cuenta</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody id="tbody_usuarios" class="align-middle">
											<tr>
												<td colspan="9" class="text-center"> No se encontraron registros de usuarios </td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>

						<div class="modal fade" tabindex="-1" role="dialog" id="modal_modificar_usaurio">
							<div class="modal-dialog modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header text-light bg-primary">
										<h5 class="modal-title">trabajador</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="container">

										<form action="" method="POST" onsubmit="return false" id="f1_modificar" class="m-auto pl-3 pr-3">
											<input type="hidden" name="id" readonly id="modificar_id">
											<div class="row">
												<div class="col-12 col-lg-4">
													<label for="cedula_modificar">Cedula</label>
													<input type="text" class="form-control" id="cedula_modificar" name="cedula" data-span="invalid-span-cedula">
													<span id="invalid-span-cedula_modificar" class="invalid-span text-danger"></span>
													
												</div>
											</div>
											<div class="row">
												<div class="col-lg-4 col-12">
													<label class="d-block" for="nombre_modificar">Nombre</label>
													<input required type="text" class="form-control" id="nombre_modificar" name="nombre" data-span="invalid-span-nombre_modificar">
													<span id="invalid-span-nombre_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label class="d-block" for="apellido_modificar">Apellido</label>
													<input required type="text" class="form-control" id="apellido_modificar" name="apellido" data-span="invalid-span-apellido_modificar">
													<span id="invalid-span-apellido_modificar" class="invalid-span text-danger"></span>
													
												</div>
												<div class="col-lg-4 col-12">
													<label class="d-block" for="telefono_modificar">Teléfono</label>
													<input type="text" class="form-control" id="telefono_modificar" name="telefono" data-span="invalid-span-telefono_modificar">
													<span id="invalid-span-telefono_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label class="d-block" for="correo_modificar">Correo</label>
													<input required type="email" class="form-control" id="correo_modificar" name="correo" data-span="invalid-span-correo_modificar">
													<span id="invalid-span-correo_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label for="numero_cuenta_modificar">Numero de cuenta</label>
													<input type="text" class="form-control" id="numero_cuenta_modificar" name="numero_cuenta" data-span="invalid-span-numero_cuenta_modificar">
													<span id="invalid-span-numero_cuenta_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label for="fecha_ingreso_modificar">Fecha de Ingreso</label>
													<input type="date" class="form-control" id="fecha_ingreso_modificar" name="fecha_ingreso" data-span="invalid-span-fecha_ingreso_modificar">
													<span id="invalid-span-fecha_ingreso_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label for="nivel_educativo_modificar">Nivel Educativo</label>
													<select required name="nivel_educativo" class="form-control" id="nivel_educativo_modificar" data-span="invalid-span-nivel_educativo_modificar">
															<option value="">Seleccione un nivel educativo</option>
													</select>
													<span id="invalid-span-nivel_educativo_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label for="rol_modificar">Rol</label>
													<select required class="form-control" id="rol_modificar" name="rol" data-span="invalid-span-rol_modificar">
														<option value="">Seleccione un rol</option>
													</select>
													<span id="invalid-span-rol_modificar" class="invalid-span text-danger"></span>
													
												</div>
												<div class="col-lg-4 col-12">
													<label for="comision_servicios_modificar" class="d-block mb-3" >Comisión de servicios?</label>
													<label for="comision_servicios_modificar" class="d-inline-block cursor-pointer">Si</label>
													<input required type="radio" class="form-check-inline" id="comision_servicios_modificar" name="comision_servicios" data-span="invalid-span-comision_servicios_modificar" value="true">
													<label for="comision_servicios_no" class="d-inline-block cursor-pointer">No</label>
													<input required type="radio" class="form-check-inline" id="comision_servicios_no_modificar" name="comision_servicios" data-span="invalid-span-comision_servicios_modificar" value="false">
													<span id="invalid-span-comision_servicios_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-4 col-12">
													<label for="discapacidad_modificar" class="d-block mb-3">Discapacidad</label>
													<input type="checkbox" id="discapacidad_modificar" name="discapacidad" data-span="invalid-span-discapacidad_modificar">
													<span id="invalid-span-discapacidad_modificar" class="invalid-span text-danger"></span>
												</div>
												<div class="col-lg-8 col-12">

													<label for="discapacidad_info_modificar">Discapacidad</label>
													<input type="text" class="form-control" id="discapacidad_info_modificar" name="discapacidad_info" data-span="invalid-span-discapacidad_info_modificar" maxlength="50">
													<span id="invalid-span-discapacidad_info_modificar" class="invalid-span text-danger"></span>
													
												</div>

												<div class="col-lg-6 col-12">
													<label class="d-block" for="pass_modificar">Clave</label>
													<div class="show-password-container">
														<input type="password" class="form-control" id="pass_modificar" name="pass" data-span="invalid-span-pass_modificar" placeholder="Sin modificar">
														<span class="show-password-btn" data-inputpass="pass_modificar" aria-label="show password button"></span>
													</div>
													<span id="invalid-span-pass_modificar" class="invalid-span text-danger"></span>




													
												</div>
											</div>
											<div class="row mt-3">
												<div class="col text-center">
													<button class="btn btn-warning text-dark" id="btn_modificar">Modificar</button>
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