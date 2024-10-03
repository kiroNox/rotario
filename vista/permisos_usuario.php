<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once 'assets/comun/head.php'; ?>
	<title>Permisos de Usuario - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top" class="<?= $modo_oscuro ?>">
	<div id="wrapper">
		<?php   require_once("assets/comun/menu.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<?php   require_once("assets/comun/navar.php"); ?>
				<div class="container-fluid">

					<main class="main-content">
						<div class="row mb-5 mt-3">
							<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
								<h3 class="mx-md-3 text-capitalize">Roles y permisos</h3>
							</div>
							<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
								<button class="btn btn-primary mx-md-3" id="btn_open_modal">Registrar Rol</button>
							</div>
						</div>




						<div class="container-fluid">
							<div class="container m-auto" style="max-width: 500px">
								<table class="table table-hover table-bordered table-middle" id="tabla_roles">
									<thead class="bg-primary text-light">
										<tr>
											<th>Rol</th>
											<th>Nº de usuarios</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody id="tbody_roles">
										
									</tbody>
								</table>
							</div>

							
						</div>
					</main>

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
				<div class="container mb-3">
					<div class="row">
						<input type="hidden" id="id_rol">
						<div class="col-12 col-md-6">
							<label for="rol_name">Rol</label>
							<input type="text" class="form-control" id="rol_name" name="rol_name" data-span="invalid-span-rol_name">
							<span id="invalid-span-rol_name" class="invalid-span text-danger"></span>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="table-responsive">
						<table class="table table-sm table-striped table-hover table-middle" id="tabla_permisos">
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
				<div class="container text-center mb-5">
					<button class="btn btn-primary" id="btn_rol_reg">Registrar Rol</button>

					<script>
						
					</script>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		add_event_to_label_checkbox();

		load_lista_roles();

		eventoKeyup("rol_name", V.expTexto(50), "El nombre del rol no es valido solo se permiten letras en el nombre",undefined,function(elem){
			elem.changed=true;
		});


		rowsEventActions("tbody_roles" ,function(action,rowId,btn){
			if(action=='modificar'){
				muestraMensaje("¿Seguro?", "Desea modificar el rol seleccionado", "?",function(resp){
					if(resp){
						load_lista_modulos(rowId,true);
					}
				});
			}
			else if(action == "eliminar" ){
				muestraMensaje("¿Seguro?", "Desea eliminar el rol seleccionado", "w",function(resp){
					if(resp){
						//load_lista_modulos(rowId,true);
						var datos = new FormData();
						datos.append("accion","eliminar_rol");
						datos.append("id",rowId);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "eliminar_roles"){

								load_lista_roles().then(()=>{
									muestraMensaje("Exito", "El Rol fue eliminado exitosamente", "s");
								});
								
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
		










		$('#modal_permisos').on('hidden.bs.modal', function (e) {
			document.getElementById('tbody_permisos').innerHTML="";
			document.getElementById('id_rol').value='';
			document.getElementById('rol_name').value='';
			document.getElementById('rol_name').changed=false;
			document.getElementById('btn_rol_reg').sending =false ;
		})

		document.getElementById('btn_rol_reg').onclick=function(){
			if(this.sending === true ){
				return false;
			}
			this.sending = true;
			if(document.getElementById('id_rol').value!=''){//modificar
				lista_checkbox = document.getElementById('tbody_permisos').querySelectorAll("tr>td:first-child+td input[type='checkbox']");

				obj_checkbox = [];



				for(x of lista_checkbox){

					if(result = x.get_obj_json(true)){
						obj_checkbox.push(result);
					}
				}




				console.log(obj_checkbox);

				
				

				if(obj_checkbox.length>0 || document.getElementById('rol_name').changed === true){
					var datos = new FormData();
					datos.append("accion","modificar_roles");
					datos.append("id",document.getElementById('id_rol').value);
					datos.append("nombre", document.getElementById('rol_name').value);
					datos.append("permisos", JSON.stringify(obj_checkbox));



					if(document.getElementById('rol_name').changed===false){
						datos.delete("nombre");
					}
					else{
						datos.clean("nombre");
					}
					datos.clean("id");

					if(document.getElementById('rol_name').validarme()){
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "modificar_roles"){

								load_lista_roles("loader_body").then(()=>{
									muestraMensaje("Exito", "El Rol fue modificado exitosamente", "s");
									$("#modal_permisos").modal("hide");
								})
								.catch((e)=>{
									$("#modal_permisos").modal("hide");
								})

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
							document.getElementById('btn_rol_reg').sending = false;
						});

					}
					else{
						this.sending = false;
					}

				}
				else{
					muestraMensaje("Exito", "El rol ha sido modificado exitosamente", "s");
					$("#modal_permisos").modal("hide");
					this.sending = false;
				}




			}
			else{ //registrar
				lista_checkbox = document.getElementById('tbody_permisos').querySelectorAll("tr>td:first-child+td input[type='checkbox']");

				obj_checkbox = [];

				for(x of lista_checkbox){
					obj_checkbox.push(x.get_obj_json());
				}

				if(document.getElementById('rol_name').validarme()){

					var datos =new FormData() ;

					datos.append("accion", "registrar_rol");
					datos.append("nombre", document.getElementById('rol_name').value);
					datos.append("permisos", JSON.stringify(obj_checkbox));
					datos.clean("nombre");


					enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "registrar_roles"){
							load_lista_roles("loader_body").then(()=>{
								muestraMensaje("Exito", "El Rol fue registrado exitosamente", "s");
								$("#modal_permisos").modal("hide");
							})
							.catch((e)=>{
								$("#modal_permisos").modal("hide");
							})
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
					},"loader_body").p.finally(()=>{
						document.getElementById('btn_rol_reg').sending = false;
					});




				}
				else{
					this.sending = false;
				}

				
			}
		};

		document.getElementById('btn_open_modal').onclick=function(){
			var datos = new FormData();
			datos.append("accion","listar_modulos_roles");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "listar_modulos_roles"){

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
									if(typeof data.id_modulos !== 'undefined'){
										checkbox.dataset.id_modulo = data.id_modulos;
									}
									if(typeof data.id_rol !== "undefined"){
										checkbox.dataset.id_rol = data.id_rol;
									}
									checkbox.id = `permiso-check-${id}`;
									cambiar_permiso(checkbox);
									x.appendChild(checkbox);
									x.appendChild(crearElem("label",`class,check-button,for,permiso-check-${id}`));
									i++;
									id++;
								}

							},
							searching:false,
							info: false,
							ordering: false,
							autoWidth: false,
							paging: false
							//order: [[1, "asc"]],
							
						});

						add_event_to_label_checkbox();
						document.getElementById('rol_name').value = "";
						document.getElementById('id_rol').value = "";
					}

					document.getElementById('btn_rol_reg').innerHTML=="Registrar Rol";

					$("#modal_permisos").modal("show");
					
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
		
		function load_lista_roles(loader=undefined){

			return new Promise((resolve,fail)=>{

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
									{data:"rol"}
									,{data:"usuarios"}
									,{data:"extra"}
								],

								createdRow: function(row,data){
									row.dataset.id = data.id;

									var acciones = row.querySelector("td:nth-child(3)");
									acciones.innerHTML = '';
									var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar Prima'></span>")
									acciones.appendChild(btn);
									btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Prima'></span>")
									acciones.appendChild(btn);
									acciones.classList.add('text-nowrap','cell-action','text-center');
									acciones.style="width:1%;";
								},
								info: false,
								autoWidth: false
								//searching:false,
								//ordering: false,
								//paging: false
								//order: [[1, "asc"]],
								
							});
						}
						resolve();
					}
					else if (lee.resultado == 'is-invalid'){
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						fail();
					}
					else if(lee.resultado == "error"){
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						fail();
						console.error(lee.mensaje);
					}
					else if(lee.resultado == "console"){
						console.log(lee.mensaje);
					}
					else{
						muestraMensaje(lee.titulo, lee.mensaje,"error");
						fail();
					}
				},loader);
			});
		}


		

		function load_lista_modulos(id_rol, show_modal = false){
			var datos = new FormData();
			datos.append("accion","listar_modulos");
			datos.append("rol",id_rol);

			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "listar_modulos"){
					//console.log(lee);


					


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
									if(typeof data.id_modulos !== 'undefined'){
										checkbox.dataset.id_modulo = data.id_modulos;
										checkbox.dataset.id_rol = data.id_rol;
									}
									checkbox.id = `permiso-check-${id}`;
									cambiar_permiso(checkbox);
									x.appendChild(checkbox);
									x.appendChild(crearElem("label",`class,check-button,for,permiso-check-${id}`));
									
									i++;
									id++;
								}

							},
							searching:false,
							info: false,
							ordering: false,
							autoWidth: false,
							paging: false
							//order: [[1, "asc"]],
							
						});
						add_event_to_label_checkbox();

						document.getElementById('rol_name').value = lee.rol.descripcion;
						document.getElementById('id_rol').value = lee.rol.id_rol;
					}

					if(show_modal){
						$("#modal_permisos").modal("show");
						document.getElementById('btn_rol_reg').innerHTML="Modificar Rol";
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
			check.changed = false;
			check.onchange=function(){
				if(typeof this.modificado_to === 'undefined'){
					this.modificado_to = this.checked;
				}
				if(this.checked == this.modificado_to){
					this.changed = true;
				}
				else{
					this.changed = false;
				}
				console.log(this.modificado_to);

			}

			check.get_obj_json=function(only_changed = false){
				

					var check = this;
					var list = this.parentNode.parentNode.querySelectorAll("input[type='checkbox']");
					var datos = new FormData();
					var obj ={};
					var check = {};
					var changed=false;
					for (y of list){
						check[y.name] = (y.checked)?true:false;
						if(y.changed===true){
							changed=true;
						}
					}

					if(only_changed=== false || (only_changed === true && changed===true)){
						obj.check = check;
					}
					else{
						obj.check = {};
						return false;
					}
					var rol = list[0].dataset.id_rol || null;
					var modulo = list[0].dataset.id_modulo || null;

					if(rol){obj.rol = rol; }
					if(modulo){obj.modulo = modulo; }


					
					 return JSON.stringify(obj);

				// datos.append("rol",list[0].dataset.id_rol);
				// datos.append("modulo",list[0].dataset.id_modulo);
				// datos.append("datos",JSON.stringify(obj));


				// datos.append("accion","cambiar_permiso");
				// enviaAjax(datos,function(respuesta, exito, fail){
				
				// 	var lee = JSON.parse(respuesta);
				// 	if(lee.resultado == "cambiar_permiso"){
				// 		//load_lista_modulos(rol,false);
				// 	}
				// 	else if (lee.resultado == 'is-invalid'){
				// 		muestraMensaje(lee.titulo, lee.mensaje,"error");
				// 		check.checked = (check.checked)?false:true;
				// 	}
				// 	else if(lee.resultado == "error"){
				// 		muestraMensaje(lee.titulo, lee.mensaje,"error");
				// 		console.error(lee.mensaje);
				// 	}
				// 	else if(lee.resultado == "console"){
				// 		console.log(lee.mensaje);
				// 	}
				// 	else{
				// 		muestraMensaje(lee.titulo, lee.mensaje,"error");
				// 	}
				// });
			}
		}
	</script>
	<script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>