<?php 
use PHPUnit\Framework\TestCase;

class AusenciasDetallesVacacionesTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    /**
     * @dataProvider obtenerDetallesVacacionesProvider
     */
    public function testObtenerDetallesVacaciones($id_trabajador, $expected_result, $caso)
    {
        $resp = $this->administrarEmpleados->obtener_detalles_vacaciones($id_trabajador);

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "listar") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function obtenerDetallesVacacionesProvider()
    {
        return [
            // test caso 1: id_trabajador válido
            [2, "listar", 1],

            // test caso 2: id_trabajador inválido (no existe)
            [999, "error", 2],

            // test caso 3: id_trabajador vacío
            ["", "is-invalid", 3],

            // test caso 4: id_trabajador nulo
            [null, "is-invalid", 4],

            // test caso 5: id_trabajador no es un número
            ["a", "is-invalid", 5],

            // test caso 6: id_trabajador es un número negativo
            [-1, "is-invalid", 6],

            // test caso 7: id_trabajador es un número muy grande
            [PHP_INT_MAX + 1, "is-invalid", 7],

            // test caso 8: id_trabajador es un número decimal
            [1.5, "is-invalid", 8],
        ];
    }
}