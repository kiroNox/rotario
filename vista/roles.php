<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require_once 'assets/comun/head.php'; ?>
		<title>Roles</title>
	</head>
	<body id="page-top">
		<div id="wrapper">
			<?php   require_once("assets/comun/menu.php"); ?>
			<div id="content-wrapper" class="d-flex flex-column">
				<div id="content">
			<?php   require_once("assets/comun/navar.php"); ?>
					<div class="container-fluid">

						<div class="d-sm-flex align-items-center justify-content-between mb-4">
							<h1 class="h3 mb-0 text-gray-800">Roles</h1>
						</div>
						<div class="container-fluid text-right mb-3">
							<button class="btn btn-primary" id="add_rol"> + Agregar Rol</button>
						</div>
						<div class="container m-auto" style="max-width: 500px">
							<table class="table table-hover table-bordered row-cursor-pointer" id="tabla_roles">
								<thead class="thead-dark">
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
			<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>



		<div class="modal fade" tabindex="-1" role="dialog" id="modal_roles">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header text-light bg-primary">
						<h5 class="modal-title" id="modal_title">Agregar Rol</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="container">
						<div id="add_rol_content" class="container-fluid d-none">
							<form action="" method="POST" id="f1" onsubmit="return false" style="max-width: 500px" class="m-auto">
								<label for="Rol">Nombre del Rol</label>
								<input type="text" class="form-control" id="Rol" name="Rol" data-span="invalid-span-Rol">
								<span id="invalid-span-Rol" class="invalid-span text-danger"></span>
								<div class="container text-center mt-3">
									<button type="submit" class="btn btn-success">Registrar</button>
								</div>
							</form>
						</div>
						<div id="modif_rol" class="container-fluid d-none">
							<form action="" method="POST" id="f2" onsubmit="return false">
								<input type="hidden" name="id" id="hidden_rol_id">
								<label for="Rol">Nombre del Rol</label>
								<input type="text" class="form-control" id="Rol_modif" name="Rol" data-span="invalid-span-Rol">
								<span id="invalid-span-Rol" class="invalid-span text-danger"></span>
								<div class="container text-center mt-4">
									<button type="submit" class="btn btn-warning text-dark mr-5">Modificar</button><button type="button" id="btn_eliminar" class="btn btn-danger">Eliminar</button>
								</div>
							</form>
						</div>
					</div>
					<div class="modal-footer bg-light">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			//TODO Validaciones

			rowsEvent("tbody_roles",(row)=>{
			 	console.log(row.dataset.id);
			 	document.getElementById('hidden_rol_id').value = row.dataset.id;
			 	document.getElementById('Rol_modif').value = row.querySelector("td:nth-child(1)").innerText;

			 	document.getElementById('modif_rol').classList.remove("d-none");
			 	document.getElementById('add_rol_content').classList.add("d-none");

			 	$("#modal_roles").modal("show");



			});


			document.getElementById('add_rol').onclick=()=>{
				document.getElementById('add_rol_content').classList.remove("d-none");
				document.getElementById('modif_rol').classList.add("d-none");
				$("#modal_roles").modal("show");
			};

			$('#modal_roles').on('hidden.bs.modal', function () {
				document.getElementById('add_rol_content').classList.add("d-none");
				document.getElementById('modif_rol').classList.add("d-none");

				$("#f1 input, #f2 input").each((i,elem)=>{
					elem.value = '';
					elem.classList.remove("is-invalid", "is-valid");
				});
				// $("#f1_modificar input, #f1_modificar select").each((i,elem)=>{
				// 	elem.value = '';
				// });
			})

			load_lista_roles();

			document.getElementById('f1').onsubmit = function(e) {
				e.preventDefault();// TODO validaciones

				muestraMensaje("Seguro?", "", "?", (resp)=>{
					if(resp){
						
						// $("#f1 input").each((i,elem)=>{
						// 	if(!elem.validarme()){
						// 		return false;
						// 	}
						// });

						var datos = new FormData($("#f1")[0]);
						datos.append("accion","registrar_roles");
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "registrar_roles"){

								muestraMensaje("Exito", "nuevo rol registrado", "s");
								$("#f1 input, #f1 select").each((i,elem)=>{
									elem.value = '';
									elem.classList.remove("is-invalid", "is-valid");
								});
								$("#modal_roles").modal("hide");
								load_lista_roles();
								
								
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

			document.getElementById('f2').onsubmit = function(e) {
				e.preventDefault();// TODO validaciones

				muestraMensaje("Seguro?", "", "?", (resp)=>{
					if(resp){
						
						// $("#f1 input").each((i,elem)=>{
						// 	if(!elem.validarme()){
						// 		return false;
						// 	}
						// });

						var datos = new FormData($("#f2")[0]);
						datos.append("accion","modificar_roles");
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "modificar_roles"){

								muestraMensaje("Exito", "El rol se ha modificado exitosamente", "s");
								
								$("#modal_roles").modal("hide");

								load_lista_roles();

								
								
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

			document.getElementById('btn_eliminar').onclick=function(){
				if(document.getElementById('hidden_rol_id').value != ''){
					if(this.sending){
						return false;
					}
					//TODO validar

					muestraMensaje("Seguro?", "Seguro que desea eliminar el usuarios", "?",(resp)=>{
						var datos = new FormData();
						datos.append("accion","eliminar_roles");
						datos.append("id",document.getElementById('hidden_rol_id').value);


						this.sending = true;
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "eliminar_roles"){
								muestraMensaje("Eliminación Exitosa", "El rol ha sido eliminado exitosamente", "s");
								
								load_lista_roles();

								$("#modal_roles").modal("hide");
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
							document.getElementById('btn_eliminar').sending=undefined;
						}).p.catch((a)=>{
							document.getElementById('btn_eliminar').sending=undefined;
						});
					});
				}
				else{
					muestraMensaje("Error", "La acción no se puede completar", "s");
				}
			};

			function load_lista_roles(){
				var datos = new FormData();
				datos.append("accion","listar_roles");
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "listar_roles"){
						console.log(lee.mensaje);

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

		</script>
	</body>
</html>