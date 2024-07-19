<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'assets/comun/head.php'; ?>
    <title>Notificaciones - Servicio Desconcentrado Hospital Rotario</title>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require_once("assets/comun/menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once("assets/comun/navar.php"); ?>
                <div class="container-fluid">
                    <main class="main-content">
                        <h1>Notificaciones</h1>
                        <table class="table table-bordered table-hover" id="table_notificaciones">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Mensaje</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_notificaciones">
                            </tbody>
                        </table>
                    </main>
                </div>
            </div>
			<?php require_once("assets/comun/footer.php"); ?>
        </div>
    </div>
    <script src="assets/js/notificaciones.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
