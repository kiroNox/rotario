$(document).ready(function () {

  //Listar asistencias

  load_asistencias();
  
  function load_asistencias() {
    var datos = new FormData();
    datos.append("accion", "listar_asistencias");
    enviaAjax(datos, function (respuesta, exito, fail) {
      var lee = JSON.parse(respuesta);
      console.log(lee);

      if (lee.resultado == "listar_asistencias") {
        if ($.fn.DataTable.isDataTable("#tabla_asistencia")) {
          $("#tabla_asistencia").DataTable().destroy();
        }
        $("#tbody_asistencia").html("");
        if (!$.fn.DataTable.isDataTable("#tabla_asistencia")) {
          $("#tabla_asistencia").DataTable({
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
              { data: "apellido" },
              { data: "cedula" },            
              { data: "descripcion" },
              { data: "codigo" },
              { data: "fecha_entrada" },
              { data: "fecha_salida" },
              { data: null, defaultContent: "" }
            ],

            data: lee.mensaje,
            createdRow: function (row, data) {
              // row.dataset.id = data[8];
              row.dataset.id = data.id_asistencia;

              

              var acciones = row.querySelector("td:nth-child(8)");
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
                "<span class='bi bi-trash' title='eliminar'></span>"
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

  // MODIFICAR Y ELIMINAR 

  rowsEvent(
    "tbody_asistencia",
    (target, cell) => {
      if (!cell.parentNode.dataset.id) {console.log("hay ID", id);return false}
      if (cell.classList.contains("cell-action")) {
        let count = 0;
        while (target.tagName != "BUTTON" && target.tagName != cell.tagName) {
          count++;
          if (count > 100) {
            console.error("se paso el while");
            return false;
          }
          target = target.parentNode;
        }
        // ELIMINAR
        if (target.dataset.action == "eliminar" ) {
          muestraMensaje(
            "¿Seguro",
            "Esta seguro de querer eliminar el trabajador",
            "?",
            (resp) => {
              if (resp) {
                target.disabled = true;
                target.blur();
                var datos = new FormData();
                datos.append("accion", "eliminar");
                datos.append("id", cell.parentNode.dataset.id);
                enviaAjax(datos, (respuesta) => {
                  var lee = JSON.parse(respuesta);
                  if (lee.resultado == 200) {
                    muestraMensaje(
                      "Eliminación Exitosa",
                      "El usuario ha sido eliminado exitosamente",
                      "s"
                    );
                    cerrarModal('ModalCenter');
                    load_areasTrabajador();
                    return 0 ;
                  } else  {
                    alert("error al eliminar asistencia");
                    return 0 ;
                  }
                })
              }
            }
          );
        } else if (target.dataset.action == "modificar") {
          // MODIFICAR
          alert("modificar");
        }
      }
    },
    false
  );
   
  
    
  
  
  });
  