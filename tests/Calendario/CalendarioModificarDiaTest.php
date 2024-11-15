<?php
//require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class CalendarioModificarDiaTest extends TestCase
{
	private $calendario;

	protected function setUp(): void {
		$this->calendario = new calendario;
        $this->calendario->set_Testing(true);
		$_SESSION['usuario_rotario'] = 2;
	}

	/**
	 * @dataProvider modificarDiaProvider 
	 */
	public function testModificarDia($description,$fecha,$recurrente,$expected_result,$caso)
	{
		$resp = $this->calendario->modificar_dia(
			$description,
			$fecha, 
			$recurrente
		);

		$this->assertNotNull($resp);
		$this->assertIsArray($resp);
		$mensaje = "caso ($caso)";
		if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
			$mensaje = "($caso)".$resp["mensaje"];
		}
		$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
	}

	public function modificarDiaProvider()
	{
		return [
				// test caso 1 modificacion valido recurrente
			 ["dia feriado","2024-11-04","1", "exito", 1],
				// test caso 2 modificacion valido no recurrente
			 ["dia feriado","2024-11-04","0", "exito", 2],
				// test caso 3 modificacion invalido (fecha no válida)
			 ["dia feriado","2024-02-30","0", "error", 3],
				// test caso 4 modificacion invalido (descripcion vacía)
			 ["","2024-11-04","0", "error", 4],
				// test caso 5 modificacion invalido (recurrente no válido)
			 ["dia feriado","2024-11-04","2", "error", 5],
				// test caso 8 modificacion invalido (descripcion nula)
			 [null,"2024-11-04","0", "error", 8],
				// test caso 9 modificacion invalido (fecha nula)
			 ["dia feriado",null,"0", "error", 9],
				// test caso 10 modificacion invalido (recurrente nulo)
			 ["dia feriado","2024-11-04",null, "error", 10],
				// test caso 11 modificacion invalido (descripcion vacía y fecha vacía)
			 ["","2024-11-04","0", "error", 11],
				// test caso 12 modificacion invalido (descripcion vacía y fecha nula)
			 ["",null,"0", "error", 12],
				   // test caso 13 modificacion invalido (descripcion nula y fecha vacía)
			 [null,"2024-11-04","0", "error", 13],
				// test caso 14 modificacion invalido (descripcion nula y fecha nula)
			 [null,null,"0", "error", 14],
				// test caso 15 modificacion invalido (contenido malicioso)
			 ["dia feriado<script>console.log('queso')</script>","2024-11-04","0", "error", 15],
				// test caso 16 modificacion invalido (recurrencia = Array)
			 ["dia feriado","2024-11-04",[], "error", 16],
			 // test caso 17 modificacion invalido (fecha inexistente)
			 ["dia feriado","2999-11-04","0", "error", 17]
		];
	}
}