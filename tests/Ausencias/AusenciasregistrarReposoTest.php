<?php 

use PHPUnit\Framework\TestCase;

class AusenciasregistrarReposoTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    /**
     * @dataProvider RegistrarReposoProvider
     */
    public function testRegistrarReposo($id, $tipo_reposo, $descripcion, $desde, $hasta, $dias_totales, $expected_result, $caso)
    {
        $resp = $this->administrarEmpleados->registrar_reposo(
            $id,
            $tipo_reposo,
            $descripcion,
            $desde,
            $hasta,
            $dias_totales
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "registro") {
            $mensaje = "($caso)".$resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function RegistrarReposoProvider()
    {
        return [
            // test caso 1 registro válido
            ["2", "Medico", "descripcion", "2024-09-05", "2024-09-07", "2", "registrar", 1],

            // test caso 2 id inválido
            ["", "Medico", "descripcion", "2024-09-05", "2024-09-07", "2", "is-invalid", 2],

            // test caso 3 tipo de reposo inválido
            ["2", "", "descripcion", "2024-09-05", "2024-09-07", "2", "is-invalid", 3],

            // test caso 4 descripción vacía
            ["2", "Medico", "", "2024-09-05", "2024-09-07", "2", "is-invalid", 4],

            // test caso 5 fecha de inicio inválida
            ["2", "Medico", "descripcion", "2024-02-30", "2024-09-07", "2", "is-invalid", 5],

            // test caso 6 fecha de fin inválida
            ["2", "Medico", "descripcion", "2024-09-05", "2024-13-07", "2", "is-invalid", 6],

            // test caso 7 fecha de inicio y fin iguales
            ["2", "Medico", "descripcion", "2024-09-05", "2024-09-05", "2", "error", 7],

            // test caso 8 fecha de inicio posterior a la fecha de fin
            ["2", "Medico", "descripcion", "2024-09-07", "2024-09-05", "2", "error", 8],

            // test caso 9 fecha de inicio vacía
            ["2", "Medico", "descripcion", "", "2024-09-07", "2", "is-invalid", 9],

            // test caso 10 fecha de fin vacía
            ["2", "Medico", "descripcion", "2024-09-05", "", "2", "is-invalid", 10],

            // test caso 11 id nulo
            [null, "vacaciones", "descripcion", "2024-09-05", "2024-09-07", "2", "is-invalid", 11],

            // test caso 12 tipo de reposo nulo
            ["2", null, "descripcion", "2024-09-05", "2024-09-07", "2", "is-invalid", 12],

            // test caso 13 descripción nula
            ["2", "vacaciones", null, "2024-09-05", "2024-09-07", "2", "is-invalid", 13],

            // test caso 14 fecha de inicio nula
            ["2", "vacaciones", "descripcion", null, "2024-09-07", "2", "is-invalid", 14],

            // test caso 15 fecha de fin nula
            ["2", "vacaciones", "descripcion", "2024-09-05", null, "2", "is-invalid", 15],

            // test caso 16 días totales nulo
            ["2", "vacaciones", "descripcion", "2024-09-05", "2024-09-07", null, "is-invalid", 16],

            // test caso 17 fecha de inicio con letras
            ["2", "vacaciones", "descripcion", "2024-09-ab", "2024-09-07", "2", "is-invalid", 17],

            // test caso 18 fecha de fin con letras
            ["2", "vacaciones", "descripcion", "2024-09-05", "2024-09-cd", "2", "is-invalid", 18],

            // test caso 19 id con letras
            ["abc", "vacaciones", "descripcion", "2024-09-05", "2024-09-07", "2", "is-invalid", 19],

            // test caso 20 tipo de reposo con números
            ["2", "123", "descripcion", "2024-09-05", "2024-09-07", "2", "registrar", 20],

            // test caso 21 descripción con caracteres especiales
            ["2", "vacaciones", "!@#$%^&*()<script>", "2024-09-05", "2024-09-07", "2", "is-invalid", 21],

            // test caso 22 días totales con letras
            ["2", "vacaciones", "descripcion", "2024-09-05", "2024-09-07", "dos", "is-invalid", 22],

            // test caso 23 fecha de inicio con formato incorrecto
            ["2", "vacaciones", "descripcion", "09/05/2024", "2024-09-07", "2", "is-invalid", 23],

            // test caso 24 fecha de fin con formato incorrecto
            ["2", "vacaciones", "descripcion", "2024-09-05", "09/07/2024", "2", "is-invalid", 24],

            // test caso 25 id trabajador no existente
            ["9999", "vacaciones", "descripcion", "2024-09-05", "2024/09/07", "2", "error", 25],
        ];
    }
}



 ?>