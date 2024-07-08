<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
	<title>Deducciones - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
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
								<button class="btn btn-primary mx-md-3">Registrar Deducciones</button>
							</div>
						</div>

						

						<table class="table table-bordered table-hover table-responsive-xl scroll-bar-style" id="table_deducciones">
							<thead>
								<th>Descripción</th>
								<th>Monto</th>
								<th>Tiempo</th>
								<th>Exclusivos de médicos</th>
								<th>ISLR</th>
								<th>trabajadores</th>
								<th>Acción</th>
							</thead>
						
							<tbody id="tbody_deducciones">
								
							</tbody>
							
						</table>
						

					</main>


					<script>

						load_deducciones();

						function load_deducciones() {
							var datos = new FormData();
							datos.append("accion","load_deducciones");
							enviaAjax(datos,function(respuesta, exito, fail){
							
								var lee = JSON.parse(respuesta);
								if(lee.resultado == "load_deducciones"){
									

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
												{data:"monto"},
												{data:"tiempo"},
												{data:"medic_only"},
												{data:"islr_temp"},
												{data:"dedic"},
												{data:"extra"},
											],
											data:lee.mensaje,
											createdRow: function(row,data){
												// row.querySelector("td:nth-child(1)").innerText;


												var acciones = row.querySelector("td:nth-child(7)");
												acciones.innerHTML = '';
												var btn = crearElem("button", "class,btn btn-warning,data-action,asignar", "<span class='bi bi-pencil-square' title='Asignar Sueldo'></span>")
												acciones.appendChild(btn);
												btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Sueldo'></span>")
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
					

					
				</div>                                                                                     
		<?php   require_once("assets/comun/footer.php"); ?>
			</div>
		</div>
	</div>

	
</body>
</html>
