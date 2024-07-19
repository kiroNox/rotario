$(document).ready(function() { 


//--------______---__---_--_--_---__--__--_---__--

    $.ajax({
        url: '', // Ruta a tu controlador PHP
        type: 'POST',
        data: { accion: 'obtener_vacaciones_anuales' },
        success: function(response) {
            var data = JSON.parse(response);
            var labels = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic"];
            var dataset = new Array(12).fill(0);
    
            data.forEach(function(item) {
                dataset[item.mes - 1] = item.total_empleados;
            });
    
            var graphData = {
                labels: labels,
                datasets: [{
                    label: "Empleados",
                    data: dataset,
                    backgroundColor: 'rgba(9, 129, 176, 0.2)'
                }]
            };
    
            var config = {
                type: 'bar',
                data: graphData,
            };
    
            var graph = document.querySelector("#grafica");
            new Chart(graph, config);
        }
    });
    
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

});