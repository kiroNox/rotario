<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once 'assets/comun/head.php'; ?>
	<title>Permisos de Usuario - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Permisos de Usuario</h1>
					</div>
					<div class="container-fluid">
						<div class="container m-auto" style="max-width: 500px">
							<table class="table table-hover table-bordered row-cursor-pointer" id="tabla_roles">
								<thead class="bg-primary text-light">
									<tr>
										<th>Rol</th>
										<th>Nº de usuarios</th>
									</tr>
								</thead>
								<tbody id="tbody_roles">
									
								</tbody>
							</table>
						</div>

						
					</div>
				</div>
			</div>
			<?php   require_once("assets/comun/footer.php"); ?>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_permisos">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Permisos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<style type="text/css">
					#tabla_permisos input[type="checkbox"]{
						transform: scale(1.4);
					}
				</style>
				<div class="container">
					<div class="table-responsive">
						<table class="table table-sm table-striped table-hover rows-cursor-pointer" id="tabla_permisos">
							<thead class="thead-dark">
								<tr>
									<th>Modulo</th>
									<th class="text-center" style="width: 72px" >Consultar</th>
									<th class="text-center" style="width: 72px" >Crear</th>
									<th class="text-center" style="width: 72px" >Modificar</th>
									<th class="text-center" style="width: 72px" >Eliminar</th>
								</tr>
							</thead>
							<tbody id="tbody_permisos">
	
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		add_event_to_label_checkbox();

		load_lista_roles();

		rowsEvent("tbody_roles",(row)=>{
			load_lista_modulos(row.dataset.id,true);
		 	//console.log(row.dataset.id);
		});
		
		function load_lista_roles(){
			var datos = new FormData();
			datos.append("accion","listar_roles");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "listar_roles"){

					if ($.fn.DataTable.isDataTable("#tabla_roles")) {
						$("#tabla_roles").DataTable().destroy();
					}
					
					$("#tbody_roles").html("");
					
					if (!$.fn.DataTable.isDataTable("#tabla_roles")) {
						$("#tabla_roles").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de usuarios",
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
							data:lee.mensaje,
							columns:[
								{data:"rol"},
								{data:"usuarios"}
							],

							createdRow: function(row,data){
								row.dataset.id = data.id;
							},
							info: false,
							autoWidth: false
							//searching:false,
							//ordering: false,
							//paging: false
							//order: [[1, "asc"]],
							
						});
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

		function load_lista_modulos(id_rol, show_modal = false){
			var datos = new FormData();
			datos.append("accion","listar_modulos");
			datos.append("rol",id_rol);

			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "listar_modulos"){
					console.log(lee);
					


					if ($.fn.DataTable.isDataTable("#tabla_permisos")) {
						$("#tabla_permisos").DataTable().destroy();
					}
					
					$("#tbody_permisos").html("");
					
					if (!$.fn.DataTable.isDataTable("#tabla_permisos")) {
						$("#tabla_permisos").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de Permisos",
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
							data:lee.mensaje,
							columns:[
							{data:"modulo"},
							{data:"consultar"},
							{data:"crear"},
							{data:"modificar"},
							{data:"eliminar"}
							],
							createdRow: function(row,data,index){
								index++;
								id = (index * 4) - 4;
								id++;

								var list = row.querySelectorAll("td:nth-child(1)~td");

								var control = ["consultar","crear","modificar","eliminar"];
								i = 0;
								for(x of list){
									
									var temp = x.innerText;
									x.innerHTML = '';
									x.classList.add("text-center");
									var checkbox = crearElem("input",'type,checkbox');
									checkbox.classList.add("check-button");
									if(temp == '1'){ checkbox.checked = true;}
									else {checkbox.checked = false;}
									checkbox.name = control[i];
									checkbox.dataset.id_modulo = data.id_modulos;
									checkbox.dataset.id_rol = data.id_rol;
									checkbox.id = `permiso-check-${id}`;
									cambiar_permiso(checkbox);
									x.appendChild(checkbox);
									x.appendChild(crearElem("label",`class,check-button,for,permiso-check-${id}`))
									i++;
									id++;
								}

							},
							//searching:false,
							info: false,
							ordering: false,
							autoWidth: false
							//paging: false
							//order: [[1, "asc"]],
							
						});
					}

					if(show_modal){
						$("#modal_permisos").modal("show");
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

		function cambiar_permiso(check){
			check.onchange=function(){
				var check = this;
				var list = this.parentNode.parentNode.querySelectorAll("input[type='checkbox']");
				var datos = new FormData();
				var obj ={}
				for (y of list){
					obj[y.name] = (y.checked)?true:false;
				}
				var rol = list[0].dataset.id_rol;
				datos.append("rol",list[0].dataset.id_rol);
				datos.append("modulo",list[0].dataset.id_modulo);
				datos.append("datos",JSON.stringify(obj));


				datos.append("accion","cambiar_permiso");
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "cambiar_permiso"){
						//load_lista_modulos(rol,false);
					}
					else if (lee.resultado == 'is-invalid'){
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						check.checked = (check.checked)?false:true;
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
	</script>
</body>

</html>