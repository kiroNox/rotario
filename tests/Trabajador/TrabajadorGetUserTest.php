<?php 

use PHPUnit\Framework\TestCase;
class TrabajadorGetUserTest extends TestCase
{
    private $trabajador;

    protected function setUp(): void {
        $this->trabajador= new Usuarios;
        $this->trabajador->set_Testing(true);
    }
    /**
     * @dataProvider miFuncionProveedora
     */
    public function testGetUser($id,$expected_result,$caso){


        $resp = $this->trabajador->get_user_s($id);

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals(
            $expected_result, 
            $resp["resultado"],
            "Error en el caso $caso");
    }

    public function miFuncionProveedora(){
    	return [
            [2,"get_user",1], // existe
            [999,"error",2],  // no existe
            ["a","is-invalid",3],  // no numerico
            [NULL,"is-invalid",4],  // Null

    	];
    }
} 