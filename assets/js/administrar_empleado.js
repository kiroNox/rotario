$(document).ready(function() {

    load_lista_usuarios();


    $('#tabla_trabajadores').on('click', '.edit-btn', function() {
        var row = $(this).closest('tr')[0];
        var id = row.dataset.id;
        console.log('ID del trabajador:', id);
        $('#id1').val(id);
        $('#id2').val(id);
        $('#id3').val(id);
    
        // Aquí puedes cargar los datos adicionales en el modal, si es necesario
    
        // Mostrar el modal
        $('#exampleModal').modal('show');
    });
   /*  rowsEvent("tbody_trabajadores",(row)=>{
        row.addEventListener('click', function() {
            // Obtén el ID del trabajador desde el atributo data-id
            const id = row.dataset.id;
            console.log('ID del trabajador:', id);
            $('#id1').val(id);
            $('#id2').val(id);
            $('#id3').val(id);

            // Aquí puedes hacer algo con el ID, como cargar datos adicionales en el modal

            // Muestra el modal
            $('#exampleModal').modal('show');
        });
        console.log(row.dataset.id);
    }); */

    $('#myButton').on('click', function() {
        
    });

    $('#f1').on('submit', function(e) {
        e.preventDefault();
        
            $("#f1 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

        

            var datos = new FormData($('#f1')[0]);
            datos.append("accion", "registrar_vacaciones");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Vacaciones nuevas registrado", "s");
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
    $('#f2').on('submit', function(e) {
        e.preventDefault();

            $("#f2 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

        

            var datos = new FormData($('#f2')[0]);
            datos.append("accion", "registrar_reposo");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Reposo nuevo registrado", "s");
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
    $('#f3').on('submit', function(e) {
        e.preventDefault();

            $("#f3 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });


            var datos = new FormData($('#f3')[0]);
            datos.append("accion", "registrar_permiso");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Permiso nuevo registrado", "s");
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
    $('#f4').on('submit', function(e) {
        e.preventDefault();
        
            $("#f4 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

        

            var datos = new FormData($('#f4')[0]);
            datos.append("accion", "registrar_trabajador");

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        load_lista_usuarios();
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


function load_lista_usuarios(){
    var datos = new FormData();
    datos.append("accion", "listar");
    enviaAjax(datos, function(respuesta, exito, fail){
        var lee = JSON.parse(respuesta);
        if(lee.resultado == "listar"){
            console.table(lee.mensaje);
            console.log(lee);

            if ($.fn.DataTable.isDataTable("#tabla_trabajadores")) {
                $("#tabla_trabajadores").DataTable().destroy();
            }
            
            $("#tbody_trabajadores").html("");
            
            if (!$.fn.DataTable.isDataTable("#tabla_trabajadores")) {
                $("#tabla_trabajadores").DataTable({
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
                    columns: [
                        { data: '0' },
                        { data: '1' },
                        { data: '2' },
                        { data: null, defaultContent: '' }
                    ],
                    createdRow: function(row, data){
                        row.dataset.id = data[4];
                        $(row).find('td:last').html(`
                            <button class="btn btn-primary edit-btn" data-action="edit">Editar</button>
                            <button class="btn btn-danger delete-btn" data-action="delete">Eliminar</button>
                        `);
                        
                    },
                    autoWidth: false,
                    dom: '<"top"f>rt<"bottom"lp><"clear">',
                    initComplete: function() {
                        $("#tabla_trabajadores_wrapper .top").append($('#custom-toolbar'));
                    }
                });
            }
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

