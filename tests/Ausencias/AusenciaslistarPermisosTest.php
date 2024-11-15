<?php 

use PHPUnit\Framework\TestCase;

class AusenciasListarPermisosTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    public function testListarPermisos()
    {
        $resp = $this->administrarEmpleados->listar_permisos();

        $mensaje = "";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "listar") {
            $mensaje = $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals("listar", $resp["resultado"], $mensaje);

        // Verificar que la respuesta tenga los datos esperados
        $this->assertArrayHasKey("titulo", $resp);
        $this->assertArrayHasKey("mensaje", $resp);
        $this->assertArrayHasKey("resultado", $resp);

        // Verificar que la respuesta tenga un arreglo de permisos
        $this->assertArrayHasKey("mensaje", $resp);
        $this->assertIsArray($resp["mensaje"]);
    }
  

}














