<?php 

use PHPUnit\Framework\TestCase;
class SueldoloadCargosTest extends TestCase
{
    private $sueldo;

    protected function setUp(): void {
    
        $this->sueldo= new Sueldo;
        $this->sueldo->set_Testing(true);
    }
   
    public function testloadCargos(){




        $resp = $this->sueldo->load_cargos();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("load_cargos", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 
