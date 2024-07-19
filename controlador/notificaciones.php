<?php
	if(is_file("vista/".$pagina.".php")){

        $cl = new notificaciones();

        if (!empty($_POST) ) { // Si hay alguna consulta tipo POST o GET
            $accion = $_POST["accion"] ; // Siempre se pasa un parámetro con la acción que se va a realizar
            
            if ($accion == "getUpcomingVacations") {
                $upcomingVacations = $cl->getUpcomingVacations();
                echo json_encode($upcomingVacations);
            } elseif ($accion == "getNotifications") {
                $notificaciones = $cl->obtenerNotificaciones();
                echo json_encode($notificaciones);
            }
        
            $cl->set_con(null);
            exit;
        }
            Bitacora::ingreso_modulo("Bitácora");
            require_once("vista/".$pagina.".php");
        }
    
else{
    require_once("vista/404.php"); 
}
?>
