<?php
use PHPUnit\Framework\TestCase;

class AusenciasListarVacacionesTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    
    public function testListarVacaciones()
    {
        $resp = $this->administrarEmpleados->listar_vacaciones();

        $mensaje = "";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "listar") {
            $mensaje = $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals("listar", $resp["resultado"], $mensaje);
    }

}