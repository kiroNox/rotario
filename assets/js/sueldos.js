// INICIALIZANDO **************************
	load_sueldos();
	load_escalafon();
	eventoMonto("sueldo");

	eventoKeyup("cargo", /^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,50}$/, "Solo se permiten letras");

	eventoKeyup("tipo_nomina", /^[0-9]*$/, "Seleccion no valida");
	eventoKeyup("escalafon", /^[0-9]*$/, "Seleccion no valida");

	document.getElementById('medico_bool').onchange=function(){
		if(this.checked){
			document.getElementById('escalafon').disabled = false;
			document.getElementById('escalafon').required = true;

		}
		else{
			document.getElementById('escalafon').disabled = true;
			document.getElementById('escalafon').required = false;
		}
	}


	document.getElementById('medico_bool').onchange();

// EVENTOS ******************************************************
	rowsEvent("tbody_sueldos",(target,cell)=>{
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

	 				muestraMensaje("¿Seguro?", "Seguro de que desea Eliminar la asignación de este sueldo al trabajador", "s",(resp)=>{
	 					if(resp){
	 						var datos = new FormData();
	 						datos.append("accion","eliminar_sueldo");
	 						datos.append('id_trabajador',cell.parentNode.dataset.id);
	 						enviaAjax(datos,function(respuesta, exito, fail){
	 						
	 							var lee = JSON.parse(respuesta);
	 							if(lee.resultado == "eliminar_sueldo"){

	 								muestraMensaje("Exito", "El sueldo asignado fue eliminado exitosamente", "s");

	 								load_sueldos();

	 								$("#modal_asignar").modal("hide");
	 								
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
	 							}
	 							else{
	 								muestraMensaje(lee.titulo, lee.mensaje,"error");
	 							}
	 						}).p.catch((a)=>{
	 							load_sueldos();
	 							$("#modal_asignar").modal("hide");
	 						});
	 					}
	 				});

	 			}
	 			else if (target.dataset.action == 'asignar'){ // asignar
	 				if(cell.parentNode.dataset.asignado !== "false"){//si no tiene asignado un sueldo

	 					var datos = new FormData();
	 					datos.append("accion","get_sueldo");
	 					datos.append('id_trabajador', cell.parentNode.dataset.id);
	 					enviaAjax(datos,function(respuesta, exito, fail){
	 					
	 						var lee = JSON.parse(respuesta);
	 						if(lee.resultado == "get_sueldo"){

	 							document.getElementById('id_trabajador').value = cell.parentNode.dataset.id
	 							document.getElementById('sueldo').value = lee.mensaje.sueldo_base;
	 							document.getElementById('sueldo').onkeyup();
	 							document.getElementById('sueldo').classList.remove("is-valid");
	 							document.getElementById('cargo').value = lee.mensaje.cargo;
	 							document.getElementById('medico_bool').checked = (lee.mensaje.sector_salud)?true:false;
	 							document.getElementById('escalafon').value =lee.mensaje.id_escalafon;

	 							document.getElementById('medico_bool').onchange();
	 							switch (lee.mensaje.tipo_nomina) {
	 							 	case 'Alto Nivel':
	 							 		document.getElementById('tipo_nomina').value = 1;
	 							 		break;
	 							 	case "Contratado":
	 							 		document.getElementById('tipo_nomina').value = 2;
	 							 		// code
	 							 		break;
	 							 	case "Obrero fijo":
	 							 		document.getElementById('tipo_nomina').value = 3;
	 							 		// code
	 							 		break;
	 							 	case "Comisión de Servicios":
	 							 		document.getElementById('tipo_nomina').value = 4;
	 							 		// code
	 							 		break;
	 							 } 

	 							 $("#modal_asignar").modal("show");
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





	 				}else{
	 					document.getElementById('id_trabajador').value = cell.parentNode.dataset.id
	 					$("#modal_asignar").modal("show");

	 				}
					
	 			}
	 		}
	 	}
	},false);


	$('#modal_asignar').on('hidden.bs.modal', function (e) {

		$("#f1 input, #f1 select").each(function(index, el) {
			el.value = "";
			el.classList.remove("is-invalid","is-valid");
		});
		
	})


	document.getElementById('f1').onsubmit=function(e){
		e.preventDefault();

		elem = document.querySelectorAll("#f1 input:not(input[type='hidden']):not(input[type='checkbox']), #f1 select");
		for(var el of elem){
			if(!el.validarme()){
				return false;
			}
		}
		muestraMensaje("¿Seguro?", "Seguro de que desea asignar este sueldo al trabajador", "s",(resp)=>{
			if(resp){
				var datos = new FormData($("#f1")[0]);
				datos.append("accion","asignar_sueldo");
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "asignar_sueldo"){

						load_sueldos();

						muestraMensaje("Exito", "El sueldo ha sido asignado exitosamente", "s");

						$("#modal_asignar").modal("hide");
						
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
					}
					else{
						muestraMensaje(lee.titulo, lee.mensaje,"error");
					}
				}).p.catch((a)=>{
					load_sueldos();
					$("#modal_asignar").modal("hide");
				});
			}
		});
	}

// FUNCIONES ******************************************

	function load_escalafon(){
		var datos = new FormData();
		datos.append("accion","load_escalafon");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "load_escalafon"){

				for(var elem of lee.mensaje){
					document.getElementById('escalafon').appendChild(crearElem("option",`class,text-center,value,${elem.id_escalafon}`,elem.escala));
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

	function load_sueldos() {
		var datos = new FormData();
		datos.append("accion","load_sueldos");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "load_sueldos"){

				if ($.fn.DataTable.isDataTable("#table_sueldos")) {
					$("#table_sueldos").DataTable().destroy();
				}
				
				$("#tbody_sueldos").html("");
				
				if (!$.fn.DataTable.isDataTable("#table_sueldos")) {
					$("#table_sueldos").DataTable({
						language: {
							lengthMenu: "Mostrar _MENU_ por página",
							zeroRecords: "No se encontraron registros de sueldos",
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
						{data:"nombre"},
						{data:"sueldo_base"},
						{data:"cargo"},
						{data:"sector_salud"},
						{data:"escala"},
						{data:"tipo_nomina"},
						{data:"extra"}
						],
						data:lee.mensaje,
						createdRow: function(row,data){

							row.dataset.id = data.id_trabajador;

							row.querySelector("td:nth-child(2)").appendChild(crearElem("span","class,d-block",data.apellido));
							if(data.sueldo_base == "Por Asignar"){
								row.querySelector("td:nth-child(3)").classList.add("text-danger","sin_asignar");
								row.querySelector("td:nth-child(3)").colSpan = "5";
								row.dataset.asignado = false;
							}


							var acciones = row.querySelector("td:nth-child(8)");
							acciones.innerHTML = '';
							var btn = crearElem("button", "class,btn btn-warning,data-action,asignar", "<span class='bi bi-pencil-square' title='Asignar Sueldo'></span>")
							acciones.appendChild(btn);
							btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar Sueldo'></span>")

								
							if(row.dataset.asignado == "false"){
								btn.dataset.action = "nada";
								btn.disabled = true; 
							}
							acciones.appendChild(btn);
							acciones.classList.add('text-nowrap','cell-action');

						},
						order: [],
						autoWidth: false,
						responsive: true
						//searching:false,
						//info: false,
						//ordering: false,
						//paging: false
						
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