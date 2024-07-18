$(document).ready(function () {


  rowsEventActions("tbody_asistencias",function(action,rowId){
    if(action == "modificar"){
      muestraMensaje("¿Seguro?", "Seguro de que desea modificar la prima", "?",function(result){
        if(result){
          target.disabled = true;
          target.blur();
          var datos = new FormData();
          datos.append("accion","eliminar_trabajadorarea");
          datos.append("id",cell.parentNode.dataset.id);

          enviaAjax(datos,function(respuesta, exito, fail){
          
            var lee = JSON.parse(respuesta);
            if(lee.resultado == "eliminar_trabajadorarea"){
              muestraMensaje("Eliminación Exitosa", "El usuario ha sido eliminado exitosamente", "s");
              
              load_areasTrabajador();

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
            load_areasTrabajador();
          });

        }
      });

    }
    else if (action == "eliminar"){

      muestraMensaje("¿Seguro?", "Esta seguro de que desea eliminar la prima seleccionada", "?",function(result){
        if(result){
          alert("eliminar");
          console.log(rowId);
        }
      });
    }
  })

  load_areasTrabajador();

  function load_areasTrabajador() {
    var datos = new FormData();
    datos.append("accion", "listar_areasTrabajador");
    enviaAjax(datos, function (respuesta, exito, fail) {
      var lee = JSON.parse(respuesta);
      console.log(lee);

      if (lee.resultado == "listar_areasTrabajador") {
        if ($.fn.DataTable.isDataTable("#tabla_asistencias")) {
          $("#tabla_asistencias").DataTable().destroy();
        }

        $("#tbody_asistencias").html("");

        if (!$.fn.DataTable.isDataTable("#tabla_asistencias")) {
          $("#tabla_asistencias").DataTable({
            language: {
              lengthMenu: "Mostrar _MENU_ por página",
              zeroRecords: "No se encontraron registros de hijos",
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
            columns: [
              { data: "nombre" },
              { data: "cedula" },
              { data: "descripcion" },
              { data: "codigo" },
              { data: "extra" },
            ],

            data: lee.mensaje,
            createdRow: function (row, data) {
              // row.dataset.id = data[8];
              row.dataset.id = data.id;
              

              var acciones = row.querySelector("td:nth-child(5)");
              acciones.innerHTML = "";
              var btn = crearElem(
                "button",
                "class,btn btn-warning,data-action,modificar",
                "<span class='bi bi-pencil-square' title='Modificar'></span>"
              );
              acciones.appendChild(btn);
              btn = crearElem(
                "button",
                "class,btn btn-danger ml-1,data-action,eliminar",
                "<span class='bi bi-trash' title='Eliminar'></span>"
              );
              acciones.appendChild(btn);
              acciones.classList.add("text-nowrap", "cell-action");
            },
            autoWidth: false,
          });
        }
      } else if (lee.resultado == "is-invalid") {
        muestraMensaje(lee.titulo, lee.mensaje, "error");
      } else if (lee.resultado == "error") {
        muestraMensaje(lee.titulo, lee.mensaje, "error");
        console.error(lee.mensaje);
      } else if (lee.resultado == "console") {
        console.log(lee.mensaje);
      } else {
        muestraMensaje(lee.titulo, lee.mensaje, "error");
      }
    });
  }

  
	rowsEvent("tbody_asistencias",(target,cell)=>{
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
	 						datos.append("accion","eliminar_trabajadorarea");
	 						datos.append("id",cell.parentNode.dataset.id);

	 						enviaAjax(datos,function(respuesta, exito, fail){
	 						
	 							var lee = JSON.parse(respuesta);
	 							if(lee.resultado == "eliminar_trabajadorArea"){
	 								muestraMensaje("Eliminación Exitosa", "El usuario ha sido eliminado exitosamente", "s");
	 								
	 								load_areasTrabajador();

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
	 							load_areasTrabajador();
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

});
