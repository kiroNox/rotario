<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Rotario - Ausencias</title>
    
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
                
            <?php   require_once("assets/comun/navar.php"); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Administración de Ausencias</h1>
    
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
                                <label for="desde">Fecha de inicio</label>
                                <input type="date" class="form-control" name="desde" id="desde" required>
                            </div>
                            <div class="form-group">
                                <label for="dias_totales">Dias Totales</label>
                                <input type="text" class="form-control" name="dias_totales" id="dias_totales" required>
                            </div>  
                            <div class="form-group">
                                <label for="hasta">Fecha de reincorporación</label>
                                <input type="date" class="form-control" name="hasta" id="hasta" readonly>
                                <input type="text" class="form-control no-validar" name="id" id="id1" value="" style="display: none;"  >
                                <input type="text" class="form-control no-validar" id="mm" value="" style="display: none;"  >
                                <input type="text" class="form-control no-validar" id="id_tabla" name="id_tabla" value="" style="display: none;"  >
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                    <div class="container"><p id="vacaciones_hasta"></p></div>
                       
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cv_w" id="cerrar_modal" data-dismiss="modal">Cerrar</button>
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
                  
                    <div class="tab-content" id="myTabContent1">
                        
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
                                <label for="dias_totales_repo">Dias Totales</label>
                                <input type="text" class="form-control" name="dias_totales_repo" id="dias_totales_repo" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_reincorporacion_reposo">Fecha de reincorporación</label>
                                <input type="date" class="form-control" name="fecha_reincorporacion_reposo" id="fecha_reincorporacion_reposo" readonly>
                                <input type="text" class="form-control no-validar" name="id" id="id2" value="" style="display: none;">
                                <input type="text" class="form-control no-validar" id="id_tabla2" name="id_tabla2" value="" style="display: none;"  >
                                <input type="text" class="form-control no-validar" id="mm2" value="" style="display: none;"  >
                            
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>

                    <div class="container"><p id="reposos_hasta"></p></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cv_w" data-dismiss="modal">Cerrar</button>
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
                  
                    <div class="tab-content" id="myTabContent2">
                        
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
                                <label for="fecha_inicio_permiso">Fecha de Permiso</label>
                                <input type="date" class="form-control" name="fecha_inicio_permiso" id="fecha_inicio_permiso" required>
                            </div>
                            <div class="form-group">
                                 <input type="text" class="form-control no-validar" name="id" id="id3" value="" style="display: none;">
                                <input type="text" class="form-control no-validar" id="mm3" value="" style="display: none;"  >
                                <input type="text" class="form-control no-validar" id="id_tabla3" name="id_tabla3" value="" style="display: none;"  >
                                
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>

                    <div class="container"><p id="permisos_hasta"></p></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cv_w" data-dismiss="modal">Cerrar</button>
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


   
<script src="assets/js/sb-admin-2.min.js"></script>



</html>