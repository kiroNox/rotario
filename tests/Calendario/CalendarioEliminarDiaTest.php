<?php
//require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class CalendarioEliminarDiaTest extends TestCase
{
	private $calendario;

	protected function setUp(): void {
		$this->calendario = new calendario;
		$_SESSION['usuario_rotario'] = 2;
	}

	/**
	 * @dataProvider eliminarDiaProvider 
	 */
	public function testEliminarDia($fecha,$expected_result,$caso)
	{
		$resp = $this->calendario->eliminar_dia(
			$fecha
		);

		$this->assertNotNull($resp);
		$this->assertIsArray($resp);
		$mensaje = "caso ($caso)";
		if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
			$mensaje = "($caso)".$resp["mensaje"];
		}
		$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
	}

	public function eliminarDiaProvider()
	{
		return [
			// test caso 1 modificacion valido recurrente
			["2024-11-04", "exito", 1],
			// test caso 2 fecha invalida (30 de febrero no existe)
			["2024-02-30", "error", 2],
			// test caso 3 fecha vacia
			["", "error", 3],
			// test caso 4 fecha con formato incorrecto
			["2024+11+04", "error", 4],
			// test caso 5 fecha inexistente
			["2020-01-01", "error", 5],
			// test caso 6 fecha nula
			[null, "error", 6]
		];
	}
}