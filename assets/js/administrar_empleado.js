$(document).ready(function() {
    $('#f1').on('submit', function(e) {
        e.preventDefault();
        
        muestraMensaje("Seguro?", "", "?", function(resp) {
            if (!resp) return; // Si el usuario cancela, no hacer nada

            var valido = true;

            $("#f1 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

            if (!valido) {
                return;
            }

            var datos = new FormData($('#f1')[0]);
            datos.append("accion", "registrar_vacaciones");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Usuario nuevo registrado", "s");
                    } else if (lee.resultado === 'is-invalid') {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    } else if (lee.resultado === "error") {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                        console.error(lee.mensaje);
                    } else if (lee.resultado === "console") {
                        console.log(lee.mensaje);
                    } else {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta JSON:", error);
                    console.error("Respuesta recibida:", respuesta);
                }
            });
        });
    });
    $('#f2').on('submit', function(e) {
        e.preventDefault();
        
        muestraMensaje("Seguro?", "", "?", function(resp) {
            if (!resp) return; // Si el usuario cancela, no hacer nada

            var valido = true;

            $("#f2 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

            if (!valido) {
                return;
            }

            var datos = new FormData($('#f2')[0]);
            datos.append("accion", "registrar_reposo");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Usuario nuevo registrado", "s");
                    } else if (lee.resultado === 'is-invalid') {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    } else if (lee.resultado === "error") {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                        console.error(lee.mensaje);
                    } else if (lee.resultado === "console") {
                        console.log(lee.mensaje);
                    } else {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta JSON:", error);
                    console.error("Respuesta recibida:", respuesta);
                }
            });
        });
    });
    $('#f3').on('submit', function(e) {
        e.preventDefault();
        
        muestraMensaje("Seguro?", "", "?", function(resp) {
            if (!resp) return; // Si el usuario cancela, no hacer nada

            var valido = true;

            $("#f3 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

            if (!valido) {
                return;
            }

            var datos = new FormData($('#f3')[0]);
            datos.append("accion", "registrar_permiso");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Usuario nuevo registrado", "s");
                    } else if (lee.resultado === 'is-invalid') {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    } else if (lee.resultado === "error") {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                        console.error(lee.mensaje);
                    } else if (lee.resultado === "console") {
                        console.log(lee.mensaje);
                    } else {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta JSON:", error);
                    console.error("Respuesta recibida:", respuesta);
                }
            });
        });
    });
});


function muestraMensaje(titulo, mensaje, tipo, callback) {
    // Implementación de tu función de mostrar mensajes (puede ser una ventana modal, un alert, etc.)
    alert(`${titulo}: ${mensaje}`);
    if (callback) callback(true);
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

$.fn.validarme = function() {
    // Implementa tu lógica de validación aquí.
    // Por ejemplo:
    if ($(this).val().trim() === "") {
        $(this).addClass('is-invalid');
        return false;
    } else {
        $(this).removeClass('is-invalid');
        return true;
    }
};

