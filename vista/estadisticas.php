<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

        <?php require_once 'assets/comun/head.php'; ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Un require Para el menu  -->
        <?php require_once 'assets/comun/menu.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php   require_once("assets/comun/navar.php"); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">


   

                    <div class="col-xl-12 col-lg-7">
    <div class="card shadow mb-4">
        <!-- Card Header - Título -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Empleados</h6>
        </div>

        <!-- Card Body - Contenido de la tabla -->
        <div class="card-body">
            <table id="tablaEmpleados" class="table table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Aquí se llenarán los datos con DataTables -->
            </table>
        </div>
    </div>
</div>



<!-- Area Chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Resumen de vacaciones (Anual)</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                    <div class="col-4">      

                        <form id="filterForm">
                            <div class="col-12" style="    padding-top: 19%;"> 
                                <div class="col-7">
                                    <label for="fecha_inicio">Fecha Inicio:</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                </div>
                                <div class="col-7">
                                    <label for="fecha_fin">Fecha Fin:</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class=" btn-primary">Filtrar</button>
                                </div>
                            </div>
                            <div class="col-12" style="    padding-top: 19%;">
                                <p id="porce"></p>
                            </div>
                        </form>
                    </div>
                    <div class="col-8">     
                            <div >
                                <canvas id="grafica"></canvas>
                            </div>
                        </div>    
                </div>
                    
            
            </div>
        </div>
    </div>  

<!-- Pie Chart -->
    <div class="col-xl-12 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Empleados Profesionales</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i> TSU
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Profesional
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> Especialista
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-danger"></i> Maestria
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-secondary"></i> Doctorado
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>



                    <!-- Content Row -->

                  ¿

                                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php   require_once("assets/comun/footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
        
    </div>

    <div class="modal fade" id="exampleModal1"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resumen Trabajador</h5>
                    <button type="button" id="cerrar_mv" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" >&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="row">
    <form id="formulario1" class="w-100">
        <input type="text" class="form-control" name="cedula1" id="cedula1" style="display: none;" required>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio1" name="fecha_inicio1" required>
            </div>
            <div class="col-md-4">
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" class="form-control" id="fecha_fin1" name="fecha_fin1" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">Mostrar estadística</button>
            </div>
        </div>
    </form>
</div>

                
<div id="resumenContenedor" class="card mb-4 shadow" style="display: none;">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Resumen del Trabajador</h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <p><strong>Fecha de Ingreso:</strong> <span id="fechaIngreso"></span></p>
                <p><strong>Años de Servicio:</strong> <span id="tiempoServicio"></span> años</p>
            </div>
        </div>

        <!-- Sección de Vacaciones -->
        <div class="mb-4">
            <h5>Vacaciones</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Descripción</th>
                        <th>Días</th>
                    </tr>
                </thead>
                <tbody id="vacacionesTable"></tbody>
            </table>
        </div>

        <!-- Sección de Reposos -->
        <div class="mb-4">
            <h5>Reposos</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Descripción</th>
                        <th>Tipo de Reposo</th>
                        <th>Días</th>
                    </tr>
                </thead>
                <tbody id="repososTable"></tbody>
            </table>
        </div>

        <!-- Sección de Permisos -->
        <div class="mb-4">
            <h5>Permisos</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Desde</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody id="permisosTable"></tbody>
            </table>
        </div>
    </div>
</div>


                    <div class="container"><p id="vacaciones_hasta"></p></div>
                       
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cv_w" id="cerrar_modal" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    

    <script src="assets/js/sb-admin-2.min.js"></script>

    <script src="vendor/chart.js/Chart.min.js"></script>

    <script src="assets/js/estadisticas.js"></script>
    

</body>

</html>