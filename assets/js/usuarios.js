
// registar *************************************
	cedulaKeypress(document.getElementById('cedula_modificar'));
	eventoKeyup("cedula_modificar", V.expCedula, "La cedula es invalida ej V-00000001");
	eventoKeyup('nombre_modificar', V.expTexto(50), "El nombre no es valido");
	eventoKeyup('apellido_modificar', V.expTexto(50), "El apellido no es valido");
	eventoKeyup('telefono_modificar', V.expTelefono, "El teléfono es invalido", undefined, (elem)=>{
		elem.value = elem.value.replace(/^([0-9]{4})\D*([0-9]{1,7})/, "$1-$2");
	});

	eventoFecha("fecha_ingreso_modificar", "La fecha de ingreso es invalida");

	eventoKeyup("numero_cuenta_modificar", /^[0-9]{20}$/, "El numero de cuenta debe tener 20 numeros");

	document.getElementById('numero_cuenta_modificar').maxLength = 20;

	document.getElementById('telefono_modificar').allow_empty = true;
	eventoKeyup("correo_modificar", V.expEmail, "El correo es invalido");
	eventoPass("pass_modificar");

	document.getElementById('pass_modificar').allow_empty=true;

	document.getElementById('discapacidad_modificar').onchange=function (e){
		if(this.checked){
			document.getElementById('discapacidad_info_modificar').disabled = false;
		}
		else{
			document.getElementById('discapacidad_info_modificar').disabled = true;
		}
	};


	eventoKeyup("discapacidad_info_modificar", /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{0,100}$/, "El texto no es valido");

	document.getElementById('discapacidad_info_modificar').allow_empty = true;

	document.getElementById('discapacidad_info_modificar').validarme = function(){
		var exp = /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{0,100}$/;
		if(this.allow_empty == true && this.value == ''){
			return validarKeyUp(true, $(this), "El texto no es valido");
		}
		return validarKeyUp(exp, $(this), "El texto no es valido");
	}

	document.getElementById('discapacidad_info_modificar').maxLength = 100;




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

	eventoFecha("fecha_ingreso", "La fecha de ingreso es invalida");

	eventoKeyup("numero_cuenta", /^[0-9]{20}$/, "El numero de cuenta debe tener 20 numeros");

	document.getElementById('numero_cuenta').maxLength = 20;

	document.getElementById('telefono').allow_empty = true;
	eventoKeyup("correo", V.expEmail, "El correo es invalido");
	eventoPass("pass");


	document.getElementById('discapacidad').onchange=function (e){
		if(this.checked){
			document.getElementById('discapacidad_info').disabled = false;
		}
		else{
			document.getElementById('discapacidad_info').disabled = true;
		}
	};


	
	eventoKeyup("discapacidad_info", /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{0,100}$/, "El texto no es valido");

	document.getElementById('discapacidad_info').allow_empty = true;

	document.getElementById('discapacidad_info').validarme = function(){
		var exp = /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{0,100}$/;
		if(this.allow_empty == true && this.value == ''){
			return validarKeyUp(true, $(this), "El texto no es valido");
		}
		return validarKeyUp(exp, $(this), "El texto no es valido");
	}

	document.getElementById('discapacidad_info').maxLength = 100;




	load_roles();
	load_nivel_profesionalismo();

	document.getElementById('f1').onsubmit = function(e) {
		e.preventDefault();

		var inputList = document.querySelectorAll("#f1 input:not(input[type='radio']):not(input[type='checkbox'])");

		for ( var elem of inputList){
			if(!elem.validarme()){
				return false;
			}
		}
		muestraMensaje("Seguro?", "", "?", (resp)=>{
			if(resp){




				var datos = new FormData($("#f1")[0]);
				if(!datos.has("comision_servicios")){
					muestraMensaje("Error", "Debe seleccionar si es de comisión de servicios o no", "e");
					return false;
				}
				datos.append("accion","registrar");
				datos.clean("correo");
				enviaAjax(datos,function(respuesta, exito, fail){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "registrar"){

						muestraMensaje("Exito", "Usuario nuevo registrado", "s");
						$("#f1 input:not(input[type='radio']):not(input[type='checkbox']), #f1 select").each((i,elem)=>{
							elem.value = '';
							elem.classList.remove("is-invalid", "is-valid");
						});
						document.getElementById('discapacidad').checked=false;
						document.getElementById('discapacidad').onchange();
						document.querySelector("#f1 input[name='comision_servicios']:checked").checked=false;

						load_lista_usuarios();
						$("#modal_registrar").modal("hide");
						
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
// listar ***************************************
	load_lista_usuarios();

	rowsEvent("tbody_usuarios",(target,cell)=>{
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

	 				muestraMensaje("¿Seguro", "Esta seguro de querer eliminar el trabajador", "?",(resp)=>{
	 					if(resp){
	 						target.disabled = true;
	 						target.blur();



	 						var datos = new FormData();
	 						datos.append("accion","eliminar_usuario");
	 						datos.append("id",cell.parentNode.dataset.id);

	 						enviaAjax(datos,function(respuesta, exito, fail){
	 						
	 							var lee = JSON.parse(respuesta);
	 							if(lee.resultado == "eliminar_usuario"){
	 								muestraMensaje("Eliminación Exitosa", "El usuario ha sido eliminado exitosamente", "s");
	 								
	 								load_lista_usuarios();

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
	 							load_lista_usuarios();
	 						});



	 					}
	 				});

	 			}
	 			else if (target.dataset.action == 'modificar'){ // MODIFICAR
					var datos = new FormData();
					datos.append("accion","get_user");
					datos.append("id",cell.parentNode.dataset.id);
					enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "get_user"){

							document.getElementById('modificar_id').value = lee.mensaje.id_trabajador;
							document.getElementById('cedula_modificar').value = lee.mensaje.cedula;
							document.getElementById('nombre_modificar').value = lee.mensaje.nombre;
							document.getElementById('apellido_modificar').value = lee.mensaje.apellido;
							document.getElementById('telefono_modificar').value = lee.mensaje.telefono;
							document.getElementById('correo_modificar').value = lee.mensaje.correo;
							document.getElementById('rol_modificar').value = lee.mensaje.rol;
							document.getElementById('nivel_educativo_modificar').value = lee.mensaje.id_prima_profesionalismo;
							document.getElementById('pass_modificar').value = "";
							document.getElementById('fecha_ingreso_modificar').value = lee.mensaje.creado;
							document.getElementById('numero_cuenta_modificar').value = lee.mensaje.numero_cuenta;
							document.getElementById('genero_trabajador_modificar').value = lee.mensaje.genero;

							console.log("lee.mensaje.discapacitado", lee.mensaje.discapacitado);
							if (lee.mensaje.discapacitado == "1") {
								document.getElementById('discapacidad_modificar').checked = true;
								document.getElementById('discapacidad_info_modificar').value = lee.mensaje.discapacidad;
							}
							else{
								document.getElementById('discapacidad_modificar').checked = false;
								document.getElementById('discapacidad_info_modificar').value = "";
							}


							if(lee.mensaje.comision_servicios == "1"){
								document.getElementById('comision_servicios_modificar').checked = true;
								document.getElementById('comision_servicios_no_modificar').checked = false;
								
							}
							else{
								document.getElementById('comision_servicios_no_modificar').checked = true;
								document.getElementById('comision_servicios_modificar').checked = false;
							}



							$("#modal_modificar_usaurio").modal("show");
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


	$('#modal_modificar_usaurio').on('hidden.bs.modal', function () {
		$("#f1_modificar input:not(input[type='radio']):not(input[type='checkbox']), #f1_modificar select").each((i,elem)=>{
			elem.value = '';
			elem.classList.remove("is-invalid");
			elem.classList.remove("is-valid");
		});
	})
// Modificar *********************************

	document.getElementById('f1_modificar').onsubmit=function(e){
		e.preventDefault();
		if(document.getElementById('modificar_id').value != ''){
			if(this.sending){
				return false;
			}
			var inputList = document.querySelectorAll("#f1_modificar input:not(input[type='radio']):not(input[type='checkbox']):not(input[type='hidden'])");

			for ( var elem of inputList){
				if(!elem.validarme()){
					return false;
				}
			}


			muestraMensaje("Seguro?", "Seguro que desea modificar el trabajador", "?",(resp)=>{
				if(resp){

					var datos = new FormData($("#f1_modificar")[0]);
					datos.append("accion","modificar_usuario");
					this.sending = true;
					document.getElementById('btn_modificar').disabled = true;
					enviaAjax(datos,function(respuesta, exito, fail){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "modificar_usuario"){
							muestraMensaje("Modificación Exitosa", "El usuario ha sido modificado exitosamente", "s");
							load_lista_usuarios();

							$("#modal_modificar_usaurio").modal("hide");
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
						document.getElementById('f1_modificar').sending = undefined;
						document.getElementById('btn_modificar').disabled = false;
					},"loader_body").p.catch((a)=>{
						document.getElementById('f1_modificar').sending = undefined;
						document.getElementById('btn_modificar').disabled = false;
					});
				}
			});
		}
		else{
			muestraMensaje("Error", "La acción no se puede completar refresque la pagina e intente de nuevo", "s");
		}
	};

// function *************************************
	function load_roles(){
		var datos = new FormData();
		datos.append("accion","get_roles");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "get_roles"){
				for(x of lee.mensaje){
					document.getElementById('rol').appendChild(crearElem('option',`value,${x.id}`,x.rol));
					document.getElementById('rol_modificar').appendChild(crearElem('option',`value,${x.id}`,x.rol));

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
	function load_nivel_profesionalismo(){
		var datos = new FormData();
		datos.append("accion","nivel_profesional");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "nivel_profesional"){
				for(x of lee.mensaje){
					document.getElementById('nivel_educativo').appendChild(crearElem('option',`value,${x.id}`,x.prof));
					document.getElementById('nivel_educativo_modificar').appendChild(crearElem('option',`value,${x.id}`,x.prof));

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

		if(document.getElementById('discapacidad').checked){

			document.getElementById('discapacidad_info').disabled = false;
		}
		else{
			document.getElementById('discapacidad_info').disabled = true;
		}
	}

	function load_lista_usuarios(){
		var datos = new FormData();
		datos.append("accion","listar_usuarios");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "listar_usuarios"){
				
				if ($.fn.DataTable.isDataTable("#tabla_usuarios")) {
					$("#tabla_usuarios").DataTable().destroy();
				}
				
				$("#tbody_usuarios").html("");
				
				if (!$.fn.DataTable.isDataTable("#tabla_usuarios")) {
					$("#tabla_usuarios").DataTable({
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
						createdRow: function(row,data){
							row.dataset.id = data[8];
							row.querySelector("td:nth-child(1)").classList.add("text-nowrap");
							row.querySelector("td:nth-child(4)").classList.add("text-nowrap");
							row.querySelector("td:nth-child(5)").classList.add("text-nowrap");
							var acciones = row.querySelector("td:nth-child(8)");
							acciones.innerHTML = '';




							var btn = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar'></span>")
							acciones.appendChild(btn);
							btn = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar'></span>")
							acciones.appendChild(btn);
							acciones.classList.add('text-nowrap','cell-action');
							
						},
						autoWidth: false
						//searching:false,
						//info: false,
						//ordering: false,
						//paging: false
						//order: [[1, "asc"]],
						
					});
					if(lee.mensaje.length>0){
						$("#tabla_usuarios")[0].classList.add("table-responsive");
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
