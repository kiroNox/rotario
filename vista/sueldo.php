<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>

	<script src="assets/select2/js/select2.full.min.js"></script>
	<link rel="stylesheet" href="assets/select2/css/select2.min.css">
	<link rel="stylesheet" href="assets/select2/css/select2-bootstrap.min.css">
	<style type="text/css">
		#tbody_sueldos td{
			text-align: center;
			vertical-align: middle;
		}
		#tbody_sueldos td:nth-child(6){
			font-family: cursive,'Times New Roman';
			font-size: 1.2rem;
		}
		#escalafon option:not(:first-child){
			font-family: 'Times New Roman';
		}
		#tbody_sueldos td:nth-child(2),
		#tbody_sueldos td:nth-child(1){
			white-space: nowrap;
		}
		.sin_asignar~td:not(:last-child){
			display: none;
		}
	</style>
	<title>Sueldo - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top" class="<?= $modo_oscuro ?>">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
		<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">                                                      

					<main class="main-content">
						<h1 data-step="1" data-intro="Aquí puede gestionar el sueldo de los trabajadores">sueldo</h1>

						<table class="table table-bordered table-responsive-xl scroll-bar-style" id="table_sueldos">
							<thead>
								<th>Cedula</th>
								<th>Nombre</th>
								<th>Sueldo Base</th>
								<th>Cargo</th>
								<th>Medico</th>
								<th>Escalafón</th>
								<th>Nomina</th>
								<th>Acción</th>
							</thead>
							<tbody id="tbody_sueldos">
								<tr>
									<td colspan="8" class="text-center">- Cargando -</td>
								</tr>
							</tbody>
						</table>

					
						
					</main>
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>


	<div class="modal fade" tabindex="-1" role="dialog" id="nuevo_cargo">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Nuevo Cargo </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">
					<form action="" method="POST" onsubmit="return false" id="f_cargos">
						<div class="row">
							<div class="col">
								<label for="cargo_codigo">Código</label>
								<input type="text" class="form-control" id="cargo_codigo" name="cargo_codigo" data-span="invalid-span-cargo_codigo">
								<span id="invalid-span-cargo_codigo" class="invalid-span text-danger"></span>
							</div>
							<div class="col">
								<label for="new_cargo">Cargo</label>
								<input type="text" class="form-control" id="new_cargo" name="new_cargo" data-span="invalid-span-new_cargo">
								<span id="invalid-span-new_cargo" class="invalid-span text-danger"></span>
							</div>
						</div>
						<div class="row py-3">
							<div class="col text-center"><button type="submit" class="btn btn-primary">Guardar Cargo</button></div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>





	<div class="modal fade" tabindex="-1" role="dialog" id="modal_asignar">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Asignar Sueldo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container pt-2 px-5">
					<form action="" id="f1" method="POST" onsubmit="return false;">
						<input type="hidden" name="id_trabajador" id="id_trabajador">

						<div class="row">
							<div class="col-lg-4 col-12">
								<label for="sueldo">Sueldo Base</label>
								<input required type="text" class="form-control text-right" id="sueldo" name="sueldo" data-span="invalid-span-sueldo">
								<span id="invalid-span-sueldo" class="invalid-span text-danger"></span>
							</div>
							<div class="col-lg-4 col-12">
								<div class="d-flex">
									<div class="flex-grow-1">
										<label for="cargo">Cargo</label>
										<select name="cargo" id="cargo" class="form-control text-center" data-span="invalid-span-cargo">
											
										</select>
										<span id="invalid-span-cargo" class="invalid-span text-danger"></span>



										<!-- 
										<input required type="text" class="form-control" id="cargo" name="cargo" list="lista_cargos" data-span="invalid-span-cargo" autocomplete="off">
										<datalist id="lista_cargos"></datalist> -->
									</div>
									<div class="flex-shrink-1 ml-2">
										<label class="hidden d-block fade no-select">l</label>
										<button type="button" class="btn btn-primary" onclick="new_cargos()" >+</button>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<label for="tipo_nomina">Tipo de Nomina </label>
								<select name="tipo_nomina" id="tipo_nomina" class="custom-select" required data-span="invalid-span-tipo_nomina">
									<option value="">- Seleccione -</option>
									<option value="1">Alto Nivel</option>
									<option value="2">Contratado</option>
									<option value="3">Obrero Fijo</option>
									<option value="4">Comisión de servicios</option>
								</select>
								<span id="invalid-span-tipo_nomina" class="invalid-span text-danger"></span>
							</div>
							<div class="col-lg-4 col-12">
								<label for="escalafon">Escalafón</label>
								<select name="escalafon" id="escalafon" class="custom-select" data-span="invalid-span-escalafon" required>
									<option class="text-center" value="">- Seleccione -</option>
								</select>
								<span id="invalid-span-escalafon" class="invalid-span text-danger"></span>
							</div>
							<div class="col-lg-4 col-12 d-flex flex-column">
								<label class="no-select fade d-none d-lg-block">l</label>
								<div class="d-flex align-items-center flex-row my-3 my-lg-0" style="flex-grow: 1">
									
									<input type="checkbox" class="check-button" id="medico_bool" name="medico_bool" data-span="invalid-span-medico_bool">
									<label for="medico_bool" class="check-button"></label>
									<label class="cursor-pointer no-select mb-0 ml-2" for="medico_bool"> Ejerce como medico? </label>



								</div>
							</div>

							<div class="col-12 text-center my-4" >
								<button type="submit" class="btn btn-primary">Asignar</button>
								
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



	<script src="vendor/intro.js-7.2.0/package/minified/intro.min.js"></script>
	<script src="assets/js/comun/introConfig.js"></script>

	<script src="assets/js/sueldos.js"></script>
	<script src="assets/js/sb-admin-2.min.js"></script>
	<script>
		Intro.start();
	</script>



</body>
</html>