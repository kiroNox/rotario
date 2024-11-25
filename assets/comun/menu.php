<?php if(isset($permisos)){?>

<div class="d-flex">
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

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
    
    <?php 

    // TODO agregar los permisos a los que tienen por permiso 'innecesario'

        $obj = new stdClass();
        $lista_separadores = [];

        $obj->{"separator"} = "Recursos Humanos";
        $obj->items = [];
        $obj->items[] = [
            "collapse"=>"Trabajadores",
            "icono"=>"fas fa-fw fa-cog",
            "lista" => [
                ["permisos"=>"usuarios", "href"=>"trabajadores_user","descrip" => "Gestionar Trabajadores"],
                ["permisos"=>"hijos", "href"=>"hijos","descrip" => "Gestionar Hijos"]
            ]
        ];

        $obj->items[] = [
            "collapse"=>"Administración",
            "icono"=>"fas fa-fw fa-wrench",
            "lista" => [
                ["permisos"=>"asistencias", "href"=>"administrar_empleado","descrip" => "Administrar Ausencias"],
                ["permisos"=>"educacion", "href"=>"nivel_educativo","descrip" => "Gestionar Nivel Educativo"]
            ]
        ];
        $obj->items[] = [
            "collapse"=>"Areas/Asistencias",
            "icono"=>"fas fa-fw fa-folder",
            "lista" => [
                ["permisos"=>"areas", "href"=>"areasTrabajador","descrip" => "Áreas Trabajador"],
                ["permisos"=>"areas", "href"=>"areas","descrip" => "Áreas"],
                ["permisos"=>"asistencias", "href"=>"asistencia","descrip" => "Asistencias"]
            ]
        ];
        $obj->items[] = [
            "collapse"=>"Documentos",
            "icono"=>"fas fa-fw fa-folder",
            "lista" => [
                ["permisos"=>"innecesario", "href"=>"generar","descrip" => "Generar Documentos"],
                ["permisos"=>"innecesario", "href"=>"generar_balance","descrip" => "Generar Balance de primas"]
            ]
        ];

        $lista_separadores[] = $obj;

        $obj = new stdClass();
        $obj->{"separator"} = "Nomina";
        $obj->items=[];
        $obj->items[] = [
            "collapse"=>"Gestión de Nomina",
            "icono"=>"fas fa-fw fa-folder",
            "lista" => [
                ["permisos"=>"sueldo", "href"=>"sueldo", "descrip" => "Gestionar Sueldos"],
                ["permisos"=>"primas", "href"=>"primas", "descrip" => "Gestionar Primas"],
                ["permisos"=>"deducciones", "href"=>"deducciones", "descrip" => "Gestionar Deducciones"],
                ["permisos"=>"formulas", "href"=>"formulas", "descrip" => "Gestionar Formulas"]
            ]
        ];
        $obj->items[] = [
            "collapse"=>"Pagos",
            "icono"=>"fas fa-fw fa-folder",
            "lista" => [
                ["permisos"=>"facturas", "href"=>"facturar", "descrip" => "Calcular Pagos"],
                ["permisos"=>"liquidacion", "href"=>"liquidacion", "descrip" => "Gestionar Liquidaciones"]
            ]
        ];
        $obj->items[] = [
            "collapse"=>"Calendario",
            "icono"=>"bi bi-calendar-day",
            "href"=>"calendario",
            "permisos" => "innecesario"
        ];
        $obj->items[] = [
            "collapse"=>"Estadisticas",
            "icono"=>"bi bi-bar-chart-line-fill",
            "href"=>"estadisticas",
            "permisos" => "innecesario"
        ];

        $lista_separadores[] = $obj;

        $obj = new stdClass();

        $obj->{"separator"} = "Administración De Usuarios";
        $obj->items=[];
        $obj->items[] = [
            "collapse"=>"Seguridad",
            "icono"=>"fas fa-fw fa-folder",
            "lista" => [
                ["permisos"=>"innecesario", "href"=>"notificaciones", "descrip" => "Notificaciones"],
                ["permisos"=>"bitacora", "href"=>"bitacora", "descrip" => "Bitácora"],
                ["permisos"=>"roles", "href"=>"roles", "descrip" => "Roles"],
                ["permisos"=>"permisos", "href"=>"permisos_usuario", "descrip" => "Roles y Permisos"]
            ]
        ];
        

        $lista_separadores[] = $obj;

        $obj = new stdClass();
        $obj->{"separator"} = "Ayuda";
        $obj->items = [];
        $obj->items[] = [
            "collapse" => "Documentación",
            "icono" => "fas fa-fw fa-book",
            "lista" => [
                ["permisos" => "innecesario", "href" => "manual_usuario", "descrip" => "Manual del Usuario"]
            ]
        ];

$lista_separadores[] = $obj;



        function print_items($items,$n,$active=false){
            global $permisos;

            // showvar($items,false);
            if(isset($items["lista"])){
                $print='';
                $showControl="";
                foreach ($items["lista"] as $link) {
                    if($link["permisos"] == 'innecesario' or (isset($permisos[$link["permisos"]]["consultar"]) and $permisos[$link["permisos"]]["consultar"] == "1")){
                        $ref = $link["href"];
                        $descrip = $link["descrip"];
                        $print .= "<a class=\"collapse-item\" href=\"?p=$ref\">$descrip</a>";

                        $_GET['p'] = $_GET['p'] ?? "dashboard";

                        if($showControl=="" and $_GET['p'] === $ref){
                            $showControl ="show";
                        }
                    }
                }
                if($print!=''){
                    if(isset($items["icono"])){
                        $icono = $items["icono"];
                    }
                    else{
                        $icono ="fa fa-fw fa-folder";
                    }
                    


                    $print = '<div class="bg-white py-2 collapse-inner rounded">'.$print.'</div>';
                    $print = "<div id=\"collapse-items-$n\" class=\"collapse $showControl\" aria-labelledby=\"head-items-$n\" data-parent=\"#accordionSidebar\">".$print."</div>";
                    $print = "<a class=\"nav-link\" href=\"#\" data-toggle=\"collapse\" data-target=\"#collapse-items-$n\" aria-expanded=\"true\" aria-controls=\"collapse-items-$n\">".
                    "<i class=\"$icono\"></i>".
                    "<span>".$items["collapse"]."</span> </a>".$print;

                    $print = '<li class="nav-item">'.$print.'</li>';

                }
                return $print;
            }
            else{
                if( $items["permisos"] == 'innecesario' or $permisos[$items["permisos"]]["consultar"] == "1"){
                    $print = <<<EOD
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="?p={$items['href']}">
                            <i class="{$items['icono']}"></i>
                            <span>{$items['collapse']}</span>
                        </a>
                        
                    </li>
                    EOD;
                }
                else{
                    $print = '';
                }

                return $print;



            }
        }


        function print_menu($lista){
            $i=1;
            foreach ($lista as $separator) {
                
                $printing = '';
                $found = false;
                foreach ($separator->items as $items) {
                    $printing .= print_items($items,$i++);
                    if($printing!=''){
                        $found = true;
                    }
                }

                if($found){

                    $printing = "<div class=\"sidebar-heading\"> ".$separator->{"separator"}." </div>".$printing;
                    echo $printing;

                }

            }
        }

     ?>

<?php 
print_menu($lista_separadores);
 ?>



<!-- 
<div class="sidebar-heading">
    Recursos humanos
</div> -->



<!-- Nav Item - Pages Collapse Menu -->

<!-- <li class="nav-item active">
    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Trabajadores</span>
    </a>
    <div id="collapseTwo" class="collapse " aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=trabajadores_user">Gestionar Trabajadores</a>
            <a class="collapse-item" href="?p=hijos">Gestionar Hijos</a>
        </div>
    </div>
</li> -->

<!-- Nav Item - Utilities Collapse Menu -->
<!-- <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Administración</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?p=administrar_empleado">Administrar Ausencias</a>
            <a class="collapse-item" href="?p=nivel_educativo">Gestionar Nivel Educativo</a>
            
        </div>
    </div>
</li> -->



<!-- Divider -->


<!-- Heading -->
<!-- Nav Item - Pages Collapse Menu -->
<!-- <li class="nav-item">
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
</li> -->

<!-- <li class="nav-item">
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
<hr class="sidebar-divider"> -->

<!-- Heading -->
<!-- <div class="sidebar-heading">
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
</li> -->

<!-- 
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
</li> -->

<!-- <li class="nav-item">
    <a class="nav-link collapsed" href="?p=calendario" aria-expanded="true" aria-controls="collapseFacturas">
        <i class="bi bi-calendar-day"></i>
        <span>Calendario</span>
    </a>
    
</li> -->
<!-- 
<li class="nav-item">
    <a class="nav-link collapsed" href="?p=estadisticas"  
        aria-expanded="true" aria-controls="collapseFacturas">
        <i class="bi bi-bar-chart-line-fill"></i>
        <span>Estadisticas</span>
    </a>
    
</li> -->
<!-- 
<div class="sidebar-heading">
    Administración de usuario
</div>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdministracion"
        aria-expanded="true" aria-controls="collapseAdministracion">
        <i class="fas fa-fw fa-folder"></i>
        <span>Seguridad</span>
    </a>
    <div id="collapseAdministracion" class="collapse" aria-labelledby="headingNomina" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="?p=notificaciones">Notififcaciones</a>
            <a class="collapse-item" href="?p=bitacora">Bitácora</a>
            <a class="collapse-item" href="?p=roles">Roles</a>
            <a class="collapse-item" href="?p=permisos_usuario">Permisos</a>
        </div>
    </div>
</li> -->
<!-- <li class="nav-item">
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
</li> -->
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->
</div>
<?php } ?>