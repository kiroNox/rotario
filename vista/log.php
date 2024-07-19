<!DOCTYPE html>
<html lang="es">
	<head>

		<?php require_once 'assets/comun/head.php'; ?>
		<link rel="stylesheet" href="assets/general/bootstrap-icons/bootstrap-icons.min.css">

		<title>Login - Servicio Desconcentrado Hospital Rotario</title>

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
			<a href="#" data-toggle="modal" data-target="#modal_reset">He olvidado mi contraseña</a>
			<p class="mt-5 mb-3 text-muted">&copy; 2024 – <?php echo date("Y"); ?></p>
		</div>
	</form>
</main>


<div class="modal fade" tabindex="-1" role="dialog" id="modal_reset">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header text-light bg-primary">
				<h5 class="modal-title">Restablecer Contraseña</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="container text-center">
				<h3>Introduzca su correo</h3>
				<p>Si su correo esta registrado recibirá un correo para el restablecimiento de su contraseña</p>
				
			</div>
			<div class="container" style="width: 500px">
				<form action="" onsubmit="return false" id="f3">
					<div class="row">
						<div class="col">
							<label for="reset_correo">Correo</label>
							<input require type="email" class="form-control" id="reset_correo" name="correo" data-span="invalid-span-reset_correo">
							<span id="invalid-span-reset_correo" class="invalid-span text-danger"></span>
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<button class="btn btn-primary" type="submit">Enviar</button>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>



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
				location.href="?p=dashboard"; //TODO cambiar ruta
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

	eventoKeyup("reset_correo", V.expEmail, "El correo no es valido");

	document.getElementById('f3').onsubmit = function(e){
		e.preventDefault();

		if(document.getElementById('reset_correo').validarme()){
			var datos = new FormData($("#f3")[0]);
			datos.append("accion","reset_pass_request");
			enviaAjax(datos,function(respuesta, exito, fail){

			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "reset_pass_request"){

					muestraMensaje("", "Si el correo esta registrado se le sera enviado un correo con instrucciones para restablecer su contraseña ", "i");
					
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
			},"loader_body");
		}
	}
	
</script>
	</body>
</html>
