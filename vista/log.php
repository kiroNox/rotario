<!DOCTYPE html>
<html lang="es">
	<head>

		<?php require_once 'assets/comun/head.php'; ?>
		<link rel="stylesheet" href="assets/general/bootstrap-icons/bootstrap-icons.min.css">

		<title>Login</title>

		<style>
			.bd-placeholder-img {
				font-size: 1.125rem;
				text-anchor: middle;
				-webkit-user-select: none;
				-moz-user-select: none;
				user-select: none;
			}

			@media (min-width: 768px) {
				.bd-placeholder-img-lg {
					font-size: 3.5rem;
				}
			}

			.b-example-divider {
				height: 3rem;
				background-color: rgba(0, 0, 0, .1);
				border: solid rgba(0, 0, 0, .15);
				border-width: 1px 0;
				box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
			}

			.b-example-vr {
				flex-shrink: 0;
				width: 1.5rem;
				height: 100vh;
			}

			.bi {
				vertical-align: -.125em;
				fill: currentColor;
			}

			.nav-scroller {
				position: relative;
				z-index: 2;
				height: 2.75rem;
				overflow-y: hidden;
			}

			.nav-scroller .nav {
				display: flex;
				flex-wrap: nowrap;
				padding-bottom: 1rem;
				margin-top: -1px;
				overflow-x: auto;
				text-align: center;
				white-space: nowrap;
				-webkit-overflow-scrolling: touch;
			}
		</style>


		
		<!-- Custom styles for this template -->
		<link href="assets/css/login.css" rel="stylesheet">
	</head>
	<body class="text-center">
		
<main class="w-100 m-auto">
	<form action="" method="POST" id="f1" onsubmit="return false">
		<img class="mb-4" src="assets/img/comun/rotario_logo.jpg" alt="" width="180" height="180">
		<h1 class="h3 mb-3 fw-normal">Servicio Desconcentrado Hospital Rotario</h1>
		<div class="form-singin m-auto">
			<div class="form-floating">
				<label for="user">Correo</label>
				<input type="text" class="form-control" id="user" name="user" data-span="invalid-span-user" placeholder="name@example.com" value="uptaebxavier@gmail.com">
				<span id="invalid-span-user" class="invalid-span text-danger"></span>
			</div>
			<div class="form-floating">
				<label for="pass">Contraseña</label>
				<div class="show-password-container">
					<input type="password" class="form-control" id="pass" name="pass" data-span="invalid-span-pass" value="hola123">
					<span class="show-password-btn" data-inputpass="pass" aria-label="show password button"></span>
				</div>
				<span id="invalid-span-pass" class="invalid-span text-danger"></span>
			</div>
			<button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
			<p class="mt-5 mb-3 text-muted">&copy; 2024 – <?php echo date("Y"); ?></p>
		</div>
	</form>
</main>
<script type="text/javascript">
	iniciar_show_password();
	document.getElementById('f1').onsubmit = function(e){
		e.preventDefault();

		//creo el form data para envairlo con los datos del formulario
		var datos = new FormData($("#f1")[0]);
		// aqui faltaria validar desde js
		datos.append("accion","singing");

		// funcion dentro de assets/js/comun/comun-x.js
		enviaAjax(datos,function(respuesta, exito, fail){// el exito y fail es para hacer promesas si se quieren hacer
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "singing"){
				location.href="?p=usuarios"; //TODO cambiar ruta
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

		// automaticamente se envia al controlador si aparece una 
		//alerta de json syntax error revisas la consola que ahi 
		//deberia estar el error
	}
</script>
	</body>
</html>
