<?php if(file_exists('../../assets/config/config.php')){
	require_once '../../assets/config/config.php'; 
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Clave</title>

    <style>
    	*{font-family: arial}
    </style>
</head>
<body>
    
<h1 style="text-align: center"><?= NOMBRE_EMPRESA  ?></h1>
<h2 style="text-align: center">Cambiar Clave</h2>
<div style="width:97%; margin: auto;"><p style="text-align: center;font-size: 15px; padding: 20px">¡Hola <?= $data['email']; ?>!</p></div>
<div class="item-date" style="text-align: center;">
<span style="color: #888787; text-align: center;">Para cambiar su clave debe presionar en el enlace de abajo 👇</span>
</div>
<div class="items item-footer" style="text-align: center;color: rgb(131, 205, 255);">
<p class="footer-link" style="text-align: center;"><a style="color: rgb(28, 158, 245);text-decoration: none;" href="<?= $data['url'] ?>" target="_blank">CLICK AQUI</a></p>
<p>Copyright &copy; <?= NOMBRE_EMPRESA ?></p>
</div>

</body>
</html>