<?php 

use PHPUnit\Framework\TestCase;
class HijosListarHijosTest extends TestCase
{
    private $hijos, $trabajadores;

    protected function setUp(): void {
    
        $this->hijos= new Hijos;
        $this->hijos->set_Testing(true);
    }
   
    public function testListarHijos(){




        $resp = $this->hijos->listar_hijos();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("listar_hijos", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 