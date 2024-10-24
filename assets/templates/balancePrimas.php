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
        }
        table{
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }
        td,th{
            border: 1px solid #000;
        }

        thead tr:first-child th:first-child,
        thead tr:first-child th:last-child{
            border-left: none;
            border-right: none;
            border-top: none;
        }

        tbody td{
            padding: 10px;
        }
        
    </style>
</head>
<body>
    <div class='container'>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th colspan='2'><?= $mes ?></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Descripción</th>
                    <th>1 Quincena</th>
                    <th>2 Quincena</th>
                    <th>TOTAL PAGADO</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($lista as $row) { ?>
                    <tr>
                        <td><?= $row['descripcion'] ?></td>
                        <td><?= $row['quincena_uno'] ?></td>
                        <td><?= $row['quincena_dos'] ?></td>
                        <td><?= $row['total'] ?></td>
                    </tr>
                <?php } ?>

                
            </tbody>
        </table>
    </div>
</body>
</html>