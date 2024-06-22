<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
    <title>Asistente</title>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php   require_once("assets/comun/menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
        <?php   require_once("assets/comun/navar.php"); ?>
                <div class="container-fluid">                                                      

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Restaurar/Exportar BD</h1>
                    </div>
                    <div class="container">
                        <h1>Mantenimiento de Base de Datos</h1>

                        
    
                        <!-- Formulario para exportar la base de datos -->
                        <form id="exportarForm">
                            <button type="submit">Exportar Base de Datos</button>
                        </form>

                        <!-- Formulario para restaurar la base de datos -->
                      <!--   <form id="restaurarForm" enctype="multipart/form-data">
                            <input type="file" name="backupFile" id="backupFile" accept=".sql">
                            <button type="submit">Restaurar Base de Datos</button>
                        </form> -->

                        <br><br>
                        <h1>Listado de Backups</h1>
                        <table id="backupsTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre del archivo</th>
                                    <th>Tamaño</th>
                                    <th>Fecha de creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>






                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>

                        function loadBackups() {
                            $.get('modelo/list_backup.php', function(response) {
                                if (response.resultado == 'exito') {
                                    var tableData = response.backups.map(function(backup) {
                                        return [
                                            backup.filename,
                                            (backup.filesize / 1024).toFixed(2) + ' KB',
                                            backup.filemtime,
                                            '<button class="restoreBackup" data-file="' + backup.filepath + '">Restaurar</button>' +
                                            '<button class="deleteBackup" data-file="' + backup.filepath + '">Eliminar</button>'
                                        ];
                                    });

                                    $('#backupsTable').DataTable({
                                        data: tableData,
                                        destroy: true
                                    });
                                } else {
                                    alert(response.mensaje);
                                }
                            }, 'json');
                        }

                        $('#backupsTable').on('click', '.deleteBackup', function() {
                            var filePath = $(this).data('file').replace('../', '');
                            console.log(filePath);
                            if (confirm('¿Seguro que deseas eliminar este backup?')) {
                                $.post('', { accion: 'eliminar_backup', filePath: filePath }, function(response) {
                                    alert(response.mensaje);
                                    if (response.resultado == 'exito') {
                                        loadBackups();
                                    }
                                }, 'json');
                            }
                        });

                        $('#backupsTable').on('click', '.restoreBackup', function() {
                            var filePath = $(this).data('file').replace('../', '');
                            $.post('', { accion: 'restaurar_bd', filePath: filePath }, function(response) {
                                alert(response.mensaje);
                                if (response.resultado == 'exito') {
                                    loadBackups();
                                }
                            }, 'json');
                        });

            loadBackups();


                            $("#exportarForm").click(function() {
                                $.post('', { accion: 'exportar_bd' }, function(response) {
                                    alert(response.mensaje);
                                    if (response.resultado == 'exito') {
                                      //  window.location.href = response.archivo;
                                    }
                                }, 'json');
                            });

                            $("#restaurarForm").submit(function(event) {
                                event.preventDefault();
                                var formData = new FormData(this);

                                formData.append('accion', 'restaurar_bd');

                                $.ajax({
                                    url: '',
                                    type: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    dataType: 'json',
                                    success: function(response) {
                                        alert(response.mensaje);
                                    }
                                });
                            });
                        </script>
                    
                    </div>                                                                                     
            </div>
        </div>
        <?php   require_once("assets/comun/footer.php"); ?>
    </div>
</body>
    

<!-- Page level plugins -->
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

</html>