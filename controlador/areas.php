<?php
if (is_file("vista/" . $pagina . ".php")) {
    $claseAreas = new Areas;

    //comprobamos si tenemos solicitudes
    if (!empty($_POST)) {
        $accion = $_POST["accion"];
        // comprobamos los permisos
        if (!isset($permisos["usuarios"]["crear"]) && $permisos["usuarios"]["crear"] == "0") {
            echo json_encode([
                'resultado' => 'error',
                'titulo' => 'Error',
                'mensaje' => 'No tienes permisos para realizar esta acción.'
            ]);
        }
        //segun el caso que llegue actuamos 
        switch ($accion) {

            case "index":
                //inicio
                echo json_encode($claseAreas->listar_areas());
                //fin
                break;

            case "create":
                // Obtener los datos JSON enviados
                // Obtener los datos enviados
                $descripcion = $_POST['descripcion'] ?? null;
                $codigo = $_POST['codigo'] ?? null;

                if ($descripcion && $codigo) {
                    $respuesta = $claseAreas->registrar_areas($descripcion, $codigo);
                    echo json_encode($respuesta);
                } else {
                    echo json_encode([
                        'resultado' => 'error',
                        'titulo' => 'Error',
                        'mensaje' => 'Faltan parámetros en la solicitud.'
                    ]);
                }
                break;

                // eliminar
            case "destroy":
               $respuesta = $claseAreas->eliminar_area($_POST["id"]);
                echo json_encode($respuesta);
                break;
                    //actualizar
            case "update":
                $respuesta = $claseAreas->actualizar_area(
                    $_POST["id"],
                    $_POST["codigo"],
                    $_POST["descripcion"]
                );
                echo json_encode($respuesta);
                break;
                // listar
            case "list":
                $areas = $claseAreas->listar_areas();
                echo json_encode([
                    'estado' => 200,
                    'datos' => $areas,
                    'mensaje' => 'Áreas listadas correctamente.'
                ]);
                break;
            default:
                echo json_encode([
                    'resultado' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Acción no válida.'
                ]);
                break;



        }

        $claseAreas->set_con(null);
        exit;
    }

    $claseAreas->set_con(null);
    Bitacora::ingreso_modulo("Areas");
    require_once ("vista/" . $pagina . ".php");
} else {
    require_once ("vista/404.php");
}

