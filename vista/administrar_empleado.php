<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>SB Admin 2 - Buttons</title>
    
    <!-- Custom fonts for this template-->
    <?php require_once 'assets/comun/head.php'; ?>
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
    
    
    
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
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Search -->
                    
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                        aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                placeholder="Search for..." aria-label="Search"
                                aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                
                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter">3+</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 12, 2019</div>
                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-donate text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 7, 2019</div>
                        $290.29 has been a into your account!
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 2, 2019</div>
                        Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>
        
        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-envelope fa-fw"></i>
            <!-- Counter - Messages -->
            <span class="badge badge-danger badge-counter">7</span>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header">
            Message Center
        </h6>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="img/undraw_profile_1.svg"
                alt="...">
                <div class="status-indicator bg-success"></div>
            </div>
            <div class="font-weight-bold">
                <div class="text-truncate">Hi there! I am wondering if you can help me with a
                    problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_2.svg"
                    alt="...">
                    <div class="status-indicator"></div>
                </div>
                <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how
                        would you like them sent to you?</div>
                        <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                        alt="...">
                        <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Last month's report looks great, I am very happy with
                            the progress so far, keep up the good work!</div>
                            <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                        </div>
                    </a>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image mr-3">
                            <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                            alt="...">
                            <div class="status-indicator bg-success"></div>
                        </div>
                        <div>
                            <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                told me that people say this to all dogs, even if they aren't good...</div>
                                <div class="small text-gray-500">Chicken the Dog · 2w</div>
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                    </div>
                </li>
                
                <div class="topbar-divider d-none d-sm-block"></div>
                
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                    <img class="img-profile rounded-circle"
                    src="img/undraw_profile.svg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
        
    </ul>
    
</nav>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Administración de Trabajador</h1>
    
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Registro</button>-->   
 
  
    <div class="row">
        
        <div class="col-lg-12">
            
            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen de trabajador</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2 card-vacaciones">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Empleados en vacaciones</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                            <!-- Earnings (Annual) Card Example -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2 card-reposos">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Empleados en reposo</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                                
                                
                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2 card-permisos">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permisos activos</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                        <!-- Brand Buttons -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Listado de trabajadores</h6>
                            </div>
                            <div class="card-body">
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
                        
                    </div>
                    
                    
                    
                </div>
                
            </div>
            <!-- /.container-fluid -->
            
        </div>
        <!-- End of Main Content -->
        
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
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

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="assets/js/administrar_empleado.js"></script>



<!-- Page level custom scripts -->
<script src="assets/js/datatables-demo.js"></script>

</body>

<div class="modal fade" id="exampleModal1"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Vacaciones</h5>
                    <button type="button" id="cerrar_mv" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" >&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"  style="display: none;" >
                        <form class="col-12" action="" method="POST" onsubmit="return false" id="f1">
                            <div class="form-group">
                                <label for="descripcion">Descripcion</label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                            </div>
                            <div class="form-group">
                                <label for="dias_totales">Dias Totales</label>
                                <input type="text" class="form-control" name="dias_totales" id="dias_totales" required>
                            </div>  
                            <div class="form-group">
                                <label for="desde">Fecha de inicio</label>
                                <input type="date" class="form-control" name="desde" id="desde" required>
                            </div>
                            <div class="form-group">
                                <label for="hasta">Fecha de reincorporación</label>
                                <input type="date" class="form-control" name="hasta" id="hasta" required>
                                <input type="text" class="form-control" name="id" id="id1" value="" style="display: none;"  >
                                <input type="text" class="form-control" id="mm" value="" style="display: none;"  >
                                <input type="text" class="form-control" id="id_tabla" name="id_tabla" value="" style="display: none;"  >
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                    <div class="container"><p id="vacaciones_hasta"></p></div>
                       
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cerrar_modal" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reposos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  
                    <div class="tab-content" id="myTabContent">
                        
                        <form class="col-12" action="" method="POST" onsubmit="return false" id="f2">
                            <div class="form-group">
                                <label for="tipo_reposo">Tipo de reposo</label>
                                <input type="text" class="form-control" name="tipo_reposo" id="tipo_reposo" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_reposo">Descripcion</label>
                                <input type="text" class="form-control" name="descripcion_reposo" id="descripcion_reposo" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio_reposo">Fecha de inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio_reposo" id="fecha_inicio_reposo" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_reincorporacion_reposo">Fecha de reincorporación</label>
                                <input type="date" class="form-control" name="fecha_reincorporacion_reposo" id="fecha_reincorporacion_reposo" required>
                                <input type="text" class="form-control" name="id" id="id2" value="" style="display: none;">
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                        
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal3"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  
                    <div class="tab-content" id="myTabContent">
                        
                        <form class="col-12" action="" method="POST" onsubmit="return false" id="f3">
                            <div class="form-group">
                                <label for="tipo_de_permiso">Tipo de permiso</label>
                                <input type="text" class="form-control" name="tipo_de_permiso" id="tipo_de_permiso" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_permiso">Descripcion</label>
                                <input type="text" class="form-control" name="descripcion_permiso" id="descripcion_permiso" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio_permiso">Fecha de inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio_permiso" id="fecha_inicio_permiso" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_reincorporacion_permiso">Fecha de reincorporación</label>
                                <input type="date" class="form-control" name="fecha_reincorporacion_permiso" id="fecha_reincorporacion_permiso" required>
                                <input type="text" class="form-control" name="id" id="id3" value="" style="display: none;">
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                        
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para Empleados en Vacaciones -->
<div class="modal fade" id="vacacionesModal" tabindex="-1" aria-labelledby="vacacionesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vacacionesModalLabel">Empleados en Vacaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover" id="tabla_vacaciones" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Descripción</th>
              <th>Desde</th>
              <th>Hasta</th>
            </tr>
          </thead>
          <tbody id="tbody_vacaciones">
            <!-- Filas generadas dinámicamente -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Empleados en Reposo -->
<div class="modal fade" id="reposoModal" tabindex="-1" aria-labelledby="reposoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reposoModalLabel">Empleados en Reposo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover" id="tabla_reposos" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Tipo de Reposo</th>
              <th>Descripción</th>
              <th>Desde</th>
              <th>Hasta</th>
            </tr>
          </thead>
          <tbody id="tbody_reposos">
            <!-- Filas generadas dinámicamente -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Permisos Activos -->
<div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="permisosModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="permisosModalLabel">Permisos Activos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover" id="tabla_permisos" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Tipo de Permiso</th>
              <th>Descripción</th>
              <th>Desde</th>
              <th>Hasta</th>
            </tr>
          </thead>
          <tbody id="tbody_permisos">
            <!-- Filas generadas dinámicamente -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" onsubmit="return false" id="f4">
                    <div class="form-row">
                        <label class="d-block" for="cedula">Cedula</label>
                        <input required type="text" class="form-control" id="cedula" name="cedula" data-span="invalid-span-cedula">
                        <span id="invalid-span-cedula" class="invalid-span text-danger"></span>
                        
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="d-block" for="nombre">Nombre</label>
                                <input required type="text" class="form-control" id="nombre" name="nombre" data-span="invalid-span-nombre">
                                <span id="invalid-span-nombre" class="invalid-span text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="d-block" for="apellido">Apellido</label>
                                <input required type="text" class="form-control" id="apellido" name="apellido" data-span="invalid-span-apellido">
                                <span id="invalid-span-apellido" class="invalid-span text-danger"></span> 
                            </div>                   
                    </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="d-block" for="correo">Correo</label>
                                <input required type="email" class="form-control" id="correo" name="correo" data-span="invalid-span-correo">
                                <span id="invalid-span-correo" class="invalid-span text-danger"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="d-block" for="num_cuenta">Telefono</label>
                                <input required type="text" class="form-control" id="telefono" name="telefono" data-span="invalid-span-num_telelfono">
                                <span id="invalid-span-num_telefono" class="invalid-span text-danger"></span> 
                            </div>
                            <div class="form-group col-md-4">
                                <label class="d-block" for="num_cuenta">Numero de cuenta</label>
                                <input required type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" data-span="invalid-span-num_cuenta">
                                <span id="invalid-span-num_cuenta" class="invalid-span text-danger"></span> 
                            </div>                    
                      </div>
                      <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="d-block" for="sexo">Sexo</label>
                                <input required type="text" class="form-control" id="sexo" name="sexo" data-span="invalid-span-sexo">
                                <span id="invalid-span-sexo" class="invalid-span text-danger"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="d-block" for="fecha_nacimiento">Fecha de nacimiento</label>
                                <input required type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" data-span="invalid-span-num_telelfono">
                                <span id="invalid-span-num_fecha_nacimiento" class="invalid-span text-danger"></span> 
                            </div>
                            <div class="form-group col-md-3">
                                <label class="d-block" for="instruccion">Instruccion</label>
                                <input required type="text" class="form-control" id="instruccion" name="instruccion" data-span="invalid-span-instruccion">
                                <span id="invalid-span-instruccion" class="invalid-span text-danger"></span> 
                            </div>  
                            <div class="form-group col-md-3">
                                <label class="d-block" for="salario">Salario</label>
                                <input required type="text" class="form-control" id="salario" name="salario" data-span="invalid-span-salario">
                                <span id="invalid-span-salario" class="invalid-span text-danger"></span> 
                            </div>                   
                      </div>
                    
                    
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

   
<script src="assets/js/sb-admin-2.min.js"></script>



</html>