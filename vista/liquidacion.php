<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Liquidaciones - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
		<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">                                                      

					<main class="main-content">
						<div class="row mb-4">
							<div class="col">
								<h1 class="h3 mb-2 text-gray-800">Liquidaciones</h1>
							</div>
							<div class="col d-flex justify-content-end align-items-center">
								<div>
									<button class="btn btn-primary <?php if(!$permisos["validar_permisos"]("liquidacion","crear",true)){echo " d-none";} ?>" data-toggle="modal" data-target="#modal_registrar_liquidacion">Registrar Liquidación</button>
								</div>
							</div>
						</div>
						<style>
							#tbody_liquidaciones td:nth-child(5)::after{
								content: " Bs";
							}
						</style>

						<table class="table table-bordered table-hover table-middle" id="table_liquidaciones">
							<thead class="bg-primary text-light">
								<th>Cedula</th>
								<th>Nombre</th>
								<th>Fecha</th>
								<th>Motivo</th>
								<th>Monto</th>
								<th>Acción</th>
							</thead>
						
							<tbody id="tbody_liquidaciones">
								
							</tbody>
							
						</table>
					</main>


					<div class="modal fade" tabindex="-1" role="dialog" id="modal_registrar_liquidacion">
						<div class="modal-dialog modal-xl" role="document">
							<div class="modal-content">
								<div class="modal-header text-light bg-primary">
									<h5 class="modal-title">Liquidaciones</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="container">

									<form action="" id="f1" onsubmit="return false;" method="POST">
										<input type="hidden" class="d-none" id="liquidacion_id" name="liquidacion_id">
										<input type="hidden" class="d-none" id="trabajador_id" name="trabajador_id">
										<div class="row">
											<div class="col-8 col-md-4">
												<label for="liquidacion_cedula">Cedula</label>
												<input required type="text" class="form-control" id="liquidacion_cedula" name="liquidacion_cedula" data-span="invalid-span-liquidacion_cedula" data-trabajador_id='trabajador_id' data-trabajador_info='trabajador_info'>
												<span id="invalid-span-liquidacion_cedula" class="invalid-span text-danger"></span>
												<div id="trabajador_info" class="text-center font-weight-bold mt-2"></div>
											</div>
											<div class="col-4">
												<label for="" class="fade no-select d-block">l</label>
												<button class="btn btn-primary" type="button" id="btn_calcular"><span class="bi bi-search"></span></button>
											</div>
											
										</div>
										<hr>

										<style>
											#tbody_resumen td:nth-child(3)::after{
												content: " Bs";
											}
										</style>
										<div class="row d-none" id="prestaciones_container">
											<div class="col-12 col-md-4" id="fecha_contratacion">
												
											</div>
											<div class="col-12 col-md-4">
												<label for="liquidaciones_fecha">Fecha de liquidación</label>
												<input required type="date" class="form-control" id="liquidaciones_fecha" name="liquidaciones_fecha" data-span="invalid-span-liquidaciones_fecha">
												<span id="invalid-span-liquidaciones_fecha" class="invalid-span text-danger"></span>
											</div>
											<div class="col-12 col-md-4">
												<label for="liquidacion_motivo">Motivo</label>
												<input required type="text" class="form-control" id="liquidacion_motivo" name="liquidacion_motivo" data-span="invalid-span-liquidacion_motivo">
												<span id="invalid-span-liquidacion_motivo" class="invalid-span text-danger"></span>
											</div>
											<div class="col-12 mt-3">
												 <table class="table table-bordered table-hover table-middle" id="table_resumen">
													<thead class="bg-primary text-light">
														<th>Mes/Año</th>
														<th>dias del mes</th>
														<th>Salario Mensual</th>
													</thead>
												
													<tbody id="tbody_resumen">
														
													</tbody>
													
												</table>
												<div class="mt-2">
													<button type="button" class="btn btn-primary" onclick="copy_resumen();" id="btn-copy">copiar</button>
												</div>
											</div>
											<div class="col-12 mb-2">
												<div class="row justify-content-md-end">
													<div class="col-12 col-md-4 text-right">
														<label for="liquidacion_monto_total">Total A Pagar</label>
														<input required type="text" class="form-control" id="liquidacion_monto_total" name="liquidacion_monto_total" data-span="invalid-span-liquidacion_monto_total">
														<span id="invalid-span-liquidacion_monto_total" class="invalid-span text-danger"></span>
													</div>
												</div>
											</div>
											<div class="col-12 text-center my-5">
												<button class="btn btn-primary" type="submit">Registrar</button>
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
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>

	<script>
		load_liquidaciones();

		eventoKeyup(`liquidacion_cedula`,V.expCedula,"La cedula es invalida ej. V-00000001",undefined,undefined,valid_trabajador);
		cedulaKeypress(`liquidacion_cedula`);

		eventoMonto("liquidacion_monto_total");


		rowsEventActions("tbody_liquidaciones" ,function(action,rowId,btn){
			if(action=='eliminar'){

				muestraMensaje("¿Seguro?", "Desea eliminar la liquidación<ENDL>Esto no restaurara el usuario, para ello debe registrarlo de nuevo", "?",function(result){
					if(result){

						var datos = new FormData();
						datos.append("accion","eliminar_liquidacion");
						datos.append("id",rowId);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "eliminar_liquidacion"){

								muestraMensaje("La liquidación fue eliminada exitosamente", "", "s");

								$("#modal_registrar_liquidacion").modal("hide");

								load_liquidaciones();
								
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
			else if (action == "modificar"){
				muestraMensaje("¿Seguro?", "Desea modificar la liquidación", "?",function(result){
					if(result){

						var datos = new FormData();
						datos.append("accion","get_liquidacion");
						datos.append("id",rowId);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "get_liquidacion"){
								let datos_liquidacion = lee.datos_liquidacion;


								document.getElementById('liquidacion_id').value=datos_liquidacion.id_liquidacion;
								document.getElementById('trabajador_id').value=datos_liquidacion.id_trabajador;
								document.getElementById('liquidacion_cedula').value=datos_liquidacion.cedula;
								document.getElementById('liquidacion_motivo').value=datos_liquidacion.descripcion;
								document.getElementById('liquidacion_monto_total').value=datos_liquidacion.monto;
								document.getElementById('liquidaciones_fecha').value=datos_liquidacion.fecha;
								document.getElementById('liquidacion_monto_total').onkeyup({key:""});

								document.getElementById('liquidacion_cedula').readOnly=true;


								document.getElementById('prestaciones_container').classList.remove("d-none");

								document.getElementById('liquidacion_monto_total').classList.remove("is-valid","is-invalid");

								document.getElementById('fecha_contratacion').innerHTML = `fecha de contratación <br>${datos_liquidacion.fecha_contrato}`;

								load_lista_resumen(lee.lista);

								document.querySelector("#f1 button[type='submit']").innerHTML="Modificar";


								$("#modal_registrar_liquidacion").modal("show");
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
		


		document.getElementById('btn_calcular').onclick=calcular_liquidacion;


		$('#modal_registrar_liquidacion').on('hidden.bs.modal', function (e) {
			document.getElementById('prestaciones_container').classList.add("d-none");
			document.getElementById('tbody_resumen').innerHTML='';
			document.getElementById('fecha_contratacion').innerHTML='';
			document.getElementById('trabajador_info').innerHTML='';
			document.getElementById('liquidaciones_fecha').value = '';
			document.getElementById('liquidacion_id').value = '';
			document.getElementById('liquidacion_motivo').value = '';
			document.getElementById('trabajador_id').value = '';
			document.getElementById('liquidacion_cedula').value = '';
			document.getElementById('liquidacion_cedula').disabled = false;
			document.getElementById('liquidacion_cedula').readOnly = false;
			document.getElementById('liquidacion_cedula').classList.remove("is-valid","is-invalid");
			document.getElementById('liquidacion_monto_total').value = '';
			document.getElementById('liquidacion_monto_total').classList.remove("is-valid","is-invalid");
			document.querySelector("#f1 button[type='submit']").innerHTML="Registrar";



			document.getElementById('btn_calcular').disabled = false;

		});



		document.getElementById('f1').onsubmit=function(e){
			e.preventDefault();
			// TODO validaciones
			var datos = new FormData($("#f1")[0]);

			datos.set("liquidacion_monto_total",sepMilesMonto(datos.get("liquidacion_monto_total"),true));
			if(document.getElementById('liquidacion_id').value==''){
				datos.append("accion","registrar_liquidacion");
				var mensaje = "Desea registrar la liquidación del trabajador, esto deshabilitara el usuario del trabajador";
			}
			else{
				datos.append("accion","modificar_liquidacion");
				var mensaje = "Desea modificar la liquidación del trabajador";
			}
			muestraMensaje("¿Seguro?", mensaje, "w",function(result){
				if(result){

					enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "registrar_liquidacion" || lee.resultado == "modificar_liquidacion"){
							var parent_lee = lee;
							muestraMensaje(lee.titulo, lee.mensaje, "s",function(){
								cargar_liquidaciones(lee.lista);
								var obj = {customClass: {
												actions: 'my-actions w-75',
												cancelButton: 'order-1 mr-auto',
												confirmButton: 'order-2',
												denyButton: 'order-3',
											}
											,showDenyButton: true
											, showCancelButton: true
											, confirmButtonText: 'Si'
											, denyButtonText: 'No'};

								if(lee.resultado !="modificar_liquidacion"){
									muestraMensaje("¿Enviar Correo?", "¿Desea notificar al trabajador de la liquidación?", "?",obj,function(a,result){
										if(result.isConfirmed){
											var datos = new FormData();
												datos.append("accion","enviar_correo");
												datos.append("id",parent_lee.id_liquidacion_inserted);
												enviaAjax(datos,function(respuesta, exito, fail){
												
													var lee = JSON.parse(respuesta);
													if(lee.resultado == "enviar_correo"){

														muestraMensaje("Éxito", "El correo fue enviado exitosamente", "s");

														
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
												},'loader_body');	
										}
										
									});
								}


								$("#modal_registrar_liquidacion").modal("hide");
							},{modal:"modal_registrar_liquidacion"});
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
					},'loader_body');
				}
			},{modal:"modal_registrar_liquidacion"});
		};


		function calcular_liquidacion(){
			var cedula = document.getElementById('liquidacion_cedula');

			document.getElementById('prestaciones_container').classList.add("d-none");
			if(!cedula.classList.contains("is-valid")){
				muestraMensaje("Error", "La cedula es invalida", "e");
				return false;
			}




			var datos = new FormData();
			datos.append("accion","nueva_liquidacion");
			datos.append("cedula",cedula.value);
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "nueva_liquidacion"){



					

					load_lista_resumen(lee.lista)

					


					


					document.getElementById('liquidacion_cedula').readOnly=true;


					document.getElementById('prestaciones_container').classList.remove("d-none");

					document.getElementById('liquidacion_monto_total').classList.remove("is-valid","is-invalid");

					document.getElementById('fecha_contratacion').innerHTML = `fecha de contratación <br>${lee.mensaje.creado}`;




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
			},'loader_body');

		}

		function load_lista_resumen(datos){
			if ($.fn.DataTable.isDataTable("#table_resumen")) {
				$("#table_resumen").DataTable().destroy();
			}
			
			$("#tbody_resumen").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_resumen")) {
				let table = new DataTable("#table_resumen",{
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de pagos del trabajador",
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
						{data:"fecha"},
						{data:"dias"},
						{data:"total_pagos"},
						],
					data:datos,
					//createdRow: function(row,data){row.querySelector("td:nth-child(1)").innerText;},
					autoWidth: false,
					searching:false,
					info: false,
					ordering: false,
					paging: false,
					//order: [[1, "asc"]], // vacio para que no ordene al principio
					
				});

				console.log(table.rows().count());
				if (!table.rows().count()) {
					document.getElementById('btn-copy').disabled = true;
					document.getElementById('btn-copy').classList.add("d-none");
				}
				else{
					document.getElementById('btn-copy').disabled = false;
					document.getElementById('btn-copy').classList.remove("d-none");
					document.getElementById('table_resumen').datos_lista=datos;
				}


			}
		}



		function valid_trabajador(etiqueta,valid)
		{
			if (etiqueta.readOnly) {
				return false;
			}

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

		function load_liquidaciones(){
			var datos = new FormData();
			datos.append("accion","load_liquidaciones");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_liquidaciones"){
					cargar_liquidaciones(lee.mensaje);
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

		function cargar_liquidaciones(jsonObj){
			if ($.fn.DataTable.isDataTable("#table_liquidaciones")) {
				$("#table_liquidaciones").DataTable().destroy();
			}
			
			$("#tbody_liquidaciones").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_liquidaciones")) {
				$("#table_liquidaciones").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de liquidaciones",
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
						{data:"cedula"}
						,{data:'nombre'}
						,{data:"fecha"}
						,{data:"motivo"}
						,{data:"monto"}
						,{data:"extra"}

					],
					data:jsonObj,
					createdRow: function(row,data){
						row.dataset.id = data.id;


						var nombre = data.nombre.replace(/^(\w+\b).*$/, "$1");
						nombre += ' '+data.apellido.replace(/^(\w+\b).*$/, "$1");

						row.querySelector("td:nth-child(2)").innerHTML = nombre;



						var acciones = row.querySelector("td:nth-child(6)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar'></span>")
						if( checkPermisos("liquidacion","modificar") )	{
							acciones.appendChild(btn);
						}
						btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar'></span>")
						if( checkPermisos("liquidacion","eliminar") )	{
							acciones.appendChild(btn);
						}
						acciones.classList.add('text-nowrap','cell-action', 'text-center');
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

		function copy_resumen(){
			let table = document.getElementById('table_resumen');
			console.log(table.datos_lista);
			var texto = "";
			table.datos_lista.forEach((x)=>{
				x.total_pagos = x.total_pagos.replace(/\./, ",");
				texto += `1-${x.mes}-${x.anio}\t${x.dias}\t${x.total_pagos}\n`;
			})
			let n = table.datos_lista.length;
			if(n>0){
				navigator.clipboard.writeText(texto)
				alert("Datos copiados al portapapeles");
			}
			else{
				alert("No hay datos validos para copiar");
			}
		}
	</script>

	<script src="assets/js/sb-admin-2.min.js"></script>

	
</body>
</html>
