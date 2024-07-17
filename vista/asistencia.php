<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'assets/comun/head.php'; ?>
    <title>Asistente</title>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once ("assets/comun/menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once ("assets/comun/navar.php"); ?>
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row">

                        <div class="col-6">
                            <h1 class="h3 mb-2 text-gray-800">Asistencias</h1>
                            <p class="mb-4">Las asistencias totales de los trabajadores

                        </div>
                        <div class="col-6">
                            <p class="mb-4">Registrar asistencias
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModalCenter">
                                    Registro de asistencia
                                    </button>
                        </div>
                    </div>
                  
                    <!-- DataTales Example -->
                    <div class="card">
                        <div class="card-header">Lista de asistencias</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered scroll-bar-style table-hover" id="tabla_areas">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Cedula</th>
                                            <th>Area</th>
                                            <th>codigo</th>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th>opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-cell-aling-middle" id="tbody_areas">
                                        <tr>
                                            <td colspan="7" class="text-center">Cargando</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                   
                </div>
            </div>
            <?php require_once ("assets/comun/footer.php"); ?>
        </div>
    </div>
                    <!-- modal -->

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                   
                    <h5 class="modal-title" id="exampleModalLongTitle">Registrar asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    </div>
</body>
<script src="./vendor/jquery/jquery.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.js"></script>
<script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="./assets/js/sb-admin-2.min.js"></script>
<script src="./vendor/datatables/jquery.dataTables.min.js"></script>
<script src="./vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="./assets/js/datatables-demo.js"></script>
<script src="./assets/js/asistencia.js"></script>

</html>