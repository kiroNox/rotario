<?php 

use PHPUnit\Framework\TestCase;
class HijosValidParentTest extends TestCase
{
    private $hijos, $trabajadores;

    protected function setUp(): void {
    
        $this->hijos= new Hijos;
    }
    /**
     * @dataProvider miFuncionProveedora
     */
    public function testvalid_parent_s_dataProvi($cedula,$expected_result){




        $resp = $this->hijos->valid_parent_s($cedula);


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"]);
    }

    public function miFuncionProveedora(){
    	return [
    		["V-27250544","valid_parent"],
    		["27250544","is-invalid"],
    		["V-2725","is-invalid"],
    		["holaMundo","is-invalid"],
    		["","is-invalid"]
    	];
    }
} 