$(document).ready(function() { 


//--------______---__---_--_--_---__--__--_---__--

// Para el gráfico de niveles educativos
$.ajax({
    url: '', // Ruta a tu controlador PHP
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

});