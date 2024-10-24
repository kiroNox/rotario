<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'assets/comun/head.php'; ?>
    <title>Generar Constancia de Trabajo - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top" class="<?= $modo_oscuro ?>">
    <div id="wrapper">
        <?php require_once("assets/comun/menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once("assets/comun/navar.php"); ?>
                <div class="container-fluid">
                    <main class="main-content">
                        <h1>Generar Balance de Primas</h1>
                        <!-- Brand Buttons -->
                        <div class="card shadow mb-4">
                            <div class="card-body" data-intro="Aqui tenem#os un listado de trabajadores para gestionar sus Ausencias">
                                <div class="tab-pane " id="nav-consultar_usuarios" role="tabpanel" aria-labelledby="nav-consultar_usuarios-tab">
                                    <form method="POST" id="f1" onsubmit="return false">
                                        <div class="row justify-content-center">
                                            <div class="col" style="max-width: 500px;">
                                                <input required type="date" id="fecha_1" name="fecha_desde" class="form-control">
                                            </div>
                                        </div>
                                        <input required type="date" id="fecha_2" name="fecha_hasta" value="2024-12-12" class="d-none">
                                        <div class="text-center">
                                            <button class="btn btn-primary mt-3" type="submit">Generar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <?php require_once("assets/comun/footer.php"); ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            document.getElementById('f1').onsubmit=function(e){
                e.preventDefault();

                var datos = new FormData(this);
                datos.append("accion","generar_balance_primas");
                datos.append("tipo","primas");
                enviaAjax(datos,function(respuesta, exito, fail){
                
                    var lee = JSON.parse(respuesta);
                    if(lee.resultado == "generar_balance"){

                        if(lee.blob){

                            let binaryData = atob(lee.blob);
                            let binaryArray = new Uint8Array(binaryData.length);
                            for (let i = 0; i < binaryData.length; i++) {
                                binaryArray[i] = binaryData.charCodeAt(i);
                            }
                            let blob = new Blob([binaryArray], { type: 'application/pdf' });
                            let url = URL.createObjectURL(blob);
                            let a = document.createElement('a');
                            a.href = url;
                            a.download = "PRIMAS-"+lee.fecha+".pdf";
                            a.click();

                        }



                        
                    }
                    else if (lee.resultado == 'is-invalid'){
                        muestraMensaje(lee.titulo, lee.mensaje,"error");
                    }
                    else if(lee.resultado == "error"){
                        muestraMensaje(lee.titulo, lee.mensaje,"error");
                        console.error(lee.mensaje);
                    }
                    else if(lee.resultado == "console"){
                        console.log(lee.mensaje);
                    }
                    else{
                        muestraMensaje(lee.titulo, lee.mensaje,"error");
                    }
                });
            };



            $(document).on('click', '.generar-constancia-btn', function() {
                var id_trabajador = $(this).data('id');
                var formData = { accion: 'generarConstancia', id_trabajador: id_trabajador };
                $.post('', formData, function(response) {
                    console.log(response);

                let binaryData = atob(response);
                let binaryArray = new Uint8Array(binaryData.length);
                for (let i = 0; i < binaryData.length; i++) {
                    binaryArray[i] = binaryData.charCodeAt(i);
                }
                let blob = new Blob([binaryArray], { type: 'application/pdf' });
                let url = URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'archivo.pdf';
                a.click();
                });
            });
        });
    </script>
</body>
</html>