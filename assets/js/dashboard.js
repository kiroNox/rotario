$(document).ready(function() {
    introJs().setOption("dontShowAgain", true).start();

    console.log("adds");
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'obtenerDatosDashboard' },
        success: function(response) {
            var data = JSON.parse(response);
            console.log(data);
            $('#totalTrabajadores').text(data[0]);
            $('#totalVacacionesActivas').text(data[1]);
            $('#totalAreas').text(data[2]);
            $('#totalFacturas').text(data[3]);
            $('#totalPermisos').text(data[4]);
            $('#totalReposos').text(data[5]);
            $('#totalHijos').text(data[6]);
        }
    });



});