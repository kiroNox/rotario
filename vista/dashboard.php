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
    
    <link href="vendor/intro.js-7.2.0/package/minified/introjs.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

        <?php require_once 'assets/comun/head.php'; ?>

</head>

<body id="page-top" class="<?= $modo_oscuro ?>">

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
                <div class="container-fluid"  >
                 <a class="card-demo intro-point-1"></a>
                    <!-- Content Row -->
                    <div class="intro-point-2">

                    <div class="row" >

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Trabajadores Registrados</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalTrabajadores"></div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-person-vcard-fill" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Vacaciones Activas</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalVacacionesActivas"></div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-controller" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Areas Registradas
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="totalAreas"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-textarea" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <div class="row">
                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Facturas Totales</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFacturas"></div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-receipt" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Permisos Totales</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPermisos"></div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-file-person-fill" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Reposos Totales</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalReposos"></div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-house-door-fill" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Hijos Registrados</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalHijos"></div>
                                        </div>
                                        <div class="col-auto">
                                        <i class="bi bi-backpack2" style="font-size:30px;"></i>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    </div>

                    <!-- Content Row -->

                   

                                    <!-- Content Row -->
                                    <div class="row">
                    <div class="col-lg-6 mb-4">
                        <!-- Illustrations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Historia</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                </div>
                                <p>El Servicio Desconcentrado Hospital Rotario (SDHR) adscrito la 
                                    secretaria del Poder Popular para la Salud, con dependencia jerárquicamente
                                    y administrativamente de la Gobernación del Estado Lara, fue creado el
                                    11 de septiembre del 2020 según Gaceta Oficial Ordinaria 24.607 del Estado Lara</p>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <!-- Approach -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Mision SDHR</h6>
                            </div>
                            <div class="card-body">
                                <p>El SDHR tiene como objeto principal la prestación de un servicio integral
                                    asistencial de atención médica, hospitalaria a la salud, creando, 
                                    desarrollando y aplicando procesos integrales de salud de óptima calidad</p>
                            </div>
                        </div>
                    </div>
                </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php   require_once("assets/comun/footer.php"); ?>
            </footer>
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

    <script src="assets/js/sb-admin-2.min.js"></script>

    
    <script src="vendor/chart.js/Chart.min.js"></script>
    
    
    <script src="vendor/intro.js-7.2.0/package/minified/intro.min.js"></script>
    
    <script src="assets/js/dashboard.js"></script>
    
</body>

</html>