<?php
if (is_file("vista/" . $pagina . ".php")) {
    $cl = new generar;

    if (!empty($_POST)) {
        $accion = $_POST["accion"];
        if ($accion == "generarConstancia") {
            $id_trabajador = $_POST["id_trabajador"];
            generar_constancia($cl, $id_trabajador);
        } elseif ($accion == "listar") {
            if ($permisos["usuarios"]["consultar"]) {
                echo json_encode($cl->listar_usuarios());
            }
        }
        $cl->set_con(null);
        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    require_once("vista/404.php");
}

function generar_constancia($cl, $id_trabajador) {
    require_once 'vendor/autoload.php';

    $datos = $cl->obtenerDatosTrabajador($id_trabajador);

    if ($datos) {
        $nombre_completo = $datos['nombre'] . ' ' . $datos['apellido'];
        $cedula = $datos['cedula'];
        $fecha_ingreso = date('d-m-Y', strtotime($datos['creado']));
        $fecha_actual = date('d-m-Y');

        $html = "
        <h1>Constancia de Trabajo</h1>
        <p>A quien pueda interesar:</p>
        <p>Quien suscribe, certifica que el(la) Sr(a). <b>$nombre_completo</b>, titular de la cédula de identidad <b>$cedula</b>, 
        presta sus servicios en nuestra institución desde el <b>$fecha_ingreso</b> hasta la fecha, desempeñándose con responsabilidad y eficiencia en las tareas asignadas.</p>
        <p>La presente constancia se expide a solicitud de la parte interesada en <b>$fecha_actual</b>.</p>
        <br>
        <p>Atentamente,</p>
        <br><br>
        <p><b>Nombre del Emisor</b></p>
        <p><b>Cargo del Emisor</b></p>
        <p><b>Servicio Desconcentrado Hospital Rotario</b></p>
        ";

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('constancia_trabajo_' . $cedula . '.pdf', array("Attachment" => false));
    } else {
        echo json_encode(['resultado' => 'error', 'mensaje' => 'Trabajador no encontrado.']);
    }
}
?>
