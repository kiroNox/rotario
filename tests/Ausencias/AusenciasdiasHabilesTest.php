<?php 

use PHPUnit\Framework\TestCase;

class AusenciasdiasHabilesTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    public function testDiasHabiles()
{
    $resp = $this->administrarEmpleados->dias_habiles();

    $mensaje = "";
    if (isset($resp["mensaje"]) and $resp["resultado"] != "dias_habiles") {
        $mensaje = $resp["mensaje"];
    }

    $this->assertNotNull($resp);
    $this->assertIsArray($resp);

    $this->assertEquals("dias_habiles", $resp["resultado"], $mensaje);

    // Verificar que la respuesta tenga los datos esperados
    $this->assertArrayHasKey("titulo", $resp);
    $this->assertArrayHasKey("mensaje", $resp);
    $this->assertArrayHasKey("resultado", $resp);

    // Verificar que la respuesta tenga un arreglo de fechas
    $this->assertArrayHasKey("mensaje", $resp);
    $this->assertIsArray($resp["mensaje"]);

    // Verificar que cada fecha sea un string válido

    foreach ($resp["mensaje"] as $fecha) {
    	$fecha = $fecha[0];
        $this->assertIsString($fecha);
        $this->assertMatchesRegularExpression("/^\d{4}-\d{2}-\d{2}$/", $fecha);
        
    }
}
  

}



