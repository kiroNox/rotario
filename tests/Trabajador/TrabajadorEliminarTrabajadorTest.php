<?php

use PHPUnit\Framework\TestCase;

class TrabajadorEliminarTrabajadorTest extends TestCase
{
    private $trabajadores;

    protected function setUp(): void {
        $this->trabajadores = new Usuarios;
        $this->trabajadores->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider eliminarTrabajadorProvider
     */
    public function testEliminarTrabajador($id, $expected_result, $intento)
    {
        $resp = $this->trabajadores->eliminar_usuario_s($id);


        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $mensaje = "intento ($intento)";
        if (isset($resp["line"])) {
            $mensaje = "($intento) " . $resp["mensaje"] . " line:" . $resp["line"];
        }
        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function eliminarTrabajadorProvider()
    {
        return [
            // Casos válidos
            [4, "eliminar_usuario", 1],
            ["5", "eliminar_usuario", 2],

            // Casos inválidos
            [2, "error", 3], // Usuario propio
            [3, "error", 4], // Usuario Administrador
            [0, "error", 5], // ID no registrado
            [9, "error", 5], // ID no registrado
            [-1, "error", 6], // ID negativo
            ["a", "is-invalid", 8], // ID no numérico
            [null, "is-invalid", 9], // ID nulo
        ];
    }
}