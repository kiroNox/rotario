$(document).ready(function() { 
    

    //resumen 

    $(document).on('click', '.estadistica-btn', function() {
        var cedula = $(this).data('cedula');
        console.log(cedula);
        limpiarEstadisticas();
        // Lógica para mostrar el modal de estadísticas
        $('#exampleModal1').modal('show');
        $('#cedula1').val(cedula);
    });
    
    $(document).on('click', '#estadistica1', function() {
        cedulaN=$('#cedula1').val();
        console.log("sakld");
        limpiarEstadisticas();
        mostrarResumenTrabajador(cedulaN);
        
    });
    

$('#tablaEmpleados').DataTable({
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
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "", // URL de tu servidor o controlador
        "type": "POST",
        "data": {
            "accion": "listarEmpleados"
        }
    },
    "columns": [
        { "data": "cedula" },
        { "data": "nombre" },
        { "data": "apellido" },
        { "data": null, "orderable": false, "searchable": false } // Columna para los botones
    ],
    "createdRow": function(row, data) {
        // Almacenar la cédula directamente en el botón
        $(row).find('td:last').html(`
            <button class="btn btn-success estadistica-btn" data-cedula="${data.cedula}" data-action="estadisticas">Ver estadísticas</button>
        `);
    }
});


//--------______---__---_--_--_---__--__--_---__--

// Para el gráfico de niveles educativos
$.ajax({
    url: '', 
    type: 'POST',
    data: { accion: 'obtener_niveles_educativos' },
    success: function(response) {
        var data = JSON.parse(response);
        var labels = data.map(function(item) { return item.nivel_educativo; });
        var dataset = data.map(function(item) { return item.total_empleados; });

        var pieData = {
            labels: labels,
            datasets: [{
                data: dataset,
                backgroundColor: ['#4e73df', '#15BB90', '#f6c23e', '#e74a3b', '#858796'],
                hoverBackgroundColor: ['#2e59d9', '#15BB90', '#70BB15', '#B5A02A', '#B5602A'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        };

        var ctx = document.getElementById("myPieChart");
        new Chart(ctx, {
            type: 'doughnut',
            data: pieData,
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    }
});





function mostrarDetalle(selector, items, tipo) {
    var container = $(selector);
    container.empty(); // Limpiar contenido previo

    items.forEach(function(item) {
        var itemHtml = "<div><strong>" + item.desde + " - " + item.hasta + ":</strong> " + item.descripcion;
        if (tipo === 'reposo' && item.tipo_reposo) {
            itemHtml += " (" + item.tipo_reposo + ")";
        }
        itemHtml += "</div>";
        container.append(itemHtml);
    });
}


function mostrarResumenTrabajador(id_trabajador) {
    console.log("asssss");
    $.ajax({
        url: '', 
        type: 'POST',
        data: { accion: 'obtener_resumen_trabajador', id_trabajador: id_trabajador },
        success: function(response) {
            console.log(response);
            var data = JSON.parse(response);
            console.log(data);

            // Datos generales
            $('#fechaIngreso').text("Fecha de Ingreso: " + data.fecha_ingreso);
            $('#tiempoTrabajo').text("Años de Trabajo: " + data.tiempo_trabajo);

            // Vacaciones
            mostrarDetalle('#detalleVacaciones', data.vacaciones, 'vacaciones');

            // Reposos
            mostrarDetalle('#detalleReposos', data.reposos, 'reposo');

            // Permisos
            mostrarDetalle('#detallePermisos', data.permisos, 'permiso');
        }
    });
}

$("#formulario1").on('submit', function(e) {
    e.preventDefault();

    if (validarFormulario("#formulario1")) {
        var datos = new FormData($('#formulario1')[0]);
        datos.append("accion", "obtener_resumen_trabajador");
      
        enviaAjax(datos, function(respuesta, exito, fail) {
            try {
                var lee = JSON.parse(respuesta);
        
                // Asignar valores principales
                $("#fechaIngreso").text(lee.fecha_ingreso);
                $("#tiempoServicio").text(lee.tiempo_trabajo);
                $("#porcVacaciones").text(calcularPorcentaje(lee.vacaciones));
                $("#porcReposos").text(calcularPorcentaje(lee.reposos));
                $("#porcPermisos").text(calcularPorcentaje(lee.permisos));
        
                // Limpiar tablas
                $("#vacacionesTable").empty();
                $("#repososTable").empty();
                $("#permisosTable").empty();
        
                // Llenar tabla de vacaciones
                lee.vacaciones.forEach(vacacion => {
                    $("#vacacionesTable").append(`
                        <tr>
                            <td>${vacacion.desde}</td>
                            <td>${vacacion.hasta}</td>
                            <td>${vacacion.descripcion}</td>
                            <td>${vacacion.dias}</td>
                        </tr>
                    `);
                });
        
                // Llenar tabla de reposos
                lee.reposos.forEach(reposo => {
                    $("#repososTable").append(`
                        <tr>
                            <td>${reposo.desde}</td>
                            <td>${reposo.hasta}</td>
                            <td>${reposo.descripcion}</td>
                            <td>${reposo.tipo_reposo}</td>
                            <td>${reposo.dias}</td>
                        </tr>
                    `);
                });
        
                // Llenar tabla de permisos
                lee.permisos.forEach(permiso => {
                    $("#permisosTable").append(`
                        <tr>
                            <td>${permiso.desde}</td>
                            <td>${permiso.descripcion}</td>
                        </tr>
                    `);
                });
                $("#resumenContenedor").fadeIn(500);
        
            } catch (error) {
                console.error("Error al parsear la respuesta JSON:", error);
                console.error("Respuesta recibida:", respuesta);
            }
        });
        
    }
});

function calcularPorcentaje(items) {
    const totalMeses = 12; // Suponiendo 12 meses en total
    return ((items.length / totalMeses) * 100).toFixed(1);
}

    
function setDateLimits() {
    $.ajax({
        url: '', // Ruta a tu controlador PHP
        type: 'POST',
        data: { accion: 'obtener_fecha_minima_vacaciones' },
        success: function(response) {
            var data = JSON.parse(response);
            var minDate = data.fecha_minima;

            $.ajax({
                url: '', // Ruta a tu controlador PHP
                type: 'POST',
                data: { accion: 'obtener_fecha_maxima_vacaciones' },
                success: function(response) {
                    var data = JSON.parse(response);
                    var maxDate = data.fecha_maxima;

                    // Establecer los límites en los inputs de fecha
                    $('#fecha_inicio').attr('min', minDate);
                    $('#fecha_fin').attr('min', minDate);
                    $('#fecha_inicio').attr('max', maxDate);
                    $('#fecha_fin').attr('max', maxDate);
                    cargarGrafico(minDate, maxDate);
                }
            });
        }
    });
}

function cargarGrafico(fecha_inicio, fecha_fin) {
    $.ajax({
        url: '', // Ruta a tu controlador PHP
        type: 'POST',
        data: { accion: 'obtener_vacaciones_y_reposos_por_rango_fechas', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
        success: function(response) {
            var data = JSON.parse(response);
            console.log(data);
            var labels = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic"];
            var datasetVacaciones = new Array(12).fill(0);
            var datasetReposos = new Array(12).fill(0);

            data.vacaciones.forEach(function(item) {
                datasetVacaciones[item.mes - 1] = item.total_empleados;
            });

            data.reposos.forEach(function(item) {
                datasetReposos[item.mes - 1] = item.total_empleados;
            });

            var porcentaje = (data.total_empleados / data.total_trabajadores * 100).toFixed(2);

            var graphData = {
                labels: labels,
                datasets: [{
                    label: "Vacaciones",
                    data: datasetVacaciones,
                    borderColor: 'rgba(190, 9, 65, 1)',
                    backgroundColor: 'rgba(190, 9, 65, 0.2)',
                    fill: true,
                },
                {
                    label: "Reposos",
                    data: datasetReposos,
                    borderColor: 'rgba(12, 92, 6, 1)',
                    backgroundColor: 'rgba(12, 92, 6, 0.2)',
                    fill: true,
                }]
            };

            var config = {
                type: 'bar',
                data: graphData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Porcentaje de Personal en Vacaciones: ' + porcentaje + '%'
                        }
                    }
                }
            };

            var graph = document.querySelector("#grafica");
            new Chart(graph, config);
            $('#porce').text('Porcentaje de Personal: ' + porcentaje + '%');
        }
    });
}

$('#filterForm').on('submit', function(e) {
    e.preventDefault();
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_fin = $('#fecha_fin').val();
    cargarGrafico(fecha_inicio, fecha_fin);
});

// Establecer límites en los inputs de fecha
setDateLimits();

// Cargar gráfico inicial sin filtro
cargarGrafico('2023-01-01', '2023-12-31');

function validarFormulario(formulario) {
    var valido = true;
    $(formulario).find('input').each(function() {
        if ($(this).hasClass('no-validar')) {
            return true; // Skip this input
        }
        if ($(this).attr('type') === 'text') {
            valido &= validarCampoTexto(this, /^[\w\s-]+$/, "El campo debe contener solo letras y números.");
        }
    });
    return !!valido; // Convertir a booleano
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

function limpiarEstadisticas() {
    $("#fechaIngreso").text("");
    $("#tiempoServicio").text("");
    $("#vacacionesTable").empty();
    $("#repososTable").empty();
    $("#permisosTable").empty();
    $("#resumenContenedor").hide();
    $("#fecha_inicio1").val('');
    $("#fecha_fin1").val('');

}

});