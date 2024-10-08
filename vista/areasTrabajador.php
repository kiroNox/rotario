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
                            <h1 class="h3 mb-2 text-gray-800">Areas Trabajadores</h1>
                            <p class="mb-4">Las areas totales de los trabajadores

                        </div>
                        <div class="col-6">
                            <p class="mb-4">Registrar asistencias
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#ModalCenter">
                                    Registro de asistencia
                                </button>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card">
                        <div class="card-header">Lista de areas de trabajadores</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered scroll-bar-style table-hover" id="tabla_asistencias">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>Trabajador</th>
                                            <th>Cedula</th>
                                            <th>Area</th>
                                            <th>codigo</th>
                                            <th>opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-cell-aling-middle" id="tbody_asistencias">
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

    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="ModalLongTitle">Registrar Areas trabajadores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="" method="POST" id="f1">
                            <div class="row">
                                <div class="col-12">
                                    <label for="select" class="form-label">Trabajador </label>
                                    <select name="select" id="select" class="form-select" aria-label="Default select example">
                                    </select>

                                </div>
                                <div class="col-12">
                                    <label for="select2" class="form-label">Area</label>
                                    <select name="select2" id="select2" class="form-select" aria-label="Default select example">
                                    </select>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">cerrar</button>
                                <div class="row mt-3">
                                    <div class="col text-center">

                                        <input type="submit" class="btn btn-primary" id="submit_btn" value="Registrar">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    </div>
</body>

<script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.js"></script>
<script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="./assets/js/sb-admin-2.min.js"></script>
<script src="./vendor/datatables/jquery.dataTables.min.js"></script>
<script src="./vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="./assets/js/datatables-demo.js"></script>
<script src="./assets/js/areasTrabajador.js"></script>

</html>