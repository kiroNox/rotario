<?php 
use PHPUnit\Framework\TestCase;
class TrabajadorValidCedulaTest extends TestCase
{
    private $trabajadores;

    protected function setUp(): void {
    
        $this->trabajadores= new Usuarios();
        $this->trabajadores->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }
    /**
     * @dataProvider miFuncionProveedora
     */
    public function testValidCedula($cedula,$expected_result){




        $resp = $this->trabajadores->valid_cedula_s($cedula);


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"]);
    }

    public function miFuncionProveedora(){
    	return [
    		["V-27250544","valid_cedula"],
    		["27250544","is-invalid"],
    		["V-2725","is-invalid"],
    		["holaMundo","is-invalid"],
    		["","is-invalid"]
    	];
    }
} 