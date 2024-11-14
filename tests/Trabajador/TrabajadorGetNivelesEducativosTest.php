<?php 

use PHPUnit\Framework\TestCase;
class TrabajadorGetNivelesEducativosTest extends TestCase
{
    private $trabajadores;

    protected function setUp(): void {
    
        $this->trabajadores= new Usuarios;
    }
   
    public function testGetNivelesEducativos(){




        $resp = $this->trabajadores->get_niveles_educativos();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("nivel_profesional", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 