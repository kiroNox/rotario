$(document).ready(function () {
  
  // Función AJAX reutilizable
  function realizarSolicitudAjax(url, metodo, datos, exitoMensaje, errorMensaje, callback) {
    $.ajax({
      url: url,
      method: metodo,
      data: datos,
      processData: false,
      contentType: false,
      success: function (respuesta) {
        console.log("Respuesta AJAX:", respuesta);  // Aquí se imprime la respuesta en la consola
        try {
          if (callback) {
            callback(respuesta);
          } else {
            alert("Éxito", exitoMensaje, "success");
            console.log(respuesta);
          }
        } catch (error) {
          alert("Error", "Respuesta inválida del servidor.", "error");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la petición AJAX:", textStatus, errorThrown);
        alert("Error Hubo un problema con la solicitud. Inténtelo de nuevo más tarde.");
      },
    });
  }

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


    
  // Función para listar usuarios
  function listarAreas() {
    var datos = new FormData();
    datos.append("accion", "list");
    // Llamar a la función AJAX
    realizarSolicitudAjax(
      "", // Cambia esta URL por la ruta real de tu controlador
      "POST",
      datos,
      "Áreas listadas correctamente",
      "Hubo un problema al listar las áreas.",
      (respuesta)=>{
        pintarTabla(
          "tabla_areas",
          [
            { key : "id_area", value: "ID"},
            { key : "descripcion", value: "descripcion"},
            { key : "id_area", value: "ID"},
          
          ]
        )
      }
    );
  }
  const optionsFn = (rowData) => {
    return `
      <button onclick="alert('Edit ${rowData.name}')">Editar</button>
      <button onclick="alert('Delete ${rowData.name}')">Eliminar</button>
    `;
  };
  // Función para cargar y mostrar usuarios en el HTML
  function loadListarAreas() {
    var datos = new FormData();
    datos.append("accion", "listar_usuarios");

    realizarSolicitudAjax(
      "", // Cambia esta URL por la ruta real de tu controlador
      "POST",
      datos,
      "Usuarios listados correctamente",
      "Hubo un problema al listar los usuarios.",
      function (respuesta) {
        console.log("Respuesta en load_lista_usuarios:", respuesta); // Aquí se imprime la respuesta en la consola
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listar_usuarios") {
          console.table(lee.mensaje);
          if ($.fn.DataTable.isDataTable("#tabla_areas")) {
            $("#tabla_areas").DataTable().destroy();
          }

          $("#tbody_areas").html("");

          if (!$.fn.DataTable.isDataTable("#tabla_areas")) {
            $("#tabla_areas").DataTable({
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
              data: lee.mensaje,
              createdRow: function (row, data) {
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
                acciones.classList.add('text-nowrap', 'cell-action');
              },
              autoWidth: false
              //searching:false,
              //info: false,
              //ordering: false,
              //paging: false
              //order: [[1, "asc"]],
            });
          }

          $("#tabla_areas")[0].classList.add("table-responsive");
        } else if (lee.resultado == 'is-invalid') {
          muestraMensaje(lee.titulo, lee.mensaje, "error");
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.titulo, lee.mensaje, "error");
          console.error(lee.mensaje);
        } else if (lee.resultado == "console") {
          console.log(lee.mensaje);
        } else {
          muestraMensaje(lee.titulo, lee.mensaje, "error");
        }
      }
    );
  }

  // Llamar a la función para cargar las áreas y usuarios al cargar la página
  listarAreas();
  loadListarAreas();
});