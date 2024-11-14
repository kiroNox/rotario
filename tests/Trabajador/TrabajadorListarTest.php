<?php 

use PHPUnit\Framework\TestCase;
class TrabajadorListarTest extends TestCase
{
    private $trabajadores;

    protected function setUp(): void {
    
        $this->trabajadores= new Usuarios();
    }
   
    public function testListarTrabajadores(){




        $resp = $this->trabajadores->listar_usuarios();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("listar_usuarios", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 