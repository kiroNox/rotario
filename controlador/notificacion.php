<?php
// controlador.php
// controlador/notificacion.php

require_once("../assets/config/config.php");
require_once '../modelo/conexion.php';
require_once '../modelo/notificacion.php';

$cl = new notificaciones();

if (!empty($_POST) || !empty($_GET)) { // Si hay alguna consulta tipo POST o GET
    $accion = $_POST["accion"] ?? $_GET["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar
    
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


?>