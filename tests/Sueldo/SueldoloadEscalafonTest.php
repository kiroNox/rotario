<?php 

use PHPUnit\Framework\TestCase;
class SueldoloadEscalafonTest extends TestCase
{
    private $sueldo;

    protected function setUp(): void {
    
        $this->sueldo= new Sueldo;
        $this->sueldo->set_Testing(true);
    }
   
    public function testLoadEscalfon(){




        $resp = $this->sueldo->load_escalafon();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("load_escalafon", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 
