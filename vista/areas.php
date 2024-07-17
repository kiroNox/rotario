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
                            <h1 class="h3 mb-2 text-gray-800">Areas</h1>
                            <p class="mb-4">Las areas totales de los trabajadores

                        </div>
                        <div class="col-6">
                            <p class="mb-4">Registrar areas
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModalCenter">
                                    Registro de areas
                                </button>
                        </div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="modal fade" id="exampleModalToggle" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel">Modal 1</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Show a second modal and hide this one with the button below.
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle2"
                                        data-bs-toggle="modal" data-bs-dismiss="modal">Open second modal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel2">Modal 2</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Hide this modal and show the first with the button below.
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle"
                                        data-bs-toggle="modal" data-bs-dismiss="modal">Back to first</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                    <div class="card">
                        <div class="card-header">Lista de áreas</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered scroll-bar-style table-hover" id="tabla_areas">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-cell-aling-middle" id="tbody_areas">
                                        <tr>
                                            <td colspan="3" class="text-center">Cargando</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                            <h5 class="modal-title" id="exampleModalLongTitle">Registro del area</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                            <form class="col-12" action="" method="POST" onsubmit="return false" id="formularioAreas">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripcion</label>
                                        <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="codigo" class="form-label">Codigo</label>
                                        <input type="text" class="form-control" name="codigo" id="codigo" required>
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" id="botonEnvio" class="btn btn-primary">Registro</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- modal -->
    </div>
</body>


<!-- <script src="./vendor/jquery/jquery.min.js"></script> -->
<!-- <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- <script src="./vendor/bootstrap/js/bootstrap.js"></script> -->
<script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="./assets/js/sb-admin-2.min.js"></script>
<!-- <script src="./vendor/datatables/jquery.dataTables.min.js"></script> -->
<script src="./vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="./assets/js/datatables-demo.js"></script>
<script src="./assets/js/areas.js"></script>


</html>