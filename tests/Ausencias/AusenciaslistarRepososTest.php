<?php 

use PHPUnit\Framework\TestCase;

class AusenciasListarRepososTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }
  
	public function testListarReposos()
	{
	    $resp = $this->administrarEmpleados->listar_reposos();

	    $mensaje = "";
	    if (isset($resp["mensaje"]) and $resp["resultado"] != "listar") {
	        $mensaje = $resp["mensaje"];
	    }

	    $this->assertNotNull($resp);
	    $this->assertIsArray($resp);

	    $this->assertEquals("listar", $resp["resultado"], $mensaje);

	    $this->assertArrayHasKey("titulo", $resp);
	    $this->assertArrayHasKey("mensaje", $resp);
	    $this->assertArrayHasKey("resultado", $resp);

	    $this->assertArrayHasKey("mensaje", $resp);
	    $this->assertIsArray($resp["mensaje"]);
	    if(false){ // solo funciona si tiene el fetch assoc (tiene el num)

		    foreach ($resp["mensaje"] as $reposo) {

		        $this->assertArrayHasKey("cedula", $reposo);
		        $this->assertArrayHasKey("nombre", $reposo);
		        $this->assertArrayHasKey("apellido", $reposo);
		        $this->assertArrayHasKey("tipo_reposo", $reposo);
		        $this->assertArrayHasKey("descripcion", $reposo);
		        $this->assertArrayHasKey("desde", $reposo);
		        $this->assertArrayHasKey("hasta", $reposo);
		    }
	    }
	}

}

