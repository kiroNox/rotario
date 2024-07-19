<!DOCTYPE html>
<html lang="es">
	<head>

		<?php require_once 'assets/comun/head.php'; ?>
		<link rel="stylesheet" href="assets/general/bootstrap-icons/bootstrap-icons.min.css">

		<title>Restablecer Contraseña - Servicio Desconcentrado Hospital Rotario</title>

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
	<?php if($control["resultado"]){ ?>
	<div class="container">
		<form action="" id="reset_form" onsubmit="return false">
			<input type="hidden" id="hidden" name="id" value="<?= $data_user_reset->id_trabajador ?>">
			<div class="row">
				<div class="col mt-5 mx-auto" style="max-width: 500px;">
					<div>
						<label for="new_pass">Ingrese la nueva contraseña</label>
						<div class="show-password-container">
							<input type="password" class="form-control" id="new_pass" name="new_pass" data-span="invalid-span-new_pass">
							<span class="show-password-btn" data-inputpass="new_pass" aria-label="show password button"></span>
						</div>
						<span id="invalid-span-new_pass" class="invalid-span text-danger"></span>
					</div>

					<div>
						<label for="confirm_pass">Ingrese la nueva contraseña</label>
						<div class="show-password-container">
							<input type="password" class="form-control" id="confirm_pass" name="confirm_pass" data-span="invalid-span-confirm_pass">
							<span class="show-password-btn" data-inputpass="confirm_pass" aria-label="show password button"></span>
						</div>
						<span id="invalid-span-confirm_pass" class="invalid-span text-danger"></span>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col text-center">
					<button class="btn btn-primary" type="submit">
						Enviar
					</button>
					
				</div>
			</div>
		</form>
		<div class="text-right mt-3">
			<a href="?=log" class="btn btn-dark" id="btn_volver"> Volver </a>
		</div>
	</div>
<?php }
else{ ?>
	<div class="container">

		<h3>El toke expiro o es invalido</h3>

		<br>
		<div class="text-right">
			<a href="?=log" class="btn btn-primary" id="btn_volver"> Volver </a>
		</div>
		
	</div>
<?php } ?>
</main>







<script type="text/javascript">
	iniciar_show_password();


	eventoPass("new_pass");
	eventoPass("confirm_pass");

	document.getElementById('reset_form').onsubmit=function(e){
		e.preventDefault();

		for (var x of document.querySelectorAll("#reset_form input:not(input[type='hidden'])")){
			if(!x.validarme()){
				return false;
			}
		}

		if(document.getElementById('new_pass').value != document.getElementById('confirm_pass').value){
			muestraMensaje("", "Las contraseñas no coinciden", "s");
			return false;
		}


		var datos = new FormData($("#reset_form")[0]);
		datos.append("accion","change_pass");
		
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "change_pass"){

				muestraMensaje("Éxito", "La contraseñas fue modifciada exitosamente", "s",function(){
					document.getElementById('btn_volver').click();
				});



				
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

	}
</script>
	</body>
</html>
