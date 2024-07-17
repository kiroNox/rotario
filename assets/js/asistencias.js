$(document).ready(function () {



  load_asistencias();

  function load_asistencias() {
    var datos = new FormData();

    datos.append("accion", "listar_asistencias");

    enviaAjax(datos, function (respuesta, exito, fail) {
      var lee = JSON.parse(respuesta);
      if (lee.respuesta == "index") {
        if ($.fn.DataTable.isDataTable("#tabla_asistencias")) {
          $("#tabla_asistencias").DataTable().destroy();
        }

        $("#tbody_hijos").html("");

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
              { data: "Trabajador" },
              { data: "cedula" },
              { data: "area" },
              { data: "codigo_de_area" },
              { data: "extra" },
            ],

            data: lee.mensaje,
            createdRow: function (row, data) {
              // row.dataset.id = data[8];
              row.dataset.id = data.id;
              row.querySelector("td:nth-child(3)").innerHTML = "";
              row.querySelector("td:nth-child(4)").innerHTML = "";
              row.querySelector("td:nth-child(5)").classList.add("text-center");
              row.querySelector("td:nth-child(6)").classList.add("text-center");
              row.querySelector("td:nth-child(6)").innerHTML =
                data.discapacidad == "1" ? "Si" : "No";
              row.querySelector("td:nth-child(7)").title = data.observacion;
              row.querySelector("td:nth-child(7)").classList.add("text-truncate");
              row.querySelector("td:nth-child(7)").setAttribute("style", "max-width: 20ch");
              row.querySelector("td:nth-child(3)").appendChild(
                  crearElem("span", "class,text-nowrap", data.cedulaMadre)
                );
              row.querySelector("td:nth-child(3)").appendChild(
                  crearElem(
                    "small",
                    "class,d-block text-center no-select",
                    data.nombreMadre
                  )
                );
              row.querySelector("td:nth-child(4)").appendChild(
                  crearElem("span", "class,text-nowrap", data.cedulaPadre || "")
                );
              row.querySelector("td:nth-child(4)").appendChild(
                  crearElem(
                    "small",
                    "class,d-block text-center no-select",
                    data.nombrePadre || ""
                  )
                );

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
                "<span class='bi bi-trash' title='Eliminar'></span>"
              );
              acciones.appendChild(btn);
              acciones.classList.add("text-nowrap", "cell-action");
            },
            autoWidth: false,
          });
          if (lee.mensaje.length > 0) {
            $("#tabla_hijos")[0].parentNode.classList.add("table-responsive");
          }
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

});
