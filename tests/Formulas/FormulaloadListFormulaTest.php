<?php 

use PHPUnit\Framework\TestCase;
class FormulaloadListFormulaTest extends TestCase
{
    private $formulas;

    protected function setUp(): void {
    
        $this->formulas= new Formulas;
        $this->formulas->set_Testing(true);
    }
   
    public function testloadFOrmulas(){




        $resp = $this->formulas->load_list_formulas();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("load_list_formulas", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 
