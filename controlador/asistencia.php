<?php

if (is_file("vista/" . $pagina . ".php")) {

	$claseasistencia = new Asistencia;

	if (!empty($_POST)) { // Si hay alguna consulta tipo POST
		$accion = $_POST["accion"]; // Siempre se pasa un parámetro con la acción que se va a realizar


		if (isset($permisos["vacaciones"]["crear"]) && $permisos["vacaciones"]["crear"] == "1") {

			//crea la estructura de un switch case con la accion que se va a realizar 

			switch ($accion) {
				case "index":	// inicio	
					echo json_encode($claseasistencia->load_asistencia());
					break;

				case "store":		// llama al metodo registrar asistencia
					$id_trabajador = $_POST["id_trabajador"];
					$id_area = $_POST["id_area"];
					$desde = $_POST["desde"];
					$hasta = $_POST["hasta"];

					if ($id_trabajador && $id_area && $desde && $hasta && $estado) {

						$resp = $claseasistencia->create_asistencia($id_trabajador, $id_area, $desde, $hasta);

						echo json_encode($resp);

					} else {
						echo json_encode([
							"resultado" => "error",
							"titulo" => "Error",
							"mensaje" => "Faltan parámetros en la solicitud."
						]);
					}

					break;

				case "destroy":		// llama al metodo registrar asistencia
					$id_asistencia = $_POST["id_asistencia"];
					$resp = $claseasistencia->delete_asistencia($id_asistencia);

					echo json_encode($resp);

					break;
				case "update":		// llama al metodo registrar asistencia
					$id_asistencia = $_POST["id_asistencia"];
					$id_trabajador = $_POST["id_trabajador"];
					$id_area = $_POST["id_area"];
					$desde = $_POST["desde"];
					$hasta = $_POST["hasta"];
					$resp = $claseasistencia->update_asistencia( $id_trabajador, $id_area, $desde, $hasta);
					echo json_encode($resp);

					break;

				case "show":
					$id_asistencia = $_POST["id_asistencia"];
					$resp = $claseasistencia->show_asistencia($id_asistencia);
					echo json_encode($resp);
					break;


			}
		} else {
			$claseasistencia->no_permision_msg();

		}
	}



	$claseasistencia->set_con(null);
	Bitacora::ingreso_modulo("Asistencia");
	require_once ("vista/" . $pagina . ".php");
} else {
	require_once ("vista/404.php");
}

?>