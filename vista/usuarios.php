
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require_once 'assets/comun/head.php'; ?>
	<title>usuarios</title>
</head>
<body>
	<div style="max-width: 500px" class="m-auto">
		<div class="container text-center">
			<form action="" method="POST" onsubmit="return false" id="f1">
				<label for="cedula">Cedula</label>
				<input type="text" class="form-control" id="cedula" name="cedula" data-span="invalid-span-cedula">
				<span id="invalid-span-cedula" class="invalid-span text-danger"></span>

				<div class="container pl-0 pr-0">
						<label class="d-block" for="nombre">Nombre</label>
						<input required type="text" class="form-control" id="nombre" name="nombre" data-span="invalid-span-nombre">
						<span id="invalid-span-nombre" class="invalid-span text-danger"></span>

						<label class="d-block" for="apellido">Apellido</label>
						<input required type="text" class="form-control" id="apellido" name="apellido" data-span="invalid-span-apellido">
						<span id="invalid-span-apellido" class="invalid-span text-danger"></span>

						<label class="d-block" for="telefono">Teléfono</label>
						<input type="text" class="form-control" id="telefono" name="telefono" data-span="invalid-span-telefono">
						<span id="invalid-span-telefono" class="invalid-span text-danger"></span>
						<label class="d-block" for="correo">Correo</label>
						<input required type="email" class="form-control" id="correo" name="correo" data-span="invalid-span-correo">
						<span id="invalid-span-correo" class="invalid-span text-danger"></span>

						<label for="rol">Rol</label>
						<select required class="form-control" id="rol" name="rol" data-span="invalid-span-rol">
							<option value="">Seleccione un rol</option>
						</select>
						<span id="invalid-span-rol" class="invalid-span text-danger"></span>

						<label class="d-block" for="pass">Clave</label>
						<div class="show-password-container">
							<input required type="password" class="form-control" id="pass" name="pass" data-span="invalid-span-pass">
							<span class="show-password-btn" data-inputpass="pass" aria-label="show password button"></span>
						</div>
						<span id="invalid-span-pass" class="invalid-span text-danger"></span>
						<div class="text-center mt-4">
							<button type="submit">Registrar</button>
						</div>
				</div>
			</form>
		</div>
	</div>

	<script type="text/javascript">
		iniciar_show_password();
		cedulaKeypress(document.getElementById('cedula'));
		eventoKeyup("cedula",V.expCedula,"La cedula es invalida ej. V-00000001");
		eventoKeyup("nombre", V.expTexto(50), "El nombre no es valido");
		eventoKeyup("apellido", V.expTexto(50), "El apellido no es valido");
		eventoKeyup("telefono", V.expTelefono, "El teléfono es invalido", undefined, (elem)=>{
			elem.value = elem.value.replace(/^([0-9]{4})\D*([0-9]{1,7})/, "$1-$2");
		});
		document.getElementById('telefono').allow_empty = true;
		eventoKeyup("correo", V.expEmail, "El correo es invalido");
		eventoPass("pass");

		var datos = new FormData();
		datos.append("accion","get_roles");
		enviaAjax(datos,function(respuesta, exito, fail){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "get_roles"){
				for(x of lee.mensaje){
					document.getElementById('rol').appendChild(crearElem('option',`value,${x.id}`,x.rol));
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



		document.getElementById('f1').onsubmit = function(e) {
			e.preventDefault();

			$("#f1 input").each((i,elem)=>{
				if(!elem.validarme()){
					return false;
				}
			});

			var datos = new FormData($("#f1")[0]);
			datos.append("accion","registrar");
			enviaAjax(datos,function(respuesta, exito, fail){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "registrar"){

					muestraMensaje("Exito", "Usuario nuevo registrado", "s");
					
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