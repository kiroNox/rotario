const IntroSteps = {
    dontShowAgain:true,
    steps:[{
        element: document.querySelector('.intro-point-1'),
        title: "Hola!",
        intro: "Bienvenido al Sistema de Recursos Humanos! 👋"
        },
        {
            element: document.querySelector('.intro-point-2'),
            intro: "Acá tenemos un resumen de varios aspectos importantes del sistema"
        },
        {
            element: document.querySelector('#accordionSidebar'),
            intro: "Aquí podemos acceder a diferentes módulos del sistema"
        },
        {
            element: document.querySelector('#userDropdown'),
            intro: "Desde aquí podemos cerrar la sesión actual"
        },
        ]
} 
const Intro = introJs();
$(document).ready(function() {
    Intro.onbeforechange((elem)=>{
        if(elem.id=="accordionSidebar"){
            document.getElementById('sidebarToggleTop').click();
        }
    })
    Intro.setOptions( IntroSteps ).start();

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