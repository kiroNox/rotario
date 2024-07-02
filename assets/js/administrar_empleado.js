$(document).ready(function() {

    load_lista_usuarios();
    $('.card-vacaciones').on('click', function() {
        cargarVacaciones(function() {
            $('#vacacionesModal').modal('show');
        });
    });
    $('.card-reposos').on('click', function() {
        cargarReposos(function() {
            $('#reposoModal').modal('show');
        });
    });
    $('.card-permisos').on('click', function() {
        cargarPermisos(function() {
            $('#permisosModal').modal('show');
        });
    });

    $("#cerrar_modal").on('click', function() {
       $('#vacaciones_hasta').text('');
       $("#home").css('display','none');
       $("#mm").val('0');

       
    });
    $("#cerrar_mv").on('click', function() {
        $('#vacaciones_hasta').text('');
        $("#home").css('display','none');
        $("#mm").val('0');
 
        
     });

    

    $(document).on('click', '.vacaciones-btn', function() {
        var idTrabajador = $(this).closest('tr').data('id');
        obtenerDetallesVacaciones(idTrabajador);
    });

    // Cargar datos y actualizar tarjetas al inicio
    cargarVacaciones();
    cargarReposos();
    cargarPermisos();

    $("#agg").on("click", function(){
        $('#exampleModal').modal('show');
    })

    $('#tabla_trabajadores').on('click', '.edit-btn', function() {
        var row = $(this).closest('tr')[0];
        var id = row.dataset.id;
        console.log('ID del trabajador:', id);
        $('#id1').val(id);
        $('#id2').val(id);
        $('#id3').val(id);
    
        // Aquí puedes cargar los datos adicionales en el modal, si es necesario
    
        // Mostrar el modal
        $('#exampleModal1').modal('show');
    });

    // Funciones para manejar los modales
$(document).on('click', '.vacaciones-btn', function() {
    var id = $(this).closest('tr').data('id');
    // Lógica para mostrar el modal de vacaciones
    $('#exampleModal1').modal('show');
    $('#id1').val(id);
    mostrarModalVacaciones(id);
});

$(document).on('click', '.reposos-btn', function() {
    var id = $(this).closest('tr').data('id');
    // Lógica para mostrar el modal de reposos
    $('#exampleModal2').modal('show');
    $('#id2').val(id);
    mostrarModalReposos(id);
});

$(document).on('click', '.permisos-btn', function() {
    var id = $(this).closest('tr').data('id');
    // Lógica para mostrar el modal de permisos
    $('#exampleModal3').modal('show');
    $('#id3').val(id);
    mostrarModalPermisos(id);
});

function mostrarModalVacaciones(id) {
    // Implementación del modal de vacaciones
    console.log("Mostrar modal de vacaciones para el usuario con ID: " + id);
}

function mostrarModalReposos(id) {
    // Implementación del modal de reposos
    console.log("Mostrar modal de reposos para el usuario con ID: " + id);
}

function mostrarModalPermisos(id) {
    // Implementación del modal de permisos
    console.log("Mostrar modal de permisos para el usuario con ID: " + id);
}
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

            if( $("#mm").val() === "1"){
                datos.append("accion", "modificar_vacaciones");
                console.log("modif");

            }else
            {
                datos.append("accion", "registrar_vacaciones");
                console.log("registrar");

            }


            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Vacaciones nuevas registrado", "s");
                        $("input").val();
                        cargarVacaciones();
                    }else if (lee.resultado === 'modificar') {
                        muestraMensaje(lee.titulo, lee.mensaje, "s");
                        $("input").val();
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
                       
                        cargarReposos();
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
                        cargarPermisos();
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
                        row.dataset.id = data[8];
                        $(row).find('td:last').html(`
                            <button class="btn btn-success vacaciones-btn" data-action="vacaciones">Vacaciones</button>
                            <button class="btn btn-warning reposos-btn" data-action="reposos">Reposos</button>
                            <button class="btn btn-info permisos-btn" data-action="permisos">Permisos</button>
                    
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


// Funciones para cargar datos en los DataTables
function cargarVacaciones(callback) {
    var datos = new FormData();
    datos.append("accion", "listar_vacaciones");
    enviaAjax(datos, function(respuesta, exito, fail) {
        if (exito) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {
                if ($.fn.DataTable.isDataTable("#tabla_vacaciones")) {
                    $("#tabla_vacaciones").DataTable().destroy();
                }
                $("#tabla_vacaciones").DataTable({
                    language: {
                        lengthMenu: "Mostrar _MENU_ por página",
                        zeroRecords: "No se encontraron registros",
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
                        { data: '3' },
                        { data: '4' },
                        { data: '5' },
                    ],
                    autoWidth: false
                });
                // Actualiza el valor de la tarjeta de vacaciones
                $('.card-vacaciones .h5.mb-0').text(lee.mensaje.length);
                if (callback) callback();
            } else {
                console.error(lee.mensaje);
            }
        } else {
            console.error(fail);
        }
    });
}

function cargarReposos(callback) {
    var datos = new FormData();
    datos.append("accion", "listar_reposos");
    enviaAjax(datos, function(respuesta, exito, fail) {
        if (exito) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {
                if ($.fn.DataTable.isDataTable("#tabla_reposos")) {
                    $("#tabla_reposos").DataTable().destroy();
                }
                $("#tabla_reposos").DataTable({
                    language: {
                        lengthMenu: "Mostrar _MENU_ por página",
                        zeroRecords: "No se encontraron registros",
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
                        { data: '3' },
                        { data: '4' },
                        { data: '5' },
                    ],
                    autoWidth: false
                });
                // Actualiza el valor de la tarjeta de reposos
                $('.card-reposos .h5.mb-0').text(lee.mensaje.length);
                if (callback) callback();
            } else {
                console.error(lee.mensaje);
            }
        } else {
            console.error(fail);
        }
    });
}

function cargarPermisos(callback) {
    var datos = new FormData();
    datos.append("accion", "listar_permisos");
    enviaAjax(datos, function(respuesta, exito, fail) {
        if (exito) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {
                if ($.fn.DataTable.isDataTable("#tabla_permisos")) {
                    $("#tabla_permisos").DataTable().destroy();
                }
                $("#tabla_permisos").DataTable({
                    language: {
                        lengthMenu: "Mostrar _MENU_ por página",
                        zeroRecords: "No se encontraron registros",
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
                        { data: '3' },
                        { data: '4' },
                        { data: '5' },
                    ],
                    autoWidth: false
                });
                // Actualiza el valor de la tarjeta de permisos
                $('.card-permisos .h5.mb-0').text(lee.mensaje.length);
                if (callback) callback();
            } else {
                console.error(lee.mensaje);
            }
        } else {
            console.error(fail);
        }
    });
}


function obtenerDetallesVacaciones(idTrabajador) {
    var datos = new FormData();
    datos.append("accion", "detalles_vacaciones");
    datos.append("id", idTrabajador);
    enviaAjax(datos, function(respuesta, exito, fail) {
        if (exito) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {
                // Aquí puedes actualizar el modal con los detalles específicos de las vacaciones
                $('#vacacionesModalLabel').text('Detalles de Vacaciones');

                $('#vacaciones_hasta').append('En vacaciones hasta<br>');
               
                $('#vacaciones_hasta').append(lee.mensaje.hasta
                   /* ` <tr>
                        <td>${lee.mensaje.id_vacaciones}</td>
                        <td>${lee.mensaje.nombre_trabajador}</td>
                        <td>${lee.mensaje.descripcion}</td>
                        <td>${lee.mensaje.dias_totales}</td>
                        <td>${lee.mensaje.desde}</td>
                        <td>${lee.mensaje.hasta}</td>
                    </tr>
                ` */);

                $('#vacaciones_hasta').append(
                    
                    '<br><button id="boton_modificar_vaca" class="btn btn-primary">Modificar vacaciones</button>'
                    );

                    $('#boton_modificar_vaca').on('click', function() {
                       
                        $("#descripcion").val(lee.mensaje.descripcion)
                        $("#dias_totales").val(lee.mensaje.dias_totales)
                        $("#desde").val(lee.mensaje.desde)
                        $("#hasta").val(lee.mensaje.hasta)
                        $("#id1").val(lee.mensaje.id_trabajador)
                        $("#id_tabla").val(lee.mensaje.id_vacaciones)
                        $("#mm").val('1');

                        

                        $('#vacaciones_hasta').text('');
                        $("#home").css('display','block');
                    });

                




               
            } else {
                $("#home").css('display','block');
                console.error(lee.mensaje);
            }
        } else {
            console.error(fail);
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

