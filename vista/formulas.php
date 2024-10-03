<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Gestionar Formulas - Servicio Desconcentrado Hospital Rotario</title>
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
								<div class="d-flex justify-content-center flex-column align-items-center d-md-block">
									<h3 class="mx-md-3 text-capitalize">Gestionar Formulas</h3>
									<p class="text-center text-md-left">Aqui puede gestionar las formulas que no están relacionadas con las primas o deducciones</p>
								</div>
							</div>
							<div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
								<button class="btn btn-primary mx-md-3" id="btn_open_modal" data-target="#formulas_modal" data-toggle="modal">Registrar Formula</button>
							</div>
						</div>

						<div class="container">
							<table class="table table-bordered table-hover table-middle" id="table_formulas">
								<thead>
									<th>Nombre</th>
									<th>Descripción</th>
									<th>Acciones</th>
								</thead>
							
								<tbody id="tbody_formulas">
									
								</tbody>
								
							</table>
						</div>
						
						
					</main>


					<div class="modal fade" tabindex="-1" role="dialog" id="formulas_modal">
						<div class="modal-dialog modal-xl" role="document">
							<div class="modal-content">
								<div class="modal-header text-light bg-primary">
									<h5 class="modal-title">Formula</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="container">
									<form id="f1" action="" method="POST" onsubmit="return false"> 
										<input type="hidden" id="id_formula" name="id_formula">
										<?php require_once "vista/calculadora-form.php" ?>
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
	<script src="assets/js/sb-admin-2.min.js"></script>
	<script src="assets/js/comun/calculadora.js"></script>
	<script>
		load_calc_functions();
		load_list_formulas();
		document.getElementById('save-form-btn-1').innerHTML="Registrar Formula";




		rowsEventActions("tbody_formulas" ,function(action,rowId,btn){
			if(action=='modificar'){
				muestraMensaje("¿Seguro?", "¿Esta seguro de querer modificar la formula seleccionada?", "?", function(result){
					if(result){

						var datos = new FormData();
						datos.append("accion","get_formula");
						datos.append("id",rowId);
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "get_formula"){
								document.getElementById('id_formula').value = lee.id_formula;

								var modal = document.getElementById('formulas_modal');

								//modal.load_calc_formulario(false).then(()=>{

									load_formulas_form(lee.mensaje.calc_formula);

									document.getElementById('f1').tested_form=true;
									document.getElementById('save-form-btn-1').innerHTML="Modificar Formula";
									$(modal).modal("show");
								//})
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

				muestraMensaje("¿Seguro?", "¿Esta seguro de querer eliminar la formula seleccionada?", "?", function(result){
					if(result){
						var datos = new FormData();
						datos.append("accion","eliminar_formula");
						datos.append("id",rowId)
						enviaAjax(datos,function(respuesta, exito, fail){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "eliminar_formula"){


								load_list_formulas().then(()=>{
									muestraMensaje("Éxito", "La formula ha sido eliminada exitosamente", "s");
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
						});

					}
				});

			}
		
		});


		$('#formulas_modal').on('hidden.bs.modal', function (e) {
			reset_calc_form(document.getElementById('f1'));
			document.getElementById('id_formula').value='';
			document.getElementById('save-form-btn-1').innerHTML="Registrar Formula";
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




			var datos = new FormData(this);

			datos = calc_formData_maker(datos,this);
			if(datos===false){
				this.action_form='testing_calc';
				return false;
			}


			if(this.action_form=='testing_calc'){
				datos.set("accion","test_formula");
			}
			else if(this.action_form == 'save_calc'){
				if(document.getElementById('id_formula').value!=''){
					datos.append("accion","modificar_formula");
				}
				else{
					datos.append("accion","registrar_formula");
				}
			}

			this.action_form = 'testing_calc';


			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "registrar_formula" || lee.resultado == "modificar_formula"){


					load_list_formulas("loader_body").then(()=>{
						muestraMensaje("Exito", lee.mensaje, 's');
						reset_calc_form(document.getElementById('f1'));
						document.getElementById('id_formula').value='';
						$("#formulas_modal").modal("hide");
					})

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





		};
		




		function load_list_formulas(loader){
			return new Promise((resolve,rejected)=>{

				var datos = new FormData();
				datos.append("accion","load_list_formulas");
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "load_list_formulas"){


						if ($.fn.DataTable.isDataTable("#table_formulas")) {
							$("#table_formulas").DataTable().destroy();
						}
						
						$("#tbody_formulas").html("");
						
						if (!$.fn.DataTable.isDataTable("#table_formulas")) {
							$("#table_formulas").DataTable({
								language: {
									lengthMenu: "Mostrar _MENU_ por página",
									zeroRecords: "No se encontraron registros de formulas",
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
									{data:"nombre"}
									,{data:"descripcion"}
									,{data:"extra"}
								],
								data:lee.mensaje,
								createdRow: function(row,data){
									console.log(data);
									row.dataset.id = data.id_formula;

									var acciones = row.querySelector("td:nth-child(3)");
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
					resolve();
				},loader).p.catch((e)=>{
					rejected();
				});

			});
		}
	</script>

	
</body>
</html>
