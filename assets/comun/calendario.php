<div class="col-md-8 offset-md-2">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
                <button id="prev" class="btn btn-primary" data-intro="Aqui vamos a un mes anterior">Anterior</button>
            <h2 id="monthYear"></h2>
            <button id="next" class="btn btn-primary" data-intro="Aqui vamos a un mes posterior">Siguiente</button>
            <button id="today" class="btn btn-secondary">Hoy</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dom</th>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mié</th>
                        <th>Jue</th>
                        <th>Vie</th>
                        <th>Sáb</th>
                    </tr>
                </thead>
                <tbody id="calendar-body" data-intro="Para poder registrar un dia, damos doble click en el dia deseado">
                    <!-- Calendar days will be injected here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for adding/editing events -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel" >Agregar/Editar Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="form-group">
                        <label for="eventTitle">Título del Evento</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="form-group">
                    <label for="recurrentCheckbox">Recurrente Anualmente</label>
                    <input type="checkbox" id="recurrentCheckbox">
                    </div>
                    <input type="hidden" id="eventDate">
                    <button type="submit" class="btn btn-primary" id="guardarEvent">Guardar</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>