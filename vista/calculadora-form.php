<div id="calculadora_formulario_content" class="mt-3">
	<div class="d-flex justify-content-between align-items-center">
		<div class="d-flex justify-content-start align-items-center">
			<input type="checkbox" class="check-button" id="lista_condicionales">
			<label for="lista_condicionales" class="check-button"></label>
			<label for="lista_condicionales" class="cursor-pointer no-select mb-0 ml-2">Lista de condiciones</label>
		</div>
		<div>
			<button class="btn btn-primary" id="ver_palabras_reservadas" type="button">Ver palabras reservadas</button>
		</div>
	</div>
	<div class="container text-center my-3">
		<select class="form-control" name="trabajador_prueba" id="trabajador_prueba-1">
			<option value="">- Seleccione un trabajador de pruebas - </option>
		</select>
	</div>
	<div id="formulario_calc_normal">
		<div class="d-flex justify-content-start align-items-center">
			<input type="checkbox" class="check-button" id="calc_condicional_check" name="calc_condicional_check" data-span="invalid-span-calc_condicional_check">
			<label for="calc_condicional_check" class="check-button"></label>
			<label class="cursor-pointer no-select mb-0 ml-2" for="calc_condicional_check">Condicional</label>
		</div>
		<br>
		<div class="d-none position-relative" id="condicional-container">
			<label for="calc_condicional">Condición</label>
			<input required disabled="true" type="text" class="form-control" id="calc_condicional" name="calc_condicional" data-span="invalid-span-calc_condicional">
			<div class="suggestions" data-input="calc_condicional"></div>
			<span id="invalid-span-calc_condicional" class="invalid-span text-danger"></span>
		</div>
		<div class="position-relative">
			<label for="calc_formula_input">Formula o Monto</label>
			<input required type="text" class="form-control" id="calc_formula_input" name="calc_formula_input" data-span="invalid-span-calc_formula_input" data-variables_container="list_calc_variables">
			<div class="suggestions" data-input="calc_formula_input"></div>
			<span id="invalid-span-calc_formula_input" class="invalid-span text-danger"></span>
		</div>
		<div class="container lista-variables my-3">
			<h4>Variables</h4>
			<div id="list_calc_variables"></div>
		</div>
	</div>
	<!-- lista de condicionales -->
	<div id="formulario_calc_lista_condicionales" class="d-none">
		<div class="container" id="container_condicionales">
			<!-- <div id="calc_lista-condicion-1">
				<label for="calc_condicional">Condición - 1</label>
				<input required type="text" class="form-control" id="calc_condicional-condicion-1" name="calc_condicional-condicion-1" data-span="invalid-span-calc_condicional-condicion-1">
				<span id="invalid-span-calc_condicional-condicion-1" class="invalid-span text-danger"></span>

				<label for="calc_formula_input">Formula - 1</label>
				<input required type="text" class="form-control" id="calc_formula_input-condicion-1" name="calc_formula_input-condicion-1" data-span="invalid-span-calc_formula_input-condicion-1">
				<span id="invalid-span-calc_formula_input-condicion-1" class="invalid-span text-danger"></span>

				<div class="container lista-variables">
					<h4>Formula - 1 - Variables</h4>
					<div id="list_calc_variables-condicion-1"></div>
				</div>
			</div> -->
		</div>
		<div class="text-right">
			<button type="button" class="btn btn-primary" title="Añadir Condición" onclick="add_lista_condicional()">+</button>
			<button type="button" class="btn btn-primary" title="Eliminar Condición" onclick="remove_lista_condicional()">-</button>
		</div>
	</div>
	<!-- lista de condicionales -->
	<div>
		<label for="calc_formula_nombre">Nombre de formula</label>
		<input type="text" class="form-control" id="calc_formula_nombre" name="calc_formula_nombre" data-span="invalid-span-calc_formula_nombre">
		<span id="invalid-span-calc_formula_nombre" class="invalid-span text-danger"></span>
	</div>
	<div>
		<label for="calc_descripcion">Descripción de formula</label>
		<input type="text" class="form-control" id="calc_descripcion" name="calc_descripcion" data-span="invalid-span-calc_descripcion">
		<span id="invalid-span-calc_descripcion" class="invalid-span text-danger"></span>
	</div>
	<div class="container text-center my-3">
		<button type="submit" class="btn btn-primary calc-btn">Probar Formula</button>
		<button type="button" class="btn btn-primary calc-btn" id="save-form-btn-1">Registrar Prima</button>
	</div>
</div>
<dialog id="lista_variables">
	<div class="h2 text-center">Palabras reservadas</div>
	<div class="container p-0 dialog-body">
	</div>
	<div class="container text-right mt-2">
		<button type="button" class="btn btn-danger" id="cerrar_dialog">Cerrar</button>
		
	</div>
</dialog>