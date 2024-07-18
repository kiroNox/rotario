$(document).ready(function() {
    
    // Cargar datos y actualizar tarjetas al inicio
    cargarVacaciones();
    cargarReposos();
    cargarPermisos();
    
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

   
    $(".close, .cv_w").on('click', function() {
        $('#vacaciones_hasta').text('');
        $("#home").css('display','block');
        $("#mm").val('0');
        $("#f1")[0].reset();

        $('#reposos_hasta').text('');
        $("#myTabContent1").css('display','block');
        $("#mm2").val('0');
        $("#f2")[0].reset();
 
        $('#permisos_hasta').text('');
        $("#myTabContent2").css('display','block');
        $("#mm3").val('0');
        $("#f3")[0].reset();
 
 
        
     });

     $( "#dias_totales" ).on( "keyup", function() {
        var dato1 = $( "#desde" ).val();
        var dato2 = $( "#dias_totales" ).val();
        var dato3 = "1";
        calcular_dia_habil(dato1, dato2, dato3);


      } );

      $( "#dias_totales_repo" ).on( "keyup", function() {
        var dato1 = $( "#fecha_inicio_reposo" ).val();
        var dato2 = $( "#dias_totales_repo" ).val();
        var dato3 = "2";
        calcular_dia_habil(dato1, dato2, dato3);


      } );

      $( "#dias_totales" ).on( "keyup", function() {
        var dato1 = $( "#desde" ).val();
        var dato2 = $( "#dias_totales" ).val();
        calcular_dia_habil(dato1, dato2);
        


      } );
    

    $(document).on('click', '.vacaciones-btn', function() {
        var idTrabajador = $(this).closest('tr').data('id');
        obtenerDetallesVacaciones(idTrabajador);
    });

    $(document).on('click', '.reposos-btn', function() {
        var idTrabajador = $(this).closest('tr').data('id');
        obtenerDetallesReposos(idTrabajador);
    });

    $(document).on('click', '.permisos-btn', function() {
        var idTrabajador = $(this).closest('tr').data('id');
        obtenerDetallesPermisos(idTrabajador);
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


function setupFormValidation(formulario, accion, successMessage, cargarFuncion) {
    $(formulario).on('submit', function(e) {
        e.preventDefault();
        if (validarFormulario(formulario)) {
            var datos = new FormData($(formulario)[0]);
            datos.append("accion", accion);

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Éxito", successMessage, "s");
                        cargarFuncion();
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
        }
    });
}

   

    $('#f1').on('submit', function(e) {
        e.preventDefault();
        
            $("#f1 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

        
            if (validarFormulario('#f1')) {


            
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
                        $('#exampleModal1').modal('hide');
                        $("#f1")[0].reset();
                        $("#mm").val("1");
                        $("input").val();
                        cargarVacaciones();
                        
                    }else if (lee.resultado === 'modificar') {
                        muestraMensaje(lee.titulo, lee.mensaje, "s");
                        $('#exampleModal1').modal('hide');
                        $("#f1")[0].reset();
                        $("#mm").val("1");
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

        }
        
    });
    $('#f2').on('submit', function(e) {
        e.preventDefault();

            $("#f2 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

            if (validarFormulario('#f2')) {

            var datos = new FormData($('#f2')[0]);

            if( $("#mm2").val() === "1"){
                datos.append("accion", "modificar_reposo");
                console.log("modif");

            }else
            {
                datos.append("accion", "registrar_reposo");
                console.log("registraaaar");

            }

            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Reposo nuevo registrado", "s");
                        $('#exampleModal2').modal('hide');
                        $("#f2")[0].reset();
                        $("#mm2").val("1");
                        cargarReposos();
                    } else if (lee.resultado === 'modificar') {
                        muestraMensaje(lee.titulo, lee.mensaje, "s");
                        $('#exampleModal2').modal('hide');
                        $("#f2")[0].reset();
                        $("#mm2").val("1");
                        $("input").val();
                        
                    } else if (lee.resultado === 'is-invalid') {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                    } else if (lee.resultado === "error") {
                        muestraMensaje(lee.titulo, lee.mensaje, "error");
                        console.error(lee.mensaje);
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
        }
    });
    $('#f3').on('submit', function(e) {
        e.preventDefault();

            $("#f3 input").each(function(i, elem) {
                if (!$(elem).validarme()) {
                    valido = false;
                    return false;  // salir del bucle each
                }
            });

            if (validarFormulario('#f3')) {
            var datos = new FormData($('#f3')[0]);

            if( $("#mm3").val() === "1"){
                datos.append("accion", "modificar_permiso");
                console.log("modif");

            }else
            {
                datos.append("accion", "registrar_permiso");
                console.log("registrar");

            }
           
            enviaAjax(datos, function(respuesta, exito, fail) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado === "registrar") {
                        muestraMensaje("Exito", "Permiso nuevo registrado", "s");
                        cargarPermisos();
                    } else if (lee.resultado === 'modificar') {
                        muestraMensaje("Exito", "Permiso Modificado", "s");
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
        }
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
        try {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {
                if(esFechaActualOFutura(lee.mensaje.hasta)){
                        $("#home").css('display','none');
                        $('#vacaciones_hasta').text('');
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

                $("#home").css('display','block');
            }
            
                
        } catch {
            console.error(fail);
        }
    });
}

function obtenerDetallesReposos(idTrabajador) {
    var datos = new FormData();
    datos.append("accion", "detalles_reposos");
    datos.append("id", idTrabajador);
    enviaAjax(datos, function(respuesta, exito, fail) {
        try {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {
                if(esFechaActualOFutura(lee.mensaje.hasta)){

                    $("#myTabContent1").css('display','none');
                    $('#reposos_hasta').text('');
                    // Aquí puedes actualizar el modal con los detalles específicos de las vacaciones
                    $('#vacacionesModalLabel').text('Detalles de Vacaciones');
    
                    $('#reposos_hasta').append('En reposo hasta<br>');
                   
                    $('#reposos_hasta').append(lee.mensaje.hasta
                       /* ` <tr>
                            <td>${lee.mensaje.id_vacaciones}</td>
                            <td>${lee.mensaje.nombre_trabajador}</td>
                            <td>${lee.mensaje.descripcion}</td>
                            <td>${lee.mensaje.dias_totales}</td>
                            <td>${lee.mensaje.desde}</td>
                            <td>${lee.mensaje.hasta}</td>
                        </tr>
                    ` */);
    
                    $('#reposos_hasta').append(
                        
                        '<br><button id="boton_modificar_repo" class="btn btn-primary">Modificar Reposo</button>'
                        );   
    
                        $('#boton_modificar_repo').on('click', function() {
                           
                            $("#tipo_reposo").val(lee.mensaje.tipo_reposo)
                            $("#descripcion_reposo").val(lee.mensaje.descripcion)
                            $("#dias_totales_repo").val(lee.mensaje.dias_totales)
                            $("#fecha_inicio_reposo").val(lee.mensaje.desde)
                            $("#fecha_reincorporacion_reposo").val(lee.mensaje.hasta)
                            $("#id2").val(lee.mensaje.id_trabajador)
                            $("#id_tabla2").val(lee.mensaje.id_reposo)
                            $("#mm2").val('1');
    
                            
    
                            $('#reposos_hasta').text('');
                            $("#myTabContent1").css('display','block');
                        });          
                }

               
            } else {
                $("#myTabContent1").css('display','block');
                console.error(lee.mensaje);
            }
        } catch {
            console.log("myTabContent1");
            console.error(fail);
        }
    });
}

function obtenerDetallesPermisos(idTrabajador) {
    var datos = new FormData();
    datos.append("accion", "detalles_permisos");
    datos.append("id", idTrabajador);
    enviaAjax(datos, function(respuesta, exito, fail) {
        try {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "listar") {

                if(esFechaActualOFutura(lee.mensaje.desde)){

                    $("#myTabContent2").css('display','none');
                    $('#permisos_hasta').text('');
                    
                    // Aquí puedes actualizar el modal con los detalles específicos de las vacaciones
                    $('#vacacionesModalLabel').text('Detalles de Vacaciones');
    
                    $('#permisos_hasta').append('Trabajador en permiso <br>');
                   
                    $('#permisos_hasta').append(lee.mensaje.desde
                       /* ` <tr>
                            <td>${lee.mensaje.id_vacaciones}</td>
                            <td>${lee.mensaje.nombre_trabajador}</td>
                            <td>${lee.mensaje.descripcion}</td>
                            <td>${lee.mensaje.dias_totales}</td>
                            <td>${lee.mensaje.desde}</td>
                            <td>${lee.mensaje.hasta}</td>
                        </tr>
                    ` */);
    
                    $('#permisos_hasta').append(
                        
                        '<br><button id="boton_modificar_perm" class="btn btn-primary">Modificar Permiso</button>'
                        );   
    
                        $('#boton_modificar_perm').on('click', function() {
                           
                            $("#tipo_de_permiso").val(lee.mensaje.tipo_de_permiso)
                            $("#descripcion_permiso").val(lee.mensaje.descripcion)
                            $("#fecha_inicio_permiso").val(lee.mensaje.desde)
                            $("#id1").val(lee.mensaje.id_trabajador)
                            $("#id_tabla3").val(lee.mensaje.id_permisos)
                            $("#mm3").val('1');
    
                            
    
                            $('#permisos_hasta').text('');
                            $("#myTabContent2").css('display','block');
                        });          
                }


               
            } else {
                $("#myTabContent2").css('display','block');
                console.error(lee.mensaje);
            }
        } catch {
            console.error(fail);
        }
    });
}

function calcular_dia_habil(desde, dias, control) {
    var datos = new FormData();
    datos.append("accion", "calculo_habil");
    datos.append("desde", desde);
    datos.append("dias_totales", dias);
    enviaAjax(datos, function(respuesta, exito, fail) {
        try{
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "fecha_calculada") {
                console.log(respuesta);
               if (control === "1"){

                   $("#hasta").val(lee.fecha_final);
               } else if(control === "2"){
                $("#fecha_reincorporacion_reposo").val(lee.fecha_final);
               }else if(control === "3"){
                $("#fecha_reincorporacion_permiso").val(lee.fecha_final);
               }
                
            } else {
                console.error(lee.mensaje);
            }
        } catch (error){
            console.error(error);
        }
    });
}



//---------------------------------------------

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

function esFechaActualOFutura(fecha) {
    // Obtener la fecha actual
    const fechaActual = new Date();
    // Formatear la fecha actual como YYYY-MM-DD
    const hoy = fechaActual.toISOString().split('T')[0];
    // Formatear la fecha proporcionada como YYYY-MM-DD
    const fechaFormateada = new Date(fecha).toISOString().split('T')[0];

    // Comparar las dos fechas formateadas
    return fechaFormateada >= hoy;
}

function validarCampoTexto(input, regex, mensajeError) {
    var valor = $(input).val();
    if (!regex.test(valor.trim())) {  // trim to remove leading/trailing spaces
        $(input).addClass('is-invalid');
        muestraMensaje("Error", mensajeError, "error");
        return false;
    } else {
        $(input).removeClass('is-invalid');
        return true;
    }
}



function validarFormulario(formulario) {
    var valido = true;
    $(formulario).find('input').each(function() {
        if ($(this).hasClass('no-validar')) {
            return true; // Skip this input
        }
        if ($(this).attr('type') === 'text') {
            valido &= validarCampoTexto(this, /^[\w\s]+$/, "El campo debe contener solo letras y números.");
        }
    });
    return !!valido; // Convertir a booleano
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

