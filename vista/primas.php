<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Primas - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
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
									<a class="nav-item nav-link" id="nav-primas_hijo-tab" data-toggle="tab" href="#nav-primas_hijo" role="tab" aria-controls="nav-primas_hijo" aria-selected="false">Hijos</a>
									<a class="nav-item nav-link" id="nav-primas_antiguedad-tab" data-toggle="tab" href="#nav-primas_antiguedad" role="tab" aria-controls="nav-primas_antiguedad" aria-selected="false">Antigüedad</a>
									<a class="nav-item nav-link" id="nav-primas_escalafon-tab" data-toggle="tab" href="#nav-primas_escalafon" role="tab" aria-controls="nav-primas_escalafon" aria-selected="false">Escalafón</a>
								</div>
							</nav>
							<div class="tab-content">
								<div class="tab-pane fade active show" id="nav-primas_generales" role="tabpanel" aria-labelledby="nav-primas_generales-tab">
									<div class="row mb-5 mt-3">
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
											<h3 class="mx-md-3 text-capitalize">primas generales</h3>
										</div>
										<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
											<button class="btn btn-primary mx-md-3">Registrar Primas</button>
										</div>
									</div>


									<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_primas_generales">
										<thead class="bg-primary text-light">
											<th>descripción</th>
											<th>Monto</th>
											<th>Exclusivo Médicos</th>
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
								<div class="col-12 col-md-6">
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
										<input type="checkbox" id="hijo_porcentaje" name="hijo_porcentaje">
										<label class="cursor-pointer no-select" for="hijo_porcentaje">Porcentaje</label>
									</div>
									<div>
										<input type="checkbox" id="hijo_menor" name="hijo_menor">
										<label class="cursor-pointer no-select" for="hijo_menor">Solo Para Menor de Edad</label>
									</div>
									<div>
										<input type="checkbox" id="hijo_discapacidad" name="hijo_discapacidad">
										<label class="cursor-pointer no-select" for="hijo_discapacidad">Solo Para Discapacitados</label>
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




	<script>

	//	$("#modal_registrar_prima_hijos").modal("show");
		// inicializar *******************************************************

			document.getElementById('nav-primas_escalafon-tab').click();

			load_all_primas();
			// inicializar primas hijos
				document.getElementById('hijo_porcentaje').onchange = document.getElementById('hijo_menor').onchange = document.getElementById('hijo_discapacidad').onchange = change_restrict_hijos;
				eventoMonto("hijo_monto");
				eventoKeyup("hijo_descripcion", /^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,50}$/, "Solo se permiten letras");

				document.getElementById('f1').onsubmit=function(e){
					e.preventDefault();
					document.querySelector("#f1 button[type='submit']").focus();
					document.querySelector("#f1 button[type='submit']").blur();

					f1 = this;

					if(f1.sending === true){
						return false;
					}

					elem = document.querySelectorAll("#f1 input:not(input[type='checkbox']):not(input[type='hidden'])");
					for(var el of elem){
						if(!el.validarme()){
							return false;
						}
					}

					if (document.getElementById('hijo_porcentaje').checked){
						var monto = document.getElementById('hijo_monto').value;
						monto = sepMilesMonto(monto,true);
						monto = parseFloat(monto);
						if(monto>100){
							muestraMensaje("Error", "El monto no puede ser mayor a 100 si la casilla de porcentaje esta activada", "e");
							document.getElementById('hijo_monto').focus();
							document.getElementById('hijo_monto').classList.replace("is-valid", "is-invalid");
							return false;
						}
					}

					if(document.getElementById('id_prima_hijo_hidden').value!=''){

						mensaje = "Seguro de que desea modificar la prima";
					}
					else{
						mensaje = "Esta seguro de registrar la prima";
					}

					muestraMensaje("¿Seguro?", mensaje, "?",function(result){
						if(result){



							var datos = new FormData($("#f1")[0]);
							datos.set("hijo_monto",sepMilesMonto(document.getElementById('hijo_monto').value,true));
							datos.set("hijo_porcentaje",(datos.has("hijo_porcentaje"))?1:0);
							datos.set("hijo_menor",(datos.has("hijo_menor"))?1:0);
							datos.set("hijo_discapacidad",(datos.has("hijo_discapacidad"))?1:0);

							if(document.getElementById('id_prima_hijo_hidden').value!=''){
								datos.append("accion","modificar_prima_hijo");
							}
							else{
								datos.append("accion","registrar_prima_hijo");
							}

							f1.sending = true;

							enviaAjax(datos,function(respuesta, exito, fail){
							
								var lee = JSON.parse(respuesta);
								if(lee.resultado == "registrar_prima_hijo"){

									muestraMensaje("Éxito", "La prima por hijos fue registrada exitosa mente", "s");

									cargar_prima_hijos(lee.mensaje);

									$("#modal_registrar_prima_hijos").modal("hide");


									
								}
								else if (lee.resultado == 'modificar_prima_hijo'){
									muestraMensaje("Éxito", "La prima por hijos fue modificada exitosa mente", "s");

									cargar_prima_hijos(lee.mensaje);

									$("#modal_registrar_prima_hijos").modal("hide");
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
								f1.sending = false;
							},"loader_body").p.catch((a)=>{
								f1.sending = false;
							});
						}

					});


				}

				$('#modal_registrar_prima_hijos').on('hidden.bs.modal', function (e) {
					$("#f1 input").each((index,el)=>{
						if(el.type=='checkbox'){
							el.checked = false;
						}
						else{
							el.value = '';
							el.classList.remove("is-invalid","is-valid");
						}
					});
					document.querySelector('#f1 button[type="submit"]').innerHTML = "Registrar";
					document.getElementById('hijo_info_prima').innerHTML='';
				})

				rowsEventActions("tbody_primas_hijos",function(action,rowId){
					if(action == "modificar"){
						muestraMensaje("¿Seguro?", "Seguro de que desea modificar la prima", "?",function(result){
							if(result){
								var datos = new FormData();
								datos.append("accion","get_prima_hijos");
								datos.append("id",rowId);
								enviaAjax(datos,function(respuesta, exito, fail){
								
									var lee = JSON.parse(respuesta);
									if(lee.resultado == "get_prima_hijos"){

										document.getElementById('id_prima_hijo_hidden').value = rowId;
										document.getElementById('hijo_descripcion').value = lee.mensaje.descripcion;
										document.getElementById('hijo_monto').value = lee.mensaje.monto;
										document.getElementById('hijo_monto').onkeyup();
										document.getElementById('hijo_monto').classList.remove("is-valid","is-invalid");

										document.getElementById('hijo_porcentaje').checked = (lee.mensaje.porcentaje == '1')?true:false;
										document.getElementById('hijo_menor').checked = (lee.mensaje.menor_edad == '1')?true:false;
										document.getElementById('hijo_discapacidad').checked = (lee.mensaje.discapacidad == '1')?true:false;
										
										document.getElementById('hijo_discapacidad').onchange();

										document.querySelector('#f1 button[type="submit"]').innerHTML = "Modificar";

										$("#modal_registrar_prima_hijos").modal("show");

										
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
					else if (action == "eliminar"){

						muestraMensaje("¿Seguro?", "Esta seguro de que desea eliminar la prima seleccionada", "?",function(result){
							if(result){

								var datos = new FormData();
								datos.append("accion","eliminar_prima_hijo");
								datos.append("id",rowId);
								enviaAjax(datos,function(respuesta, exito, fail){
								
									var lee = JSON.parse(respuesta);
									if(lee.resultado == "eliminar_prima_hijo"){
										muestraMensaje("Éxito", "La prima fue eliminada exitosamente", "s");
										load_primas_hijos();
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
				})

			// inicializar primas antigüedad

				eventoMonto("prima_antiguedad_monto");
				eventoKeyup("prima_antiguedad_year", /^[0-9]+$/, "Solo se permiten números");



				rowsEventActions("tbody_primas_antiguedad",(action, rowId)=>{
					console.log("action", action);

				 	if(action == "modificar"){

				 		var datos = new FormData();
				 		datos.append("accion","get_prima_antiguedad");
				 		datos.append("id",rowId);

				 		enviaAjax(datos,function(respuesta, exito, fail){
				 		
				 			var lee = JSON.parse(respuesta);
				 			if(lee.resultado == "get_prima_antiguedad"){
				 				document.getElementById('prima_antiguedad_id').value = rowId;
				 				document.getElementById('prima_antiguedad_year').value = lee.mensaje.year;
				 				document.getElementById('prima_antiguedad_monto').value = lee.mensaje.monto;

				 				document.querySelector("#f2 button[type='submit']").innerHTML = "Modificar";

				 				$("#modal_registrar_prima_antiguedad").modal("show");


				 				
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
				 	else if (action == "eliminar"){

				 		muestraMensaje("¿Seguro?", "Esta seguro de querer eliminar la prima seleccionada?", "?", function(result){
				 			var datos = new FormData();
				 			datos.append("accion","eliminar_prima_antiguedad");
				 			datos.append("id",rowId);

				 			enviaAjax(datos,function(respuesta, exito, fail){
				 			
				 				var lee = JSON.parse(respuesta);
				 				if(lee.resultado == "eliminar_prima_antiguedad"){
				 					
				 					muestraMensaje("Éxito", "La prima ha sido modificada exitosamente", "s");
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
				 		});

				 	}
				});

				document.getElementById('f2').onsubmit=function(e){
					e.preventDefault();

					f2 = this;

					if(f2.sending === true){
						return false;
					}

					//TODO validaciones



					if(document.getElementById('prima_antiguedad_id').value!=''){

						mensaje = "Seguro de que desea modificar la prima";
					}
					else{
						mensaje = "Esta seguro de registrar la prima";
					}

					muestraMensaje("¿Seguro?", mensaje, "?",function(result){
						if(result){

							var datos = new FormData($("#f2")[0]);

							var temp_monto = parseFloat(sepMilesMonto(datos.get("porcentaje_monto"),true));

							if(temp_monto > 100){
								muestraMensaje("Error", "El porcentaje no puede ser mayor a 100", "e");
								return false;
							}
							else if(temp_monto <= 0){
								muestraMensaje("Error", "El porcentaje no puede ser menor o igual a 0", "e");
								return false;	
							}

							if(datos.get("monto") == '' || datos.get("anio") == ''){
								muestraMensaje("Error", "Los datos no pueden estar vacíos", "e");
								return false;
							}

							// TODO validaciones


							if(document.getElementById('prima_antiguedad_id').value!=''){

								datos.append("accion","modificar_prima_antiguedad");
							}
							else{
								datos.append("accion","registrar_prima_antiguedad");
							}

							datos.set("porcentaje_monto",sepMilesMonto(document.getElementById('prima_antiguedad_monto').value,true));


							f2.sending = true;
							enviaAjax(datos,function(respuesta, exito, fail){
							
								var lee = JSON.parse(respuesta);
								if(lee.resultado == "registrar_prima_antiguedad"){
									muestraMensaje("Exito", "La prima fue registrada exitosamente", "s");

									cargar_prima_antiguedad(lee.mensaje);
									$("#modal_registrar_prima_antiguedad").modal("hide");
								}
								else if(lee.resultado == "modificar_prima_antiguedad"){
									muestraMensaje("Exito", "La prima fue modificada exitosamente", "s");

									cargar_prima_antiguedad(lee.mensaje);
									$("#modal_registrar_prima_antiguedad").modal("hide");
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
								f2.sending = false;

							}).p.catch((a)=>{
								f2.sending = false;
							});

						}
					});



				}

				$('#modal_registrar_prima_antiguedad').on('hidden.bs.modal', function (e) {
					$("#input").each(function(index,el){
						el.value = '';
						el.classList.remove("is-invalid", "is-valid");
					});

					document.querySelector("#f2 button[type='submit']").innerHTML = "Registrar";
				})

			// inicializar primas escalafón

			eventoMonto("primas_escalafon_monto");

			eventoKeyup("primas_escalfon_escala", /^[ivxlcdm]+$/i, "Se esperan números romanos ej I,IV,IX");

			eventoKeyup("primas_escalafon_tiempo", /^[\d\-\s]+$/, "se espera un plazo de tiempo ej 1 - 2 (años) este es solo un campo informativo");

			document.getElementById('f3').onsubmit=function(e){

				e.preventDefault();

				f3 = this;

				if(f3.sending === true){
					return false;
				}




				if(document.getElementById('primas_escalfon_id').value!=''){

					mensaje = "Seguro de que desea modificar la prima";
				}
				else{
					mensaje = "Esta seguro de registrar la prima";
				}

				muestraMensaje("¿Seguro?", mensaje, "?",function(result){
					if(result){


						var datos = new FormData($("#f3")[0]);

						datos.consoleAll();

						datos.removeSpace();

						datos.consoleAll();

						return false;


						var temp_monto = parseFloat(sepMilesMonto(datos.get("porcentaje"),true));

						if(temp_monto > 100){
							muestraMensaje("Error", "El porcentaje no puede ser mayor a 100", "e");
							return false;
						}
						else if(temp_monto <= 0){
							muestraMensaje("Error", "El porcentaje no puede ser menor o igual a 0", "e");
							return false;	
						}
						

						if(document.getElementById('primas_escalfon_id').value!=''){

							datos.append("accion","modificar_prima_escalafon");
						}
						else{
							datos.append("accion","registrar_prima_escalafon");
						}

						datos.set("porcentaje", sepMilesMonto(document.getElementById('primas_escalafon_monto').value,true) )
						datos.set("tiempo", removeSpace(datos.get("tiempo")));


						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "registrar_prima_escalafon"){
								
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

			};


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
						,{data:"monto"}
						,{data:"sector_salud"}
						,{data:"dedicada"}
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

		
	</script>
<script src="assets/js/sb-admin-2.min.js"></script>
	
</body>
</html>
