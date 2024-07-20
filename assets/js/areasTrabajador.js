$(document).ready(function () {



  load_areasTrabajador();

  function load_areasTrabajador() {
    var datos = new FormData();
    datos.append("accion", "listar_areasTrabajador");
    enviaAjax(datos, function (respuesta, exito, fail) {
      var lee = JSON.parse(respuesta);

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

  rowsEvent(
    "tbody_asistencias",
    (target, cell) => {
      const id = cell.parentNode;
      console.log(id,"soy id de areas trabajador js");
      if (!cell.parentNode.dataset.id) return false;
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
        if (target.dataset.action == "eliminar") {
          muestraMensaje(
            "¿Seguro",
            "Esta seguro de querer eliminar el trabajador",
            "?",
            (resp) => {
              if (resp) {
                target.disabled = true;
                target.blur();
                var datos = new FormData();
                datos.append("accion", "eliminar_trabajadorarea");
                datos.append("id", cell.parentNode.dataset.id);

                enviaAjax(datos, (respuesta) => {
                  var lee = JSON.parse(respuesta);
                  if (lee.resultado == "eliminar_trabajadorArea") {
                    muestraMensaje(
                      "Eliminación Exitosa",
                      "El usuario ha sido eliminado exitosamente",
                      "s"
                    );
                    cerrarModal('ModalCenter');
                    load_areasTrabajador();
                    return;
                  } else if (lee.resultado == "is-invalid") {
                    muestraMensaje(lee.titulo, lee.mensaje, "error");
                  }
                }).p.catch(() => load_areasTrabajador());
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

  //GUARDAR
  document.getElementById("f1").onsubmit = function (e) {
    e.preventDefault();
    let datos = new FormData($("#f1")[0]);
    if (document.getElementById("submit_btn").value == "Registrar") {
      datos.append("accion", "registrar");
      datos.append("id_trabajador", document.getElementById("select").value);
      datos.append("id_area", document.getElementById("select2").value);
      enviaAjax(datos, function (respuesta, exito, fail) {
        try {
          var lee = JSON.parse(respuesta);
          if (lee.resultado == 200) {
            muestraMensaje("Exito", lee.mensaje, "s");

            $("#ModalCenter").modal("hide");
            load_areasTrabajador();
            
            return;
          } else {
            //muestraToast(lee.titulo, lee.mensaje, lee.resultado);
            alert("error del else");
            return;
          }
        } catch (e) {
          console.error("Error al parsear la respuesta JSON:", e);
        }
      });
    } else if (document.getElementById("submit_btn").value == "Modificar") {
      alert("modificar");
    }
  };

  // LISTAR USUARIOS
  listar_usuarios();
  function listar_usuarios() {
    var datos = new FormData();
    datos.append("accion", "listar_usuarios");
    enviaAjax(datos, function (respuesta, exito, fail) {
      try {
        var lee = JSON.parse(respuesta);

        if (lee.mensaje && Array.isArray(lee.mensaje)) {
          var select = document.getElementById("select");
          select.innerHTML = "";

          for (var usuario of lee.mensaje) {
            var option = document.createElement("option");
            option.value = usuario[8];
            option.textContent = usuario[2];
            select.appendChild(option);
          }
        } else {
          console.error(
            "La propiedad 'usuarios' no está definida o no es un array."
          );
        }
      } catch (e) {
        console.error("Error al parsear la respuesta JSON:", e);
      }
    });
  }

  function listar_areas() {
    var datos = new FormData();
    datos.append("accion", "listar_areas");
    enviaAjax(datos, function (respuesta, exito, fail) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee && Array.isArray(lee)) {
          var select = document.getElementById("select2");
          select.innerHTML = "";

          for (var area of lee) {
            var option = document.createElement("option");
            option.value = area.id_area;
            option.textContent = area.descripcion;
            select.appendChild(option);
          }
        } else {
          console.error(
            "La propiedad 'areas' no está definida o no es un array."
          );
        }
      } catch (e) {
        console.error("Error al parsear la respuesta JSON:", e);
      }
    });
  }

  listar_areas();
});
