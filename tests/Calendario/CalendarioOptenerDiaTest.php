<?php
//require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class CalendarioObtenerDiaTest extends TestCase
{
	private $calendario;

	protected function setUp(): void {
		$this->calendario = new calendario;
		$_SESSION['usuario_rotario'] = 2;
	}

	/**
	 * @dataProvider ObtenerDiaProvider 
	 */
	public function testObtenerDia($year, $month, $expected_result, $caso)
	{
		$resp = $this->calendario->obtener_dia(
			$year,
			$month
		);

		$this->assertNotNull($resp);
		$this->assertIsArray($resp);
		$mensaje = "caso ($caso)";
		if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
			$mensaje = "($caso)".$resp["mensaje"];
		}
		$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
	}

	public function ObtenerDiaProvider()
	{
		return [
			// test caso 1 optener dia valido y existente en bd
			["2024","11", "exito", 1],
			// test caso 2 optener dia invalido (mes no válido)
			["2024","13", "error", 2],
			// test caso 3 optener dia invalido (año no válido)
			["20244","11", "error", 3],
			// test caso 5 optener dia con mes y año vacíos
			["", "", "error", 5],
			// test caso 6 optener dia con mes y año nulos
			[null, null, "error", 6],
		];
	}
}