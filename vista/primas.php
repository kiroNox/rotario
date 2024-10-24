<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Primas - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top" class="<?= $modo_oscuro ?>">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
		<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">                                                      

					<main class="main-content">
						<div class="row mb-5">
							<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
								<h1 class="mx-md-3">Primas</h1>
							</div>
						</div>
						<div class="container-fluid">
							
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" id="nav-primas_generales-tab" data-toggle="tab" href="#nav-primas_generales" role="tab" aria-controls="nav-primas_generales" aria-selected="true">Generales</a>
									<a class="nav-item nav-link d-none" id="nav-primas_hijo-tab" data-toggle="tab" href="#nav-primas_hijo" role="tab" aria-controls="nav-primas_hijo" aria-selected="false">Hijos</a>
									<a class="nav-item nav-link d-none" id="nav-primas_antiguedad-tab" data-toggle="tab" href="#nav-primas_antiguedad" role="tab" aria-controls="nav-primas_antiguedad" aria-selected="false">Antigüedad</a>
									<a class="nav-item nav-link d-none" id="nav-primas_escalafon-tab" data-toggle="tab" href="#nav-primas_escalafon" role="tab" aria-controls="nav-primas_escalafon" aria-selected="false">Escalafón</a>
								</div>
							</nav>
							<div class="tab-content">
								<div class="tab-pane fade active show" id="nav-primas_generales" role="tabpanel" aria-labelledby="nav-primas_generales-tab">
									<div class="row mb-5 mt-3">
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
											<h3 class="mx-md-3 text-capitalize">primas generales</h3>
										</div>
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
											<button class="btn btn-primary mx-md-3" data-open_modal_calc = "modal_registrar_prima_general">Registrar Primas</button>
										</div>
									</div>


									<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_primas_generales">
										<thead class="bg-primary text-light">
											<th>descripción</th>
											<th>trabajadores</th>
											<th>acción</th>
										</thead>
									
										<tbody id="tbody_primas_generales">

											<tr>
												<td colspan="6" class="text-center"> - Cargando - </td>
											</tr>
											
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="nav-primas_hijo" role="tabpanel" aria-labelledby="nav-primas_hijo-tab">
									<div class="row mb-5 mt-3">
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
											<h3 class="mx-md-3 text-capitalize">primas Hijos</h3>
										</div>
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
											<button class="btn btn-primary mx-md-3" type="button" data-toggle="modal" data-target="#modal_registrar_prima_hijos">Registrar Primas</button>
										</div>
									</div>

									<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_primas_hijos">
										<thead class="bg-primary text-light">
											<th>Descripción</th>
											<th>Monto</th>
											<th>Menor Edad</th>
											<th>Discapacidad</th>
											<th>Acción</th>
										</thead>
									
										<tbody id="tbody_primas_hijos">
											<tr>
												<td colspan="5" class="text-center"> - Cargando - </td>
											</tr>
										</tbody>
										
									</table>
								</div>
								<div class="tab-pane fade" id="nav-primas_antiguedad" role="tabpanel" aria-labelledby="nav-primas_antiguedad-tab">
									<div class="row mb-5 mt-3">
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
											<h3 class="mx-md-3 text-capitalize">primas Antigüedad</h3>
										</div>
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
											<button class="btn btn-primary mx-md-3" type="button" data-target='#modal_registrar_prima_antiguedad' data-toggle='modal'>Registrar Primas</button>
										</div>
									</div>
									<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_primas_antiguedad">
										<thead class="bg-primary text-light">
											<th>Años</th>
											<th>Porcentaje</th>
											<th>Acción</th>
										</thead>
									
										<tbody id="tbody_primas_antiguedad">
											<tr>
												<td colspan="3" class="text-center"> - Cargando - </td>
											</tr>
										</tbody>
										
									</table>
								</div>

								<div class="tab-pane fade" id="nav-primas_escalafon" role="tabpanel" aria-labelledby="nav-primas_escalafon-tab">
									<div class="row mb-5 mt-3">
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
											<h3 class="mx-md-3 text-capitalize">Prima Escalafón</h3>
										</div>
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
											<button class="btn btn-primary mx-md-3" data-toggle="modal" data-target="#modal_registrar_prima_escalafon">Registrar Primas</button>
										</div>
									</div>
									<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_primas_escalafon">
										<thead class="bg-primary text-light">
											<th>Escala</th>
											<th>Tiempo</th>
											<th>Porcentaje</th>
											<th>Acción</th>
										</thead>
									
										<tbody id="tbody_primas_escalafon">
											<tr>
												<td colspan="3" class="text-center"> - Cargando - </td>
											</tr>
										</tbody>
										
									</table>
								</div>
							</div>
						</div>

						
						
					</main>
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>

	<!-- modal primas hijos -->
		<div class="modal fade" tabindex="-1" role="dialog" id="modal_registrar_prima_hijos">
			<div class="modal-dialog modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header text-light bg-primary">
						<h5 class="modal-title">Primas - Hijos</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="container">

						<form action="" id="f1" method="POST" onsubmit="return false">
							<input type="hidden" name="id" id="id_prima_hijo_hidden">
							<div class="row">
								<div class="col-12">
									<label for="hijo_descripcion">Descripción</label>
									<input required type="text" class="form-control" id="hijo_descripcion" name="hijo_descripcion" data-span="invalid-span-hijo_descripcion" maxlength="100">
									<span id="invalid-span-hijo_descripcion" class="invalid-span text-danger"></span>
								</div>
								<div class="col-12 col-md-6">
									<label for="hijo_monto">Monto</label>
									<input required type="text" class="form-control text-right" id="hijo_monto" name="hijo_monto" data-span="invalid-span-hijo_monto">
									<span id="invalid-span-hijo_monto" class="invalid-span text-danger"></span>
								</div>
								<div class="col-12 col-md-6 mt-3">
									<div>
										<div class="d-flex align-items-center flex-row">
											<input type="checkbox" id="hijo_porcentaje" name="hijo_porcentaje" class="check-button">
											<label for="hijo_porcentaje" class="check-button"></label>
											<label class="cursor-pointer no-select m-0 ml-1" for="hijo_porcentaje">Porcentaje</label>
										</div>
									</div>
									<div class="d-flex align-items-center flex-row">
										<input type="checkbox" id="hijo_menor" name="hijo_menor" class="check-button">
										<label for="hijo_menor" class="check-button"></label>
										<label class="cursor-pointer no-select mb-0 ml-1" for="hijo_menor">Solo Para Menor de Edad</label>
									</div>
									<div class="d-flex align-items-center flex-row">
										<input type="checkbox" id="hijo_discapacidad" name="hijo_discapacidad" class="check-button">
										<label for="hijo_discapacidad" class="check-button"></label>
										<label class="cursor-pointer no-select mb-0 ml-1" for="hijo_discapacidad">Solo Para Discapacitados</label>
									</div>
								</div>
								<div class="col-12 col-md-6 mt-md-3" id="hijo_info_prima"></div>
								<div class="col-12 text-center mt-3">
									<button class="btn btn-primary" type="submit">Registrar</button>
								</div>
							</div>
						</form>

						<script>
							


							

						</script>


						
					</div>
					<div class="modal-footer bg-light">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

	<!-- modal primas antigüedad -->


		<div class="modal fade" tabindex="-1" role="dialog" id="modal_registrar_prima_antiguedad">
			<div class="modal-dialog modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header text-light bg-primary">
						<h5 class="modal-title">Primas - Antigüedad</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="container">

						<form action="" id="f2" method="POST" onsubmit="return false">
							<input type="hidden" name="id" id="prima_antiguedad_id">
							<div class="row justify-content-center">
								<div class="col-12 col-md-4">
									<label for="prima_antiguedad_year">Año(s)</label>
									<input required type="text" class="form-control" id="prima_antiguedad_year" name="anio" data-span="invalid-span-prima_antiguedad_year" autocomplete="off">
									<span id="invalid-span-prima_antiguedad_year" class="invalid-span text-danger"></span>
								</div>
								<div class="col-12 col-md-4">
									<label for="prima_antiguedad_monto">Porcentaje</label>
									<input required type="text" class="form-control text-right" id="prima_antiguedad_monto" name="porcentaje_monto" data-span="invalid-span-prima_antiguedad_monto" autocomplete="off">
									<span id="invalid-span-prima_antiguedad_monto" class="invalid-span text-danger"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center mt-3"><button class="btn btn-primary" type="submit">Registrar</button></div>
							</div>
						</form>
						
					</div>
					<div class="modal-footer bg-light">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

	<!-- modal primas escalafón -->

		<div class="modal fade" tabindex="-1" role="dialog" id="modal_registrar_prima_escalafon">
			<div class="modal-dialog modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header text-light bg-primary">
						<h5 class="modal-title">Primas - Escalafón</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="container">
						<form action="" id="f3" method="POST" onsubmit="return false">
							<input type="hidden" id="primas_escalfon_id" name="id">
							<div class="row justify-content-center">
								<div class="col-12 col-md-4">
									<label for="primas_escalfon_escala">Escala</label>
									<input required type="text" class="form-control" id="primas_escalfon_escala" name="escala" data-span="invalid-span-primas_escalfon_escala" required>
									<span id="invalid-span-primas_escalfon_escala" class="invalid-span text-danger"></span>
								</div>
								<div class="col-12 col-md-4">
									<label for="primas_escalafon_tiempo">Tiempo</label>
									<input required type="text" class="form-control" id="primas_escalafon_tiempo" name="tiempo" data-span="invalid-span-primas_escalafon_tiempo">
									<span id="invalid-span-primas_escalafon_tiempo" class="invalid-span text-danger"></span>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-12 col-md-4">
									<label for="primas_escalafon_monto">Porcentaje</label>
									<input required type="text" class="form-control text-right" id="primas_escalafon_monto" name="porcentaje" data-span="invalid-span-primas_escalafon_monto">
									<span id="invalid-span-primas_escalafon_monto" class="invalid-span text-danger"></span>
								</div>
								<div class="col-md-4 d-md-block d-none">
									
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12 text-center"><button class="btn btn-primary" type="submit">Registrar</button></div>
							</div>
						</form>
						
					</div>
					<div class="modal-footer bg-light">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

	<!-- modal primas generales -->
		<div class="modal fade" tabindex="-1" role="dialog" id="modal_registrar_prima_general">
			<div class="modal-dialog modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header text-light bg-primary">
						<h5 class="modal-title">Primas - Generales</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="container">
						<form action="" id="f4" methot="POST" onsubmit="return false">
							<input type="hidden" id="primas_generales_id" name="id">
							<div class="row">
								<div class="col-12 col-md-4">
									<label for="primas_generales_descripcion">Descripción</label>
									<input required type="text" class="form-control" id="primas_generales_descripcion" name="descripcion" data-span="invalid-span-primas_generales_descripcion">
									<span id="invalid-span-primas_generales_descripcion" class="invalid-span text-danger"></span>
								</div>
								<!-- <div class="col-8 col-md-4">
									<label for="primas_generales_monto">Monto</label>
									<input required type="text" class="form-control" id="primas_generales_monto" name="monto" data-span="invalid-span-primas_generales_monto">
									<span id="invalid-span-primas_generales_monto" class="invalid-span text-danger"></span>
								</div>
								<div class="col-4 d-flex flex-column">
									<label for="" class="fade no-select">l</label>
									<div class="d-flex justify-content-start align-items-center flex-grow-1">
										<label for="primas_generales_porcentaje" class="mb-0 mr-1 cursor-pointer no-select">Porcentaje</label>
										<input type="checkbox" id="primas_generales_porcentaje" name="porcentaje" class="check-button">
										<label for="primas_generales_porcentaje" class="check-button" tabindex="0"></label>
									</div>
								</div> -->
								<div class="col-12 mt-2">
									<div class="d-table table-middle">
										<!-- <div class="d-table-row">
											<div class="d-table-cell">
												<label for="primas_generales_salud" class="m-0 cursor-pointer no-select">Sector Salud</label>
											</div>
											<div class="d-table-cell pl-2">
												<div class="d-flex align-items-center">
													<input type="checkbox" id="primas_generales_salud" name="sector_salud" class="check-button">
													<label for="primas_generales_salud" class="check-button" tabindex="0"></label>
												</div>
											</div>
										</div> -->

										<div class="d-flex justify-content-start mb-3 align-items-center">
											<label for="primas_generales_mensual" class="m-0 cursor-pointer no-select">Quincenal</label>
											<input type="checkbox" id="primas_generales_mensual" name="mensual" class="check-button">
											<label for="primas_generales_mensual" class="check-button mx-2" tabindex="0"></label>
											<label for="primas_generales_mensual" class="m-0 cursor-pointer no-select">Mensual</label>
										</div>

										<div class="d-flex justify-content-start align-items-center">
											<input type="checkbox" id="primas_generales_dedicada" name="dedicada" class="check-button">
											<label for="primas_generales_dedicada" class="check-button" tabindex="0"></label>
											<label for="primas_generales_dedicada" class="m-0 cursor-pointer no-select pl-2">Dedicada</label>
										</div>




										<!-- <div class="d-table-row">
											<div class="d-table-cell pr-2">
												<div class="d-flex align-items-center">
													<input type="checkbox" id="primas_generales_dedicada" name="dedicada" class="check-button">
													<label for="primas_generales_dedicada" class="check-button" tabindex="0"></label>
												</div>
											</div>
											<div class="d-table-cell">
												<label for="primas_generales_dedicada" class="m-0 cursor-pointer no-select">Dedicada</label>
											</div>
										</div> -->
										<!-- <div class="d-table-row">
											<div class="d-table-cell px-2">
												<div class="d-flex align-items-center">
													<input type="checkbox" id="primas_generales_mensual" name="mensual" class="check-button">
													<label for="primas_generales_mensual" class="check-button" tabindex="0"></label>
												</div>
											</div>
											<div class="d-table-cell">
												<label for="primas_generales_mensual" class="m-0 cursor-pointer no-select">Mensual</label>
											</div>
										</div> -->
									</div>
								</div>
							</div>
							<div class="row d-none" id="primas_generales_container_trabajadores">
								<div class="col-12 text-right">
									<span>Añadir/Eliminar Trabajadores</span>
									<button type="button" class="btn btn-primary font-weight-bold no-select" style="width: 37px" id="add_trabajador">+</button>
									<button type="button" class="btn btn-primary font-weight-bold no-select" style="width: 37px" id="sub_trabajador">-</button>
								</div>
								<div class="col-12">
									<table class="table table-bordered table-middle" id="table_trabajadores_dedicada">
										<thead>
											<th>Cedula</th>
											<th>Nombre</th>
											<th>Acción</th>
										</thead>
									
										<tbody id="tbody_trabajadores_dedicada">
											
										</tbody>
										
									</table>
								</div>
							</div>

							<div class="form_calc_container">
								
							</div>
						</form>
					</div>
					<div class="modal-footer bg-light">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

	<script src="./assets/js/comun/calculadora.js"></script>
	<script>

		



		



		

		// inicializar *******************************************************
			event_modal_calc();

			//load_calc_functions()


			


			add_event_to_label_checkbox();

			//document.getElementById('nav-primas_generales-tab').click();

			//$("#modal_registrar_prima_general").modal("show");

			load_primas_generales();

			// load_all_primas();
			// inicializar primas hijos
				
			// inicializar primas antigüedad

				

			// inicializar primas escalafón

				

			// inicializar primas generales

				//eventoMonto("primas_generales_monto");



				document.getElementById('add_trabajador').onclick=add_trabajador;
				document.getElementById('sub_trabajador').onclick=sub_trabajador;

				document.getElementById('primas_generales_dedicada').onclick = function(){
					if(this.checked){
						muestraMensaje("Advertencia", "Se aconseja colocar en la(s) condicional(es) la variable: <ENDL> 'DEDICADA' <ENDL> de no hacerlo, si utiliza el nombre de esta formula en otra, la lista dedicada sera ignorada y se ejecutará para otros trabajadores", "¡");
						add_trabajador();
						document.getElementById('primas_generales_container_trabajadores').classList.remove("d-none");
					}
					else{

						document.getElementById('primas_generales_container_trabajadores').classList.add("d-none");
						document.getElementById('tbody_trabajadores_dedicada').innerHTML='';
					}
				}

				document.getElementById('primas_generales_dedicada').onclick();

				rowsEventActions("tbody_trabajadores_dedicada" ,function(action,rowId,btn){
					if(action=='eliminar'){
						document.getElementById('tbody_trabajadores_dedicada').removeChild(btn.parentNode.parentNode);
					}

				});



				rowsEventActions("table_primas_generales" ,function(action,rowId,btn){
					if(action=='eliminar'){

						muestraMensaje("¿Seguro?", "¿Esta seguro de querer eliminar la prima seleccionada?", "?", function(result){
							if(result){
								var datos = new FormData();
								datos.append("accion","eliminar_prima_general");
								datos.append("id",rowId)
								enviaAjax(datos,function(respuesta, exito, fail){
								
									var lee = JSON.parse(respuesta);
									if(lee.resultado == "eliminar_prima_general"){

										muestraMensaje("Éxito", "La prima ha sido eliminada exitosamente", "s");
										cargar_prima_generales(lee.mensaje);
										
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

							}
						});







					}
					else if(action=='modificar'){

						muestraMensaje("¿Seguro?", "¿Esta seguro de querer modificar la prima seleccionada?", "?", function(result){
							if(result){

								var datos = new FormData();
								datos.append("accion","get_prima_general");
								datos.append("id",rowId);
								enviaAjax(datos,function(respuesta, exito, fail){
								
									var lee = JSON.parse(respuesta);
									if(lee.resultado == "get_prima_general"){


										var temp_quincena = (lee.mensaje.quincena=='0')?true:false;
										document.getElementById('primas_generales_mensual').checked = temp_quincena;

										document.getElementById('primas_generales_id').value = lee.mensaje.id;
										document.getElementById('primas_generales_descripcion').value = lee.mensaje.descripcion;
										document.getElementById('primas_generales_dedicada').checked = (lee.mensaje.dedicada=='1')?true:false;
										document.getElementById('primas_generales_dedicada').onclick();

										//document.querySelector("#f4 button[type='submit']").innerHTML='Modificar'; // TODO cambiar esto

										if(lee.mensaje.dedicada == '1'){
											sub_trabajador();
											for(var el of lee.lista){
												add_trabajador(el.cedula,el.nombre,el.id);
											}

										}

										var modal = document.getElementById('modal_registrar_prima_general');

										modal.load_calc_formulario(false).then(()=>{

											load_formulas_form(lee.mensaje.calc_formula);

											document.getElementById('f4').tested_form=true;
											document.getElementById('save-form-btn-1').innerHTML='Modificar Prima';
											$(modal).modal("show");
										})









										//$("#modal_registrar_prima_general").modal("show");
										
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

							}
						});
					}

				});



				

				document.getElementById('f4').action_form='testing_calc';
				document.getElementById('f4').onsubmit=function(e){
					e.preventDefault();
					f4 = this;

					if(f4.sending === true){
						return false;
					}


					var focus = document.querySelector("#f4 button[type='submit']");
					focus.focus();
					focus.blur();



					for(var el of document.querySelectorAll("#f4 input:not(:disabled)")){
						if(el.classList.contains('is-invalid')){
							el.focus();
							this.action_form='testing_calc';
							return false;
						}
					}




					var datos = new FormData(this);

					datos = calc_formData_maker(datos,this);
					if(datos===false){
						this.action_form='testing_calc';
						return false;
					}


					if(this.action_form=='testing_calc'){
						datos.set("accion","test_formula");
					}
					else if(this.action_form == 'save_calc'){
						if(document.getElementById('primas_generales_id').value!=''){
							datos.append("accion","modificar_prima_general");
						}
						else{
							datos.append("accion","registra_prima_general");
						}
					}

					datos.setter("sector_salud");
					datos.setter("mensual",0,1);
					datos.setter("dedicada");

					// datos.set("sector_salud",datos.has("sector_salud") ? "1":"0");
					// datos.set("mensual",datos.has("mensual") ? "1":"0");
					// datos.set("dedicada",datos.has("dedicada") ? "1":"0");

					if(datos.get("dedicada") == '1' && !datos.has("trabajadores")){
						muestraMensaje("Error", `Debe agregar al menos un trabajador si la prima esta seleccionada como "dedicada"`, "w");
						return false;
					}

					datos.groupby("trabajadores");
					//datos.groupby("trabajador_id_input");



					if(datos.get("accion") == 'modificar_prima_general'){
						//alert("esto aun no");;
						//return false;
					}


					var obj_msg = {};

					if(this.action_form == "testing_calc"){
						obj_msg.ignore = true;
					}

					if(document.getElementById('primas_generales_id').value != ''){
						var sms = "¿Desea modificar la prima seleccionada?";
					}
					else{
						var sms = "¿Esta seguro de registrar la nueva prima?";
					}



					muestraMensaje("¿Seguro?", sms , "?",obj_msg, (resul)=>{
						if(resul){
							this.action_form = 'testing_calc';


							enviaAjax(datos,function(respuesta, exito, fail){
							
								var lee = JSON.parse(respuesta);
								if(lee.resultado == "registra_prima_general" || lee.resultado == "modificar_prima_general"){

									muestraMensaje("Exito", lee.mensaje, 's');
									cargar_prima_generales(lee.lista);
									$("#modal_registrar_prima_general").modal("hide");
								}
								else if (lee.resultado == 'leer_formula'){

									if(lee.total!==null){
										muestraMensaje("Prueba Exitosa", `La formula fue evaluada exitosamente <ENDL> total <ENDL> ${lee.total}`, "s",);
									}
									else{
										muestraMensaje("Prueba Exitosa <br> (Advertencia)", "La condicional no se ha cumplido por lo tanto el resultado es '0' <ENDL> se sugiere probar la formula con una condicional positiva para evitar errores", "¡",);
									}
									f4.tested_form = true;
								}
								else if (lee.resultado == 'leer_formula_condicional'){
									if(lee.total!==null){
										muestraMensaje("Prueba Exitosa", `La formula '${lee.n_formula}' fue evaluada exitosamente <ENDL> total <ENDL> ${lee.total}`, "s");
									}
									else{
										muestraMensaje("Prueba Exitosa <br> (Advertencia)", "Ninguna condicional se ha cumplido por lo tanto el resultado es '0' <ENDL> se sugiere probar cada formula con una condicional positiva para evitar errores", "¡",);
									}



									f4.tested_form = true;
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
								else if(lee.resultado == "console-table" ){
									console.table(JSON.parse(lee.mensaje));
								}
								else{
									muestraMensaje(lee.titulo, lee.mensaje,"error");
								}
								f4.sending = false; 
							},"loader_body").p.finally((a)=>{
								f4.sending = undefined; 
							});

						}
						else{
							f4.action_form = "testing_calc";
							f4.sending = undefined;
						}
					});












				};

				$('#modal_registrar_prima_general').on('hidden.bs.modal', function (e) {

					document.getElementById('tbody_trabajadores_dedicada').innerHTML="";

					$("#f4 input,#f4 span.invalid-span").each((index,el)=>{

						if(el.tagName=='INPUT'){
							if(el.type == 'checkbox'){
								el.checked=false;
							}
							else{
								el.value='';
								el.classList.remove('is-invalid','is-valid');
							}
						}
						else{
							el.innerHTML='';
						}
					});
					document.getElementById('f4').tested_form=false;
					document.getElementById('f4').action_form='testing_calc';
					document.getElementById('f4').sending=undefined;
					document.getElementById('primas_generales_dedicada').checked = false;
					document.getElementById('primas_generales_dedicada').onclick();

					document.querySelector("#f4 button[type='submit']").innerHTML='Probar Formula';

					document.querySelector("#modal_registrar_prima_general .form_calc_container").innerHTML='';

				})






		// primas generales **************************************************

			// load_primas_generales();

			//	 load_primas_hijos();
			// load_primas_antiguedad();

			// load_primas_escalafon();
			

		// funciones**************************************************

		function load_all_primas(){
			var datos = new FormData();
			datos.append("accion","load_all_primas");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_all_primas"){

					cargar_prima_generales(lee.mensaje.generales);
					cargar_prima_hijos(lee.mensaje.hijos);
					cargar_prima_antiguedad(lee.mensaje.antiguedad);
					cargar_prima_escalafon(lee.mensaje.escalafon);
					
				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje(lee.titulo, lee.mensaje,"error");
				}
				else if(lee.resultado == "error"){
					muestraMensaje(lee.titulo, "Error al cargar las primas","error");
					console.error(lee.mensaje);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
				}
				else{
					muestraMensaje(lee.titulo, lee.mensaje,"error");
				}
			});
		}


		function load_primas_generales() {
			var datos = new FormData();
			datos.append("accion","load_primas_generales");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_primas_generales"){

					cargar_prima_generales(lee.mensaje);
					
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
		}

		function load_primas_hijos() {
			var datos = new FormData();
			datos.append("accion","load_primas_hijos");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_primas_hijos"){
					cargar_prima_hijos(lee.mensaje);
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
		}

		function load_primas_antiguedad(){
			var datos = new FormData();
			datos.append("accion","load_primas_antiguedad");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_primas_antiguedad"){
					cargar_prima_antiguedad(lee.mensaje);
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
		}

		function load_primas_escalafon(){
			var datos = new FormData();
			datos.append("accion","load_primas_escalafon");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_primas_escalafon"){

					cargar_prima_escalafon(lee.mensaje);
					
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
		}


		function cargar_prima_generales(datosJson) {
			if ($.fn.DataTable.isDataTable("#table_primas_generales")) {
				$("#table_primas_generales").DataTable().destroy();
			}
			
			$("#tbody_primas_generales").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_primas_generales")) {
				$("#table_primas_generales").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de primas generales",
						info: "Mostrando página _PAGE_ de _PAGES_",
						infoEmpty: "No hay registros disponibles",
						infoFiltered: "(filtrado de _MAX_ registros totales)",
						search: "Buscar:",
						paginate: {
							first: "Primera",
							last: "Última",
							next: "Siguiente",
							previous: "Anterior",
						},
					},
					columns:[
						{data:"descripcion"}
						,{data:"dedicada"}
						,{data:"extra"}
					],
					data:datosJson,
					createdRow: function(row,data){
						// row.querySelector("td:nth-child(1)").innerText;
						row.dataset.id = data.id;

						row.querySelector("td:nth-child(1)").style="min-width:200px;";


						var acciones = row.querySelector("td:nth-child(3)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Prima'></span>")
						acciones.appendChild(btn);
						btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Prima'></span>")
						acciones.appendChild(btn);
						acciones.classList.add('text-nowrap','cell-action',"text-center");
					},
					autoWidth: false,
					//searching:false,
					//info: false,
					//ordering: false,
					//paging: false
					//order: [[1, "asc"]], // vacio para que no ordene al principio
					
				});
			}
		}
		function cargar_prima_hijos(datosJson) {
			if ($.fn.DataTable.isDataTable("#table_primas_hijos")) {
				$("#table_primas_hijos").DataTable().destroy();
			}
			
			$("#tbody_primas_hijos").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_primas_hijos")) {
				$("#table_primas_hijos").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de primas para hijos",
						info: "Mostrando página _PAGE_ de _PAGES_",
						infoEmpty: "No hay registros disponibles",
						infoFiltered: "(filtrado de _MAX_ registros totales)",
						search: "Buscar:",
						paginate: {
							first: "Primera",
							last: "Última",
							next: "Siguiente",
							previous: "Anterior",
						},
					},
					columns:[
						{data:"descripcion"}
						,{data:"monto"}
						,{data:"menor_edad"}
						,{data:"discapacidad"}
						,{data:"extra"}

						],
					data:datosJson,
					createdRow: function(row,data){
						// row.querySelector("td:nth-child(1)").innerText;
						row.dataset.id = data.id;

						row.querySelector("td:nth-child(1)").style="min-width:200px;";


						var acciones = row.querySelector("td:nth-child(5)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Prima'></span>")
						acciones.appendChild(btn);
						btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Prima'></span>")
						acciones.appendChild(btn);
						acciones.classList.add('text-nowrap','cell-action','text-center');
					},
					autoWidth: false
					//searching:false,
					//info: false,
					//ordering: false,
					//paging: false
					//order: [[1, "asc"]], // vacio para que no ordene al principio
					
				});
			}
		}
		function cargar_prima_antiguedad(datosJson){

			if ($.fn.DataTable.isDataTable("#table_primas_antiguedad")) {
				$("#table_primas_antiguedad").DataTable().destroy();
			}
			
			$("#tbody_primas_antiguedad").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_primas_antiguedad")) {
				$("#table_primas_antiguedad").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de primas por antigüedad",
						info: "Mostrando página _PAGE_ de _PAGES_",
						infoEmpty: "No hay registros disponibles",
						infoFiltered: "(filtrado de _MAX_ registros totales)",
						search: "Buscar:",
						paginate: {
							first: "Primera",
							last: "Última",
							next: "Siguiente",
							previous: "Anterior",
						},
					},
					columns:[
						{data:"tiempo"}
						,{data:"monto"}
						,{data:"extra"}
					],
					data:datosJson,
					createdRow: function(row,data){
						// row.querySelector("td:nth-child(1)").innerText;
						row.dataset.id = data.id;

						row.querySelector("td:nth-child(1)").classList.add("text-center","align-middle","text-nowrap");
						row.querySelector("td:nth-child(2)").classList.add("text-center","align-middle","text-nowrap");

						var acciones = row.querySelector("td:nth-child(3)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Prima'></span>")
						acciones.appendChild(btn);
						btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Prima'></span>")
						acciones.appendChild(btn);
						acciones.classList.add('text-nowrap','cell-action','text-center');
						acciones.style="width:1%;";
					},
					ordering: false,
					autoWidth: false
					//searching:false,
					//info: false,
					//paging: false
					//order: [[1, "asc"]], // vacio para que no ordene al principio
					
				});
			}
		}
		function cargar_prima_escalafon(datosJson) {
			if ($.fn.DataTable.isDataTable("#table_primas_escalafon")) {
				$("#table_primas_escalafon").DataTable().destroy();
			}
			
			$("#tbody_primas_escalafon").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_primas_escalafon")) {
				$("#table_primas_escalafon").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de escalafón",
						info: "Mostrando página _PAGE_ de _PAGES_",
						infoEmpty: "No hay registros disponibles",
						infoFiltered: "(filtrado de _MAX_ registros totales)",
						search: "Buscar:",
						paginate: {
							first: "Primera",
							last: "Última",
							next: "Siguiente",
							previous: "Anterior",
						},
					},
					columns:[
						{data:"escala"}
						,{data:"tiempo"}
						,{data:"monto"}
						,{data:"extra"}
						
					],
					data:datosJson,
					createdRow: function(row,data){
						// row.querySelector("td:nth-child(1)").innerText;
						row.dataset.id = data.id;

						row.querySelector("td:nth-child(1)").classList.add("text-center","align-middle","text-nowrap");
						row.querySelector("td:nth-child(2)").classList.add("text-center","align-middle","text-nowrap");
						row.querySelector("td:nth-child(3)").classList.add("text-center","align-middle","text-nowrap");

						var acciones = row.querySelector("td:nth-child(4)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Prima'></span>")
						acciones.appendChild(btn);
						btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Prima'></span>")
						acciones.appendChild(btn);
						acciones.classList.add('text-nowrap','cell-action','text-center');
						acciones.style="width:1%;";
					},
					autoWidth: false
					//searching:false,
					//info: false,
					//ordering: false,
					//paging: false
					//order: [[1, "asc"]], // vacio para que no ordene al principio
					
				});
			}
		}

		function change_restrict_hijos(){
			var info = document.getElementById('hijo_info_prima');
			info.innerHTML="";
			if (document.getElementById('hijo_porcentaje').checked) {
				info.innerHTML +="*La prima se aplicara sobre el Porcentaje del sueldo base<br>";
			}
			if (document.getElementById('hijo_menor').checked) {
				info.innerHTML +="*La prima se aplicara solo por los hijos menores de edad<br>";
			}
			if (document.getElementById('hijo_discapacidad').checked) {
				info.innerHTML +="*La prima se aplicara solo por los hijos con discapacidad<br>";
			}
		}

		function add_trabajador(cedula = '', nombre = '', id = ''){
			if(typeof cedula !== 'string'){
				cedula = '';
			}

			var tbody = document.getElementById('tbody_trabajadores_dedicada');

			var n = tbody.querySelectorAll("tr").length + 1;

			while(document.getElementById(`general-row-${n}`)){
				n++;
			}

			var tr = crearElem("tr",`id,general-row-${n}`);
			tr.dataset.id='1';

			var input = crearElem("input","class,form-control,name,trabajadores,type,text,autocomplete,off");
			input.id = `primas_generalse_trabajador-${n}`;
			input.dataset.span = `invalid-span-primas_generalse_trabajador-${n}`;
			input.dataset.trabajador_info = `trabajador-info-${n}`;
			input.dataset.trabajador_id = `trabajador_hide-${n}`;
			input.required=true;
			input.value = cedula;





			var td = crearElem("td",'',input);

			td.appendChild(crearElem("span",`id,invalid-span-primas_generalse_trabajador-${n},class,invalid-span text-danger`));

			input = crearElem("input",`type,hidden,id,trabajador_hide-${n},name,trabajador_id_input`);
			input.value = id;

			td.appendChild(input);

			td.style = "width: 45%;";


			tr.appendChild(td);
			tr.appendChild(crearElem("td",`id,trabajador-info-${n},class,text-center text-nowrap font-weight-bold align-items-center,style,width:45%;`,nombre));

			btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar,type,button", "<span class='bi bi-trash' title='Eliminar trabajador de la lista'></span>")
			tr.appendChild(crearElem("td",'class,text-center cell-action,style,10%', btn ));

			tbody.appendChild(tr);

			eventoKeyup(`primas_generalse_trabajador-${n}`,V.expCedula,"La cedula es invalida ej. V-00000001",undefined,undefined,valid_trabajador);
			cedulaKeypress(`primas_generalse_trabajador-${n}`);

			document.getElementById(`primas_generalse_trabajador-${n}`).validarme = function(){
				if(this.dataset.trabajador_id == ''){
					return validarKeyUp(false, this, "La cedula del trabajador no existe");;
				}
				else{
					return validarKeyUp(true, this, "La cedula del trabajador no existe");;
				}

				for(var el of document.querySelectorAll('#primas_generales_list_trabajadores input[id^=primas_generalse_trabajador]')){
					if(el.value = this.value){
						return validarKeyUp(false, this, "La cedula del trabajador esta duplicada en la lista");;
					}
					else{
						return validarKeyUp(true, this, "La cedula del trabajador esta duplicada en la lista");;
					}
				}
			}

			document.getElementById(`primas_generalse_trabajador-${n}`).focus();

		}


		function sub_trabajador(){
			var tbody = document.getElementById('tbody_trabajadores_dedicada');
			var tr = tbody.querySelector("tr:last-child");
			if(tr){
				tbody.removeChild(tr);
			}
		}



		function valid_trabajador(etiqueta,valid)
		{

			if(etiqueta.xhr!= undefined){
				etiqueta.xhr.abort();
			}
			if(valid){
				

				if(etiqueta.value == ''){
					return false;
				}


				var datos = new FormData();
				datos.append("accion","valid_cedula_trabajador");
				datos.append('cedula',etiqueta.value);



				
				document.getElementById(etiqueta.dataset.trabajador_id).value = '';

				var ajax = enviaAjax(datos,function(respuesta, exito, fail){
					
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "valid_cedula_trabajador"){

						document.getElementById(etiqueta.dataset.trabajador_info).innerText = lee.mensaje;
						document.getElementById(etiqueta.dataset.trabajador_id).value = lee.id;

						for(var el of document.querySelectorAll("#tbody_trabajadores_dedicada input[id^=primas_generalse_trabajador]")){
							if(el != etiqueta && el.value == etiqueta.value){
								validarKeyUp(false, etiqueta, "Cedula duplicada en la lista");
								break;
							}
							else{
								validarKeyUp(true, etiqueta, '');
							}
						}
					}
					else if (lee.resultado == 'no_existe'){
						validarKeyUp(false, etiqueta, lee.mensaje);
						fail();
					}
					else if (lee.resultado == 'is-invalid'){
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						fail();
					}
					else if(lee.resultado == "error"){
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						console.error(lee.mensaje);
						fail();
					}
					else if(lee.resultado == "console"){
						console.log(lee.mensaje);
						fail();
					}
					else{
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						fail();
					}
					etiqueta.xhr = undefined; 
				},'loader_body');

				etiqueta.xhr = ajax.xhr; 

				ajax.p.catch((a)=>{
					etiqueta.xhr = undefined; 
					document.getElementById(etiqueta.dataset.trabajador_id).value='';
					document.getElementById(etiqueta.dataset.trabajador_info).innerHTML='';
				})
			}
			else{
				document.getElementById(etiqueta.dataset.trabajador_id).value='';
				document.getElementById(etiqueta.dataset.trabajador_info).innerHTML='';
			}
		}


		function event_modal_calc(){
			document.querySelectorAll("button[data-open_modal_calc]").forEach((button)=>{

				var modal = document.getElementById(button.dataset.open_modal_calc);

				if(modal){

					modal.load_calc_formulario=function(show_modal=true){
						

						var newUrl = window.location.href+'&calc_form_1=1';

						return new Promise((resolve,rejected)=>{

							$.ajax({
								async: true,
								url: newUrl,
								type: "GET",
								contentType: false,
								processData: false,
								cache: true,
								headers: {
								     'Cache-Control': 'max-age=3600,public' 
								},
								beforeSend: function () {
									ajaxCounterConsult++; 
									loader_main(true,ajaxCounterConsult);
									// ajaxCounterConsult_body++;
									// loader_body(true,ajaxCounterConsult_body);
								},
								timeout: 30000,
								success: function (respuesta) {
									try {
										if(respuesta==='close_sesion_user'){
											location.reload();
										}

										modal.querySelector(".form_calc_container").innerHTML = respuesta;
										load_calc_functions().then(()=>{
											if(show_modal===true){
												$(modal).modal("show");
											}
											resolve();
										});
										
									} catch (e) {
										alert("Error en " + e.name + " !!!");
										console.error(e);
										console.log(respuesta);
									}
								},
								error: function (request, status, err) {
									if (status == "timeout") {
										muestraMensaje("Servidor Ocupado", "Intente de nuevo", "error");

									} 
									else if(request.readyState===0){
										if(status != 'abort'){
										muestraMensaje("No Hay Conexión Con El Servidor", "Intente de nuevo", "error");}
									}
									else {
										muestraMensaje("Error", request + status + err, "error");
									}

									ajaxCounterConsult--;
									loader_main(false,ajaxCounterConsult);
									rejected();
									
								},
								complete: function (xhr, status) { 
									ajaxCounterConsult--;
									loader_main(false,ajaxCounterConsult);
								},
							});
						})

					}


					button.onclick=function (){

						var modal = document.getElementById(this.dataset.open_modal_calc);

						modal.load_calc_formulario();

					}

				}
				else{
					console.error(`EL modal no existe (#${button.dataset.open_modal_calc})`);
				}





				




			})


		}

		
	</script>
<script src="assets/js/sb-admin-2.min.js"></script>
	
</body>
</html>
