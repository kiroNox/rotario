<?php
//require_once '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class AusenciasmodificarReposoTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    /**
     * @dataProvider ModificarReposoProvider
     */
    public function testModificarReposo($desde, $hasta, $dias_totales, $tipo_reposo, $descripcion, $id_tabla, $expected_result, $caso)
    {
        $resp = $this->administrarEmpleados->modificar_reposo(
            $desde,
            $hasta,
            $dias_totales,
            $tipo_reposo,
            $descripcion,
            $id_tabla
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "modificar") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function ModificarReposoProvider()
    {
        return [
            // Casos de prueba válidos
            ["2024-09-05", "2024-09-07", "2", "reposo", "descripcion", "25", "modificar", 1],

            // Casos de prueba con fechas inválidas
            ["2024-02-30", "2024-09-07", "2", "reposo", "descripcion", "25", "is-invalid", 2],
            ["2024-09-05", "2024-13-07", "2", "reposo", "descripcion", "25", "is-invalid", 3],
            ["2024-09-07", "2024-09-05", "2", "reposo", "descripcion", "25", "error", 4],
            ["2024-09-07", "2024-09-07", "2", "reposo", "descripcion", "25", "error", 4],

            // Casos de prueba con días totales inválidos
            ["2024-09-05", "2024-09-07", "asdf", "reposo", "descripcion", "25", "is-invalid", 5],
            ["2024-09-05", "2024-09-07", "-1", "reposo", "descripcion", "25", "is-invalid", 6],

            // Casos de prueba con tipo de reposo inválido
            ["2024-09-05", "2024-09-07", "2", "", "descripcion", "25", "is-invalid", 7],
            ["2024-09-05", "2024-09-07", "2", null, "descripcion", "25", "is-invalid", 8],

            // Casos de prueba con descripción inválida
            ["2024-09-05", "2024-09-07", "2", "reposo", "", "25", "is-invalid", 9],
            ["2024-09-05", "2024-09-07", "2", "reposo", null, "25", "is-invalid", 10],

            // Casos de prueba con id tabla inválido
            ["2024-09-05", "2024-09-07", "2", "reposo", "descripcion", "", "is-invalid", 11],
            ["2024-09-05", "2024-09-07", "2", "reposo", "descripcion", null, "is-invalid", 12],
            ["2024-09-05", "2024-09-07", "2", "reposo", "descripcion", "9999", "error", 13],

            // Casos de prueba con fechas vacías
            ["", "2024-09-07", "2", "reposo", "descripcion", "25", "is-invalid", 14],
            ["2024-09-05", "", "2", "reposo", "descripcion", "25", "is-invalid", 15],

            // Casos de prueba con fechas nulas
            [null, "2024-09-07", "2", "reposo", "descripcion", "25", "is-invalid", 16],
            ["2024-09-05", null, "2", "reposo", "descripcion", "25", "is-invalid", 17],
        ];
    }
}