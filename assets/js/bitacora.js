load_bitacora();
function load_bitacora() {
	var datos = new FormData();
	datos.append("accion","load_bitacora");
	enviaAjax(datos,function(respuesta, exito, fail){
	
		var lee = JSON.parse(respuesta);
		if(lee.resultado == "load_bitacora"){

			if ($.fn.DataTable.isDataTable("#table_bitacora")) {
				$("#table_bitacora").DataTable().destroy();
			}
			
			$("#tbody_bitacora").html("");
			
			if (!$.fn.DataTable.isDataTable("#table_bitacora")) {
				$("#table_bitacora").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de bitácora",
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
					{data:"cedula"},
					{data:"fecha"},
					{data:"descripcion"}
					],
					data:lee.mensaje,
					//createdRow: function(row,data){row.querySelector("td:nth-child(1)").innerText;},
					ordering: false,
					autoWidth: false
					//searching:false,
					//info: false,
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