<div class="d-flex">
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" data-intro="Aqui podemos acceder a diferentes modulos del sistema" data-step="3">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="?p=dashboard">
    <div class="sidebar-brand-icon rotate-n-15">
    <i class="bi bi-hospital"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SDHR</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="?p=dashboard">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Inicio</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Recursos humanos
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item active">
    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
        aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Trabajadores</span>
    </a>
    <div id="collapseTwo" class="collapse " aria-labelledby="headingTwo"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <!-- <a class="collapse-item active" href="#">Personal</a> -->
            <a class="collapse-item" href="?p=trabajadores_user">Gestionar Trabajadores</a>
            <!-- <a class="collapse-item" href="?p=administrar_empleado">Trabajadores</a> -->
            <a class="collapse-item" href="?p=hijos">Gestionar Hijos</a>

        </div>
    </div>
</li>

<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Administración</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=administrar_empleado">Administrar Ausencias</a>
           <!--  <a class="collapse-item" href="#">Reposos</a>
            <a class="collapse-item" href="#">Vacaciones</a> -->
            <a class="collapse-item" href="?p=nivel_educativo">Gestionar Nivel Educativo</a>
            
        </div>
    </div>
</li>



<!-- Divider -->


<!-- Heading -->
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-folder"></i>
        <span>Áreas / Asistencias</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=areasTrabajador">Areas Trabajador</a>
            <a class="collapse-item" href="?p=areas">Áreas</a>
            <a class="collapse-item" href="?p=asistencia">Asistencias</a>
           
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1"
        aria-expanded="true" aria-controls="collapseUtilities1">
        <i class="fas fa-fw fa-folder"></i>
        <span>Documentos</span>
    </a>
    <div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=generar">Generar Documentos</a>
            
        </div>
    </div>
</li>
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Nomina
</div>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNomina"
        aria-expanded="true" aria-controls="collapseNomina">
        <i class="fas fa-fw fa-folder"></i>
        <span>Gestión de nomina</span>
    </a>
    <div id="collapseNomina" class="collapse" aria-labelledby="headingNomina" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=sueldo">Gestionar Sueldos</a>
            <a class="collapse-item" href="?p=primas">Gestionar Primas</a>
            <a class="collapse-item" href="?p=deducciones">Gestionar Deducciones</a>
            <a class="collapse-item" href="?p=liquidacion">Gestionar Liquidación</a>
        </div>
    </div>
</li>


<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFacturas"
        aria-expanded="true" aria-controls="collapseFacturas">
        <i class="fas fa-fw fa-folder"></i>
        <span>Pagos</span>
    </a>
    <div id="collapseFacturas" class="collapse" aria-labelledby="headingPagos" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=facturar">Gestionar Facturas de Pagos</a>
            
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="?p=calendario"  
        aria-expanded="true" aria-controls="collapseFacturas">
        <i class="bi bi-calendar-day"></i>
        <span>Calendario</span>
    </a>
    
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="?p=estadisticas"  
        aria-expanded="true" aria-controls="collapseFacturas">
        <i class="bi bi-bar-chart-line-fill"></i>
        <span>Estadisticas</span>
    </a>
    
</li>

<div class="sidebar-heading">
    Administración de usuario
</div>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdministracion"
        aria-expanded="true" aria-controls="collapseAdministracion">
        <i class="fas fa-fw fa-folder"></i>
        <span>Configuración</span>
    </a>
    <div id="collapseAdministracion" class="collapse" aria-labelledby="headingNomina" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="?p=notificaciones">Notififcaciones</a>
            <a class="collapse-item" href="?p=bitacora">Bitácora</a>
            <a class="collapse-item" href="?p=roles">Roles</a>
            <a class="collapse-item" href="?p=permisos_usuario">Permisos</a>
            <!-- <a class="collapse-item" href="#">Módulos</a> -->
        </div>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMantenimiento"
        aria-expanded="true" aria-controls="collapseMantenimiento">
        <i class="fas fa-fw fa-folder"></i>
        <span>Mantenimiento</span>
    </a>
    <div id="collapseMantenimiento" class="collapse" aria-labelledby="headingNomina" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=restaurar_bd">Restaurar/Exportar BD</a>
            
        </div>
    </div>
</li>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->
</div>

