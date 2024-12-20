<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Gestionar Pagos - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top" class="<?= $modo_oscuro ?>">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
		<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">                                                      

					<main class="main-content">
						<div class="row mb-4">
							<div class="col">
								<h1 class="h3 mb-2 text-gray-800">Gestionar Pagos de los trabajadores</h1>
							</div>
						</div>

						<div class="container-fluid p-0">
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" id="nav-facturas_culminadas-tab" data-toggle="tab" href="#nav-facturas_culminadas" role="tab" aria-controls="nav-facturas_culminadas" aria-selected="true">Pagos Culminados</a>
									<a class="nav-item nav-link" id="nav-facturas_pendientes-tab" data-toggle="tab" href="#nav-facturas_pendientes" role="tab" aria-controls="nav-facturas_pendientes" aria-selected="false">Pagos Pendientes</a>
								</div>
							</nav>
						</div>

						<div class="container-fluid p-0">
								
							<div class="tab-content" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-facturas_culminadas" role="tabpanel" aria-labelledby="nav-facturas_culminadas-tab">
									<div class="row mb-4">
										<div class="col">
											<h1 class="h3 mb-2 text-gray-800">Pagos Culminados</h1>
										</div>
									</div>
									<div class="col d-flex justify-content-end align-items-center">
										<div>
											<button class="btn btn-primary d-none" id="btn_notificar">Notificar Pagos</button>
										</div>
									</div>
									<div class="m-auto">
										<div class="container">
											<table class="table table-bordered table-hover table-middle table-responsive-md scroll-bar-style" id="table_facturas_culminadas">
												<thead class="bg-primary text-light">
													<th>Cedula</th>
													<th>Nombre</th>
													<th>Fecha</th>
													<th>sueldo total</th>
													<th>Acción</th>
												</thead>
											
												<tbody id="tbody_facturas_culminadas">
													
												</tbody>
												
											</table>
										</div>
									</div>
								</div>


								<div class="tab-pane fade" id="nav-facturas_pendientes" role="tabpanel" aria-labelledby="nav-facturas_pendientes-tab">
									<div class="col">
										<h1 class="h3 mb-2 text-gray-800">Pagos Pendientes</h1>
									</div>
									<div class="col d-flex justify-content-end align-items-center">
										<div>
											<button class="btn btn-primary" id="btn_calcular_facturas">Calcular Facturas</button>
											<button class="btn btn-primary hide-on-disable" id="btn_txt">Descargar TXT</button>
											<button class="btn btn-primary hide-on-disable" id="btn_concluir_factura">Concluir Factura</button>

											<button id="btn_prueba_bd_calc" class="d-none">probar formula desde la bd</button>
										</div>
									</div>
									<div class="m-auto">
										<div class="container">
											<table class="table table-bordered table-hover table-middle table-responsive-md scroll-bar-style" id="table_facturas_pendientes">
												<thead class="bg-primary text-light">
													<th>Cedula</th>
													<th>Nombre</th>
													<th>Fecha</th>
													<th>sueldo total</th>
													<th>Acción</th>
												</thead>
											
												<tbody id="tbody_facturas_pendientes">
													
												</tbody>
												
											</table>
										</div>
									</div>
								</div>

							</div>
						</div>
						
					</main>

					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>


	<div class="modal fade" tabindex="-1" role="dialog" id="modal_detalles_factura_culminada">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Detalles de Factura Nº<span id="facturas_id_info"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">
					<div class="row pt-3 px-4">
						<div class="flex-shrink-1">
							<span class="d-block m-0">Cedula:</span>
							<span class="d-block m-0">Nombre:</span>
							<span class="d-block m-0">Tipo de Personal:</span>
							<span class="d-block m-0">Fecha de ingreso:</span>
							<span class="d-block m-0">Quincena:</span>
						</div>
						<div class="col col-md-4">
							<span class="d-block m-0"><strong id="datos-1">27250544</strong></span>
							<span class="d-block m-0"><strong id="datos-2">Xavier Suarez</strong></span>
							<span class="d-block m-0"><strong id="datos-3">Contratado</strong></span>
							<span class="d-block m-0"><strong id="datos-4">2004/02/01</strong></span>
							<span class="d-block m-0"><strong id="datos-quincena" class="text-monospace">I</strong></span>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<style>
								#table_detalles_factura tbody tr:nth-last-child(-n+3) td:first-child{
									border-bottom: none;
									border-top: none;
									text-align: right;
								}
								#table_detalles_factura tbody tr:nth-last-child(4) td:first-child{
									border-bottom: none;
									text-align: right;
								}

								#table_detalles_factura tr td:last-child{
									text-align: right;
								}
								#table_detalles_factura tr td:last-child::after{
									content: ' Bs';
								}


							</style>
							<table class="table table-bordered border-dark table-middle" id="table_detalles_factura">
								<thead class="bg-primary text-light">
									<th>descripción</th>
									<th style="width: 15ch">Monto</th>

								</thead>
							
								<tbody id="tbody_detalles_factura">
									
								</tbody>
								
							</table>
						</div>
					</div>
					<div class="row justify-content-end mb-2 d-none" style="padding-right: 12px">
						<div class="text-right flex-shrink-1 ml-3 border-left" >
							<p class="m-0 font-weight-bold border-bottom px-2" id="datos-5">Sueldo Base</p>
							<p class="m-0 font-weight-bold border-bottom px-2" id="datos-6">Sueldo Integral</p>
							<p class="m-0 font-weight-bold border-bottom px-2" id="datos-7">Deducciones</p>
							<p class="m-0 font-weight-bold border-bottom px-2" id="datos-8">Total a pagar</p>
						</div>
						<div class="flex-shrink-1 text-right border-right" >
							<p class="m-0 border-bottom px-2">Sueldo Base</p>
							<p class="m-0 border-bottom px-2">Sueldo Integral</p>
							<p class="m-0 border-bottom px-2">Deducciones</p>
							<p class="m-0 border-bottom px-2">Total a pagar</p>
						</div>
					</div>
					
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_calcular">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Calcular Facturas</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">
					<form action="" id="f2" onsubmit="return false">
						<div class="row">
							<div class="col">
								<label for="calcular_anio">Año</label>
								<input required type="number" max="9999" min="1900" class="form-control" id="calcular_anio" name="calcular_anio" data-span="invalid-span-calcular_anio">
								<span id="invalid-span-calcular_anio" class="invalid-span text-danger"></span>
							</div>
							<div class="col">
								<label for="calcular_mes">Mes</label>
								<select required name="calcular_mes" id="calcular_mes" class="form-control">
									<option value="">Seleccione</option>
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
								<span id="invalid-span-calcular_mes" class="invalid-span text-danger"></span>
							</div>
						</div>
						<div class="row my-2">
							<div class="col"><span>Quincena: </span><span id="info_quincena" class="text-monospace"></span></div>
						</div>
						<div class="row my-2">
							<div class="col-12 text-center">
								<button class="btn btn-primary" type="submit">Calcular</button>
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


<!-- 	<div class="loader_body_msg">
		<div class="loader_body_msg-head">Enviando Correos</div>
		<div class="loader_body_msg-body" >
			<span id="loader_body_msg-from">1</span><span id="loader_body_msg-to">/5  Correos</span>

			<div class="loader_body_msg-bar" style="--barWait:20%"></div>

		</div>

	</div> -->


	<script>
		document.getElementById('btn_concluir_factura').onclick=function(){
			muestraMensaje("¿Seguro?", "¿Culminar el proceso de la factura?, Una vez concluidas enviara a los trabajadores un correo con los detalles de las facturas", "?",function(result){
				if(result){
					var datos = new FormData();
					datos.append("accion","concluir_facturas");
					var ajax = enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "concluir_facturas"){

							muestraMensaje("Éxito", "Las facturas han sido concluidas exitosamente", "s");
							load_facturas();

							
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

					console.log(ajax);

					ajax.p.catch((a,b)=>{
						console.log(a);
						console.log(b);
						return "timeout";
					})
				}
			});

		}
		document.getElementById('btn_txt').onclick=function(){
			var datos = new FormData();
			datos.append("accion","imprimir_txt");
			$.ajax({
				url: "",
				method: "POST",
				data: {"accion":"imprimir_txt"},
				responseType: 'blob',
				success: function(data) {
					console.log("data", data);

					var blob = new Blob([data], {type: 'text/plain'});
					                var link = document.createElement('a');
					                link.href = URL.createObjectURL(blob);
					                link.download = 'archivo.txt';
					                link.click();
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
						muestraMensaje("Error", "Error al descargar el archivo", "error");
						console.error(request + status + err)
					}
					fail();
				}
			});
		};



		eventoKeyup("calcular_anio", /^[0-9]{4}$/, "El año no es valida",undefined,check_quincena);
		eventoKeyup("calcular_mes", /^[0-9]{1,2}$/, "El mes no es valida",undefined,check_quincena);
		document.getElementById('calcular_mes').onchange=document.getElementById('calcular_anio').onchange=check_quincena;




		document.getElementById('f2').onsubmit=function(e){
			e.preventDefault();
			if(this.sending==true){
				return false;
			}
			console.log("document.querySelector(\"#tbody_facturas_pendientes>tr:first-child>td:nth-child(2)\")", document.querySelector("#tbody_facturas_pendientes>tr:first-child>td:nth-child(2)"));
			if(document.querySelector("#tbody_facturas_pendientes>tr:first-child>td:nth-child(2)")){
				console.log("hola");
				var mensaje = "Aun hay facturas sin culminar esto sobrescribirá las facturas sin culminar, ¿Desea Continuar?  ";
				var icono = "w";
			}
			else{
				console.log("hola3");
				var mensaje = "Desea Calcular las facturas de los trabajadores";
				var icono = "?";
			}










			muestraMensaje("¿Seguro?", mensaje, icono,function(result){
				if(result){
					
					var datos = new FormData($("#f2")[0]);
					datos.append("accion","calcular_facturas");
					enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "calcular_facturas"){
							load_facturas();
							$("#modal_calcular").modal("hide");
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
						exito();
					},'loader_body').p.finally(()=>{
						document.getElementById('f2').sending = undefined;
					});

				}
			});
		};

		document.getElementById('btn_calcular_facturas').onclick=function(){
			muestraMensaje("¿Seguro?", "Desea Calcular las facturas de los trabajadores", "?",function(result){
				if(result){
					fecha = new Date();

					document.getElementById('calcular_anio').value=fecha.getFullYear();
					document.getElementById('calcular_mes').value=fecha.getMonth();
					document.getElementById('calcular_mes').onchange().then(()=>{

						$("#modal_calcular").modal("show");
					});


				}
			});
		};


		document.getElementById('btn_notificar').onclick=function(){
			notificar_pagos();
		};


		function notificar_pagos(from_main=0,to_main=0){

			var datos = new FormData();
			datos.append("accion","notificar_pagos");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "notificar_pagos"){

					if(lee.mensaje=='complete'){
						loader_body_msg_correos(false);
						load_facturas();
						exito(true);
						muestraMensaje("Advertencia", "Los correos están desactivados temporalmente", "s");
						console.error("correos desactivados");
					}
					else{
						exito({to:lee.mensaje.to});
					}

				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje(lee.titulo, lee.mensaje,"error");
					fail(lee.resultado);
				}
				else if(lee.resultado == "error"){

					if(/SMTP Error/.test(lee.mensaje)){
						muestraMensaje(lee.titulo, "Error de conexión al servidor","error");
					}
					else{
						muestraMensaje(lee.titulo, lee.mensaje,"error");
					}
					console.error(lee.mensaje);
					fail(lee.resultado);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
					fail(lee.resultado);
				}
				else{
					muestraMensaje(lee.titulo, lee.mensaje,"error");
					fail(lee.resultado);
				}
			},()=>{
				loader_body_msg_correos(from_main,to_main);
			}).p.then((x)=>{
				if(x!==true){
					if(to_main==0){
						to_main=x.to;
					}
					from_main = parseInt(from_main);
					from_main++;
					notificar_pagos(from_main,parseInt(to_main));
				}
			}).catch((x)=>{
				console.log("XD",x);
				loader_body_msg_correos(false);
			});

		}

		function loader_body_msg_correos(from=0,to=0,sms="Enviando Correos"){
			if(!document.body.querySelector(".loader_body_msg") && from !== false){
				from = parseInt(from);
				to = parseInt(to);
				var loader = crearElem("div","class,loader_body_msg");
				loader.appendChild(crearElem("div","class,loader_body_msg-head",sms));

				var body = crearElem("div",'class,loader_body_msg-body');
				console.log(from);
				if(from==0 && to == 0){
					body.appendChild(crearElem("span","id,loader_body_msg-from"));
					body.appendChild(crearElem("span","id,loader_body_msg-to"));
				}
				else{
					body.appendChild(crearElem("span","id,loader_body_msg-from",""+from+""));
					body.appendChild(crearElem("span","id,loader_body_msg-to","/"+to+" Correos"));	
				}
				var porcentaje = "0%";
				if(to>0){
					porcentaje = (from * 100) / to;
					porcentaje = porcentaje+"%";
				}

				body.appendChild(crearElem("span",`class,loader_body_msg-bar,style,--barWait:${porcentaje};`,'<div></div>'));
				loader.appendChild(body);
				document.body.appendChild(loader);
			}
			else if(document.body.querySelector(".loader_body_msg")){
				if(from===false){
					document.body.removeChild(document.body.querySelector(".loader_body_msg"));
				}
				else{
					from = parseInt(from);
					to = parseInt(to);
					var porcentaje = (from * 100) / to;
					porcentaje = porcentaje+"%";
					document.getElementById('loader_body_msg-from').innerHTML=from;
					document.getElementById('loader_body_msg-to').innerHTML=`/${to} Correos`;

					document.body.querySelector(".loader_body_msg-bar").style=`--barWait:${porcentaje}`;
				}
			}
		}




		load_facturas();


		rowsEventActions("tbody_facturas_culminadas" ,function(action,rowId,btn){
			if(action=='detalles'){
				
				var datos = new FormData();
				datos.append("accion","detalles_factura");
				datos.append("id",rowId);
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "detalles_factura"){
						cargar_detalles_facturas_c(lee.detalles,lee.factura);
						$("#modal_detalles_factura_culminada").modal("show");
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
		rowsEventActions("tbody_facturas_pendientes" ,function(action,rowId,btn){
			if(action=='detalles'){
				
				var datos = new FormData();
				datos.append("accion","detalles_factura");
				datos.append("id",rowId);
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "detalles_factura"){
						cargar_detalles_facturas_c(lee.detalles,lee.factura);
						$("#modal_detalles_factura_culminada").modal("show");
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

		function cargar_detalles_facturas_c(detalles,factura){

			data_table_detalles("table_detalles_factura",detalles,factura);
			
			document.getElementById('datos-1').innerHTML = factura.cedula;
			document.getElementById('datos-2').innerHTML = factura.nombre;
			document.getElementById('datos-3').innerHTML = factura.tipo_nomina;
			document.getElementById('datos-4').innerHTML = factura.fecha;
			document.getElementById('datos-quincena').innerHTML = (factura.quincena=='1')?'I':'II';
			document.getElementById('facturas_id_info').innerHTML = factura.id_factura;
			document.getElementById('datos-5').innerHTML = factura.sueldo_base;
			document.getElementById('datos-6').innerHTML = factura.suma_integral;
			document.getElementById('datos-7').innerHTML = factura.sueldo_deducido;
			document.getElementById('datos-8').innerHTML = factura.sueldo_total;
		}


		function data_table_detalles(table,data,extra = ''){
			if(extra != ''){
				data.push({descripcion: "<b>Sueldo Base</b>", monto: extra.sueldo_base})
				data.push({descripcion: "<b>Sueldo Bruto</b>", monto: extra.suma_integral})
				data.push({descripcion: "<b>Deducciones</b>", monto: extra.sueldo_deducido})
				data.push({descripcion: "<b>Total A Pagar</b>", monto: extra.sueldo_total})
			}

			if ($.fn.DataTable.isDataTable(`#${table}`)) {
				$(`#${table}`).DataTable().destroy();
			}
			
			$(`#${table} tbody`).html("");
			
			if (!$.fn.DataTable.isDataTable(`#${table}`)) {
				$(`#${table}`).DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de detalles",
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
					],
					data:data,
					//createdRow: function(row,data){row.querySelector("td:nth-child(1)").innerText;},
					autoWidth: false,
					searching:false,
					info: false,
					ordering: false,
					paging: false
					//order: [[1, "asc"]], // vacio para que no ordene al principio
					
				});
			}
		}
		


		function load_facturas(){
			var datos = new FormData();
			datos.append("accion","load_facturas");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_facturas"){
					cargar_facturas(lee.mensaje.activo,lee.mensaje.inactivo);
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

		function cargar_facturas(lista1='',lista2=''){
			if(lista1 == ''){
				lista1 = [];
			}
			if(lista2 == ''){
				lista2 = [];
			}


				if ($.fn.DataTable.isDataTable("#table_facturas_culminadas")) {
					$("#table_facturas_culminadas").DataTable().destroy();
				}
				
				$("#tbody_facturas_culminadas").html("");
				var found_rows_culminadas = false;
				var found_rows_culminadas_no_notificadas = false;
				
				if (!$.fn.DataTable.isDataTable("#table_facturas_culminadas")) {
					$("#table_facturas_culminadas").DataTable({
						language: {
							lengthMenu: "Mostrar _MENU_ por página",
							zeroRecords: "No se encontraron registros de facturas",
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
						,{data:"nombre"}
						,{data:"fecha"}
						,{data:"sueldo_total"}
						,{data:"extra"}
						],
						data:lista1,
						createdRow: function(row,data){
							// row.querySelector("td:nth-child(1)").innerText;
							found_rows_culminadas = true;


							if(found_rows_culminadas_no_notificadas==false && (typeof data.notificado!== "undefined" && data.notificado == '0')){
								found_rows_culminadas_no_notificadas = data.notificado;
							}

							row.dataset.id = data.id_factura;

							row.querySelector("td:nth-child(1)").classList.add("text-center","align-middle","text-nowrap");
							row.querySelector("td:nth-child(2)").classList.add("text-center","align-middle","text-nowrap");
							row.querySelector("td:nth-child(3)").classList.add("text-center","align-middle","text-nowrap");
							row.querySelector("td:nth-child(4)").classList.add("text-center","align-middle","text-nowrap");

							var acciones = row.querySelector("td:nth-child(5)");
							acciones.innerHTML = '';
							var btn = crearElem("button", "class,btn btn-info,data-action,detalles", "<span>Detalles</span> <span class='bi bi-card-list' title='Mostrar Detalles'></span>")
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

				if ($.fn.DataTable.isDataTable("#table_facturas_pendientes")) {
					$("#table_facturas_pendientes").DataTable().destroy();
				}

				if(found_rows_culminadas_no_notificadas){
					document.getElementById('btn_notificar').classList.remove("d-none");
					document.getElementById('btn_notificar').disabled = !found_rows_culminadas_no_notificadas;
				}
				else{
					document.getElementById('btn_notificar').classList.add("d-none");
					document.getElementById('btn_notificar').disabled = !found_rows_culminadas_no_notificadas;	
				}
				
				$("#tbody_facturas_pendientes").html("");

				var found_rows_pendientes = false;
				
				if (!$.fn.DataTable.isDataTable("#table_facturas_pendientes")) {
					$("#table_facturas_pendientes").DataTable({
						language: {
							lengthMenu: "Mostrar _MENU_ por página",
							zeroRecords: "No se encontraron registros de facturas",
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
						,{data:"nombre"}
						,{data:"fecha"}
						,{data:"sueldo_total"}
						,{data:"extra"}
						],
						data:lista2,
						createdRow: function(row,data){
							found_rows_pendientes = true;
						// row.querySelector("td:nth-child(1)").innerText;
						row.dataset.id = data.id_factura;

						row.querySelector("td:nth-child(1)").classList.add("text-center","align-middle","text-nowrap");
						row.querySelector("td:nth-child(2)").classList.add("text-center","align-middle","text-nowrap");
						row.querySelector("td:nth-child(3)").classList.add("text-center","align-middle","text-nowrap");
						row.querySelector("td:nth-child(4)").classList.add("text-center","align-middle","text-nowrap");

						var acciones = row.querySelector("td:nth-child(5)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-info,data-action,detalles", "<span>Detalles</span> <span class='bi bi-card-list' title='Mostrar Detalles'></span>")
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

				document.getElementById('btn_txt').disabled = !found_rows_pendientes;
				document.getElementById('btn_concluir_factura').disabled = !found_rows_pendientes;
			

		}


		document.getElementById('btn_prueba_bd_calc').onclick=function(){
			var datos = new FormData();
			datos.append("accion","probar_formula_bd");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "probar_formula_bd"){

					console.log(lee);
					
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
		};


		function check_quincena(){

			return new Promise(function(check_quincena_exito,check_quincena_fail){

			

				var resp1 = /^[0-9]{4}$/.test(document.getElementById("calcular_anio").value);
				var resp2 = /^[0-9]{1,2}$/.test(document.getElementById("calcular_mes").value);

				if(resp1 && resp2){
					var datos = new FormData();
					datos.append("accion","check_quincena");
					datos.append("anio",document.getElementById('calcular_anio').value);
					datos.append("mes",document.getElementById('calcular_mes').value);
					enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "check_quincena"){

							if(lee.mensaje=='1' ){
								lee.mensaje = 'I';

							}
							else if(lee.mensaje =='2'){
								lee.mensaje = 'II';
							}

							document.getElementById('info_quincena').innerHTML=lee.mensaje;

							if(lee.mensaje == "Mensualidad Pagada"){
								document.getElementById('f2').sending = true;
								document.getElementById('f2').querySelector("button[type='submit']").disabled=true;

							}
							else{
								document.getElementById('f2').sending = undefined;
								document.getElementById('f2').querySelector("button[type='submit']").disabled=false;
							}
							
						}
						else if (lee.resultado == 'is-invalid'){
							// muestraMensaje(lee.titulo, lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "error"){
							// muestraMensaje(lee.titulo, lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "console"){
							console.log(lee.mensaje);
						}
						exito();
					},"loader_body").p.finally(()=>{
						check_quincena_exito();
					});
				}
				else{
					check_quincena_exito();
				}


			});

			
		}


		
	</script>

	

	<script src="assets/js/sb-admin-2.min.js"></script>

	
</body>
</html>
