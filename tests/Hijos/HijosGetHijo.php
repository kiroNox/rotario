<?php 

use PHPUnit\Framework\TestCase;
class HijosGetHijoTest extends TestCase
{
    private $hijos;

    protected function setUp(): void {
        $this->hijos= new Hijos;
    }
    /**
     * @dataProvider miFuncionProveedora
     */
    public function testGetHijo($id,$expected_result,$caso){


        $resp = $this->hijos->get_hijo_s($id);

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals(
            $expected_result, 
            $resp["resultado"],
            "Error en el caso $caso");
    }

    public function miFuncionProveedora(){
    	return [
            [7,"get_hijo",1], // existe
            [999,"error",2],  // no existe
            ["a","is-invalid",3],  // no numerico
            [NULL,"is-invalid",4],  // Null

    	];
    }
} 