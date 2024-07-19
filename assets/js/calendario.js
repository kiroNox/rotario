$(document).ready(function() {

    introJs().setOption("dontShowAgain", true).start();
     const $calendarBody = $('#calendar-body');
    const $monthYear = $('#monthYear');
    const $prevButton = $('#prev');
    const $nextButton = $('#next');
    const $todayButton = $('#today');
    const $eventModal = $('#eventModal');
    const $eventForm = $('#eventForm');
    const $eventTitleInput = $('#eventTitle');
    const $eventDateInput = $('#eventDate');
    const $deleteEventButton = $('#deleteEvent');
    const recurrentCheckbox = $('#recurrentCheckbox');
    const selectedDateInput = $('#selectedDate'); //aqui usamos el input que usaremos como datepiker

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    let eventosDelMes = []; // Variable para almacenar los eventos del mes actual

    function renderCalendar(year, month) {
        $calendarBody.empty();
        $monthYear.text(`${months[month]} ${year}`);

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let date = 1;
        const today = new Date();

        for (let i = 0; i < 6; i++) {
            const $row = $('<tr>');

            for (let j = 0; j < 7; j++) {
                const $cell = $('<td>');

                if (i === 0 && j < firstDay) {
                    $cell.addClass('empty');
                } else if (date > daysInMonth) {
                    $cell.addClass('empty');
                } else {
                    $cell.text(date);
                    const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    $cell.data('date', currentDate);
                    $cell.on('dblclick', () => openEventModal(currentDate));
                    $cell.on('click', () => selectDate(currentDate));

                    const eventosDelDia = eventosDelMes.filter(evento => evento.fecha === currentDate);
                    if (eventosDelDia.length > 0) {
                        $cell.addClass('has-event');
                        eventosDelDia.forEach(evento => {
                            const $eventElem = $('<div>').addClass('event').text(evento.descripcion);
                            $cell.append($eventElem);
                        });
                    }
                    
                   

                    if (today.getFullYear() === year && today.getMonth() === month && today.getDate() === date) {
                        $cell.addClass('current-day');
                    }

                    if (j === 6) {
                        $cell.addClass('saturday');
                    } else if (j === 0) {
                        $cell.addClass('sunday');
                    }

                    date++;
                }

                $row.append($cell);
            }

            $calendarBody.append($row);
        }
    }

    function openEventModal(date) {
        $eventDateInput.val(date);
        const eventData = eventosDelMes.find(evento => evento.fecha === date);
        if (eventData) {
            $eventTitleInput.val(eventData.descripcion);
            $deleteEventButton.show();
            recurrentCheckbox.prop('checked', eventData.recurrente == 1);
            $('#guardarEvent').text('Modificar evento');
        } else {
            $eventTitleInput.val('');
            $deleteEventButton.hide();
            $('#guardarEvent').text('Agregar evento');
        }
        $eventModal.modal('show');
    }

    function saveEvent(event) {
        event.preventDefault();
        const title = $eventTitleInput.val();
        const date = $eventDateInput.val();
        const recurrente = recurrentCheckbox.is(':checked') ? 1 : 0;
        const existingEvent = eventosDelMes.find(evento => evento.fecha === date);

        if (title) {
            if (existingEvent) {
                // Modificar evento existente
                $.post('', { accion: 'modificar_dia', descripcion: title, fecha: date, recurrente: recurrente }, function(response) {
                    cargarEventosDelMes(currentYear, currentMonth);
                    muestraMensaje("Exito", "Dia modificado con exito", "s");
                    $eventModal.modal('hide');
                }, 'json');
            } else {
                // Agregar nuevo evento
                $.post('', { accion: 'agregar_dia', descripcion: title, fecha: date, recurrente: recurrente }, function(response) {
                    cargarEventosDelMes(currentYear, currentMonth);
                    muestraMensaje("Exito", "Dia agregado con exito", "s");
                    $eventModal.modal('hide');
                }, 'json');
            }
        }
    }

    function selectDate(date) {
        selectedDateInput.val(date);
        console.log(date);
    }

    function deleteEvent() {
        const date = $eventDateInput.val();
        $.post('', { accion: 'eliminar_dia', fecha: date }, function(response) {
            cargarEventosDelMes(currentYear, currentMonth);
            muestraMensaje("Exito", "Dia eliminado con exito", "s");
            $eventModal.modal('hide');
        }, 'json');
    }

    function cargarEventosDelMes(year, month) {
        $.ajax({
            url: '', // URL de tu endpoint para obtener eventos del mes
            method: 'POST',
            data: {
                accion: 'obtener_dia',
                year: year,
                month: month + 1 // Ajustar para que el mes sea 1-based
            },
            dataType: 'json',
            success: function(response) {
                if (response.resultado === 'exito') {
                    console.log(response);
                    eventosDelMes = response.evento || []; // Almacenar eventos en la variable global
                    console.log(eventosDelMes);
                    renderCalendar(year, month); // Renderizar el calendario con los eventos obtenidos
                } else {
                    console.error('Error al obtener eventos del mes:', response.mensaje);
                    eventosDelMes = []; // Establecer eventos del mes como vacío si no hay eventos
                    renderCalendar(year, month); // Renderizar el calendario vacío
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
                eventosDelMes = []; // Establecer eventos del mes como vacío en caso de error
                renderCalendar(year, month); // Renderizar el calendario vacío
            }
        });
    }

    $prevButton.on('click', () => {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        cargarEventosDelMes(currentYear, currentMonth);
    });

    $nextButton.on('click', () => {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        cargarEventosDelMes(currentYear, currentMonth);
    });

    $todayButton.on('click', () => {
        currentYear = new Date().getFullYear();
        currentMonth = new Date().getMonth();
        cargarEventosDelMes(currentYear, currentMonth);
    });

    $eventForm.on('submit', saveEvent);
    $deleteEventButton.on('click', deleteEvent);

    // Inicialmente cargar eventos del mes actual
    cargarEventosDelMes(currentYear, currentMonth);
});


    $("#agg").on("click", function(){
        $('#exampleModal').modal('show');
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#send').on('click', function(e) {
        e.preventDefault();
        
        let valido = true;
        $("#f1 input").each(function(i, elem) {
            if (!$(elem).validarme()) {
                valido = false;
                return false;  // salir del bucle each
            }
        });

        if (!valido) return;

        var datos = new FormData($('#f1')[0]);
        datos.append("accion", "agregar_dia");
    
        var esRecurrente = $('#recurrente').is(':checked');
        var fechaInput = $('#fecha').val();
        if (esRecurrente && fechaInput) {
            var partesFecha = fechaInput.split("-");
            partesFecha[0] = "0000";
            var fechaModificada = partesFecha.join("-");
            datos.set('fecha', fechaModificada);
        }

        enviaAjax(datos, function(respuesta, exito, fail) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado === "registrar") {
                    muestraMensaje("Exito", "Dia agregado con exito", "s");
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


$.fn.validarme = function() {
    if ($(this).val().trim() === "") {
        $(this).addClass('is-invalid');
        return false;
    } else {
        $(this).removeClass('is-invalid');
        return true;
    }
};



