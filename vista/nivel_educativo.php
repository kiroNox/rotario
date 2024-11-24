<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Nivel Educativo - Servicio Desconcentrado Hospital Rotario</title>
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
							<div class="col" data-step="1" data-intro="Aquí puede gestionar el nivel educativo que se puede asignar a los trabajadores">
								<h1 class="h3 mb-2 text-gray-800">Nivel Educativo</h1>
								<p>Nivel Educativo de los trabajadores y la prima correspondiente</p>
							</div>
							<div class="col d-flex justify-content-end align-items-center">
								<div>
									<button data-step="2" data-intro="Para registrar un nuevo nivel educativo debe pulsar este boton" class="btn btn-primary" data-toggle="modal" data-target="#modal_registrar_nivel_educativo">Registrar Nivel Educativo</button>
								</div>
							</div>
						</div>




						<table data-step="4" data-intro="Aquí puede ver la lista de todos los niveles educativos registrados" class="table table-bordered table-hover table-middle" id="table_nivel_profesional">
							<thead class="bg-primary text-light">
								<th>Nivel</th>
								<!-- <th>Monto</th> -->
								<th>Acción</th>
							</thead>
							<tbody data-step="5" data-intro="Si posee los permisos podrá modificar/eliminar los niveles registrados" id="tbody_nivel_profesional" class="text-center">
								
							</tbody>
							
						</table>
						
					</main>
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_registrar_nivel_educativo">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content" data-step="3" data-intro="Solo necesita ingresar el nombre del nuevo nivel educativo y pulsar registrar">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Nivel - Educación</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">

					<form action="" id="f1" method="POST" onsubmit="return false">
						<input type="hidden" class="d-none" id="nivel_id" name="id">
						<div class="row">
							<div class="col-12">
								<label for="nivel_descripcion">Descripción</label>
								<input required type="text" class="form-control" id="nivel_descripcion" name="nivel_descripcion" data-span="invalid-span-nivel_descripcion">
								<span id="invalid-span-nivel_descripcion" class="invalid-span text-danger"></span>
							</div>
						</div>
						<div class="row my-3">
							<div class="col text-center">
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

	<script src="vendor/intro.js-7.2.0/package/minified/intro.min.js"></script>
	<script src="assets/js/comun/introConfig.js"></script>
	<script>
		load_niveles();




		eventoKeyup("nivel_descripcion",/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,45}$/,"La descripción tiene caracteres inválidos solo se permiten letras");
		document.getElementById('nivel_descripcion').maxLength = 45;


		rowsEventActions("tbody_nivel_profesional" ,function(action,rowId,btn){
			if(action=='modificar'){

				muestraMensaje("¿Seguro?", "Desea modificar el nivel educativo seleccionado", "?", function(result){
					if(result){
						var datos = new FormData();
						datos.append("accion","get_nivel_educativo");
						datos.append("id",rowId);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "get_nivel_educativo"){

								document.getElementById('nivel_id').value = lee.mensaje.id_prima_profesionalismo;
								document.getElementById('nivel_descripcion').value = lee.mensaje.descripcion;
								//document.getElementById('nivel_monto').value = sepMilesMonto(lee.mensaje.incremento);

								document.querySelector("#f1 button[type='submit']").innerHTML='Modificar';

								$("#modal_registrar_nivel_educativo").modal("show");
								
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
			else if(action == 'eliminar'){
				muestraMensaje("¿Seguro?", "Desea eliminar el nivel educativo seleccionado", "w", function(result){
					if(result){
						var datos = new FormData();
						datos.append("accion","eliminar_nivel_educativo");
						datos.append("id",rowId);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "eliminar_nivel_educativo"){
								muestraMensaje("Éxito", "El nivel educativo fue eliminado exitosamente", "s");
								cargar_niveles(lee.mensaje);
								
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


		$('#modal_registrar_nivel_educativo').on('hidden.bs.modal', function (e) {
			document.getElementById('nivel_id').value = '';
			document.getElementById('nivel_descripcion').value = '';

			document.querySelector("#f1 button[type='submit']").innerHTML='Registrar';
		})


		document.getElementById('f1').onsubmit=function(e){
			e.preventDefault();

			if(document.getElementById('nivel_descripcion').classList.contains('is-invalid')){
				return false;
			}

			document.querySelector("#f1 button[type='submit']").focus();
			document.querySelector("#f1 button[type='submit']").blur();

			var datos = new FormData($("#f1")[0]);
			if(document.getElementById('nivel_id').value==''){
				datos.append("accion","registrar_nivel_educativo");
			}else{
				datos.append("accion","modificar_nivel_educativo");
			}
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "registrar_nivel_educativo" || lee.resultado == "modificar_nivel_educativo"){
					muestraMensaje(lee.titulo, lee.mensaje, "s");
					cargar_niveles(lee.lista);

					$("#modal_registrar_nivel_educativo").modal("hide");
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
			},"loader_body");



		};


		Intro.setOption("disableInteraction",true);
		Intro.setOption("buttonClass","hide-prevButtom introjs-button");
		Intro.onexit(()=>{$("#modal_registrar_nivel_educativo").modal("hide");})
		console.log(Intro,"intro");
		Intro.onbeforechange(async (elem)=>{
			if(elem){
				if(elem.dataset.step==3){
					$("#modal_registrar_nivel_educativo").modal("show");
	  				await new Promise(resolve => setTimeout(resolve, 400));

				}
				else if(elem.dataset.step==4){
					$("#modal_registrar_nivel_educativo").modal("hide");
	  				await new Promise(resolve => setTimeout(resolve, 400));
				}
			}
		})
		Intro.start();
		


		function load_niveles() {
			var datos = new FormData();
			datos.append("accion","load_niveles");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_niveles"){


					lee.mensaje.forEach(function(el){
						el.incremento = sepMilesMonto(el.incremento)+' %';
					});


					cargar_niveles(lee.mensaje);
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

		function cargar_niveles(jsonObj){
			if ($.fn.DataTable.isDataTable("#table_nivel_profesional")) {
				$("#table_nivel_profesional").DataTable().destroy();
			}
			
			$("#tbody_nivel_profesional").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_nivel_profesional")) {
				$("#table_nivel_profesional").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de nivel de educación",
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
						//,{data:"incremento"}
						,{data:"extra"}
					],
					data:jsonObj,
					createdRow: function(row,data){

						row.dataset.id = data.id_prima_profesionalismo;
						
						var acciones = row.querySelector("td:nth-child(2)");
						acciones.innerHTML = '';
						var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Nivel Educativo'></span>")
						acciones.appendChild(btn);
						btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Educativo'></span>")
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




		
	</script>




	<script src="assets/js/sb-admin-2.min.js"></script>

	
</body>
</html>
