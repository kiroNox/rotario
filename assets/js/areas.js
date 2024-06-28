$(document).ready(function () {
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
  
      $.ajax({
        url: "", // Cambia esta URL por la ruta real de tu controlador
        method: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function (respuesta) {
          try {
            alert("Éxito", "Área registrada correctamente", "success");
            console.log(respuesta);
          } catch (error) {
            alert("Error", "Respuesta inválida del servidor.", "error");
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la petición AJAX:", textStatus, errorThrown);
          alert("Error Hubo un problema con la solicitud. Inténtelo de nuevo más tarde.");
        },
      });
    });
  });
  






/*$(document).ready(function () {
  $("#botonEnvio").on("click", function () {
    let datos = new FormData($("#formularioAreas")[0]);

    const descripcion = datos.get("descripcion");
    const codigo = datos.get("codigo");
    datos.append("accion", "create"); // Validar los campos

    if (descripcion === "" || codigo === "") { alert("Por favor, complete todos los campos."); return; }

    $.ajax({
      url: "", // Cambia esta URL por la ruta real de tu controlador
      method: "POST",
      data: datos,
      processData: false,
      contentType: false,
      success: function (respuesta) {
        try {
          alert("Éxito", "Área registrada correctamente", "success");
          console.log(respuesta);
        } catch (error) {
          alert("Error", "Respuesta inválida del servidor.", "error");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la petición AJAX:", textStatus, errorThrown);
        alert(
          "Error Hubo un problema con la solicitud. Inténtelo de nuevo más tarde."
        );
      },
    });
  });
});*/
