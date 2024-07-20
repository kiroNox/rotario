function load_notifications() {
    var datos = new FormData();
    datos.append("accion", "obtener_noti");

    enviaAjax(datos, function(respuesta, exito, fail) {
        var lee = JSON.parse(respuesta);
		console.log(lee);
        if (lee.resultado !== "error") {
            if ($.fn.DataTable.isDataTable("#table_notificaciones")) {
                $("#table_notificaciones").DataTable().destroy();
            }

            $("#tbody_notificaciones").html("");

            $("#table_notificaciones").DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_ por página",
                    zeroRecords: "No se encontraron notificaciones",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay notificaciones disponibles",
                    infoFiltered: "(filtrado de _MAX_ notificaciones totales)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior",
                    },
                },
                columns: [
                   
                    { data: "fecha" },
                    { data: "mensaje" }
                ],
                data: lee,
                ordering: false,
                autoWidth: false
            });
        } else {
            muestraMensaje("Error", lee.mensaje, "error");
            console.error(lee.mensaje);
        }
    });
}

function enviaAjax(datos, callback) {
    $.ajax({
        url: '', 
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            callback(respuesta, true, false);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            callback(jqXHR.responseText, false, true);
        }
    });
}

$(document).ready(function() {
    load_notifications();
});