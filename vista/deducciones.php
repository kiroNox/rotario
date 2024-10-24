<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Deducciones - Servicio Desconcentrado Hospital Rotario</title>
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
								<h1 class="mx-md-3">Deducciones</h1>
							</div>
							<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
								<button class="btn btn-primary mx-md-3" data-open_modal_calc = "modal_registar_deducciones">Registrar Deducciones</button>
								<!-- <button class="btn btn-primary mx-md-3" data-target="#modal_registar_deducciones" data-toggle='modal'>Registrar Deducciones</button> -->
							</div>
						</div>

						

						<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_deducciones">
							<thead class="bg-primary text-light">
								<th>Descripción</th>
								<!-- <th>Monto</th> -->
								<!-- <th>Tiempo</th> -->
								<!-- <th>Exclusivos Médicos</th> -->
								<th>ISLR</th>
								<th>trabajadores</th>
								<th>Acción</th>
							</thead>
						
							<tbody id="tbody_deducciones">
								
							</tbody>
							
						</table>
						

					</main>


					<!-- modal deducciones -->

					<div class="modal fade" tabindex="-1" role="dialog" id="modal_registar_deducciones">
						<div class="modal-dialog modal-xl" role="document">
							<div class="modal-content">
								<div class="modal-header text-light bg-primary">
									<h5 class="modal-title">Deducciones</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="container">

									<form action="" method="POST" id="f1" onsubmit="return false;">
										<input type="hidden" id="deducciones_id" name="id" class="d-none">
										<div class="row">
											<div class="col-12 col-md-4">
												<label for="deducciones_descripcion">Descripción</label>
												<input required type="text" class="form-control" id="deducciones_descripcion" name="deducciones_descripcion" data-span="invalid-span-deducciones_descripcion">
												<span id="invalid-span-deducciones_descripcion" class="invalid-span text-danger"></span>
											</div>
											<!-- <div class="col-8 col-md-4">
												<label for="deducciones_monto">Monto</label>
												<input required type="text" class="form-control" id="deducciones_monto" name="deducciones_monto" data-span="invalid-span-deducciones_monto">
												<span id="invalid-span-deducciones_monto" class="invalid-span text-danger"></span>
											</div>
											<div class="col-4 d-flex flex-column">
												<label for="" class="fade no-select">l</label>
												<div class="d-flex flex-row align-items-center flex-grow-1 justify-content-start">
													<label for="deducciones_procentaje" class="m-0 cursor-pointer no-select">Porcentaje</label>
													<input type="checkbox" id="deducciones_procentaje" class="check-button" name="deducciones_procentaje">
													<label for="deducciones_procentaje" class="check-button ml-2"></label>
												</div>
											</div>
											<div class="col-12 col-md-4">
												<label for="deducciones_meses">Meses a multiplicar</label>
												<input required type="text" class="form-control" id="deducciones_meses" name="deducciones_meses" data-span="invalid-span-deducciones_meses" value="0">
												<span id="invalid-span-deducciones_meses" class="invalid-span text-danger"></span>
											</div>
											<div class="col-12 col-md-4">
												<label for="deducciones_semanas">Semanas a dividir</label>
												<input required type="text" class="form-control" id="deducciones_semanas" name="deducciones_semanas" data-span="invalid-span-deducciones_semanas" value="0">
												<span id="invalid-span-deducciones_semanas" class="invalid-span text-danger"></span>
											</div> -->
										</div>
										<div class="row mt-2">
											<div class="col-12 col-sm-6">
												
												<div class="d-flex justify-content-start mb-3 align-items-center">
													<label for="primas_generales_mensual" class="m-0 cursor-pointer no-select">Quincenal</label>
													<input type="checkbox" id="primas_generales_mensual" name="mensual" class="check-button">
													<label for="primas_generales_mensual" class="check-button mx-2" tabindex="0"></label>
													<label for="primas_generales_mensual" class="m-0 cursor-pointer no-select">Mensual</label>
												</div>
												<div class="d-flex flex-row align-items-center">
													<input type="checkbox" class="check-button" id="deducciones_islr" name="deducciones_islr">
													<label for="deducciones_islr" class="check-button"></label>
													<label for="deducciones_islr" class="mb-0 ml-2 cursor-pointer no-select">ISLR</label>
												</div>
											
												<div class="d-flex flex-row align-items-center">
													<input type="checkbox" id="deducciones_dedicada" name="deducciones_dedicada" class="check-button">
													<label for="deducciones_dedicada" class="check-button" tabindex="0"></label>
													<label for="deducciones_dedicada" class="mb-0 ml-2 cursor-pointer no-select">Dedicada</label>
												</div>
											</div>
											<div class="col-12 col-sm-6 mt-sm-0 mt-2">
												<span id="print_guia_deduccion"></span>
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
										<!-- <div class="row"> -->
											<!-- <div class="col-12 text-center">
												<button class="btn btn-primary" type="submit">Registrar</button>
											</div> -->

											<div class="form_calc_container">
												
											</div>







										<!-- </div> -->
									</form>
									
								</div>
								<div class="modal-footer bg-light">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								</div>
							</div>
						</div>
					</div>


					<script type="text/javascript" src="./assets/js/comun/calculadora.js"></script>
					<script>
						add_event_to_label_checkbox();
						load_deducciones();

						event_modal_calc();

						// document.getElementById('deducciones_meses').onchange = print_guia_deduccion;
						// document.getElementById('deducciones_semanas').onchange = print_guia_deduccion;
						// document.getElementById('deducciones_quincena').onchange = print_guia_deduccion;
						// document.getElementById('deducciones_multi_dia').onchange = print_guia_deduccion;
						document.getElementById('deducciones_islr').onchange = print_guia_deduccion;
						// document.getElementById('deducciones_sector_salud').onchange = print_guia_deduccion;
						document.getElementById('add_trabajador').onclick=add_trabajador;
						document.getElementById('sub_trabajador').onclick=sub_trabajador;

						// eventoMonto("deducciones_monto");

						document.getElementById('deducciones_dedicada').onchange = function(){
							if(this.checked){
								// document.getElementById('deducciones_sector_salud').disabled = true;
								// document.getElementById('deducciones_sector_salud').checked = false;
								document.getElementById('primas_generales_container_trabajadores').classList.remove("d-none");
								add_trabajador();
							}
							else{
								// document.getElementById('deducciones_sector_salud').disabled = false;
								document.getElementById('tbody_trabajadores_dedicada').innerHTML='';
								document.getElementById('primas_generales_container_trabajadores').classList.add("d-none");
							}
							print_guia_deduccion();
						};

						// document.getElementById('deducciones_procentaje').onchange = function(){
						// 	if(this.checked){
						// 		document.getElementById('deducciones_meses').disabled = false;
						// 		document.getElementById('deducciones_semanas').disabled = false;
						// 		document.getElementById('deducciones_multi_dia').disabled = false;	
						// 	}
						// 	else{
						// 		document.getElementById('deducciones_meses').disabled = true;
						// 		document.getElementById('deducciones_semanas').disabled = true;
						// 		document.getElementById('deducciones_multi_dia').disabled = true;
						// 		document.getElementById('deducciones_meses').value = '0';
						// 		document.getElementById('deducciones_semanas').value = '0';
						// 		document.getElementById('deducciones_multi_dia').checked = false;
						// 	}
						// 	print_guia_deduccion();
						// };

						// document.getElementById('deducciones_procentaje').onchange();

						rowsEventActions("tbody_trabajadores_dedicada" ,function(action,rowId,btn){
							if(action=='eliminar'){
								document.getElementById('tbody_trabajadores_dedicada').removeChild(btn.parentNode.parentNode);

							}

						});




						$('#modal_registar_deducciones').on('hidden.bs.modal', function (e) {
							document.getElementById('primas_generales_container_trabajadores').classList.add("d-none");
							document.getElementById('tbody_trabajadores_dedicada').innerHTML='';

							$("#f1 input:not(input[type='checkbox'])").each((index,el)=>{
								el.value = '';
								el.classList.remove("is-valid", "is-invalid")
							});
							$("#f1 input[type='checkbox']").each((index,el)=>{
								el.checked = false;
							});

							// document.getElementById('deducciones_procentaje').onchange();
							// document.getElementById('deducciones_dedicada').onchange();
							document.getElementById('print_guia_deduccion').innerHTML = '';

							document.getElementById('f1').tested_form=false;
							document.getElementById('f1').action_form='testing_calc';
							document.getElementById('f1').sending=undefined;

							//document.querySelector("#f1 button[type='submit']").innerHTML= 'Registrar';
						})





						rowsEventActions("tbody_deducciones",(action,rowId,target)=>{

							if(action == 'modificar'){

								muestraMensaje("¿Seguro?", "¿Quieres modificar la deducción seleccionada?", "?",function(resul){
									if(resul){
										var datos = new FormData();
										datos.append("accion","get_deduccion");
										datos.append("id",rowId);
										enviaAjax(datos,function(respuesta, exito, fail){
										
											var lee = JSON.parse(respuesta);
											if(lee.resultado == "get_deduccion"){

												var temp_quincena = (lee.mensaje.quincena=='1')?false:true;

												document.getElementById('primas_generales_mensual').checked = temp_quincena;



												document.getElementById('deducciones_id').value = rowId;
												document.getElementById('deducciones_descripcion').value = lee.mensaje.descripcion ;
												// document.getElementById('deducciones_monto').value = lee.mensaje.monto ;
												// document.getElementById('deducciones_monto').onchange();
												// document.getElementById('deducciones_monto').classList.remove("is-valid");

												// document.getElementById('deducciones_procentaje').checked = (lee.mensaje.porcentaje=='1')?true:false ;
												// document.getElementById('deducciones_procentaje').onchange();
												// document.getElementById('deducciones_meses').value = lee.mensaje.multi_meses ;
												// document.getElementById('deducciones_semanas').value = lee.mensaje.div_sem ;
												// document.getElementById('deducciones_quincena').checked = (lee.mensaje.quincena == '1')?true:false ;
												// document.getElementById('deducciones_multi_dia').checked =(lee.mensaje.multi_dia == '1')?true:false ;
												document.getElementById('deducciones_islr').checked = (lee.mensaje.islr == '1')?true:false ;
												// document.getElementById('deducciones_sector_salud').checked = (lee.mensaje.sector_salud == '1')?true:false ;
												document.getElementById('deducciones_dedicada').checked = (lee.mensaje.dedicada == '1')?true:false ;

												//document.querySelector("#f1 button[type='submit']").innerHTML= 'Modificar';




												if(lee.mensaje.dedicada == '1'){
													document.getElementById('primas_generales_container_trabajadores').classList.remove("d-none");
													for(var x of lee.lista){
														add_trabajador(x.cedula,x.nombre,x.id);
													}
												}



												var modal = document.getElementById('modal_registar_deducciones');

												modal.load_calc_formulario(false).then(()=>{

													load_formulas_form(lee.mensaje.calc_formula);

													document.getElementById('f1').tested_form=true;
													document.getElementById('save-form-btn-1').innerHTML="Modificar Deducción";
													$(modal).modal("show");
												})





												//$("#modal_registar_deducciones").modal("show");


												
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
							else if(action == "eliminar"){
								
								muestraMensaje("¿Seguro?", "¿Desea eliminar la deducción seleccionada?", "?",function(resul){
									if(resul){
										var datos = new FormData();
										datos.append("accion","eliminar_deduccion");
										datos.append("id",rowId);
										enviaAjax(datos,function(respuesta, exito, fail){
										
											var lee = JSON.parse(respuesta);
											if(lee.resultado == "eliminar_deduccion"){

												muestraMensaje("Éxito", "La deducción fue eliminada exitosamente", "s");

												cargar_deducciones(lee.mensaje);
												
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
						document.getElementById('f1').action_form='testing_calc';
						document.getElementById('f1').onsubmit=function(e){
							e.preventDefault();
							f1 = this;

							if(f1.sending === true){
								return false;
							}


							var focus = document.querySelector("#f1 button[type='submit']");
							focus.focus();
							focus.blur();

							for(var el of document.querySelectorAll("#f1 input:not(:disabled)")){
								if(el.classList.contains('is-invalid')){
									el.focus();
									this.action_form='testing_calc';
									return false;
								}
							}

							var obj_msg = {};

							if(this.action_form == "testing_calc"){
								obj_msg.ignore = true;
							}

							if(document.getElementById('deducciones_id').value != ''){
								var sms = "¿Desea modificar la deducción seleccionada?";
							}
							else{
								var sms = "¿Esta seguro de registrar la nueva deducción?";
							}

							muestraMensaje("¿Seguro?", sms , "?",obj_msg, (resul)=>{
								if(resul){


									var datos = new FormData(this);

									datos = calc_formData_maker(datos,this);
									if(datos===false){
										this.action_form='testing_calc';
										return false;
									}


									// datos.setter("deducciones_procentaje");
									// datos.setter("deducciones_quincena");
									// datos.setter("deducciones_multi_dia");
									datos.setter("deducciones_islr");
									// datos.setter("deducciones_sector_salud");
									datos.setter("deducciones_dedicada");
									datos.setter("mensual",0,1);

									// datos.setter("deducciones_meses", datos.get("deducciones_meses"));
									// datos.setter("deducciones_semanas", datos.get("deducciones_semanas"));

									datos.clean("deducciones_descripcion");


									datos.set("deducciones_monto",sepMilesMonto(datos.get("deducciones_monto"),true));
									datos.groupby("trabajadores");

									if(!((JSON.parse(datos.get("trabajadores"))).length > 0) && datos.get("deducciones_dedicada") == '1'){
										muestraMensaje("Error", "Debe seleccionar al menos un trabajador si esta habilitada la opcion \"Dedicada\"", "w");
										return false;
									}

									if(this.action_form=='testing_calc'){
										datos.set("accion","test_formula");
									}
									else if(this.action_form == "save_calc"){
										if(datos.get("id") != ''){
											datos.append("accion","modificar_deduccion");
										}
										else{
											datos.append("accion","registrar_deduccion");
										}
									}







									datos.consoleAll();
									enviaAjax(datos,function(respuesta, exito, fail){
									
										var lee = JSON.parse(respuesta);
										if(lee.resultado == "registrar_deduccion" || lee.resultado == 'modificar_deduccion'){

											muestraMensaje(lee.titulo, lee.mensaje, "s");
											cargar_deducciones(lee.lista);

											$("#modal_registar_deducciones").modal("hide");

											
										}
										else if (lee.resultado == 'leer_formula'){

											if(lee.total!==null){
												muestraMensaje("Prueba Exitosa", `La formula fue evaluada exitosamente <ENDL> total <ENDL> ${lee.total}`, "s",);
											}
											else{
												muestraMensaje("Prueba Exitosa <br> (Advertencia)", "La condicional no se ha cumplido por lo tanto el resultado es '0' <ENDL> se sugiere probar la formula con una condicional positiva para evitar errores", "¡",);
											}
											f1.tested_form = true;
										}
										else if (lee.resultado == 'leer_formula_condicional'){
											if(lee.total!==null){
												muestraMensaje("Prueba Exitosa", `La formula '${lee.n_formula}' fue evaluada exitosamente <ENDL> total <ENDL> ${lee.total}`, "s");
											}
											else{
												muestraMensaje("Prueba Exitosa <br> (Advertencia)", "Ninguna condicional se ha cumplido por lo tanto el resultado es '0' <ENDL> se sugiere probar cada formula con una condicional positiva para evitar errores", "¡",);
											}



											f1.tested_form = true;
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
										f1.sending = false; 
									},"loader_body").p.finally((a)=>{
										f1.sending = false; 
									});
									
								}
								else{
									f1.action_form = "testing_calc";
									f1.sending = undefined;
								}
							});
						};

						

						function load_deducciones() {
							var datos = new FormData();
							datos.append("accion","load_deducciones");
							enviaAjax(datos,function(respuesta, exito, fail){
							
								var lee = JSON.parse(respuesta);
								if(lee.resultado == "load_deducciones"){
									

									cargar_deducciones(lee.mensaje);


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
						function print_guia_deduccion(){
							var deducciones_descripcion = document.getElementById('deducciones_descripcion');
							// var deducciones_monto = document.getElementById('deducciones_monto');
							// var deducciones_procentaje = document.getElementById('deducciones_procentaje');
							// var deducciones_meses = document.getElementById('deducciones_meses');
							// var deducciones_semanas = document.getElementById('deducciones_semanas');
							// var deducciones_quincena = document.getElementById('deducciones_quincena');
							// var deducciones_multi_dia = document.getElementById('deducciones_multi_dia');
							var deducciones_islr = document.getElementById('deducciones_islr');
							// var deducciones_sector_salud = document.getElementById('deducciones_sector_salud');
							var deducciones_dedicada = document.getElementById('deducciones_dedicada');

							if(deducciones_islr.checked){
								var mensaje ="La deducción sera catalogada como ISLR";
							}
							else{
								var mensaje = '';
							}

							//var mensaje = "(Sueldo Integral)";

							// if(deducciones_meses.value!='0' && deducciones_meses.value != ''){
							// 	mensaje +=` * meses(${deducciones_meses.value})`;
							// }
							// if(deducciones_semanas.value!='0' && deducciones_semanas.value != ''){
							// 	mensaje +=` / Semanas(${deducciones_semanas.value})`;
							// }

							// if(deducciones_monto.value!=''){
							// 	if(deducciones_procentaje.checked){
							// 		mensaje += ") * monto %";
							// 	}
							// 	else{
							// 		mensaje +=") + monto";
							// 	}
							// }
							// else{
							// 	if(deducciones_procentaje.checked){
							// 		mensaje += ") * monto %";
							// 	}
							// 	else{
							// 		mensaje +=") + monto";
							// 	}
							// }

							// if(deducciones_quincena.checked){
							// 	if(deducciones_multi_dia.checked){
							// 		mensaje += " * n lunes en la quincena";
							// 	}
							// 	else{
							// 		//mensaje += " quincenal";
							// 	}
							// }
							// else{
							// 	if(deducciones_multi_dia.checked){
							// 		mensaje += " * n lunes en el mes";
							// 	}
							// 	else{
							// 		//mensaje += " mensual";
							// 	}	
							// }

							// if(deducciones_quincena.checked){
							// 	mensaje = `[ ${mensaje} ] * 2 "Quincenal"`;
							// }
							// else{
							// 	mensaje = `[ ${mensaje} ] "Mensual"`;
							// }



							document.getElementById('print_guia_deduccion').innerHTML = mensaje;

						}

						function cargar_deducciones(datosJson){

							if ($.fn.DataTable.isDataTable("#table_deducciones")) {
								$("#table_deducciones").DataTable().destroy();
							}
							
							$("#tbody_deducciones").html("");
							
							if (!$.fn.DataTable.isDataTable("#table_deducciones")) {
								$("#table_deducciones").DataTable({
									language: {
										lengthMenu: "Mostrar _MENU_ por página",
										zeroRecords: "No se encontraron registros de deducciones",
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
										{data:"descripcion"},
										{data:"islr_temp"},
										{data:"dedic"},
										{data:"extra"},
									],
									data:datosJson,
									createdRow: function(row,data){
										// row.querySelector("td:nth-child(1)").innerText;


										// row.querySelector("td:nth-child(2)").innerHTML = sepMilesMonto(data.monto)+data.monto.replace(/^[\d\.,]+/, '');
										// row.querySelector("td:nth-child(2)").classList.add("text-nowrap");
										row.dataset.id=data.id_deducciones;
										var acciones = row.querySelector("td:nth-child(4)");
										acciones.innerHTML = '';
										var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Deducción'></span>")
										acciones.appendChild(btn);
										btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Deducción '></span>")
										acciones.appendChild(btn);
										acciones.classList.add('text-nowrap','cell-action');

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
															document.getElementById('save-form-btn-1').innerHTML='Registrar Deducción';
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
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>

	
	<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
