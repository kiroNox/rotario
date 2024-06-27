<?php


if (is_file("vista/" . $pagina . ".php")) {

    $mantenimiento_OO = new restaurar_bd;

    if (!empty($_POST)) { 
        $accion = $_POST["accion"]; 

        if ($accion == "exportar_bd") { //Permisos debens er modificados luego _--__ Modificado solo para pruebas tempo
            if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
                $resp = $mantenimiento_OO->exportar_bd();
                echo json_encode($resp);
            } else {
                echo json_encode(["resultado" => "error", "titulo" => "Sin Permisos", "mensaje" => "No posee los permisos para realizar la acción"]);
            }

        } elseif ($accion == "restaurar_bd") {
            if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
                $filePath = $_POST["filePath"];
                $resp = $mantenimiento_OO->restaurar_bd($filePath);
                echo json_encode($resp);
            } else {
                $mantenimiento_OO->no_permision_msg();
            }
        } elseif ($accion == "eliminar_backup") {
            if (isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "1") {
                $filePath = $_POST["filePath"];
                if (file_exists($filePath)) {
                    unlink($filePath);
                    echo json_encode(['resultado' => 'exito', 'mensaje' => 'Backup eliminado correctamente']);
                } else {
                    echo json_encode(['resultado' => 'error', 'mensaje' => 'Archivo de backup no encontrado']);
                }
            } else {
                $mantenimiento_OO->no_permision_msg();
            }
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    require_once("vista/404.php");
}

?>