<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Hijos - Servicio Desconcentrado Hospital Rotario</title>
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
							<div class="col" data-intro="Aquí podrá gestionar los hijos de los trabajadores registrados" data-step="1">
								<h1 class="h3 mb-2 text-gray-800">Hijos</h1>
								<p>Hijos de los trabajadores</p>
							</div>
							<div class="col d-flex justify-content-end align-items-center">
								<div>
									<button class="btn btn-primary" data-toggle="modal" data-target="#modal_registar_hijos" data-intro="Aqui podra registrar nuevos hijos para los trabajadores ya registrados" data-step="2">Registrar Hijos</button>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card-header">Lista de Hijos</div>
							<div class="card-body">
								<div class="table-responsive">
									
									<table data-intro="Aquí se puede ver la lista de hijos registrados" data-step="5" class="table table-bordered scroll-bar-style table-hover" id="tabla_hijos">
										<thead class="bg-primary text-light">
											<tr>
												<th>Nombre</th>
												<th>Nacimiento</th>
												<th>Madre</th>
												<th>Padre</th>
												<th>Genero</th>
												<th>Discapacidad</th>
												<th>Observación</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody class="table-cell-aling-middle" id="tbody_hijos" data-intro="si tiene los permisos podrá modificarlos/eliminarlos" data-step="6">
											<tr>
												<td colspan="8" class="text-center"> Cargando </td>
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

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_registar_hijos">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title" data-step="3" data-intro="Aquí podra registrar nuevos hijos">Registrar Hijo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">

					<form action="" method="POST" onsubmit="return false" id="f1">
						<input type="hidden" name="id" id="form_id_hijo">
						<div class="row" data-intro="Debe ingresar al menos un tutor" data-step="4">
							<div class="col-6">
								<div>
									<label for="madre_cedula">Madre (cedula)</label>
									<input type="text" class="form-control" id="madre_cedula" name="madre_cedula" data-span="invalid-span-madre_cedula" placeholder="cedula" data-paretnname = 'nombre-madre'>
									<span id="invalid-span-madre_cedula" class="invalid-span text-danger"></span>
								</div>
								<div><span id="nombre-madre" class="text-center d-block font-weight-bold m-2"></span></div>
							</div>
							<div class="col-6">
								<div>
									<label for="padre_cedula">Padre (cedula)</label>
									<input type="text" class="form-control" id="padre_cedula" name="padre_cedula" data-span="invalid-span-padre_cedula" placeholder="Cedula" data-paretnname = 'nombre-padre'>
									<span id="invalid-span-padre_cedula" class="invalid-span text-danger"></span>
								</div>
								<div><span id="nombre-padre" class="text-center d-block font-weight-bold m-2"></span></div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-4">
								<label for="nombre">Nombre del Niño(a)</label>
								<input type="text" class="form-control" id="nombre" name="nombre" data-span="invalid-span-nombre">
								<span id="invalid-span-nombre" class="invalid-span text-danger"></span>
							</div>
							<div class="col-12 col-md-4">
								<label for="fecha_nacimiento">Fecha de Nacimiento</label>
								<input required type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" data-span="invalid-span-fecha_nacimiento">
								<span id="invalid-span-fecha_nacimiento" class="invalid-span text-danger"></span>
							</div>
							<div class="col-12 col-md-4">
								<label for="genero">Genero</label>
								<select required id="genero" name="genero" data-span="invalid-span-genero" class="custom-select">
									<option value="">-Seleccione-</option>
									<option value="F">Femenino</option>
									<option value="M">Masculino</option>
								</select>
								<span id="invalid-span-genero" class="invalid-span text-danger"></span>
							</div>
							<div class="col-12 col-md-6 d-flex align-items-center py-2">
								<input type="checkbox" id="discapacitado" class="cursor-pointer check-button" name="discapacitado" data-span="invalid-span-discapacitado">
								<label for="discapacitado" class="check-button mr-2"></label>
								<label for="discapacitado" class="d-block cursor-pointer m-0">Discapacitado</label>
								<span id="invalid-span-discapacitado" class="invalid-span text-danger"></span>
							</div>
							<div class="col-12">
								<label for="observacion">Observación</label>
								<input type="text" class="form-control" id="observacion" name="observacion" data-span="invalid-span-observacion" maxlength="100">
								<span id="invalid-span-observacion" class="invalid-span text-danger"></span>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col text-center">
								
								<input type="submit" class="btn btn-primary" id="submit_btn" value="Registrar">
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
	<script>
		$('#modal_registar_hijos').on('hidden.bs.modal', function (e) {
			$("#f1 input:not(input[type='checkbox']):not(input[type='submit']), #f1 select").each(function(index, el) {
				el.value = '';
				if(el.id != 'form_id_hijo' ){
					validarKeyUp(true, el.id);
					el.classList.remove("is-valid");
				}
			});;
			document.querySelector("#f1 input[type='checkbox']").checked = false;
			document.getElementById('nombre-padre').innerHTML = '';
			document.getElementById('nombre-madre').innerHTML = '';
			document.getElementById('submit_btn').value = 'Registrar';
			document.getElementById('submit_btn').classList.remove('text-dark');
			document.getElementById('submit_btn').classList.replace("btn-warning", "btn-primary");

			document.querySelector("#modal_registar_hijos h5.modal-title").innerHTML = 'Registrar Hijo';
		})

		load_lista_hijos();


		cedulaKeypress(document.getElementById('madre_cedula'));
		cedulaKeypress(document.getElementById('padre_cedula'));

		document.getElementById('madre_cedula').allow_empty = true;
		document.getElementById('padre_cedula').allow_empty = true;
		eventoKeyup("madre_cedula",V.expCedula,"La cedula es invalida ej. V-00000001",undefined,()=>{document.getElementById('nombre-madre').innerHTML='';},valid_parent);
		eventoKeyup("padre_cedula",V.expCedula,"La cedula es invalida ej. V-00000001",undefined,()=>{document.getElementById('nombre-padre').innerHTML='';},valid_parent);
		eventoFecha("fecha_nacimiento", "La fecha de nacimiento es invalida");

		eventoKeyup("nombre", V.expTexto(50), "El nombre no es valido");
		eventoKeyup("observacion", V.alfanumerico(100), "El texto no es valido");


		document.getElementById('f1').onsubmit=function(e){
			e.preventDefault();
			if(document.getElementById('f1').value != ''){
				if(this.sending){
					return false;
				}
				if(document.getElementById('submit_btn').value == 'Registrar'){
					var mensaje = "Seguro que desea registrar nuevo hijo de trabajador"
				}
				else if (document.getElementById('submit_btn').value == 'Modificar'){
					var mensaje = "Seguro que desea modificar hijo de trabajador"
				}
				else {
					alert("error");
					return false;
				}

				muestraMensaje("¿Seguro?", mensaje, "?",(resp)=>{
					if(resp){

						var datos = new FormData($("#f1")[0]);
						if($("#padre_cedula").val() == '' && $("#madre_cedula").val() == ''){
							muestraMensaje("Invalido", "Debe registrar al menos una cedula de un padre/madre", "e");
							return false;
						}

						if(document.getElementById('submit_btn').value == 'Registrar'){
							datos.append("accion","registrar_hijo");
						}
						else if (document.getElementById('submit_btn').value == 'Modificar'){
							datos.append("accion","modificar_hijo");
						}

						this.sending = true;
						document.getElementById('submit_btn').disabled = true;
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "registrar_hijo"){
								muestraMensaje("Registro Exitoso", "El hijo ha sido registrado exitosamente", "s");
								$("#modal_registar_hijos").modal("hide");

							}
							else if (lee.resultado == 'modificar_hijo'){
								muestraMensaje("Modificación Exitosa", "El hijo ha sido modificado exitosamente", "s");
								$("#modal_registar_hijos").modal("hide");

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
							load_lista_hijos();
							document.getElementById('f1').sending = undefined;
							document.getElementById('submit_btn').disabled = false;
						},"loader_body").p.catch((a)=>{
							load_lista_hijos();
							document.getElementById('f1').sending = undefined;
							document.getElementById('submit_btn').disabled = false;
						});
					}
				});
			}
			else{
				muestraMensaje("Error", "La acción no se puede completar", "s");
			}
		};


		rowsEvent("tbody_hijos",(target,cell)=>{
			if(!cell.parentNode.dataset.id){
				return false;
			}
		 	//console.log(row.dataset.id);
		 	if(cell.classList.contains("cell-action")){
		 		while(target.tagName!='BUTTON'&&target.tagName!=cell.tagName){
		 			count++;
		 			if(count>100)
		 			{
		 				console.error('se paso el while');
		 				return false;
		 				break
		 			}
		 			target=target.parentNode;
		 		}
		 		if(target.tagName == "BUTTON"){
		 			if(target.dataset.action == "eliminar"){ // ELIMINAR

		 				muestraMensaje("¿Seguro", "Esta seguro de querer eliminar al hijo", "?",(resp)=>{
		 					if(resp){
		 						target.disabled = true;
		 						target.blur();



		 						var datos = new FormData();
		 						datos.append("accion","eliminar_hijo");
		 						datos.append("id",cell.parentNode.dataset.id);

		 						enviaAjax(datos,function(respuesta, exito, fail){
		 						
		 							var lee = JSON.parse(respuesta);
		 							if(lee.resultado == "eliminar_hijo"){
		 								muestraMensaje("Eliminación Exitosa", "El hijo ha sido eliminado exitosamente", "s");
		 								
		 								load_lista_hijos();

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
		 						}).p.catch((a)=>{
		 							load_lista_hijos();
		 						});



		 					}
		 				});

		 			}
		 			else if (target.dataset.action == 'modificar'){ // MODIFICAR
						var datos = new FormData();
						datos.append("accion","get_hijo");
						datos.append("id",cell.parentNode.dataset.id);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "get_hijo"){

								document.getElementById('form_id_hijo').value = lee.mensaje.id_hijo;
								document.getElementById('madre_cedula').value = lee.mensaje.cedulaMadre;
								document.getElementById('padre_cedula').value = lee.mensaje.cedulaPadre;
								document.getElementById('nombre').value = lee.mensaje.nombre;
								document.getElementById('fecha_nacimiento').value = lee.mensaje.fecha_nacimiento;
								document.getElementById('genero').value = lee.mensaje.genero;
								document.getElementById('discapacitado').checked = (lee.mensaje.discapacidad == '1')?true:false;
								document.getElementById('observacion').value = lee.mensaje.observacion;
								document.getElementById('nombre-padre').innerHTML = lee.mensaje.nombrePadre;
								document.getElementById('nombre-madre').innerHTML = lee.mensaje.nombreMadre;

								document.getElementById('submit_btn').value = 'Modificar';
								document.getElementById('submit_btn').classList.replace("btn-primary", "btn-warning");
								document.getElementById('submit_btn').classList.add("text-dark");

								document.querySelector("#modal_registar_hijos h5.modal-title").innerHTML = 'Modificar Hijo';


								


								$("#modal_registar_hijos").modal("show");
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
		 		}
		 	}
		},false);

		
		Intro.setOption("disableInteraction",true);
		Intro.setOption("buttonClass","hide-prevButtom introjs-button");
		Intro.onexit(()=>{$("#modal_registar_hijos").modal("hide");})
		console.log(Intro,"intro");
		Intro.onbeforechange(async (elem)=>{
			if(elem){
				if(elem.dataset.step==3){
					$("#modal_registar_hijos").modal("show");
	  				await new Promise(resolve => setTimeout(resolve, 400));

				}
				else if(elem.dataset.step==5){
					$("#modal_registar_hijos").modal("hide");
	  				await new Promise(resolve => setTimeout(resolve, 400));
				}
			}
		})
		Intro.start();



	// Funciones ************************************************
		function valid_parent(etiqueta,valid)
		{
			if(etiqueta.xhr!= undefined){
				etiqueta.xhr.abort();
			}
			if(valid){

				if($("#madre_cedula").val() == $("#padre_cedula").val() && $("#madre_cedula").val() != ""){
					validarKeyUp(false, etiqueta, "No puede duplicar la cedula");
					return false;
				}

				if(etiqueta.value == ''){
					return false;
				}

				

				var datos = new FormData();
				datos.append("accion","valid_cedula_parent");
				datos.append('cedula',etiqueta.value);

				
				document.getElementById(etiqueta.dataset.paretnname).innerText = '';

				var ajax = enviaAjax(datos,function(respuesta, exito, fail){
					
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "valid_parent"){

						document.getElementById(etiqueta.dataset.paretnname).innerText = lee.mensaje;
						
					}
					else if (lee.resultado == 'no_existe'){
						validarKeyUp(false, etiqueta, "La cedula del trabajador no existe");
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
					etiqueta.xhr = undefined; 
				},'loader_body').xhr;

				etiqueta.xhr = ajax; 
			}
			


		}




		function load_lista_hijos(){
			var datos = new FormData();
			datos.append("accion","listar_hijos");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "listar_hijos"){
					if ($.fn.DataTable.isDataTable("#tabla_hijos")) {
						$("#tabla_hijos").DataTable().destroy();
					}
					
					$("#tbody_hijos").html("");
					
					if (!$.fn.DataTable.isDataTable("#tabla_hijos")) {
						$("#tabla_hijos").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de hijos",
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
								{data:"nombreHijo"},
								{data:"fecha_nacimiento"},
								{data:"cedulaMadre"},
								{data:"cedulaPadre"},
								{data:"genero"},
								{data:"discapacidad"},
								{data:"observacion"},
								{data:"extra"}
							],

							data:lee.mensaje,
							createdRow: function(row,data){
								// row.dataset.id = data[8];
								row.dataset.id = data.id;
								row.querySelector("td:nth-child(3)").innerHTML = '';
								row.querySelector("td:nth-child(4)").innerHTML = '';
								row.querySelector("td:nth-child(5)").classList.add("text-center");
								row.querySelector("td:nth-child(6)").classList.add("text-center");
								row.querySelector("td:nth-child(6)").innerHTML = (data.discapacidad == '1')? "Si" : "No" ;
								row.querySelector("td:nth-child(7)").title = data.observacion;
								row.querySelector("td:nth-child(7)").classList.add("text-truncate");
								row.querySelector("td:nth-child(7)").setAttribute("style","max-width: 20ch");;
								row.querySelector("td:nth-child(3)").appendChild(crearElem("span",'class,text-nowrap',data.cedulaMadre));
								row.querySelector("td:nth-child(3)").appendChild(crearElem("small",'class,d-block text-center no-select',data.nombreMadre));
								row.querySelector("td:nth-child(4)").appendChild(crearElem("span",'class,text-nowrap',data.cedulaPadre || ''));
								row.querySelector("td:nth-child(4)").appendChild(crearElem("small",'class,d-block text-center no-select',data.nombrePadre || ''));


								var acciones = row.querySelector("td:nth-child(8)");
								acciones.innerHTML = '';
								var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar'></span>")
								acciones.appendChild(btn);
								btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar'></span>")
								acciones.appendChild(btn);
								acciones.classList.add('text-nowrap','cell-action');
								
							},
							autoWidth: false
							
						});
						if(lee.mensaje.length>0){
							$("#tabla_hijos")[0].parentNode.classList.add("table-responsive");
						}
					}







					
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













	</script>
	<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>

	
