<?php



require_once('vendor/autoload.php');
define("DOMPDF_ENABLE_REMOTE", false);

 if (is_file("vista/" . $pagina . ".php")) {
    
    $cl = new generar;

  
  
    if (!empty($_POST)) {
        $accion = $_POST["accion"];
        if ($accion == "generarConstancia") {
            $id_trabajador = $_POST["id_trabajador"];

            $datos = $cl->obtenerDatosTrabajador($id_trabajador);
            $datos2 = $cl->obtenerDatosJefe();

            if ($datos) {
                $nombre_completo = $datos['nombre'] . ' ' . $datos['apellido'];
                $cedula = $datos['cedula'];
                $fecha_ingreso = date('d-m-Y', strtotime($datos['creado']));
                $fecha_actual = date('d-m-Y');

                $path='rotario_logo.jpg';
                $logo ='data:image/jpg;base64,'.base64_encode(file_get_contents($path));
               
               
                $html = "
                <html>
                <head>
                    <meta charset='utf-8'>
                    <style>
                    body {
                        font-family: 'Roboto', sans-serif;
                        margin: 0;
                        padding: 0; 
                        background: #fff;
                        color: #333;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                    }
                
                    .container {
                        width: 80%;  
                        max-width: 600px;  
                        margin: 0 auto;
                        padding: 20px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        display: grid;
                        grid-template-columns: 1fr;
                        gap: 20px;
                    }
                
                    .header-container {
                        display: flex;
                        align-items: center;
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
                        <div class='header-container'>
                        <img src='$logo' width='50'>
                           
                            <h1>Constancia de Trabajo</h1>
                        </div>
                        <p>A quien pueda interesar:</p>
                        <p>Quien suscribe, certifica que el(la) Sr(a). <b>$nombre_completo</b>, titular de la cédula de identidad <b>$cedula</b>, 
                        presta sus servicios en nuestra institución desde el <b>$fecha_ingreso</b> hasta la fecha, desempeñándose con responsabilidad y eficiencia en las tareas asignadas.</p>
                        <p>La presente constancia se expide a solicitud de la parte interesada en <b>$fecha_actual</b>.</p>
                        <p>Atentamente,</p>
                        <div class='container'>
                            <div class='signature-section'>
                                <hr>
                                <div class='signature'>
                                    <p>Firma:</p>
                                    <p>$nombre_completo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
                </html>";

        
               //echo $html;
               //exit;
               	// Instanciamos un objeto de la clase DOMPDF.
                $pdf = new Dompdf\Dompdf();
                $pdf->set_option("enable_remote", true);
                
                
                
                // Cargamos el contenido HTML.
                $pdf->loadHtml($html);
                
                // Definimos el tamaño y orientación del papel que queremos.
                $pdf->setPaper("A4", "portrait");
                
                // Renderizamos el documento PDF.
                $pdf->render();

               //$pdf->stream("sdda.pdf", ["Attachment" => 0]);

                /* $outputString = $pdf->output();
                $pdfFile = fopen('my_pdf.pdf', 'w');
                fwrite($pdfFile, $outputString);
                fclose($pdfFile); */

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
    

    
$imageData1 = base64_encode(file_get_contents('rotario_logo.jpg'));



   
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
