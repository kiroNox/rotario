$(document).ready(function () {
  
  function realizarSolicitudAjax(
    url,
    metodo,
    datos,
    exitoMensaje,
    errorMensaje,
    callback
  ) {
    $.ajax({
      url: url,
      method: metodo,
      data: datos,
      processData: false,
      contentType: false,
      success: function (respuesta) {
        try {
          if (callback) {
            callback(respuesta);
          } else {
            muestraMensaje("Éxito", exitoMensaje, "s");
            //cargar_areas();
            //$("#exampleModalCenter").modal("hide");
            console.log(respuesta);
          }
        } catch (error) {
          alert("Error", "Respuesta inválida del servidor.", "error");
        }
        return respuesta; // Aquí se imprime la respuesta en la consola
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la petición AJAX:", textStatus, errorThrown);
        alert(
          "Error Hubo un problema con la solicitud. Inténtelo de nuevo más tarde."
        );
      },
    });
  }

  // Función AJAX reutilizable

  // Evento para el botón de envío
  $("#botonEnvio").on("click", function () {
    let datos = new FormData($("#formularioAreas")[0]);
    const descripcion = datos.get("descripcion");
    const codigo = datos.get("codigo");
    datos.append("accion", "create");
    // Validar los campos
    if (descripcion === "" || codigo === "") {
      alert("Por favor, complete todos los campos.");
      return;
    }
    // Llamar a la función AJAX
    realizarSolicitudAjax(
      "",
      "POST",
      datos,
      "Área registrada correctamente",
      "Hubo un problema al registrar el área."
    );
  });

  // Función para listar areas

  function cargar_areas() {
    let datos = new FormData();
    datos.append("accion", "list");
    realizarSolicitudAjax(
      "",
      "POST",
      datos,
      "Área listada correctamente",
      "Hubo un problema al listando el área.",
      (respuesta) => {
        let lee = JSON.parse(respuesta);
        console.log(lee);
        if ($.fn.DataTable.isDataTable("#tabla_areas")) {
          $("#tabla_areas").DataTable().destroy();
        }
        $("#tbody_areas").html("");

        if (!$.fn.DataTable.isDataTable("#tabla_areas")) {
          $("#tabla_areas").DataTable({
              language: {
                  lengthMenu: "Mostrar _MENU_ por página",
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
              columnDefs: [
                  {targets: 1, title: "ID" },
                  { targets: 2, title: "Descripción" },
                  { targets: 3, title: "Código" },
              ],
              columns: [
                  { data: "id_area" },
                  { data: "descripcion" },
                  { data: "codigo" },
                  { data: "extra" },
              ],
              data: lee.datos,

              createdRow: function (row, data) {
                console.log("dentro de la funcion", data);  
                  row.dataset.id = data.id_area;

                  var acciones = row.querySelector("td:nth-child(4)");
                  
                  acciones.style.width = "15%";
                  acciones.style.textAlign = "center";
                  acciones.classList.add('text-nowrap', 'cell-action');

                  var btn1 = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar'></span>");
                  var btn2 = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='izar'></span>");
                  
                  acciones.appendChild(btn1);
                  acciones.appendChild(btn2);
              },
              autoWidth: false,
          });
      
          if (lee.mensaje.length > 0) {
              $("#tabla_areas")[0].parentNode.classList.add("table-responsive");
          }
      } else if (lee.resultado == "is-invalid") {
          console.log("Error: ", lee.titulo, lee.mensaje);
      } else if (lee.resultado == "error") {
          console.log("Error: ", lee.titulo, lee.mensaje);
          console.error("Error detallado: ", lee.mensaje);
      } else if (lee.resultado == "console") {
          console.log("Mensaje de consola: ", lee.mensaje);
      } else {
          console.log("Error desconocido: ", lee.titulo, lee.mensaje);
      }


        
      });
  }

  $('#exampleModalCenter').on('hidden.bs.modal', function (e) {
    document.getElementById('descripcion').value = "";
    document.getElementById('codigo').value = "";
  })

  cargar_areas();
});
