<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'assets/comun/head.php'; ?>
<link rel="stylesheet" href="assets/css/calendario.css">
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
                        <h1 class="h3 mb-0 text-gray-800">Calendario</h1>
                    </div>
                                    <div class="container mt-5">
                    <div class="row">
                    <?php   require_once("assets/comun/calendario.php"); ?>
                    </div>
            
            
                </div>                                                                                                 
            </div>
        </div>
        <?php   require_once("assets/comun/footer.php"); ?>
    </div>
</body>

<script src="assets/js/calendario.js"></script>

</html>