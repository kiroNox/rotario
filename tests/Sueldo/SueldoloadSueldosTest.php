<?php 

use PHPUnit\Framework\TestCase;
class SueldoloadSueldosTest extends TestCase
{
    private $sueldo;

    protected function setUp(): void {
    
        $this->sueldo= new Sueldo;
        $this->sueldo->set_Testing(true);
    }
   
    public function testLoadSueldo(){




        $resp = $this->sueldo->load_sueldos();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("load_sueldos", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 
