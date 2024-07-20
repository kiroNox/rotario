<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'assets/comun/head.php'; ?>
    <title>Generar Constancia de Trabajo - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require_once("assets/comun/menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once("assets/comun/navar.php"); ?>
                <div class="container-fluid">
                    <main class="main-content">
                        <h1>Generar Constancia de Trabajo</h1>
                        <!-- Brand Buttons -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Listado de trabajadores</h6>
                            </div>
                            <div class="card-body" data-intro="Aqui tenemos un listado de trabajadores para gestionar sus Ausencias">
                                <div class="tab-pane " id="nav-consultar_usuarios" role="tabpanel" aria-labelledby="nav-consultar_usuarios-tab">
                                    <table class="table table-bordered table-hover" id="tabla_trabajadores" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Cedula</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_trabajadores" class="row-cursor-pointer">
                                            <!-- Filas generadas dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <?php require_once("assets/comun/footer.php"); ?>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
    <script>
        $(document).ready(function() {
            load_lista_usuarios();
            
            function load_lista_usuarios(){
                var datos = new FormData();
                datos.append("accion", "listar");
                enviaAjax(datos, function(respuesta, exito, fail){
                    var lee = JSON.parse(respuesta);
                    if(lee.resultado == "listar"){
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
                                        <button class="btn btn-success generar-constancia-btn" data-id="${data[8]}">Generar Constancia</button>
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

            $(document).on('click', '.generar-constancia-btn', function() {
                var id_trabajador = $(this).data('id');
                var formData = { accion: 'generarConstancia', id_trabajador: id_trabajador };
                $.post('', formData, function(response) {
                    console.log(response);
                });
            });
        });
    </script>
</body>
</html>
