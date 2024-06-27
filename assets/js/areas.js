$(document).ready(function() {
    $('#botonEnvio').on('click', function() {
        alert("holamundo"); return;
        // Mostrar alerta de confirmación
        if (!confirm("¿Está seguro de que desea enviar el formulario?")) {
            return; // Si el usuario cancela, no hacer nada
        }

        var descripcion = $('#descripcion').val();
        var codigo = $('#codigo').val();

        // Validar los campos
        if (descripcion === "" || codigo === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }

        var datos = {
            descripcion: descripcion,
            codigo: codigo,
            accion: "registrar_areas"
        };

        $.ajax({
            url: '../controlador/areas.php', // Cambia esta URL por la ruta real de tu controlador
            method: 'POST',
            data: datos,
            success: function(respuesta) {
                try {
                    var resultd = JSON.parse(respuesta);
                    if (resultd.resultado === "registrar") {
                        alert("Éxito: Área registrada correctamente.");
                    } else if (resultd.resultado === 'is-invalid') {
                        alert("Error: " + resultd.mensaje);
                    } else if (resultd.resultado === "error") {
                        alert("Error: " + resultd.mensaje);
                        console.error(resultd.mensaje);
                    } else {
                        alert("Error: " + resultd.mensaje);
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta JSON:", error);
                    console.error("Respuesta recibida:", respuesta);
                    alert("Error: Respuesta inválida del servidor.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX:", textStatus, errorThrown);
                alert("Error: Hubo un problema con la solicitud. Inténtelo de nuevo más tarde.");
            }
        });
    });
});


