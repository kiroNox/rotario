
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require_once 'assets/comun/head.php'; ?>
	<title>usuarios</title>
</head>
<body>
	<div class="container">
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-registrar_usuario-tab" data-toggle="tab" href="#nav-registrar_usuario" role="tab" aria-controls="nav-registrar_usuario" aria-selected="true">Registrar</a>
				<a class="nav-item nav-link" id="nav-consultar_usuarios-tab" data-toggle="tab" href="#nav-consultar_usuarios" role="tab" aria-controls="nav-consultar_usuarios" aria-selected="false">listar</a>
			</div>
		</nav>
	</div>
	<div class="container">
			
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-registrar_usuario" role="tabpanel" aria-labelledby="nav-registrar_usuario-tab">
				<div style="max-width: 500px" class="m-auto">
					<div class="container text-center">
						<form action="" method="POST" onsubmit="return false" id="f1">
							<label for="cedula">Cedula</label>
							<input type="text" class="form-control" id="cedula" name="cedula" data-span="invalid-span-cedula">
							<span id="invalid-span-cedula" class="invalid-span text-danger"></span>

							<div class="container pl-0 pr-0" id="fields">
									<label class="d-block" for="nombre">Nombre</label>
									<input required type="text" class="form-control" id="nombre" name="nombre" data-span="invalid-span-nombre">
									<span id="invalid-span-nombre" class="invalid-span text-danger"></span>

									<label class="d-block" for="apellido">Apellido</label>
									<input required type="text" class="form-control" id="apellido" name="apellido" data-span="invalid-span-apellido">
									<span id="invalid-span-apellido" class="invalid-span text-danger"></span>

									<label class="d-block" for="telefono">Teléfono</label>
									<input type="text" class="form-control" id="telefono" name="telefono" data-span="invalid-span-telefono">
									<span id="invalid-span-telefono" class="invalid-span text-danger"></span>
									<label class="d-block" for="correo">Correo</label>
									<input required type="email" class="form-control" id="correo" name="correo" data-span="invalid-span-correo">
									<span id="invalid-span-correo" class="invalid-span text-danger"></span>

									<label for="rol">Rol</label>
									<select required class="form-control" id="rol" name="rol" data-span="invalid-span-rol">
										<option value="">Seleccione un rol</option>
									</select>
									<span id="invalid-span-rol" class="invalid-span text-danger"></span>

									<label class="d-block" for="pass">Clave</label>
									<div class="show-password-container">
										<input required type="password" class="form-control" id="pass" name="pass" data-span="invalid-span-pass">
										<span class="show-password-btn" data-inputpass="pass" aria-label="show password button"></span>
									</div>
									<span id="invalid-span-pass" class="invalid-span text-danger"></span>
									<div class="text-center mt-4">
										<button type="submit">Registrar</button>
									</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="nav-consultar_usuarios" role="tabpanel" aria-labelledby="nav-consultar_usuarios-tab">
				<table class="table table-dark table-bordered table-hover" id="tabla_usuarios">
					<thead>
						<tr>
							<th>id</th>
							<th>Cedula</th>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Telefono</th>
							<th>Correo</th>
							<th>Rol</th>
						</tr>
					</thead>
					<tbody id="tbody_usuarios">
						
					</tbody>
				</table>
			</div>

		</div>
	</div>

	

	<script type="text/javascript">
		// registar *************************************
			iniciar_show_password();
			show_fields(false);
			cedulaKeypress(document.getElementById('cedula'));
			eventoKeyup("cedula",V.expCedula,"La cedula es invalida ej. V-00000001",undefined,undefined,(etiqueta,valid)=>{
				if(etiqueta.xhr!= undefined){
					etiqueta.xhr.abort();
				}
				if(valid){

					var datos = new FormData();
					datos.append("accion","valid_cedula");
					datos.append(etiqueta.name,etiqueta.value);

					var ajax = enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "valid_cedula"){
							if(lee.mensaje == '1'){
								show_fields(false);
								validarKeyUp(false, "cedula", "La cedula ya existe");
							}
							else{
								show_fields();
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
						etiqueta.xhr = undefined; 
					}).xhr;

					etiqueta.xhr = ajax; 
				}
				else{
					show_fields(false);
				}


			});
			eventoKeyup("nombre", V.expTexto(50), "El nombre no es valido");
			eventoKeyup("apellido", V.expTexto(50), "El apellido no es valido");
			eventoKeyup("telefono", V.expTelefono, "El teléfono es invalido", undefined, (elem)=>{
				elem.value = elem.value.replace(/^([0-9]{4})\D*([0-9]{1,7})/, "$1-$2");
			});
			document.getElementById('telefono').allow_empty = true;
			eventoKeyup("correo", V.expEmail, "El correo es invalido");
			eventoPass("pass");

			load_roles();
		// listar ***************************************


		



		document.getElementById('f1').onsubmit = function(e) {
			e.preventDefault();

			muestraMensaje("Seguro?", "", "?", (resp)=>{


				$("#f1 input").each((i,elem)=>{
					if(!elem.validarme()){
						return false;
					}
				});

				var datos = new FormData($("#f1")[0]);
				datos.append("accion","registrar");
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "registrar"){

						muestraMensaje("Exito", "Usuario nuevo registrado", "s");
						
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

		function load_roles(){
			var datos = new FormData();
			datos.append("accion","get_roles");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "get_roles"){
					for(x of lee.mensaje){
						document.getElementById('rol').appendChild(crearElem('option',`value,${x.id}`,x.rol));
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

		function show_fields (control = true){
			if(control){
				document.getElementById('fields').classList.remove("d-none");
				var list = document.querySelectorAll("#fields input, #fields select, #fields button");
				for (x of list){
					x.disabled = false;
				}
			}
			else{
				document.getElementById('fields').classList.add("d-none");
				var list = document.querySelectorAll("#fields input, #fields select, #fields button");
				for (x of list){
					x.disabled = true;
				}	
			}
		}

		function load_lista_usuarios(){
			if ($.fn.DataTable.isDataTable("#tabla_usuarios")) {
				$("#tabla_usuarios").DataTable().destroy();
			}
			
			$("#tbody_usuarios").html("");
			
			if (!$.fn.DataTable.isDataTable("#tabla_usuarios")) {
				$("#tabla_usuarios").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de Usuarios",
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
					//columns:[{data:"columnJSON"}],
					//data:DATOS_JSON,
					//createdRow: function(row,data){row.querySelector("td:nth-child(1)").innerText;},
					autoWidth: false
					//searching:false,
					//info: false,
					//ordering: false,
					//paging: false,
					//order: [[1, "asc"]],
					
				});
			}
		}
		

	</script>
</body>
</html>