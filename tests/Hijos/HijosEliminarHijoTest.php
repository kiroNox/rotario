<?php

use PHPUnit\Framework\TestCase;

class HijosEliminarTest extends TestCase
{
    private $hijos;

    protected function setUp(): void {
        $this->hijos = new Hijos;
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider eliminarHijoProvider
     */
    public function testEliminarHijo($id, $expected_result, $intento)
    {
        $resp = $this->hijos->eliminar_hijo_s($id);


        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $mensaje = "intento ($intento)";
        if (isset($resp["mensaje"])) {
            $mensaje = "($intento) " . $resp["mensaje"] . " line:" . $resp["line"];
        }
        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function eliminarHijoProvider()
    {
        return [
            // Casos válidos
            [7, "eliminar_hijo", 1],
            [8, "eliminar_hijo", 2],

            // Casos inválidos
            [0, "error", 3], // ID no registrado
            [9, "error", 4], // ID no registrado
            [-1, "error", 5], // ID negativo
            ["a", "is-invalid", 6], // ID no numérico
            [null, "is-invalid", 7], // ID nulo
        ];
    }
}