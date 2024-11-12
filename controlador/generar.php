<?php



require_once('vendor/autoload.php');

 if (is_file("vista/" . $pagina . ".php")) {
    
    $cl = new generar;

  
  
    if (!empty($_POST)) {
        $accion = $_POST["accion"];
        if ($accion == "generarConstancia") {
            $id_trabajador = $_POST["id_trabajador"];

            $datos = $cl->obtenerDatosTrabajador($id_trabajador);

            if ($datos) {
                $nombre_completo = $datos['nombre'] . ' ' . $datos['apellido'];
                $cedula = $datos['cedula'];
                $fecha_ingreso = date('d-m-Y', strtotime($datos['creado']));
                $fecha_actual = date('d-m-Y');
                $cargo = $datos["cargo"];
                if($cargo!=''){
                    $cargo = " bajo el cargo de <b>$cargo</b>";
                }
        
                $html = "
<html>
<head>
    <meta charset='utf-8'>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            text-align: justify;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .signature {
            text-align: center;
            margin-top: 50px;
        }
        .signature p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Constancia de Trabajo</h1>
        <p>A quien pueda interesar:</p>
        <p>Quien suscribe, certifica que el(la) Sr(a). <b>$nombre_completo</b>, titular de la cédula de identidad <b>$cedula</b>, 
        presta sus servicios en nuestra institución desde el <b>$fecha_ingreso</b> hasta la fecha, desempeñándose con responsabilidad y eficiencia en las tareas asignadas$cargo.</p>
        <p>La presente constancia se expide a solicitud de la parte interesada en <b>$fecha_actual</b>.</p>
        <p>Atentamente,</p>
        <div class='signature'>
            <p><b>Nombre del Emisor</b></p>
            <p><b>Cargo del Emisor</b></p>
            <p><b>Servicio Desconcentrado Hospital Rotario</b></p>
        </div>
    </div>
</body>
</html>";

        
               // echo $html;
               // exit;
               	// Instanciamos un objeto de la clase DOMPDF.
                $pdf = new Dompdf\Dompdf();
                
                // Definimos el tamaño y orientación del papel que queremos.
                $pdf->set_paper("A4", "portrait");
                
                // Cargamos el contenido HTML.
                $pdf->load_html($html);
                
                // Renderizamos el documento PDF.
                $pdf->render();

                $pdfOutput = $pdf->output();
                $base64Data = base64_encode($pdfOutput);

                // Imprimir la cadena base64 para que JavaScript pueda capturarla
                echo $base64Data;

               
            } else {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'Trabajador no encontrado.']);
            }


       


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

       // echo $html;
       // exit;
     

       
    } else {
        echo json_encode(['resultado' => 'error', 'mensaje' => 'Trabajador no encontrado.']);
    }
}

?>
